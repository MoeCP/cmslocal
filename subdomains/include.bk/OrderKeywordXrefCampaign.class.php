<?php
class OrderKeywordXrefCampaign {

 
    function save($arr) 
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                $arr[$k] = serialize($value);
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        extract($arr);
        
        $conn->StartTrans();

        if (empty($xref_id)) {
            $keys = array_keys($arr);
            $q = "INSERT INTO `order_keyword_xref_campaigns` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            unset($arr['payment_id']);
            $q = "UPDATE `order_keyword_xref_campaigns` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE xref_id='{$xref_id}'";
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

    function getKeywordIDByCampaignId($order_id)
    {
        global $conn;
        $q = "SELECT keyword_id FROM `order_keyword_xref_campaigns` WHERE campaign_id = '{$campaign_id}'";
        return $conn->GetOne($q);
    }
}
?>
