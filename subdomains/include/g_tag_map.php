<?php
//some globle array in here

$g_tag['user_permission'][5] = 'admin';
$g_tag['user_permission'][4] = 'project manager';
$g_tag['user_permission'][3] = 'editor';
$g_tag['user_permission'][2] = 'agency';
/*$g_tag['user_permission']['1.2'] = 'designer';*/
$g_tag['user_permission'][1] = 'copy writer';
$g_tag['user_type'] = array(1 => 'external', 2 => 'internal');

$g_tag['language']['en'] = 'US English';

if (User::getPermission() >= 4) { // 3=>4
    $g_tag['search_type']['users'] = 'User Info.';
    $g_tag['search_type']['clients'] = 'Client Info.';
}

require_once CMS_INC_ROOT.'/Client.class.php';
if (User::getPermission() >= 4 || client_is_loggedin()) { // 3=>4
    $g_tag['search_type']['campaings'] = 'Campaign Info.';
}
$g_tag['search_type']['keywords'] = 'Keyword Info.';
$g_tag['search_type']['articles'] = 'Article Info.';

//$g_tag['article_type'][1] = 'Keyword rich article';//default
//$g_tag['article_type'][2] = 'Editorial/informational';
//$g_tag['article_type'][3] = 'Distribution/guideline/tips/white paper';

// modified by snug xu 2007-05-15 18:52 - STARTED
$g_tag['article_type'] = ArticleType::getAllTypes(array('parent_id' => -1));
//if (client_is_loggedin()) {
//    $g_tag['leaf_article_type'] = ArticleType::getAllLeafNodes(array('is_hidden' => 0));
//} else {
//    
//}
$g_tag['leaf_article_type'] = ArticleType::getAllLeafNodes();
//$g_tag['article_type'][0] = 'Type 1'; //'Keyword rich article';//default
//$g_tag['article_type'][1] = 'Type 2'; //'Editorial/informational';
//$g_tag['article_type'][2] = 'Type 3'; //'Distribution/guideline/tips/white paper';
// modified by snug xu 2007-05-15 18:52 - FINISHED

$g_tag['article_status'][99] = 'Not Available';
$g_tag['article_status'][-1] = 'Unassigned';
$g_tag['article_status'][0] = 'Writing';
$g_tag['article_status'][1] = 'Copywriter complete';
$g_tag['article_status']['1gd'] = 'Possible Duplication';
$g_tag['article_status']['1gc'] = 'Google Clean';
$g_tag['article_status'][2] = 'Edit Requested';//3
$g_tag['article_status'][3] = 'Client reject';//4
$g_tag['article_status'][4] = 'Editor approved';//2
$g_tag['article_status'][5] = 'Client approved and article finished';
$g_tag['article_status'][6] = 'Published';

// added by nancy xu 2012-11-29 15:45
$g_tag['image_status'][99] = 'Not Available';
$g_tag['image_status'][-1] = 'Unassigned';
$g_tag['image_status'][0] = 'Writing';
$g_tag['image_status'][1] = 'Copywriter complete';
$g_tag['image_status'][2] = 'Edit Requested';//3
$g_tag['image_status'][3] = 'Client reject';//4
$g_tag['image_status'][4] = 'Editor approved';//2
$g_tag['image_status'][5] = 'Client approved and article finished';
$g_tag['image_status'][6] = 'Published';
// end

$g_tag['noflow_status'][10] = 'Edit Requested';
$g_tag['noflow_status'][20] = 'Edit Completed';
$g_tag['noflow_status'][30] = 'Client Ready';

if (User::getPermission() == 3) { 
    $g_tag['article_status'][1] .= ' - Verification pending';
}

