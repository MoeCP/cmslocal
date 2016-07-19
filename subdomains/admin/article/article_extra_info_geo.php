<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/ArticleExtraInfo.class.php';
require_once CMS_INC_ROOT.'/GeographicName.class.php';
$logout_folder = '';

if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
if (isset($_GET['t']))
{
    $options = '';
    $type = $_GET['t'];// geo level
    $parent_id = 0;
    $geos = array();
    if ($type > 1)
    {
        if (isset($_GET['p'])) // parent id
        {
            $parent_id = $_GET['p'];
        }
    }
    $is_get_db = true;
    switch ($type)
    {
    case 1:
        $geo_type = 'country';
        $options = 'onclick="ajaxAction(\'/article/article_extra_info_geo.php?t=2&p=\' + this.value, \'div_state\');"';
        break;
    case 2:
        $geo_type = 'state';
        $options = 'onclick="ajaxAction(\'/article/article_extra_info_geo.php?t=3&p=\' + this.value, \'div_city\');"';
        $total = GeographicName::getTotal(array('type' => $type, 'parent_id' => $parent_id));
        if ($total <= 0 && strlen($parent_id))
        {
            $is_get_db = false;
            $options = '';
            echo "<script>ajaxAction('/article/article_extra_info_geo.php?t=3&p=' + {$parent_id}+'&s={$_GET['s']}', 'div_city');</script>";
        }
        break;
    case 3:
        $geo_type = 'city';
        break;
    }
    if (strlen($parent_id) && $is_get_db)
    {
        $geos = GeographicName::getGeoListByParentId($parent_id);
    }
    $geos = array('' => '[choose ' . $geo_type . ']') + $geos;
    $smarty->assign('geos', $geos);
    $smarty->assign('options', $options);
    $smarty->assign('geo_type', $geo_type);
    $smarty->assign('selected_geo', array_search($_GET['s'], $geos)); // selected item
    $smarty->display('article/article_extra_info_geo.html');
}
else
{
    exit("Please choose geo level.");
}

?>