<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Client.class.php';
require_once CMS_INC_ROOT . '/Campaign.class.php';
require_once CMS_INC_ROOT . '/Email.class.php';
//$admins = User::getAllUsers('username_email_only', 'admin');
$campaigns = Campaign::getCampaignByParam(array('cc.has_new' => 1));
$total = count($campaigns);
$cids = array();
for ($i=0;$i<$total;$i++) {
    $hash = $campaigns[$i];
    $info = array(
        'campaign_name' => $hash['campaign_name'],
        'client_name' => $hash['user_name'],
    );
    // added by nancy xu 2011-02-15 17:31
    // sent new keyword email to pm
    $campaign_id = $hash['campaign_id'];
    $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_id));
    //end
    Email::sendNewKeywordMail($info, $client_pm['email']);
    $cids[] = $hash['campaign_id'];
}
Campaign::updateFieldsByCampaignId($cids, array('has_new' => 0));
?>
