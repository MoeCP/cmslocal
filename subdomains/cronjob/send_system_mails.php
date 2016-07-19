<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'system_mails.class.php';//parameter settings
$conn->debug = true;
$result = SystemMails::getAllPendingMails();
global $mailer_param;
$mail_index = 0;
$i = 0;
foreach ($result as $row) {
    echo '=====================' . $row['mail_id'] . "\n";
    if ($mail_index >= 2) {
        $mail_index = 0;
    }
    $mailer_param = $mailer_params[$mail_index];
    $mail_index++;
    //echo $mail_index . "\n";
    foreach ($row as $k => $v) {
        $row[$k] = htmlspecialchars_decode(stripslashes($v));
    }
    extract($row);
    $feedback = '';
    if (!empty($to_ids)) {
        $to_ids = trim($to_ids, ';');
        $to_ids = explode(";", $to_ids);
        $tos = User::getAllUsersByUserIDs('all_infos', $to_ids);
        if (!empty($tos)) {
            if (!empty($cc_email)) $mailer_param['cc'] = $cc_email;
            if (!empty($from_email)) $mailer_param['from'] = $from_email;
            
            if (!empty($attachments)) {
                $mailer_param['attachment'] = unserialize($attachments);
            }
            foreach ($tos as $r) {
                $i++;
                echo '==========================' . $i . "\n";
                $r['login_link'] = $row['login_link'];
                $email = $r['email'];
                $body = $mailbody;
                $subject = email_replace_placeholders($subject, $r);
                $body = email_replace_placeholders($body, $r);
                if (!empty($email)) {
                    $address = $email;
                    echo $address . "\n";
                    if (!send_smtp_mail($email, $subject, $body, $mailer_param)) {
                        $feedback .= $email.",";
                     }
                     sleep(1);
                }
            }
        }
        echo $feedback . "\n";
        SystemMails::setStatusById(1, $mail_id);
    }
}
?>
