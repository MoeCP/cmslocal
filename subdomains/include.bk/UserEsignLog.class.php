<?php
class UserESignLog {

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

        if ($config_id > 0)
        {
            $q = 'UPDATE user_esign_logs SET ';
            foreach ($p as $k => $value)
            {
                $sets[] = "{$k} = '{$value}'";
            }
            $q .= implode(", ", $sets);
            $q .= " WHERE config_id='{$config_id}'";
            
        }
        else
        {
            if (isset($p['config_id'])) {
                unset($p['config_id']);
            }
            $fields = array_keys($p);
            $q = "INSERT INTO user_esign_logs (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $p)."')";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $extra_info_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getInfoById($id)
    {
        global $conn, $feedback;
        $sql = 'SELECT * FROM user_esign_logs where esign_id=' . $id;
        return $conn->GetRow($sql);
    }

    function getInfosByParam($p)
    {
        global $conn, $feedback;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        $qw = array();
        if (isset($esign_id) && $esign_id > 0) {
            $qw[] = 'uel.esign_id=' . $esign_id;
        }
        $sql = 'SELECT * FROM user_esign_logs as uel ' . (empty($qw) ? '': 'WHERE ' . implode(",", $qw)); 
        return $conn->GetAll($sql);
    }

    function getDefaultConfig()
    {
        global $conn, $feedback;
        $sql = 'SELECT * FROM user_esign_logs where is_default=1';
        return $conn->GetRow($sql);
    }

    function maxCreatedByParam($p)
    {
        global $conn, $feedback, $g_pager_params;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        $qw = array();
        if (isset($esign_id) && $esign_id > 0) {
            $qw[] = 'uel.esign_id=' . $esign_id;
        }
        $sql = 'SELECT max(created) from user_esign_logs as uel ' . (empty($qw) ? '' : 'WHERE ' . implode(",", $qw));
        return $conn->GetOne($sql);
    }

    function storeBatch($logs)
    {
        global $conn;
        if (!empty($logs)) {
            $keys = array_keys($logs[0]);
            $sql = 'INSERT INTO `user_esign_logs` ( `' . implode("`,`", $keys) . '` ) VALUES ';
            $rows = array();
            foreach ($logs as $log) {
                foreach ($log as $k => $v) {
                    $log[$k] = addslashes(htmlspecialchars(trim($v)));
                }
                $rows[] = '(\'' .  implode("','", $log) . '\')';
            }
            $sql .= implode(",", $rows);
            $conn->Execute($sql);
        }
    }
}//end class Preference
?>