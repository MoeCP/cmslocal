<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . "campaign_files.class.php";
require_once CMS_INC_ROOT . DS . "campaign_logs.class.php";
require_once CMS_INC_ROOT . DS . "Category.class.php";
require_once CMS_INC_ROOT . DS . "client_user.class.php";
$start_time = time();
$conn->debug = true;
$oFile = new CampaignFile();
$oLog = new CampaignLog();
$result  = $oLog->search(array('is_parsed' => 0));
global $feedback;
//pr($result);
foreach ($result as $k => $row) {
    Campaign::autoAddCampaignAndKeywords($row, $oLog, $oFile);
}
$diff = time() - $start_time;
echo $diff . "\n";
?>