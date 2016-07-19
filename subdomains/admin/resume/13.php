<?php
require_once dirname(__FILE__)  . DIRECTORY_SEPARATOR . "pre.php";
require_once INCLUDE_DIR . 'configurable_product.class.php';
require_once INCLUDE_DIR . 'attribute_option.class.php';

// added by snug xu 2007-12-18 11:05  - STARTED
// get entity_type_id
$entity_type_code = 'catalog_product';
$oEntityType = new EntityType($oConn);
$entity_type_id = $oEntityType->getID(array('entity_type_code' => $entity_type_code));
$condition = array(
    'entity_type_id' => $entity_type_id
);
$oAttribute   = new Attribute($oConn);
$oAttributeOption   = new AttributeOption($oConn);
$fields = array('brand', 'activities');
$check_fields = array('brand', 'activities', 'gender', 'internal_id', 'width', 'color', 'shoe_size');
// added by snug xu 2007-12-18 11:05  - FINISHED


$oModule = new Module($oConn);
$oModule->setTableAndPKField('core_config_data', 'config_id');
$product_url_suffix = $oModule->getFieldByParam(array('path' => 'catalog/seo/product_url_suffix'), 'value');
$title_separator = $oModule->getFieldByParam(array('path' => 'catalog/seo/title_separator'), 'value');
$oConfigurable_products = new ConfigurableProduct($oConn);
$oModule->setTableAndPKField('catalog_product_entity', 'entity_id');
$oSuperLink = new Module($oConn);
$oSuperLink->setTableAndPKField('catalog_product_super_link', 'link_id');
$oProductEntity = new Module($oConn);
$condition['attribute_code'] = $check_fields;
$attributes = $oAttribute->getAttributesByParam($condition);

$total = $oConfigurable_products->getTotal();
$perPage  = 20;
$file_name = BASE_DIR . DS . 'log' . DS . 'adjust_prod_log' . date("Y-m-d-H-i-s") . '.txt';
$handle = fopen($file_name, "a+");
for ($i = 0; $i < $total; ($i += $perPage))
{
    $limits = array(
        'perPage' => $perPage,
        'startNo' => $i,
    );
    $list = $oConfigurable_products->getPagination($limits);
    foreach ($list as $k => $row)
    {
        $is_active = true;
        $old_is_active = $row['is_active'];
        $is_update = false;
        // get all its children products
        $product_id = $row['product_id'];
        $ids = $oSuperLink->getListByFieldAndParam(array('parent_id' => $product_id), 'product_id');
        // if they have no children set the configurable product as inactive

        for ($i = 0 ; $i < count($check_fields) ; $i ++)
        {
            $check_field = $check_fields[$i];
            if (empty($row[$check_field])) 
            {
                if (isset($attributes[$check_field]))
                {
                    if ($check_field != 'width' && $check_field != 'color' && $check_field != 'shoe_size')
                        $p = array('entity_id' => $product_id);
                    else
                        $p = array('entity_id' => $ids);
                    $p['entity_type_id'] = $entity_type_id;
                    $values = get_product_entity_value($oProductEntity, $attributes[$check_field], $p);

                    $row[$check_field] = implode(',', $values);
                    if (($check_field == 'width' || $check_field == 'color' || $check_field == 'shoe_size') && !empty($row[$check_field]))
                        $row[$check_field] = ',' . $row[$check_field] . ',';

                    if (empty($row[$check_field]) && $is_active) $is_active = false;
                    else if (strlen($row[$check_field]) && $is_update == false) $is_update = true;
                }
                else if ($is_active)
                {
                    $is_active = false;
                }
            }
        }
        if (empty($ids) && $is_active) $is_active = false;
        $ids[] = $product_id;

        if ($is_active) $is_active = url_ct_exists($row['small_image']);

        // set system product is_active value
        $p = array();
        $row['is_active'] = $is_active ? 1 : 0;
        $hash = array('is_active' => $row['is_active']);
        $p['is_active'] = $is_active ? 0 : 1;
        $p['entity_id'] = $ids;
        $oModule->updateByParam($p, $hash);
        
        // generate product url by url key
        if (empty($row['product_url']))
        {
            $url = $row['url_key'];
            $url = str_replace("/", $title_separator, $url) . $product_url_suffix;
            $row['product_url'] = $url;
            if ($is_update == false) $is_update = true;
        }
        // added by snug xu 2007-12-18 11:13 - STARTED
        // get attribute value by fields
        foreach ($fields as $value)
        {
            if (isset($row[$value . '_value']) && empty($row[$value . '_value']) && $row[$value] > 0)
            {
                $p['attribute_id'] = $attributes[$value]['attribute_id'];
                $p['option_id'] = $row[$value];
                $ret = $oAttributeOption->getOptionValuesByParam($p);
                $row[$value . '_value'] = $ret[0]['value'];
                if ($is_update == false) $is_update = true;
            }
        }
        // added by snug xu 2007-12-18 11:13 - FINISHED
        if ($is_update) $oConfigurable_products->store($row);
    }
}
fclose($handle);
echo "done!\n";
file_get_contents("http://dev.shoebacca.com/catalogsearch/UpdateActivitiesOrder");
echo "done!\n";

function url_ct_exists($url, $format = 'image') {
    $head=@get_headers($url, 1);
    if (!$format) $format = 'image';
    if(is_array($head)) {
        if (isset($head['Content-Type']) && stripos($head['Content-Type'], $format) !== false) {//default is image
            return true;
        }
        return false;
    }
    return false;
}

function storeUrlRewrite($p = array())
{
    $target_path = 'catalog/product/view/id/';
    $id_path = 'product/';
    $product_id = $p['product_id'];
    $param['target_path'] = $target_path . $product_id;
    $param['id_path'] = $id_path . $product_id;
    $param['store_id'] = 1;
    $this->oModule->setTableAndPKField('core_url_rewrite', 'url_rewrite_id');
    $id = $this->oModule->getID($param);
    $param['options'] = '';
    $param['request_path'] = $p['product_url'];
    if (empty($id))
    {
        $this->oModule->create();
        $id = $this->oModule->store($param);
    }
}

?>