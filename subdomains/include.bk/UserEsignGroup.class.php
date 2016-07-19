<?php
class UserESignGroup {

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
            } else if ($k == 'docs'){
                $p[$k] = serialize($v);
            }
        }

        extract($p);

        if ($group_id > 0)
        {
            $q = 'UPDATE user_esign_groups SET ';
            foreach ($p as $k => $value)
            {
                $sets[] = "{$k} = '{$value}'";
            }
            $q .= implode(", ", $sets);
            $q .= " WHERE group_id='{$group_id}'";
            
        }
        else
        {
            if (isset($p['group_id'])) {
                unset($p['group_id']);
            }
            if (!isset($p['created'])) $p['created'] = date("Y-m-d H:i:s");
            $fields = array_keys($p);
            $q = "INSERT INTO user_esign_groups  (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $p)."')";
        }
        $rs = $conn->Execute($q);
        if (empty($group_id)) $group_id = $conn->Insert_ID();
        if ($rs) {
            $feedback = 'Success';
            return $group_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getInfoById($id)
    {
        global $conn, $feedback;
        $sql = 'SELECT ueg.*, u.user_name ' 
            .'FROM user_esign_groups AS ueg '
            .'LEFT JOIN users AS u on(u.user_id = ueg.user_id) '
            .'where group_id=' . $id;
        $info = $conn->GetRow($sql);
        $info['sub']= UserESign::getByGroupId($id);
        return $info;
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
                    $qw[] = "ueg.{$k2} <=" . $v2;
                }
                unset($p['el']);
            }
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);

        if (isset($user_id) && $user_id > 0) {
            $qw[] = 'ueg.user_id=' . $user_id;
        }
        if (isset($estatus) && $estatus > 0) {
            $qw[] = 'ueg.estatus=' . $estatus;
        }
        if (isset($keyword) && $keyword != '') {
            $fields = array('ueg.email', 'ueg.message', 'ueg.title');
            $qw[] = keyword_search($keyword, $fields);
        }
        $tables  = ' FROM user_esign_groups AS ueg ' 
                        . ' LEFT JOIN users AS u ON (u.user_id =ueg.user_id) ';
        $where = empty($qw) ? '' : ' WHERE ' . implode(" AND ", $qw);
        $sql = 'SELECT ueg.*, u.user_name ' . $tables . $where;
        if ($is_page) {
            $perpage = 50;
            if (trim($_GET['perPage']) > 0) {
                $perpage = $_GET['perPage'];
            }
            $pager = get_pager($perpage, "SELECT COUNT(ueg.group_id) " . $tables . $where);
            if (is_object($pager)) {
                $result = get_result_by_pager($pager, $sql , $perpage, 'user_esigns', 'group_id');
                return array('pager'  => $pager->links,
                             'total'  => $pager->numPages(),
                             'count'  =>count($result),
                             'result' => $result);
            } else {
                return false;
            }
        } else {
            $result = $conn->GetAll($sql);
            if (!empty($result)) {
                $ids = array();
                foreach ($result as $k => $row) {
                    $ids[$k] = $row['group_id'];
                }
                $sql = "SELECT * FROM user_esigns AS ue WHERE ue.group_id IN (" . implode(", ", $ids).  ")";
                $gids = $ids;
                $rs = self::getResultGroupByFk($sql, 'group_id', 'esign_id');
                extract($rs);
                $esigns = $data;
                if (!empty($esigns)) {
                    $sql = "SELECT * FROM user_esign_logs AS uel WHERE uel.esign_id IN (" . implode(", ", $ids).  ")";
                    $rs = self::getResultGroupByFk($sql, 'esign_id', 'log_id');
                    extract($rs);
                    $logs = $data;
                    foreach ($gids as $k => $gid) {
                        if (isset($esigns[$gid])) {
                            $esign = $esigns[$gid];
                            foreach ($esign as $ek => $row) {
                                $esign_id = $row['esign_id'];
                                if (isset($logs[$esign_id]))
                                    $esign[$ek]['sub'] = $logs[$esign_id];
                            }
                            $result[$k]['sub'] = $esign;
                        }
                    }
                }
            }
            return $result;
        }
    }

    function getResultGroupByFk($sql, $fk, $id)
    {
        global $conn;
        $rs = $conn->Execute($sql);
        $ids =  $data = array();
        if ($rs) {
            while (!$rs->EOF) {
                $fields = $rs->fields;
                if (isset($fields['docs'])) {
                    $fields['docs'] = unserialize($fields['docs']);
                }
                $key = $fields[$fk];
                if (!isset($data[$key])) {
                    $data[$key] = array();
                }
                $data[$key][] = $fields;
                $ids[]= $id;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return compact('data', 'ids');
    }

    function storeDocs($p, $oEsign)
    {
        global $conn, $feedback;;
        $conn->StartTrans();
        $send_docs = $p['reusable'];
        unset($p['reusable']);
        $group_id = UserESignGroup::store($p);
        if ($group_id) {
            $data = array('group_id' => $group_id);
            foreach ($send_docs as $v) {
                $arr = explode("%%||||%%", $v);
                $name = $arr[0];
                $libName = array('libraryDocumentKey'=>$arr[1]);
                $p['title'] = $name;
                $documentKey = $oEsign->send($p, $libName);
                $docs = array(array('documentkey' => $arr[1], 'name' => $name));
                if (!empty($documentKey)) {
                    $data['title'] = $name;
                    $data = UserESign::storeData($data, $documentKey, $docs);
                    UserESign::store($data);
                } else {
                    $feedback = 'Failed!';
                }
            }
        }
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
        } else {
            $feedback = 'Failed!';
        }
    }

}//end class Preference
?>