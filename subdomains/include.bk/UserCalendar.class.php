<?php
class UserCalendar {
    function getListByParam($param = array(), $fields = array('c_date'))
    {
        global $conn;
        $sql = self::__genarateSql($param, $fields);
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
                $i ++;
            }
            $rs->Close();
        }
        return $result;
    }

    function __genarateSql($param = array(), $fields = array('*'), $order = 'id')
    {
         $conditions = self::__disposeParam($param);
         $q = "SELECT " . implode(", ", $fields) . " FROM user_calendar ".
             "WHERE " . implode(" AND ", $conditions) . " ORDER BY " . $order;
         return $q;
    }
    
    function __genarateDelSql($param = array()) {
         $conditions = $this->__disposeParam($param);
         $q = "DELETE FROM user_calendar ".
             "WHERE " . implode(" AND ", $conditions);
         return $q;
    }


    function __disposeParam($param = array())
    {
        $conditions = array();
        foreach ($param as $k => $value)
        {
            switch ($k )
            {
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
                $conditions[] = "{$k}='{$value}'";
            }
        }
        return $conditions;
    }

    function getUnFreeDate($from, $to, $user_id, $fields=array('c_date')) {
        $param = array(
            '>='=>array('c_date'=>$from),
            '<='=>array('c_date'=>$to),
            'user_id'=>$user_id,
        );
        return $this->getListByParam($param, $fields);
    }

    function save($arr) {
        global $conn;
        $param = $arr;
        $result = self::getListByParam($param);
        if (!empty($result)) {
            $sql = self::__genarateDelSql($param);
            return $conn->Execute($sql);
        } else {
            $q = "INSERT INTO `user_calendar` (`user_id` ,`user_name` ,`c_date` ,`role` ,`is_free`)
                  VALUES ('{$arr['user_id']}', '{$arr['user_name']}', '{$arr['c_date']}', '{$arr['role']}', '{$arr['is_free']}')";
            return $conn->Execute($q);
        }
    }
}
?>