$g_tag['email_event'][0] = 'Project manager assign keyword and email to copywriter';
$g_tag['email_event'][11] = 'Project manager assign keyword and email to editor';
$g_tag['email_event'][1] = 'Copywriter complete and email to editor';
$g_tag['email_event'][2] = 'Edit Request';
$g_tag['email_event'][3] = 'Editor approved and email to client';
$g_tag['email_event'][4] = 'Editor approved and CC to copywriter';
$g_tag['email_event'][5] = 'Client reject and email to editor';
$g_tag['email_event'][6] = 'Client reject and CC to copywriter';
$g_tag['email_event'][7] = 'Client approved and email to editor';
$g_tag['email_event'][8] = 'Client approved and CC to copywriter';
$g_tag['email_event'][9] = 'Send warning to copywriter by system check';
$g_tag['email_event'][10] = 'Send warning to editor by system check';
$g_tag['email_event'][12] = 'Reassign after payroll calculation and email to project manager';
$g_tag['email_event'][13] = 'Article has an edit request after payroll calculation.  An email has been sent to the project manager.';
$g_tag['email_event'][14] = 'Editor Auto Reminder';
$g_tag['email_event'][15] = 'Writer Auto Reminder';
$g_tag['email_event'][16] = 'Candidate Application Auto Reminder';
$g_tag['email_event'][17] = 'Candidate Rejection Auto Reminder';
$g_tag['email_event'][18] = 'Candidate Hired Auto Reminder';
$g_tag['email_event'][19] = 'Welcome aboard Auto Reminder';
$g_tag['email_event'][20] = 'Email rejecting extension request';
$g_tag['email_event'][21] = 'Email granting extension request';
$g_tag['email_event'][22] = 'Email extension request to writer';
$g_tag['email_event'][23] = 'Order New Campaign';
$g_tag['email_event'][24] = 'Add New Keyword(s)';
$g_tag['email_event'][25] = 'campaign has completed';
$g_tag['email_event'][26] = 'API Key Email';
$g_tag['email_event'][27] = 'editor approved article notice';
$g_tag['email_event'][28] = 'Email Notifications to writers 48 hours beore the deadline of articles still in writing status ';
$g_tag['email_event'][29] = 'Client welcome email';
//$g_tag['email_event'][30] = 'Ordered a new campaign';
$g_tag['email_event'][30] = 'Copypress Confirm Campaign Order Email';
$g_tag['email_event'][31] = 'Client Confirm Campaign Order Email';
$g_tag['email_event'][32] = 'CopyPress Order Confirmation Receipt';
$g_tag['email_event'][33] = 'Campaign finished notification';
$g_tag['email_event'][34] = 'New Campaign';
$g_tag['email_event'][35] = 'Email extension request to editor';
$g_tag['email_event'][36] = 'Fraud attempt on PayPal account';
$g_tag['email_event'][37] = 'Client add new campaign email';
$g_tag['email_event'][38] = 'Check out editor\'s/writer\'s approved articles';
$g_tag['email_event'][39] = 'Look over  editor\'s/writer\'s client approved articles';
$g_tag['email_event'][40] = 'Writer denied email';


$g_tag['user_role']['admin'] = 'admin';
$g_tag['user_role']['project manager'] = 'project manager';
$g_tag['user_role']['editor'] = 'editor';
$g_tag['user_role']['agency'] = 'agency';
/*$g_tag['user_role']['designer'] = 'designer';*/
$g_tag['user_role']['copy writer'] = 'copywriter';

// article rating added by Snug Xu 2006-10-23 17:07
// Start
$g_tag['rating'][1] = 1;
$g_tag['rating'][2] = 2;
$g_tag['rating'][3] = 3;
$g_tag['rating'][4] = 4;
$g_tag['rating'][5] = 5;
// End

/**
 *nht后面的值表示editor or program manager每隔几篇文章发送一篇给tracy检查一下
 *如果选择$g_tag['nth'][20]那就表示editor每修改20篇，发送一篇文章给tracy检查
**/
$g_tag['auditing_frequency'][1] = '1';
$g_tag['auditing_frequency'][5] = '5';
$g_tag['auditing_frequency'][10] = '10';
$g_tag['auditing_frequency'][20] = '20';
$g_tag['auditing_frequency'][30] = '30';
$g_tag['auditing_frequency'][40] = '40';

// article extra info type
$g_tag['article_extra_info_type'][1] = 'overview';
$g_tag['article_extra_info_type'][2] = 'non-overview';
// geo leave
$g_tag['geolevel'] = array(
    1=>'country',
    2=>'state',
    3=>'city',
);

$g_tag['url']['aol'] = "travel.aol.com/articles/";
$g_tag['xml_dir']['aol']          = BASE_PATH . 'xml' . DS . 'aolxml' . DS ;
$g_tag['xml_dir']['zipsearch'] = BASE_PATH . 'xml' . DS . 'zipsearch' . DS ;
$g_tag['xml_dir']['housing_info'] = BASE_PATH . 'xml' . DS . 'housing_info' . DS ;
$g_tag['xml_dir']['academicinfo'] = BASE_PATH . 'xml' . DS . 'academicinfo' . DS ;
$g_tag['status'] = array(
  'A' => 'Active',
  'D' => 'Inactive',
);
//User Info.
//Keyword Info.
//Article Info.
//Campaing Info.

// copywriter campaign ranking readability, informational_quality, timeliness added by liu shu fen 2007-12-17 11:42
// Start
$g_tag['cp_ranking'][1] = 1;
$g_tag['cp_ranking'][2] = 2;
$g_tag['cp_ranking'][3] = 3;
$g_tag['cp_ranking'][4] = 4;
$g_tag['cp_ranking'][5] = 5;
$g_tag['ranking'] = array(1,2,3,4,5,6,7,8,9,10);
// End

