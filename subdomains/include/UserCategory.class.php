<?php
class UserCategory
{
    function storeCategories($p, $current_user_id, $role) {
        global $conn, $feedback;
        $len = count($p['parent_id']);
        $rows = array();
        if ($len > 0) {
            for ($i = 0; $i < $len; $i++) {
                $parent_id = $p['parent_id'][$i];
                $cids = $p['category_id'][$parent_id];
                $desc = addslashes(trim($p['descs'][$parent_id]));
                $name = $p['category'][$parent_id];
                if (strlen($desc) == 0) {
                    $feedback = 'Please sepcify the description for ' . $name;
                    return false;
                }
                $sample = addslashes(trim($p['sample'][$parent_id]));
                if (strlen($sample) == 0) {
                    $feedback = 'Please sepcify the sample for ' . $name;
                    return false;
                }
                $plevel = 0;
                if (!is_array($p['level'][$parent_id])) {
                    $plevel = trim($p['level'][$parent_id]);
                    if (strlen($plevel) == 0) {
                        $feedback = 'Please sepcify the level for ' . $name;
                        return false;
                    }
                } else {
                    if (count($cids)) {
                        foreach ($cids as $k => $v) {
                            $level = $p['level'][$parent_id][$v];
                            $name = $p['category'][$v];
                            if (strlen($level) == 0) {
                                $feedback = 'Please sepcify the level for ' . $name;
                                return false;
                            }
                            $rows[] = "($current_user_id, {$v}, '{$role}', '{$level}',  '', '', {$parent_id})";
                        }
                    } else {
                        $feedback = 'Please sepcify sub category for ' . $name;
                        return false;
                    }
                }
                $rows[] =  "($current_user_id, {$parent_id}, '{$role}', '{$plevel}',  '{$desc}', '{$sample}', 0)";
            }
            $sql = "INSERT INTO users_categories VALUES ";
            
            $sql .= implode(',', $rows);
            $conn->Execute($sql);
        }
        return true;
    }
    
    function delete($p)
    {
        //TODO
        global $conn;
        $sql = "DELETE FROM users_categories ";
        $qw[] =  " WHERE 1 ";
        if (isset($p['category_id']) && trim($p['category_id']) != 0) {
            $qw[] = " category_id=" . trim($p['category_id']);
        }
        if (isset($p['user_id'])) {
            $qw[] = " user_id=" . trim($p['user_id']);
        }
        if (isset($p['role'])) {
            $qw[] = 'role=\'' . htmlspecialchars(addslashes(trim($p['role']))) . '\'';
        }
        if (isset($p['level'])) {
            $qw[] = 'level=\'' . htmlspecialchars(addslashes(trim($p['level']))). '\'';
        }
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $res = $conn->Execute($sql);
        if ($res) {
            return true;
        } else return false;
    }

    function store($p)
    {
        global $conn;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes($v);
        }
        $keys = array_keys($p);
        $sql = "INSERT INTO users_categories (`" . implode("`,`", $keys) . "`) VALUES ";
        $sql .= '(\'' . implode("','", $p) . '\')';
        $res = $conn->Execute($sql);
        $parend_id = $p['parent_id'];
        $user_id = $p['user_id'];
        return true;
    }
    
    function getInfo($cate_id, $user_id)
    {
        global $conn;
        $sql = "SELECT * FROM users_categories WHERE category_id={$cate_id} and user_id={$user_id}";
        return $conn->getRow($sql);
    }
    
    function userCategories($p, $is_page = true)
    {
        global $conn;
        $qw = array();
        $from = 'FROM users_categories AS uc ';
        $from .= 'LEFT JOIN users AS u ON u.user_id=uc.user_id ';
        $from .= 'LEFT JOIN category AS c ON c.category_id =uc.category_id ';
        if (isset($p['cp_category']) && !empty($p['cp_category'])) {
            $cp_category = trim($p['cp_category']);
            if (is_numeric($cp_category)) {
                $qw[] = 'uc.category_id=' . $cp_category;
            }
        }
        if (isset($p['user_id']) && !empty($p['user_id'])) {
            $user_id = trim($p['user_id']);
            if (is_numeric($user_id)) {
                $qw[] = 'uc.user_id=' . $user_id;
            }
        }
        $where = empty($qw) ? '' : ' WHERE ' . implode(' AND ', $qw);
        $sql = 'SELECT uc.*, u.user_name, u.first_name, u.last_name, u.sex, u.email, c.category  ';
        $sql .= $from . $where ;
        if ($is_page) {
            global $g_pager_params;
            $q = 'SELECT COUNT(*) ' . $from . $where;
            $count = $conn->GetOne($q);
            if ($count == 0 || !isset($count)) {
                return false;
            }
            $perpage = 50;
            if (trim($p['perPage']) > 0) {
                $perpage = $p['perPage'];
            }
            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
            if ($rs) {
                 $result = array();
                while (!$rs->EOF) {
                    $result[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
        } else {
            return $conn->GetAll($sql);
        }
    }

}
?>
