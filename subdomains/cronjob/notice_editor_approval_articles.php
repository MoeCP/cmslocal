<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . 'Client.class.php';
require_once CMS_INC_ROOT . DS . 'Email.class.php';
require_once CMS_INC_ROOT . DS . 'Campaign.class.php';
$conn->debug = true;
$time = time() - 3600*24;
$start_date = date("Y-m-d H:i:s", $time);
$end_date = date("Y-m-d H:i:s");
$conditions = array(
    '(ar.approval_date>=\'' . $start_date . '\' AND ar.approval_date <=\'' .$end_date . '\' )',
    'ar.article_status=4'
);
$result = Client::getAllEditorFinishedArticles($conditions);
$mail_template = Email::getInfoByEventId(27);
$mail_template['body'] = nl2br($mail_template['body']);
if (!empty($result)) {
    $ids = array_keys($result);
    $clients = Client::getClientsByParam(array('client_id' => $ids));
    foreach ($clients as $row) {
        $client_id = $row['client_id'];
        $email = $row['email'];  
        $row['datastring'] = generateArticleListFormat($result[$client_id]);
        $body = email_replace_placeholders($mail_template['body'], $row);
        $subject = $mail_template['subject'];
        if (valid_email($email)) send_smtp_mail($email, $subject, $body, $mailer_param);
    }
}

function generateArticleListFormat($data, $fields = array('campaign_name', 'keyword', 'article_number'))
{
    $hints = array();
    $rows = array();
    foreach ($fields as $field) {
        $hints[] = '<th>' . stripUnderlineUCWord($field) . '</th>';
    }
    foreach ($data as $row) {
        $tmp = array();
        foreach ($fields as $field) {
            $tmp[] =  '<td>' . $row[$field]. '</td>';
        }
        $rows[] = '<tr>' . implode("", $tmp) . '</tr>';
    }
   $html = '<table border="1" ><tr>' .  implode("", $hints) . '</tr>' . "\n" . implode("\n", $rows) . '</table>';
   return $html;
}
?>