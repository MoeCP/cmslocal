<?php

class ClientSetting
{
    
    function getInfo($setting_id)
    {
        global $conn;
        $sql = "SELECT * FROM client_settings WHERE client_setting_uid=$setting_id";
        return $conn->getRow($sql);
    }

    function getAllByParam($param = array())
    {
        global $conn;
        $conditions = array();
        foreach ($param as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $subk => $subv) {
                    $v[$subk] = htmlspecialchars(addslashes(trim($subv)));
                }
                $conditions[] = $k . ' IN (\'' . implode("','", $v) . '\')';
            } else {
                $v = htmlspecialchars(addslashes(trim($v)));
                $conditions[] = $k . '=\'' . $v . '\'';
            }
        }
        $sql = "SELECT * FROM client_settings  WHERE " .  implode(" AND ", $conditions);
        return $conn->getAll($sql);
    }
}
?>
