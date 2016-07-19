<?php
require_once 'pre_cron.php';
require_once COMMON_PATH . 'netsuite'  . DS . 'PHPtoolkit.php';
$users = User::getAllUsers();

require_once 'netsuite_login_info.php';
global $myNSclient;
$itemSearch = new nsComplexObject("VendorSearchBasic");
foreach ($users as $i => $user) {
    $phone = preg_replace("/[^0-9]+/", "", $user['phone']);
    if (empty($phone)) continue;
    $itemId = substr($phone,0,3) . '-' . substr($phone,3,3) . '-' . substr($phone, 6);
    $itemSearch->setFields(array('phone' => array(	"operator" 		=> "is",
                                                        "searchValue"	=>$itemId)));
    $myNSclient->setSearchPreferences(false, 10);
    $searchResponse = $myNSclient->search($itemSearch);
   
   if (!$searchResponse->isSuccess) {
        echo $user['phone'] . "\t" . $user['user_id'] . "\t" . $searchResponse->statusDetail[0]->message;
    } else {
        //require_once 'displaySearchResults.php';

        // echo "<font color='green'><b> - Found $searchResponse->totalRecords records</b></font>";
        //displayResults($searchResponse, false);
        if (is_array($searchResponse->recordList)) {
            foreach ( $searchResponse->recordList as $record ) {
                $internalId = $record->getField('internalId');
                $entityId = $record->getField('entityId');
                echo $user['phone'] . "\t" . $itemId . "\t". $internalId . "\t" . $entityId . "\t" . $user['user_id']. "\n";
            }
        } else {
            //var_dump($searchResponse);
            // echo $user['phone'] . "\n";
        }
    }
}
?>