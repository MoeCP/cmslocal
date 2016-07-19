<?php
class Notification {
    function getListByParam($param = array(), $fields = array('notification_id'), $order = 'generate_date DESC', $is_all = false)
    {
        global $conn;
        if (!$is_all) $param['is_hidden'] = 0;
        $sql = self::__genarateSql($param, $fields, $order );
        $rs = $conn->Execute($sql);
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                if (count($fields) == 1)
                    $result[$i] = $rs->fields[$fields[0]];
                else
                    $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        return $result;
    }

    function __genarateSql($param = array(), $fields = array('*'), $order = 'notification_id')
    {
         $conditions = self::__disposeParam($param);
         $q = "SELECT " . implode(", ", $fields) . " FROM notifications ".
             "WHERE " . implode(" AND ", $conditions) . " ORDER BY " . $order;
         return $q;
    }
    
    function __genarateDelSql($param = array()) {
         $conditions = self::__disposeParam($param);
         $q = "DELETE FROM notifications ".
             "WHERE " . implode(" AND ", $conditions);
         return $q;
    }

    function deleteByParam($param)
    {
        global $conn;
        $sql = self::__genarateDelSql($param);
        return $conn->Execute($sql);
    }

    function hiddenNotification($param)
    {
        global $conn;
        $conditions = self::__disposeParam($param);
        $q = "UPDATE notifications SET is_hidden = 1 ".
             "WHERE " . implode(" AND ", $conditions);
        return $conn->Execute($q);
    }

    function updateByParam($arr, $param)
    {
        global $conn;
        $conditions = self::__disposeParam($param);
        $q = "UPDATE notifications SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $sets[] = $k . '=\'' . $v . '\'';
        }
        $q .= implode(" , ", $sets);
        $q .= "WHERE " . implode(" AND ", $conditions);
        return $conn->Execute($q);
    }


    function __disposeParam($param = array())
    {
        $conditions = array();
        foreach ($param as $k => $value)
        {
            switch ($k )
            {
            case 'not in':
                foreach ($value as $key => $item)
                {
                    if (is_array($item)) $conditions[] = "{$key} NOT IN ('" . implode("', '", $item) . "') ";
                    else $conditions[] =  $key. "!='{$item}'";
                }
                break;
            case '>':
            case '<':
            case '!=':
            case ">=":
            case "<=":
                foreach ($value as $key => $item)
                {
                    $conditions[] =  $key. $k . "'{$item}'";
                }
                break;
            default:
                if (is_array($value)) {
                    $conditions[] = "{$k} IN ('" . implode("', '", $value) . "') ";
                } else {
                    $conditions[] = "{$k}='{$value}'";
                }
            }
        }
        return $conditions;
    }

    function save($arr) {
        global $conn;
        $param = $arr;
        foreach ($arr as $k => $v) {
            $arr[$k] = addslashes($v);
        }
        $arr_keys = array_keys($arr);
        $q = "INSERT INTO `notifications` (" . implode(",", $arr_keys).") VALUES ('" . implode("','", $arr) . "')";
        return $conn->Execute($q);
    }

    function getNoticesByUserID($user_id)
    {
        $result = array();
        $result['notifications'] = Notification::getListByParam(array('user_id'=> $user_id), array('generate_date', 'field_name','campaign_name','notes', 'campaign_id', 'notification_id'), 'generate_date DESC ,campaign_name ');
        $result['total_notifications'] = count($result['notifications']);
        return $result;
    }
}
?>
