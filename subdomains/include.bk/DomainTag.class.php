<?php
require_once CMS_INC_ROOT . '/Item.class.php';
require_once CMS_INC_ROOT . '/ArticleTag.class.php';
class DomainTag {

    function getAllTagsBySource($source, $selected_tags = array(), $only_selected = false)
    {
        $result = DomainTag::getTagByParam(array('source' => $source));
        $tags = array();
        foreach ($result as $row) {
            $parent = $row['ptag_id'];
            if (!isset($tags[$parent])) {
                $tags[$parent] = array();
            }
            $tags[$parent][] = $row;
        }
        return DomainTag::__showTags($tags, $items, $selected_tags, $only_selected);
    }

    function __showTags($tags, &$items, $selected_tags, $only_selected, $deep = 0, $parent_id = 0)
    {
        $list = array();
        
        $output = '';
        if (isset($tags[$parent_id])) {
            $parents = $tags[$parent_id];
            foreach ($parents as $k=>$tag) {
                $tag_id = $tag['tag_id'];
                $name = $tag['name'];
                $data = array(
                    'deep' => $deep,
                    'name' => $tag['name'],
                    'parent_id' => $parent_id,
                );
                if (!empty($selected_tags) && in_array($tag_id, $selected_tags)) {
                    $data['selected'] = 1;
                }
                if (!isset($items[$tag_id])) {
                    $items[$tag_id] = array();
                }
                if ($parent_id > 0) {
                    $items[$tag_id] = $items[$parent_id];
                }
                $items[$tag_id][] = $name;
                $data['output_name'] = implode(">", $items[$tag_id]);
                if (!$only_selected || $only_selected && $data['selected']){
                    $list[$tag_id] = $data;
                }
                if (isset($tags[$tag_id])) {
                    $list = $list + DomainTag::__showTags($tags, $items, $selected_tags, $only_selected, $deep+1, $tag_id);
                }
            }
        }
        return $list;
    }
    
    function save($arr) 
    {
        global $conn;
        $keys = array_keys($arr);
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes($value);
        }
        if (isset($arr['domain_tag_id']) && $arr['domain_tag_id'] > 0) {
            $domain_tag_id=$arr['domain_tag_id'];
            $sets = array();
            foreach ($arr as $k => $value)  {
                $sets[] = "{$k}='{$value}'";
            }
            $q = 'UPDATE `domain_tags` SET ' . implode(',', $sets) . ' WHERE domain_tag_id=' . $domain_tag_id;
        } else {
            $q = "INSERT INTO `domain_tags` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        }
        return $conn->Execute($q);
    }

    function delTags4Article($result, $source)
    {
        return DomainTag::doActionTags4Article($result, $source, 'del');
    }
    
    function addTags2Article($result, $source)
    {
        return DomainTag::doActionTags4Article($result, $source);
    }

    function doActionTags4Article($result, $source, $opt = 'add')
    {
        $data = $ids = $rs = array();
        foreach ($result as $k => $row) {
            $article_id = trim($row['articleId']);
            if ($article_id > 0) {
                $ids[] = $article_id;
                $row['articleId'] = $article_id;
                $data[$article_id] = $row;
            } else {
                 unset($row['tagId']);
                $rs[] = array_merge($row, array('status' =>'denied', 'memo' => 'please specify the article'));
            }
        }
        $ids = ArticleTag::getCampaignSourceByArticleIds($ids, $source);
        foreach ($data as $row) {
            $article_id = $row['articleId'];
            if (in_array($article_id, $ids)) {
                $tag_ids = $row['tagId'];
                if (!empty($tag_ids)) {
                    if (is_array($tag_ids)) {
                        foreach ($tag_ids as $k => $v) {
                            if (empty($v)) unset($tag_ids[$k]);
                        }
                    } else {
                        $tag_ids = array($tag_ids);
                    }
                    $row['status'] = 'accepted';
                    unset($row['memo']);
                    $tags = DomainTag::getTagByTagIdAndSource($tag_ids, $source);
                    if (empty($tags) || count($tags) < count($tag_ids)) {
                        $exists_ids = array();
                        if (empty($tags)) {
                            $diff_ids = $tag_ids;
                        } else {
                            foreach ($tags as $item) {
                                $exists_ids[] = $item['tag_id'];
                            }
                            $diff_ids = array_diff($tag_ids, $exists_ids);
                        }
                        $row['memo'] = 'tag id ' . implode(',', $diff_ids) . ' are not exists, please create those first';
                        $row['status'] = 'denied';
                    } else {
                        ArticleTag::storeTags($article_id, $tag_ids, $source, $opt);
                    }
                } else {
                    $row['status'] = 'denied';
                    $row['memo'] = 'Please specify the tags';
                }
            } else {
                $row['status'] = 'denied';
                $row['memo'] = 'Permission deny, please check your article id';
            }
            unset($row['tagId']);
            $rs[] = $row;
        }
        return $rs;
    }

