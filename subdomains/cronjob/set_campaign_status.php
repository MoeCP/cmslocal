<?php
/*
*Creator: Snug Xu
*Created Time: 14:19 2006-8-25
*Function Description: check whether campaign is finished or not, if the campaign is finished set status as completed
*Statsu Value Description:0 means uncompleted; 1 means completed
*/
require_once 'pre_cron.php';//parameter settings

$p = array( 
			'is_single_status' => 0, 
			'ar_status' => '^(0|1|2|3|1gc)$'
		);
$campaign_ids = Campaign::getCampaignIdsByArticleStatus($p);//get all completed campaigns
if(count($campaign_ids))
{
	if(Campaign::setCampaignStatus('1', $campaign_ids, '1') && Campaign::setCampaignStatus('0', $campaign_ids, '0'))
		echo "Seccuss";
	else
		echo "Failed";
}
?>