<?php
$mailer_params = array(
    array(
        'smtp_host'     => 'smtp.gmail.com',
        'smtp_port' => 465,
        'smtp_username' => 'no-reply@copypress.com',
        'smtp_password' => '!Glass99',
        'sender'          => 'no-reply@copypress.com',
        'from'          => 'no-reply@copypress.com',
        'from_name' => "CopyPress",
        'reply_to'     => 'no-reply@copypress.com',
        'smtp_secure'     => 'ssl',
        'smtp_auth'     => true
    ),
    array(
        'smtp_host'     => 'smtp.gmail.com',
        'smtp_port' => 465,
        'smtp_username' => 'notifications@copypress.com',
        'smtp_password' => '!copy99',
        'sender'          => 'no-reply@copypress.com',
        'from'          => 'no-reply@copypress.com',
        'from_name' => "CopyPress",
        'reply_to'     => 'no-reply@copypress.com',
        'smtp_secure'     => 'ssl',
        'smtp_auth'     => true
    )
);
$mailer_param = $mailer_params[rand(0,1)];
//$g_mailer_server = 'gmail'; // send by gmail
$g_mailer_server = 'ses'; // amazon s3
$g_placeholders= array(
    'campaign_name' => '%%CAMPAIGN_NAME%%',
    'campaign_id' => '%%CAMPAIGN_ID%%',
    'client_name' => '%%CLIENT_NAME%%',
    'company_name' => '%%COMPANY_NAME%%',
    'ask_days' => '%%ASK_DAYS%%',
    'first_name' => '%%FIRST_NAME%%',
    'user_pw' => '%%USER_PW%%',
    'login_link' => '%%LOGIN_LINK%%',
    'user_name' => '%%USER_NAME%%',
    'apikey' => '%%API_KEY%%',
    'token' => '%%SECRET_KEY%%',
    'domain' => '%%DOMAIN%%',
    'datastring' => '%%DATASTRING%%',
    'total' => '%%TOTAL%%',
    'article_type' => '%%ARTICLE_TYPE%%',
    'article_number' => '%%ARTICLE_NUMBER%%',
);

$admin_host = "content.copypress.com";
$client_host = 'clients.copypress.com';
$editor_cc_email = 'editor@secondstepsearch.com';

$g_option_fields = array(
    'skip' => 'skip',
    'optional1' => 'Optional Field 1',
    'optional2' => 'Optional Field 2',
    'optional3' => 'Optional Field 3',
    'optional4' => 'Optional Field 4',
    'optional5' => 'Optional Field 5',
    'optional6' => 'Optional Field 6',
    'optional7' => 'Optional Field 7',
    'optional8' => 'Optional Field 8',
    'optional9' => 'Optional Field 9',
    'optional10' => 'Optional Field 10',
);
$g_keyword_fields = array(
    'skip' => 'skip',
    'keyword' => 'Keyword',
    'subcid' => 'SCID',
    'mapping_id' => 'Mapping-ID',
);
$g_keyword_fields += $g_option_fields;

$g_campaign_order_status = array(
    0=> 'pending',
    4=> 'awaiting confirmation',
    7=> 'confirmed',
    10=> 'paid',
    15=> 'campaign created',
);

$g_word_options = array(50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000);

$g_merchant_accounts = array(
    'NetSuite', 'PayPal', 'Google Checkout', 'Authorize'
);

$g_referrer_types = array(
    1=>'Individual',
    2=>'Share-a-Sale ',
);

$g_to_email = 'cptech@blueglass.com ';
$g_tag['campaign_limit'] = 15;

$g_pay_per_month = 2;
$g_interval_days = floor(30/$g_pay_per_month);
$g_delay_days = $g_interval_days;
$g_payment_settings = null;
if (empty($g_payment_settings)) {
    require_once CMS_INC_ROOT . '/PaymentSetting.class.php';
    $g_payment_settings = PaymentSetting::getAll();
}
$g_atd_key = '456c38733d25d589c50c1be5a5d8b916';
//$g_assign_status = array(
//    -1=> 'Waiting Accept', 
//    1=> 'ACCEPT',
//    0=> 'DECLINE',
//);
$g_assign_status = array(
    -1=> 'Pending', 
    1=> 'Accepted',
    0=> 'Declined',
);
$g_assign_interval = 24;
$g_note_fields = array(
    'total_rejected' => 'You have %s articles from campaign %s that need revision',
    'total_assigned' => 'You have been assigned %s keyword(s) from campaign %s',
    'total_client_rejected' => 'You have %s articles from campaign %s client rejected', // You have [total client rejected articles] articles from campaign [campaign name] client rejected
    'total_client_approval' => 'You have %s articles from campaign %s client approved', // You have [total client approved article] articles from campaign [campaig name] client approved
    'total_google_clean' => 'You have %s keywords from campaign %s that need to be approved', // You have [total google clean articles] keywords from campaign [campaign name] that need to be approved
    'pcnt_editor_approval' => '%s is %s editor approved', // [campaign name] is [percentage of editor approval] editor approved
    'pcnt_client_approval' => '%s is %s client approved', // [campaign name] is [percentage of editor approval] client approved
    'new_campaign_order' => '%s has ordered new campaign %s', // [company name] has ordered new campaign [campaign order]
    'confirm_campaign_order' => '%s has confirmed new campaign %s',
    'assigned_denied' => '%s from %s has been denied by %s', // [keyword] from [campaign name] has been denied by [writer/editor]
    'noaction_within24' => '%s has not responded within 24 hours of %s of %s ', // [editor/writer] has not responded within 24 hours of [keyword] of [campaignname] 
//    'new_message' => 'You have a new message in your inbox',
//    'total_paid' => 'You have been paid %s dollars for campaign %s',
);

$g_questions = array(
        'source'=> array(
            'purpose'=>"What is the site's main focus and purpose?", 
            'audience'=>"What 5 words best describe the site's main audience?", 
            'competitor'=>'Please list your top 5 competitors.'),
        'article_type'=> array(
            'link'=>"Please provide at least one link to the type of sharable content you you'd like us to emulate (please specify reasons why).", 
            'idea'=>'What topic ideas do you have for the content?', 
            'topic'=>'What taboo topics or themes must be avoided?',
            'addition'=>'If you have additional requirements, instructions, or resources, please provide.',
         ),
    );
?>