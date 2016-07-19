<?php
ini_set('max_execution_time', 0);
require_once 'pre_cron.php';//parameter settings





exit;




//######## send email to announce #########//
global $mailer_param;

$subject = "Copywriter Warning!";

$body = "We¡¯re waiting for you to log in and pick up your assignment. If you haven¡¯t already logged in, please visit the CMS and confirm your assignment and availability. <br><br>";
$body .= "If you don¡¯t confirm within 24 hours you will lose your assignment this round, so hop to it!  <br><br>";
$body .= "Thanks";

require_once MAILER_INC_ROOT.'/class.phpmailer.php';

$warning_time = 60*60*24*3; // how often to check an article

$q = "SELECT s.*, u.user_name, u.first_name, u.email FROM session AS s ".
     "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = s.user_id) ".
     "LEFT JOIN users AS u ON (u.user_id = s.user_id) ".
     "WHERE UNIX_TIMESTAMP(now()) - s.time > '".$warning_time."' ".
     "AND s.warning_counter < '3' AND ck.status!='D'  AND ck.date_created > '".date('Y-m-d H:i:s', 1153430655)."'";
if ($rs) {
    $users = array();
    while (!$rs->EOF) {
        $users[] = $rs->fields;
        $rs->MoveNext();
    }
    $rs->Close();
}

if (!empty($users)) {
    foreach ($users as $k => $v) {
        $body = "Hey  &nbsp;".$v['first_name']."<br><br>".$body;
        $mail = new PHPMailer();
        $mail->CharSet = "iso-8859-1";
        $mail->IsSMTP();
        $mail->Host     = $mailer_param['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailer_param['smtp_username'];
        $mail->Password = $mailer_param['smtp_password'];
        $mail->From     = $mailer_param['from'];
        $mail->FromName = $mailer_param['from_name'];
        $mail->AddReplyTo($mailer_param['reply_to']);

        $mail->IsHTML(true);
        $mail->AddAddress($v['email']);
        $mail->Subject = $subject;
        $mail->Body = $body;
        //$mail->AddAttachment('Copywriter Instructions.doc');
        //$mail->SMTPDebug = true;

        $mail->Send();
        //######## end announce email #########//
    }
}
?>