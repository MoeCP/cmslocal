<?php
class UserESign {

     /**
     * store article extra info
     *
     * @param array $p
     *
     * @return boolean/int  if success will return true，else return false
     */
    function store($p = array())
    {
        global $conn, $feedback;
        foreach ($p as $k => $v) {
            if (is_string($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            } else if ($k == 'docs' || $k == 'fields'){
                $p[$k] = addslashes(serialize($v));
            }
        }

        extract($p);

        if ($esign_id > 0)
        {
            $q = 'UPDATE user_esigns SET ';
            foreach ($p as $k => $value)
            {
                $sets[] = "{$k} = '{$value}'";
            }
            $q .= implode(", ", $sets);
            $q .= " WHERE esign_id='{$esign_id}'";
            
        }
        else
        {
            if (isset($p['esign_id'])) {
                unset($p['esign_id']);
            }
            $fields = array_keys($p);
            $q = "INSERT INTO user_esigns (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $p)."')";
        }
        $rs = $conn->Execute($q);
        if (empty($esign_id)) $esign_id = $conn->Insert_ID();

        if ($rs) {
            $feedback = 'Success';
            return $esign_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getInfoById($id)
    {
        global $conn, $feedback;
        $sql = 'SELECT ue.*, u.user_name, ueg.user_id ' 
            .'FROM user_esigns AS ue '
            .'LEFT JOIN user_esign_groups AS ueg ON (ueg.group_id = ue.group_id) '
            .'LEFT JOIN users AS u ON (u.user_id = ueg.user_id) '
            .'where esign_id=' . $id;
        return $conn->GetRow($sql);
    }

    function getInfoByUserId($user_id)
    {
        global $conn, $feedback;
        $sql = 'SELECT ue.*, ueg.user_id ' 
            .'FROM user_esigns AS ue '
            .'LEFT JOIN user_esign_groups AS ueg ON (ueg.group_id = ue.group_id) '
            .'LEFT JOIN users AS u ON (u.user_id = ueg.user_id) '
            .'WHERE ueg.user_id=' . $user_id 
            .' ORDER BY ue.signed DESC ';
        $result = $conn->GetAll($sql);
        foreach ($result as $k => $row) {
            foreach ($row as $subk => $v) {
                if ($subk == 'docs' || $subk == 'fields') {
                    $row[$subk] = unserialize($v);
                }
            }
            $result[$k] = $row;
        }
        return $result;
    }

    function getByGroupId($group_id)
    {
        global $conn, $feedback;
        $sql = 'SELECT ue.* ' 
            .'FROM user_esigns AS ue '
            .'where group_id=' . $group_id;
        $result = $conn->GetAll($sql);
        foreach ($result as $k => $row) {
            $result[$k]['sub'] = UserEsignLog::getInfosByParam(array('esign_id' => $row['esign_id']));
            $result[$k]['docs'] = unserialize($row['docs']);
        }
        return $result;
    }

    function getDefaultConfig()
    {
        global $conn, $feedback;
        $sql = 'SELECT * FROM user_esigns where is_default=1';
        return $conn->GetRow($sql);
    }

    function storeData($p, $documentKey, $docs)
    {
        $p['doc_key'] = $documentKey;
        $p['estatus'] = 4;
        $p['dstatus'] = 1;
        $now = date("Y-m-d H:i:s");
        $p['sent'] = $now;
        $p['docs'] = $docs;
        return $p;
    }
    
    function search($p, $is_page = true)
    {
        global $conn, $feedback, $g_pager_params;
        $qw = array();
        foreach ($p as $k => $v) {
            if (isset($p['el'])) {
                $elc = $p['el'];
                foreach ($elc as $k2 => $v2) {
                    $v2 = addslashes(htmlspecialchars(trim($v2)));
                    $qw[] = "ue.{$k2} <=" . $v2;
                }
                unset($p['el']);
            }
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);

        if (isset($user_id) && $user_id > 0) {
            $qw[] = 'ue.user_id=' . $user_id;
        }
        if (isset($estatus) && $estatus > 0) {
            $qw[] = 'ue.estatus=' . $estatus;
        }
        if (isset($keyword) && $keyword != '') {
            $fields = array('ueg.email', 'ueg.message', 'ueg.title', 'ue.doc_key', 'ue.docs', 'ue.title');
            $qw[] = keyword_search($keyword, $fields);
        }
        $tables  = ' FROM user_esigns AS ue ' 
                        .'LEFT JOIN user_esign_groups AS ueg ON (ueg.group_id = ue.group_id) '
                        . 'LEFT JOIN users AS u ON (u.user_id =ueg.user_id) ';
        $where = empty($qw) ? '' : ' WHERE ' . implode(" AND ", $qw);
        $sql = 'SELECT ue.*, u.user_name, ueg.user_id,u.w9_status, u.vendor_id ' . $tables . $where;
        if ($is_page) {
            $perpage = 50;
            if (trim($_GET['perPage']) > 0) {
                $perpage = $_GET['perPage'];
            }
            $pager = get_pager($perpage, "SELECT COUNT(ue.esign_id) " . $tables . $where);
            if (is_object($pager)) {
                $result = get_result_by_pager($pager, $sql , $perpage);
                return array('pager'  => $pager->links,
                             'total'  => $pager->numPages(),
                             'result' => $result);
            } else {
                return false;
            }
        } else {
            return $conn->GetAll($sql);
        }
    }

}//end class Preference
?>