//add by liu shu fen 10:11 2007-12-19
//search choice: all, top 5, top 10, top 20, top 50, top 100, top 200
$g_tag['search_choice'][0] = '[choose]';
$g_tag['search_choice'][1] = 'All';
$g_tag['search_choice'][2] = 'Top 5';
$g_tag['search_choice'][3] = 'Top 10';
$g_tag['search_choice'][4] = 'Top 20';
$g_tag['search_choice'][5] = 'Top 50';
$g_tag['search_choice'][6] = 'Top 100';
$g_tag['search_choice'][7] = 'Top 200';
$g_tag['notification_interval'] = '1 month';
$g_tag['candidate_status']['new'] = 'Under Review';
$g_tag['candidate_status']['hired'] = 'Hired';
//end

#added by nancy xu 2009-12-11 17:40
$g_tag['payment_preference'][1] = 'Check';
$g_tag['payment_preference'][2] = 'Direct Deposit';
$g_tag['payment_preference'][3] = 'Paypal';
$g_tag['forms_submitted'][1] = 'W-9';
$g_tag['forms_submitted'][2] = 'Contract';
$g_tag['forms_submitted'][3] = 'Direct Deposit';
#end
#added by nancy xu 2010-03-04 11:40
$g_tag['operating_systems']['XP'] = 'XP';
$g_tag['operating_systems']['Vista'] = 'Vista';
$g_tag['operating_systems']['Windows 2003'] = 'Windows 2003';
$g_tag['operating_systems']['Windows 7'] = 'Windows 7';
$g_tag['operating_systems']['Mac OS'] = 'Mac OS';
$g_tag['operating_systems']['Linux'] = 'Linux';
$g_tag['operating_systems']['FreeBSD'] = 'FreeBSD';
$g_tag['operating_systems']['Other'] = 'Other';
$g_tag['browsers']['IE6'] = 'IE6';
$g_tag['browsers']['IE7'] = 'IE7';
$g_tag['browsers']['IE8'] = 'IE8';
$g_tag['browsers']['firefox'] = 'firefox';
$g_tag['browsers']['Chrome'] = 'Chrome';
$g_tag['browsers']['Safari'] = 'Safari';
$g_tag['browsers']['Opera'] = 'Opera';
$g_tag['browsers']['Netscape'] = 'Netscape';
$g_tag['browsers']['Maxthon'] = 'Maxthon';
$g_tag['browsers']['Other'] = 'Other';
#end
// $g_to_email = 'contentmanager@secondstepsearch.com';
$g_to_email = 'cptech@copypress.com ';
$g_bcc_email = 'pm@copypress.com';
//$g_to_email = 'xusnug11@gmail.com';

$g_tag['content_levels'][1] = 'Level 1';
$g_tag['content_levels'][2] = 'Level 2';
$g_tag['content_levels'][3] = 'Level 3';
$g_tag['extension_statuses'][0] = 'Submitted';
$g_tag['extension_statuses'][1] = 'Rejected';
$g_tag['extension_statuses'][2] = 'Granted';
$g_archived_month_time =  strtotime('-3 month'); 
$g_user_levels = array(
    '1' => 'Casual/Basic Interest',
    '3' => 'Writing Experience',
    '4' => 'Work Experience',
    '2' => 'Expert/Certified',
);
$g_tag['article_tones'] = array(1 => 'Casual and Friendly',  2=> 'Professional and Straightforward');
$g_tag['yesorno'] = array(
    1=> 'Yes',
    0=> 'No',
);
$g_estatuses = array(
 1=> 'Waiting for Faxin',
 2=> 'Prefill',
 3=> 'Authoring',
 4=> 'Out For Signature',
 7=> 'Signed',
 8=> 'Aborted',
 9=> 'Document librar',
 10=> 'Widget',
 11=> 'Expired',
 12=> 'Archived',
 20=> 'Other',
 -1=> 'Cancelled',
 0=> 'Removed',
);
$client_downloaded_statuses = array(5,6);

$g_pay_plugin = 'QuickBook';

$g_bank_acct_types = array(
 0 => 'Unknown',
 1 => 'Checking',
 2 => 'Savings',
);

$g_er_cc = 'cptech@copypress.com';// extension requests notification emails to editor CC cptech@blueglass.com

