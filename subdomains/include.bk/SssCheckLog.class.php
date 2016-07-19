<?php
class SssCheckLog {

    function save($arr) 
    {
        global $conn;
        $keys = array_keys($arr);
        $arr = SssCheckLog::safeData($arr);
        $q = "INSERT INTO `sss_check_logs` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        return $conn->Execute($q);
    }

    function safeData($data)
    {
        foreach ($data as $k => $value)  {
            $data[$k] = addslashes($value);
        }
        return $data;
    }

    function batchSave($arr) 
    {
        global $conn;
        if (!empty($arr)) {
            $keys = array_keys($arr[0]);
            $values = array();
            foreach ($arr as $row) {
                $row = self::safeData($row);
                $values[] = "('" . implode("','", $row) . "')";
            }
             $q = "INSERT INTO `sss_check_logs` (`" . implode('`,`', $keys)."`) VALUES " . implode(", ", $values);
             return $conn->Execute($q);
        }
    }
    
    function mArticleId($func = 'MAX')
    {
        global $conn;
        $q = "SELECT " . $func. "(article_id) FROM `sss_check_logs` ";
        return $conn->GetOne($q);
    }

    function getTotalGroupByCopyWriterId()
    {
        global $conn;
        $q = "SELECT COUNT(*) AS total, copy_writer_id FROM `sss_check_logs` GROUP BY copy_writer_id ";
        $result = $conn->GetAll($q);
        $list = array();
        foreach ($result as $row) {
            $list[$row['copy_writer_id']] = $row['total'];
        }
        return $list;
    }
}
?>
