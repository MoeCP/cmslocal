<?php

class Category
{
    function store($p) {
        //TODO 
        global $conn, $feedback;
        if (isset($p['parent_id'])) {
            $content[] = " parent_id=" . trim($p['parent_id']);
        }
        $content[] = "category='" . htmlspecialchars(addslashes(trim($p['category']))) . "'";
        if (isset($p['category_id']) && trim($p['category_id']) != 0) {
            $sql = "UPDATE category SET ";
            $qw[] = " WHERE category_id=" . trim($p['category_id']);
        } else {
            $q = " SELECT * FROM category WHERE " . implode(" AND ", $content);
            $res = $conn->getAll($q);
            if (!empty($res)) {
                $feedback = 'Duplicated, please to check';
                return false;
            }
            $sql = "INSERT INTO category SET ";
        }
        if (!empty($content)) {
            $sql .= implode(",", $content);
        }
        if(!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $result = $conn->Execute($sql);
        if ($result) {
            return true;
        } else return false;
    }
    
    function delete($p)
    {
        //TODO
        global $conn;
        $sql = "DELETE FROM category ";
        $qw[] =  " WHERE 1 ";
        if (isset($p['category_id']) && trim($p['category_id']) != 0) {
            $qw[] = " category_id=" . trim($p['category_id']);
        }
        if (isset($p['parent_id'])) {
            $qw[] = " parent_id=" . trim($p['parent_id']);
        }
        if (isset($p['category'])) {
            $qw[] = 'category =\'' . htmlspecialchars(addslashes(trim($p['category']))) . '\'';
        }
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $res = $conn->Execute($sql);
        if ($res) {
            return true;
        } else return false;
    }
    
    function getInfo($cate_id)
    {
        global $conn;
        $sql = "SELECT * FROM category WHERE category_id=$cate_id";
        return $conn->getRow($sql);
    }
    
    function getChildren($pid)
    {
        global $conn;
        if ($pid < 0) return false;
        $sql = "SELECT * FROM category WHERE parent_id={$pid} ORDER BY category_id";
        $children =& $conn->GetAll($sql);
        return $children;
    }
    
    function getAll($cond = null)
    {
        global $conn;
        $all = array();
        //get all root category
        $sql = "SELECT * FROM category WHERE parent_id=0 ORDER BY category_id";
        $rs =& $conn->Execute($sql);
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $all[$i] = $rs->fields;
                $children = $this->getChildren($rs->fields['category_id']);
                if (!empty($children)) {
                    $all[$i]['children'] = $children;
                }
                $i++;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        
        return $all;
    }

    // add by liu shu fen 19:11 2007-12-20
    function getAllCategoryByCategoryId($cat_ids = '', $is_list=false) {
        global $conn;
        $qw .= ' WHERE is_hidden = 0';
        if (!empty($cat_ids)) {
            if (is_array($cat_ids)) {
                $qw = " AND category_id IN (" . implode(",", $cat_ids) . ")";
            } else {
                $qw = " AND category_id=" . trim($cat_ids);
            }
        }
        $sql = "SELECT * FROM category";
        if (!empty($qw)) {
            $sql .= $qw;
        }
        $sql .= ' ORDER BY parent_id,  category ';
        $res = $conn->Execute($sql);
        if ($res) {
            $pcategories = $pids = array();
            while (!$res->EOF ) {
                $category_id = $res->fields['category_id'];
                $category = $res->fields['category'];
                if ($is_list) {
                    $all_cat[$category_id] = $category;
                } else {
                    $parent_id = $res->fields['parent_id'];
                    if ($parent_id > 0) {
                        if (!isset($all_cat[$parent_id]['chidren'])) $all_cat[$parent_id]['chidren'] = array();
                        $all_cat[$parent_id]['chidren'][$category_id] = '&nbsp;|-' . $category;
                    } else {
                        $all_cat[$category_id]['name'] = $category;
                    }
                }
                $res->MoveNext();
            }
            $res->Close();
        }
        return $all_cat;
    }

    function getIdsByParentId($pid = 0)
    {
        global $conn;
        $sql = "SELECT category_id FROM category ";
        if (!empty($pid) || strlen($pid)) {
            if (is_array($pid)) {
                $qw = " WHERE parent_id IN (" . implode(",", $pid) . ")";
            } else {
                $qw = " WHERE parent_id=" . trim($pid);
            }
        }
        $sql .= $qw;
        $result = $conn->GetAll($sql);
        $list = array();
        if (!empty($result)) {
            foreach ($result as $k => $row) {
                $list[] = $row['category_id'];
            }
        }
        return $list;
    }

    // add by liu shu fen 19:11 2007-12-20
    function getAllCategoryByParentId($pids = '') {
        global $conn;
        $all_cat = array();
        if (!empty($pids)) {
            foreach ($pids as $id) {
                $all_cat[$id] = array('0' => 'Select');
            }
            if (is_array($pids)) {
                $qw = " WHERE parent_id IN (" . implode(",", $pids) . ")";
            } else {
                $qw = " WHERE parent_id=" . trim($pids);
            }
        } else if (strlen($pids)) {
            $qw = " WHERE parent_id=0";
        }
        $sql = "SELECT * FROM category";
        if (!empty($qw)) {
            $sql .= $qw;
        }
        $sql .= ' ORDER BY category ';
        $res = $conn->Execute($sql);
        if ($res) {
            while (!$res->EOF ) {
                $fields = $res->fields;
                if (empty($pids)) {
                    $all_cat[$fields['category_id']] = $fields['category'];
                } else {
                    $all_cat[$fields['parent_id']][$fields['category_id']] = $fields['category'];
                }
                $res->MoveNext();
            }
            $res->Close();
        }
        
        return $all_cat;
    }

    function search($p = array())
    {
        global $conn, $feedback;
        global $g_pager_params;
        $q = 'WHERE c.is_hidden=0 ';
        $sql = "SELECT COUNT(c.category_id) AS count FROM category AS c ". $q;
        $count = $conn->GetOne($sql);
        if ($count == 0 || !isset($count)) {
            return false;
        }
        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
         $sql = "SELECT c.*, pc.category as pcategory  FROM category AS c " .
             "LEFT JOIN category as pc on (pc.category_id=c.parent_id) " . $q . ' ORDER BY category';
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
    }

    
    function getAllCategoryByUserid($user_id, $parend_id = null)
    {
        global $conn;
        $sql = "SELECT c.*, uc.user_id, uc.level, uc.description , uc.sample 
                FROM category c LEFT JOIN ( SELECT * FROM users_categories WHERE user_id=$user_id) uc ON c.category_id=uc.category_id";
        $sql = "SELECT c.*, uc.user_id, uc.level, uc.description,uc.sample   ".
              " FROM category  AS c " . 
              " LEFT JOIN users_categories AS  uc ON (c.category_id=uc.category_id AND uc.user_id=" . $user_id . ') ';
        $conditions = array('c.is_hidden = 0');
        if (strlen($parend_id)  > 0) {
            $conditions[] = 'c.parent_id=' . $parend_id;
        }
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
        $rs = $conn->Execute($sql);
        $root = array();
        if ($rs) {
            while (!$rs->EOF) {
                $cate = $rs->fields;
                $parent_id = $cate['parent_id'];
                if ($parent_id == 0) {
                    $category_id = $cate['category_id'];
                    if (isset($root[$category_id]['children'])) { 
                        $root[$category_id] = $cate + $root[$category_id]['children'];
                    } else {
                        $root[$category_id] = $cate;
                    }
                } else {
                    if (!isset($root[$parent_id]['children'])) $root[$parent_id]['children'] = array();
                    $root[$parent_id]['children'][] = $cate;
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        ksort($root);
        return $root;
        
    }

    function getAllSelectedCategoryByUserid($user_id)
    {
        global $conn;
        $sql = "SELECT c.*, uc.user_id, uc.level, uc.description,uc.sample ".
                  " FROM category  AS c " . 
                  " LEFT JOIN users_categories AS  uc ON c.category_id=uc.category_id " .
                  " WHERE uc.user_id=" . $user_id;
        $rs = $conn->Execute($sql);
        $root = array();
        if ($rs)
            while (!$rs->EOF) {
                $cate = $rs->fields;
                if ($cate['parent_id'] == 0) {
                    $root[$cate['category_id']] = $cate;
                } else {
                    if ($cate['user_id'] > 0) {
                        $root[$cate['parent_id']]['children'][] = $cate;
                    }
                }
                $rs->MoveNext();
            }
        $rs->Close();
        ksort($root);
        return $root;
    }


    function getCategoryGroupByCopeWriters() 
    {
        $all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
        foreach ($all_copy_writer as $user_id=>$user_name) {
        	$cats = self::getAllSelectedCategoryByUserid($user_id);
            $cp_categories[$user_id]['copy_writer'] = $user_name;
            $cp_categories[$user_id]['categories']   = $cats;
        }
        return $cp_categories;
    }

    //add by liu shu fen 20:39 2007-12-20
    function getUserCategoryInfo($p = array()) {
        //TODO
        global $conn;
        $qw[] = 'WHERE  1 ';
        if (isset($p['category_id']) && !empty($p['category_id'])) {
            if (is_array($p['category_id'])) {
                $qw[] = " category_id IN (" . implode(',', $p['category_id']) . ")";
            } else {
                $qw[] = " category_id=" . $p['category_id'];
            }
        }
        if (isset($p['copywriter_id']) && !empty($p['copywriter_id'])) {
            $qw[] = " user_id=" . $p['copywriter_id'];
        }

        $sql = "SELECT * FROM users_categories ";
        if (!empty($qw))
            $sql .= implode(' AND ', $qw);
        $res = $conn->Execute($sql);
        if ($res) {
            while (!$res->EOF) {
                $cat_res[$res->fields['user_id']][] = $res->fields['category_id'];
                $res->MoveNext();
            }
            $res->Close();
            return $cat_res;
        } else return null;
    }//END
}
?>
