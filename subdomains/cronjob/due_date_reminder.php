<?php
require_once 'pre_cron.php';//parameter settings
global $mailer_param;

$now = time();
$nowh = date('G', $now);
$date_end = strtotime("+1 day");
$date_end = date("Y-m-d", $date_end);


$sql ='SELECT DISTINCT ar.article_id, ar.title, ck.keyword, cc.campaign_name, ck.copy_writer_id, ck.date_end, u.first_name, u.email, cc.campaign_id ';
$sql .='FROM  campaign_keyword AS ck ';
$sql .= 'LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id) ';
$sql .= 'LEFT JOIN users AS u ON (u.user_id=ck.copy_writer_id) ';
$sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ';
$sql .= "WHERE ar.article_status = 0 AND ck.status!='D' AND ck.date_end = '".$date_end."' ";
$sql .= "GROUP BY ck.keyword_id ORDER BY ck.copy_writer_id ";
// $conn->debug = true;
$result = $conn->GetAll($sql);



echo "cronjob start";
if( count( $result ) )
{
    $user_id = 0;
    $subject = "";
    $body = "";
    $prev_email = "";
    foreach( $result as $k => $user)
    {
        if ($user_id == 0) $body = "<div>Hey {$user['first_name']}!</div><br />";
        if ($user_id > 0 && $user_id != $user['copy_writer_id'] && !empty($subject)) {
            $body .= "Please log into the CMS and complete your assignment.";
            send_smtp_mail($prev_email, $subject, $body, $mailer_param);
            //echo $body;
            $subject = "";
            $body = "<div>Hey {$user['first_name']}!</div><br />";
        }
        $user_id = $user['copy_writer_id'];
        $prev_email = $user["email"];

        $subject = "Dear {$user['first_name']}, Due Date Reminder!";
        $body .= "<div>The article '".$user['keyword']."' for the campaign '".$user['campaign_name']."' is due within 24 hours.  </div><br />";

    }
    $body .= "Please log into the CMS and complete your assignment.";
    send_smtp_mail($prev_email, $subject, $body, $mailer_param);
    //echo $body;
}
echo "cronjob over";
?>