<?php
class ZemantaApi {

    function minusUnusedByApiKey($api_key)
    {
        global $conn,$mailer_param;
        $now = time();
        $sql = "UPDATE `zemanta_apis` SET unused_per_day=unused_per_day-2,last_used={$now} WHERE api_key='{$api_key}' AND unused_per_day>0";
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            return true;
        } else {
            $subject = 'zemanta is over the day limit ';
            $body = $subject;
            $address = 'nancy@infinitenine.com'; 
            send_smtp_mail( $address, $subject, $body, $mailer_param );
            return false;
        }
    }

    function setMaxPerDay($api_key)
    {
        global $conn;
        $sql = "UPDATE `zemanta_apis` SET unused_per_day=15000,last_used={$now} WHERE api_key='{$api_key}'";
        $conn->Execute($sql);
    }

}
?>
