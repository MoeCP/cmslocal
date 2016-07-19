<?php
//$mailer_param = array(
//    'smtp_host'     => 'mail.secondstepsearch.com',
//    'smtp_port' => '25',
//    'smtp_username' => 'contentmanager@SECONDSTEPSEARCH.COM',
//    'smtp_password' => 'search2email',
//    'sender'          => 'contentmanager@secondstepsearch.com',
//    'from'          => 'contentmanager@secondstepsearch.com',
//    'from_name' => "Second Step Search",
//    'reply_to'     => 'contentmanager@secondstepsearch.com',
//    //'smtp_secure'     => 'ssl'
//);
$editor_cc_email = 'editor@secondstepsearch.com';
$hostname = 'localhost';
$database = 'cms_db';
$username = 'cms_dbuser';
$password = 'OxA8B4Uf';

// pay attention, when domain is changed
//$admin_host = "cp.infinitenine.com";
$admin_host = "content.copypress.com";
$mailer_param = array(
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
);
?>
