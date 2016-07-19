<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
ini_set('max_execution_time', 0);
require_once CMS_INC_ROOT . DS . 'QuickBook.php';//load google search API
$oQB = new QuickBook($g_pay_user);
$oQB->login();
$txnDates = array('2011-10-31', '2011-11-15', '2011-11-30');
$bill_xml = '<BillQueryRq><TxnDateRangeFilter><FromTxnDate>2011-11-30</FromTxnDate><ToTxnDate>2011-11-30</ToTxnDate></TxnDateRangeFilter></BillQueryRq>';
$oQB->getAPI()->qbxml($bill_xml, 'xmlParserCallBack');
$check_xml = '<CheckQueryRq><TxnDateRangeFilter><FromTxnDate>2011-11-30</FromTxnDate><ToTxnDate>2011-11-30</ToTxnDate></TxnDateRangeFilter><EntityFilter><ListID>293</ListID><ListID>117</ListID></EntityFilter></CheckQueryRq>';
$oQB->getAPI()->qbxml($check_xml, 'xmlParserCallBack');
function xmlParserCallBack($method, $action, $ID, $err, $qbxml, $qbobject, $qbres)
{
    echo $method . '<br />';
    echo $action . '<br />';
    echo $ID . '<br />';
    print_r($qbxml . '<br />');
    print_r($qbobject);
    if (empty($err)) {
        $result = simplexml_load_string($qbxml);
        print_r($qbxml);
        $data = array();
    }
}
?>
