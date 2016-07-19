<?php
class OrderCampaignKeyword {

    function save($arr) 
    {
        global $conn, $feedback;
        // strip redundant "\n"
        extract($arr);
        foreach ($fields as $k => $v) {
            foreach ($arr[$k] as $k1 => $v1) {
                $arr[$k][$k1] = trim($v1);
            }
            $tmp = trim(implode("\n", $arr[$k]), "\n");
            $arr[$k] = explode("\n", $tmp);
        }

        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                $arr[$k] = serialize($value);
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }

        if ($order_id == '') {
            $feedback = "Please Choose a client";
            return false;
        } 
        //$campaign_name = $arr['campaign_name'];
        if (empty($fields)) {
            $feedback = "Please specify the mapping fields";
            return false;
        }
        

        //$order_campaign_id = $arr['order_campaign_id'];
        if (empty($keyword_id)) {
            $keyword_id = self::getIDByOrderId($order_id);
        }
        $conn->StartTrans();
        if (empty($keyword_id)) {
            $arr['created'] = date("Y-m-d H:i:s");
            $keys = array_keys($arr);
            $q = "INSERT INTO `order_campaign_keywords` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            unset($arr['keyword_id']);
            $q = "UPDATE `order_campaign_keywords` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE keyword_id='{$keyword_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }


    function getInfoByOrderId($order_id)
    {
        global $conn;
        $q = "SELECT * FROM `order_campaign_keywords` WHERE order_id = '{$order_id}'";
        $info = $conn->GetRow($q);
        foreach ($info as $k => $v) {
            if ($k != 'keyword_id' && $k != 'order_id' && $k != 'created' && $k != 'is_parsed') {
                $info[$k] = unserialize($v);
            }
        }
        return $info;            
    }


    function getIDByOrderId($order_id)
    {
        global $conn;
        $q = "SELECT keyword_id FROM `order_campaign_keywords` WHERE order_id = '{$order_id}'";
        return $conn->GetOne($q);
    }

    function store($arr)
    {
        global $conn;
        $q = "UPDATE `order_campaign_keywords` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            if ($k == 'keyword_id') {
                $keyword_id = $v;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= "WHERE keyword_id='{$keyword_id}'";
        $conn->Execute($q);
    }

    function getInfo($keyword_id)
    {
        global $conn, $feedback;
        $keyword_id = addslashes(htmlspecialchars(trim($keyword_id)));
        if ($keyword_id == '') {
            $feedback = "Please Choose a  order campaign keyword";
            return false;
        }
        $q = "SELECT * FROM `order_campaign_keywords` WHERE keyword_id = '{$keyword_id}'";
        return $conn->GetRow($q);
    }
}
?>
