<?php
//define('WEB_PATH', dirname(__FILE__));//publish path 
require_once 'pre_cron.php';//parameter settings
//define('DS', DIRECTORY_SEPARATOR);
//$wp_arr = explode(DS, WEB_PATH);
//array_pop($wp_arr);
//define('BASE_PATH', implode(DS, $wp_arr) . DS);
//define('CMS_INC_ROOT', BASE_PATH . 'include');
//require_once CMS_INC_ROOT . DS . 'g_parameters.php';
//require_once CMS_INC_ROOT . DS . 'config.php';
//require_once CMS_INC_ROOT . DS . 'utils.php';

$mysqli = new mysqli($hostname, $username, $password, $database);

$unpub_sql = "SELECT COUNT(*) AS 'unpublished'
FROM campaign_keyword, client_campaigns,  users AS writer, articles
LEFT JOIN hipmunkrss ON hipmunkrss.copypress_id = articles.article_id
WHERE campaign_keyword.campaign_id = client_campaigns.campaign_id
AND articles.keyword_id = campaign_keyword.keyword_id
AND client_campaigns.client_id =350
AND campaign_keyword.copy_writer_id = writer.user_id
AND hipmunkrss.link IS NULL
AND articles.client_approval_date  IS NOT NULL";

$newer_sql = "SELECT COUNT(*) AS 'newerversions'
FROM campaign_keyword, client_campaigns,  users AS writer, articles
LEFT JOIN hipmunkrss ON hipmunkrss.copypress_id = articles.article_id
WHERE campaign_keyword.campaign_id = client_campaigns.campaign_id
AND articles.keyword_id = campaign_keyword.keyword_id
AND client_campaigns.client_id =350
AND campaign_keyword.copy_writer_id = writer.user_id
AND hipmunkrss.link IS NOT NULL

AND (articles.delivered_date > hipmunkrss.modDate OR
     articles.client_approval_date >  hipmunkrss.modDate OR
     articles.approval_date > hipmunkrss.modDate)";

$unpub_res = $mysqli->query($unpub_sql);
$unpub = $unpub_res->fetch_assoc();
$unpubs = $unpub['unpublished'];
$newer_res = $mysqli->query($newer_sql);
$newer = $newer_res->fetch_assoc();
$newers = $newer['newerversions'];

//print_r($unpub);
//print_r($newer);
$message_body =<<<EOT
Daily Copypress/Hipmunk Article Update<br>
<br>
# of client approved articles that have not been published on hipmunk.com<br>
$unpubs<br>
<br>
# articles on hipmunk.com that have a newer version available<br>
$newers<br>
<br>
The specific items missing from Hipmunk.com can be found under the current months tracking folder located at: <a href="https://drive.google.com/open?id=0B4NmN-GujtzaUURMWjZBLVdrOFE">https://drive.google.com/open?id=0B4NmN-GujtzaUURMWjZBLVdrOFE</a><br>
EOT;

//echo $message_body;

$owner_email = 'hipmunk@copypress.com';
$headers = 'From:hipmunk@copypress.com';
$subject = 'Daily Copypress/Hipmunk Article Update for '. date('Y-m-d');

//try{
//    if(!mail($owner_email, $subject, $message_body, $headers)){
//            throw new Exception('mail failed');
//    }else{
//            echo 'mail sent to '. $owner_email;
//    }
//}catch(Exception $e){
//    echo $e->getMessage() ."\n";
//}
try{
    if (!send_smtp_mail($owner_email, $subject, $message_body, $mailer_param)) {
        throw new Exception('mail failed');
    } else {

        echo 'mail sent to '. $owner_email;
    }
} catch(Exception $e){
    echo $e->getMessage() ."\n";
}