    function getTagByTagIdAndSource($tag_id, $source)
    {
        $result = DomainTag::getTagByParam(array('tag_id' => $tag_id, 'source' => $source));
        return isset($result[0]) ? (is_array($tag_id) ? $result :  $result[0]) : null;
    }

    function getTagByParam($param)
    {
        global $conn;
        foreach ($param as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key=> $value) {
                    $v[$key] = addslashes(trim($value));
                }
            } else {
                $v = addslashes(trim($v));
            }
            $param[$k] = $v;
        }
        extract($param);
        $condtions = array();
        if (isset($tag_id) && !empty($tag_id)) {
            if (is_array($tag_id)) {
                $condtions[] = "dt.tag_id IN ('" . implode("','", $tag_id). "')";
            } else {
                $condtions[] = "dt.tag_id='{$tag_id}'";
            }
        }
        if (isset($source) && !empty($source)) {
            $condtions[] = "dt.source='{$source}'";
        }


        $sql = "SELECT dt.*, i.name FROM `domain_tags` AS dt ";
        $sql .= "LEFT JOIN items AS i ON(i.item_id=dt.item_id)";
        if (!empty($condtions)) {
            $sql .= 'WHERE  ' . implode(" AND ", $condtions);
        }
        return $conn->GetAll($sql);
    }

    function getTagsBySouce($source)
    {
        $result = DomainTag::getTagByParam($source);
    }

    function addBatchTags($result, $source)
    {
        $item = $rs = array();
        foreach ($result as $row) {
            $tag_name = trim($row['tagName']);
            $tag_id = trim($row['tagId']);
            $data = DomainTag::getTagByTagIdAndSource($tag_id, $source);      
            $error = null;
            if (!empty($tag_id)) {
                if (!empty($tag_name)) {
                    $parent = $pitem_id = $item_id = 0;
                    if (isset($row['parent'])) {
                        $parent = trim($row['parent']);
                        $pdata = DomainTag::getTagByTagIdAndSource($parent, $source);
                        $pitem_id = $pdata['item_id'];
                        if (empty($pdata)) {
                            $error ='Invalid parent tag, please create this parent tag first';
                        }
                    }
                    if (empty($error)) {
                        $item = Item::getItemByName($tag_name);
                        if (empty($item)) {
                            $tmp = array(
                                'name' => $tag_name,
                                'slug' => replaceSpaceToLine($tag_name),
                            );
                            Item::save($tmp);
                            $item = Item::getItemByName($tag_name);                            
                        }
                        $item_id = $item['item_id'];
                        if ($item_id  >0 ) {
                            if (!empty($data)) {
                                if ($data['name'] != $tag_name) {
                                    $data['item_id'] = $item_id;
                                }
                                unset($data['name']);
                            } else {
                                $data = array(
                                    'item_id' => $item_id, 
                                    'tag_id' => $tag_id, 
                                    'source' => $source, 
                                );
                            }
                            $data['pitem_id'] = $pitem_id;
                            $data['ptag_id'] = $parent;
                            DomainTag::save($data);
                        } else {
                            $error = 'Invalid item or sql error for store item';
                        }
                    }
                } else {
                    $error = 'Please specify the tag name';
                }
            } else {
                $error = 'Please specify the tag id';
            }
            if (empty($error)) {
                $row['status'] = 'accepted';
            } else {
                $row['status'] = 'denied';
                $row['memo'] = $error;
            }
            $rs[] = $row;
        }
        return $rs;
    }
}
?>
