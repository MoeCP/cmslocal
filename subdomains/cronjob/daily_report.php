<?php
    require_once 'pre_cron.php';//parameter settings
    require_once CMS_INC_ROOT . '/article_type.class.php';
    
    // added by snug xu 10:42 2007-05-21 - STARTED
    // get all article type
    $all_article_types = $g_tag['article_type'];
    // added by snug xu 10:42 2007-05-21 - FINISHED
	//get the campaign report
	$campaign_reports = array();
	$campaigns = Article::getTotalArticleGroupByCampaignID();
	$i=0;
	$body .= '<table align="right" width="100%"  cellspacing="1" cellpadding="4" >
				<tr style="font-family: \'Arial\', \'Simsun\', \'Verdana\', \'Helvetica\';font-weight: bold;font-size: 12px;color: #ffffff;text-decoration: none;background-color: #356799;">
				<th align="center" height="25" rowspan="2" >Campaign Name</th>
				<th align="center" colspan="' . count($all_article_types) . '">Total Submitted Today</th>
				<th align="center" rowspan="2">Total Completed UPTO DATE</th>	
				<th align="center" rowspan="2">Total Possible Duplication</th>';
    foreach ($all_article_types as $k => $v) {
	    $body .= "<th align=\"cente\" rowspan=\"2\">{$v}</th>";
    }
	$body .= '<th align="center" rowspan="2">Total Articles</th>
				<th align="center" rowspan="2">Start Date</th>
				<th align="center"rowspan="2">End Date</th>
				</tr>
				<tr>';
    foreach ($all_article_types as $k => $v) {
	    $body .= "<th align=\"cente\" >{$v}</th>";
    }
	$body .= '</tr>';
	foreach( $campaigns as $campaign_id => $campaign  )
	{
		$body .= '<tr style="background:#eeeecc;">';
		$body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;"><a href="'. $baseUrl . '/client_campaign/keyword_list.php?campaign_id=' . $campaign['campaign_id'] . '"><b>'. $campaign['campaign_name'] . '</b></a></td>';
		$campaign_reports[$campaign_id] = $campaign;
		$submitted_total = Article::getTotalSubmittedAritcleByCampaignID($campaign_id, false);
		if( count($submitted_total) )
		{
			$campaign_reports[$campaign_id]['submitted'] = $submitted_total[$campaign_id];
		}
		else
		{
			$campaign_reports[$campaign_id]['submitted']=0;
		}
		//START::Added By Snug 10:45 2006-8-9
		/** get total of possible gogole duplicated articles **/
		$campaign_reports[$campaign_id]['google_duplicated_total'] = Campaign::countArticleBySubWhere('1gd',1,', campaign_keyword AS ck', "AND ck.keyword_id=ar.keyword_id AND ck.campaign_Id=$campaign_id AND ck.status!='D' ");
		if( $campaign_reports[$campaign_id]['google_duplicated_total']>0 )
		{
			$campaign_reports[$campaign_id]['google_duplicated_total']= '<a href="'. $baseUrl . '/client_campaign/keyword_list.php?campaign_id='. $campaign['campaign_id'] . '&article_status=1gd&perPage=50" />' . $campaign_reports[$campaign_id]['google_duplicated_total']. '</a>';
		}
		//END Added
		$today_submitted_total = Article::getTotalAritcleGroupByArticleTypeByCampaignID($campaign_id, true, true);
		if( count( $today_submitted_total ) )
		{
			$campaign_reports[$campaign_id]['today_submitted'] = $today_submitted_total;
		}
		$total_article_types = Article::getTotalAritcleGroupByArticleTypeByCampaignID($campaign_id);
		foreach( $total_article_types as $article_type => $total )
		{
			$campaign_reports[$campaign_id][$article_type] = $total; 
		}
		foreach ($all_article_types as $i => $val)
		{
			$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">';
			if ($campaign_reports[$campaign_id]['today_submitted'][$i] > 0) 
			{		
				$body .= '<a href="'. $baseUrl . '/client_campaign/keyword_list.php?campaign_id='. $campaign['campaign_id'] . '&article_status=4&article_type=' . $i . '&perPage=50&is_today=1" />' . $campaign_reports[$campaign_id]['today_submitted'][$i]. '</a>';
			}
			else
				$body .= '0';
			$body .= '</td>';
		}
		$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $campaign_reports[$campaign_id]['submitted'] . '</td>';
		$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $campaign_reports[$campaign_id]['google_duplicated_total'] . '</td>';
		foreach ($all_article_types as $i => $val)
		{
			$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">';
			if ($campaign_reports[$campaign_id][$i] > 0) 
			{		
				$body .= $campaign_reports[$campaign_id][$i];
			}
			else
				$body .= '0';
			$body .= '</td>';
		}
		$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $campaign['total'] . '</td>';
		$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $campaign['date_start'] . '</td>';
		$body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $campaign['date_end'] . '</td>';
		$body .= "</tr>";
	}
	$body .= "</table>";
	require_once MAILER_INC_ROOT.'/class.phpmailer.php';
	$admins  = User::getAllUsers('all_infos', 'admin');
	$subject = 'Daily Report for Campaigns';
    global $mailer_param;

	foreach( $admins as $k => $admin)
	{
		if( strlen( $admin['email']))
			send_smtp_mail($admin['email'], $subject, $body, $mailer_param);
	}
?>