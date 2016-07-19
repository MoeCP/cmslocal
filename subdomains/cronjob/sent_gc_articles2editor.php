<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . 'Email.class.php';
require_once CMS_INC_ROOT . DS . 'Campaign.class.php';
$conn->debug = true;
$fields = array(
    'cc.campaign_name', 
    'ar.article_id', 
    'ck.keyword', 
    'ck.editor_id',
    'ue.user_name',
    'ue.first_name',
    'ue.email AS editor',
);
$result = Campaign::getKeywords(array('ar.article_status=\'1gc\''), $fields);
$data = $editors = array();
foreach ($result as $k => $row) {
    $editor_id = $row['editor_id'];
    if (!isset($data[$editor_id])) {
        $data[$editor_id] = array();
        $editors[$editor_id] = $row;
    }
    unset($row['editor_id']);
    unset($row['user_name']);
    unset($row['first_name']);
    unset($row['editor']);
    $data[$editor_id][] = $row;
}

$table = '<table border="1" ><tr><td>Campaign Name</td><td>Article ID</td><td>Keyword</td></tr>%s</table>';
$tpl = Email::getInfoByEventId(1);
global $mailer_param;
foreach ($editors as $editor_id => $row) {
    $items = $data[$editor_id];
    if (!empty($items)) {
        $rows = array();
        foreach ($items as $item) {
            $string = '';
            foreach ($item as $k => $v) {
                $string .= '<td>' . $v . '</td>';
            }
            $rows[] = '<tr>' . $string  .'</tr>';
        }
        $string = implode("", $rows);
        $string = sprintf($table, $string);
        $email = $row['editor'];
        $p = $row;
        $p['datastring'] = $string;
        if (!empty($p)) {
            $body = email_replace_placeholders($tpl['body'], $p);
            $subject = email_replace_placeholders($tpl['subject'], $p);
        }
        $body = nl2br($body);
        send_smtp_mail($email, $subject, $body, $mailer_param);
    }
}
exit();
?>