$g_tag['candiate_permission'][3] = 'editor';
$g_tag['candiate_permission'][1] = 'writer';
//$g_tag['candiate_permission'][0] = 'both';
// added by nancy xu 2012-03-05
// candidate global values
$g_tag['candidate_plinks'] = array(
    5=>'Website',
    2=>'Blog',
    1=>'Portfolio',
//    3=>'Linkedin',
//    4=>'Other'
);
$g_tag['candidate_sample_types'] = array(
    1=> 'Product Copy',
    2=> 'Website Landing Page Copy',
    3=> 'Blog Post',
    4=> 'Technical Writing',
    5=> "Buyer's Guide",
    6=> 'Press Release',
    7=> 'Journalistic Article',
    8=> 'White Paper',
);
$g_tag['candidate_levels'] = array(
    '4' => 'Work Experience',
    '2' => 'Higher Education');
$g_tag['candidate_writers'] = array(
    1=>'Copywriter', 
    2=>'Blog Writer',
    3=>'Technical Writer',
    4=>'Journalist'
);
//End
$g_sql = '';
$g_api_types = array(
    'wordpress' => 'WP Plugin',
    'linkme' => 'Link Me',
    'secondstepsearch' => 'Second Step Search',
);

$g_tag['sale_types'] = array(
    1=>'Hard Sell',
    2=>'Soft sell',
    3=>'No sell (Informational Approach)'
);

$g_tag['campaign_type'] = array(
    1=> 'article',
    2=> 'image',
);

$g_tag['pay_levels'] = array(1,2,3,4,5);
$g_tag['states'] = array(
	"AL"=>"Alabama",
	"AK"=>"Alaska",
	"AZ"=>"Arizona",
	"AR"=>"Arkansas",
	"CA"=>"California",
	"CO"=>"Colorado",
	"CT"=>"Connecticut",
	"DE"=>"Delaware",
	"FL"=>"Florida",
	"GA"=>"Georgia",
	"HI"=>"Hawaii",
	"ID"=>"Idaho",
	"IL"=>"Illinois",
	"IN"=>"Indiana",
	"IA"=>"Iowa",
	"KS"=>"Kansas",
	"KY"=>"Kentucky",
	"LA"=>"Louisiana",
	"ME"=>"Maine",
	"MD"=>"Maryland",
	"MA"=>"Massachusetts",
	"MI"=>"Michigan",
	"MN"=>"Minnesota",
	"MS"=>"Mississippi",
	"MO"=>"Missouri",
	"MT"=>"Montana",
	"NE"=>"Nebraska",
	"NV"=>"Nevada",
	"NH"=>"New Hampshire",
	"NJ"=>"New Jersey",
	"NM"=>"New Mexico",
	"NY"=>"New York",
	"NC"=>"North Carolina",
	"ND"=>"North Dakota",
	"OH"=>"Ohio",
	"OK"=>"Oklahoma",
	"OR"=>"Oregon",
	"PA"=>"Pennsylvania",
	"RI"=>"Rhode Island",
	"SC"=>"South Carolina",
	"SD"=>"South Dakota",
	"TN"=>"Tennessee",
	"TX"=>"Texas",
	"UT"=>"Utah",
	"VT"=>"Vermont",
	"VA"=>"Virginia",
	"WA"=>"Washington",
	"DC"=>"Washington D.C.",
	"WV"=>"West Virginia",
	"WI"=>"Wisconsin",
	"WY"=>"Wyoming");

$g_http_host = strtolower($_SERVER['HTTP_HOST']);

if (substr($g_http_host, -13) == 'copypress.com') {
    $environment = 'live';
    $g_api_param = array(
        'USER' => 'billing_api1.copypress.com',
        'PWD' => '3WVG2VEQBRU7US7H',
        'SIGNATURE' => 'AdObW0wmOzgtCfszkjBMzB9AyZmBAhHQgVHfVHGY4qkpj6iNE293yr7e',
        'VERSION' => 51.0
    );
    $g_pay_pal_email = 'billing@copypress.com';
} else {
    $environment = 'sandbox';	// or 'beta-sandbox' or 'live'
    $g_api_param = array(
        'USER' => 'techni_1294101564_biz_api1.blueglass.com',
        'PWD' => '1294101572',
        'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AIRWA3NQp0LwUSWK2e6CuOjtVFne',
        'VERSION' => 51.0
    );
    $g_pay_pal_email = 'techni_1297287905_biz@blueglass.com';
}

$g_paymentaction = 'Order';
$g_currencycode = 'USD';
$g_base_url = ((isset($_SERVER['https']) && !empty($_SERVER['https'])) ? 'https://' :'http://') . $g_http_host;
if (empty($g_http_host)) $g_base_url = '';
$g_returnurl = $g_base_url . '/client_campaign/return.php';
$g_cancelurl = $g_base_url . '/client_campaign/cancel.php';
$g_custom_fields = null;
$g_optional_fields = array();
$g_qa = array(0=>'No', 1=> 'Yes');
?>