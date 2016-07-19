<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'ArticleSearchresult.class.php';//parameter settings
$time = time() - 86400;
$conn->debug = true;
$last_date = date("Y-m-d H:i:s", $time);
$conditions = array('asr.created > \'' . $last_date . '\'');
$result = ArticleSearchresult::getResult(array('limit' => 1, 'orderby' => 'asr.search_id DESC', 'conditions' => $conditions));
if (!empty($result)) {
    $xml = html_entity_decode($result[0]['response']);
    $obj = get_object_vars(@simplexml_load_string($xml));
    if (isset($obj['error'])) {
        copyscope_credit_alert($obj['error']);
    }
}
?>
