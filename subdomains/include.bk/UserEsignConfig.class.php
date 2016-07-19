<?php
class UserEsignConfig {

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
            } else if ($k == 'docs' || $k == 'params' || $k == 'libs'){
                $p[$k] = serialize($v);
            }
        }
        extract($p);
        $conn->StartTrans();
        if ($config_id > 0)
        {
            $q = 'UPDATE user_esign_config SET ';
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
            $q = "INSERT INTO user_esign_config (" . implode(", ", $fields). ") ".
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
        $sql = 'SELECT * FROM user_esign_config where config_id=' . $id;
        return $conn->GetRow($sql);
    }

    function getAll($p = array())
    {
        global $conn;
        $sql =  'SELECT * FROM user_esign_config';
        $result = $conn->GetAll($sql);
        $rs = array();
        foreach ($result as $row) {
            $rs[$row['config_id']] = $row;
        }
        return $rs;
    }

    function getDefaultConfig()
    {
        global $conn, $feedback;
        $sql = 'SELECT * FROM user_esign_config where is_default=1';
        $result = $conn->GetRow($sql);
        $result['params'] = unserialize($result['params']);
        $result['libs'] = unserialize($result['libs']);
        $result['docs'] = unserialize($result['docs']);
        return $result;
    }

}//end class Preference
?>