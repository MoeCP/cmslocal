<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Client.class.php';
require_once CMS_INC_ROOT . '/Campaign.class.php';
require_once CMS_INC_ROOT . '/Category.class.php';
// $admins = User::getAllUsers('username_email_only', 'admin');
$campaigns = Campaign::getCampaignFromApi();
$total = count($campaigns);
$cids = array();
for ($i=0;$i<$total;$i++) {
    $hash = $campaigns[$i];
    Campaign::sendAnnouceMail($hash, $hash['user_name']);
    $cids[] = $hash['campaign_id'];
}
Campaign::updateFieldsByCampaignId($cids, array('is_sent' => 1));
?>
