<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . "Campaign.class.php";
require_once CMS_INC_ROOT . DS . "Email.class.php";
$conn->debug = true;
$fields = array('ar.article_id', 'cc.campaign_name', 'ck.keyword', 'cc.campaign_id', 'u.email');
$date = date("Y-m-d", strtotime("+2 days"));
$conditons = array(
    'ck.date_end<\'' . $date . '\'',
    'ck.date_end>=\'' . date("Y-m-d") . '\'',
    'ck.copy_writer_id > 0 ',
    'ar.article_status=\'0\' ',
);
$result = Campaign::getKeywords($conditons, $fields);
$info = Email::getInfoByEventId(28);
if (!empty($result)) {
    $data = array();
    foreach ($result as $k => $row) {
        extract($row);
        if (!isset($data[$email])) $data[$email] = array();
        $data[$email][] = $row;
    }
    foreach ($data as $email => $item) {
        $param = array('datastring' => generate_data_table($item));
        $subject = email_replace_placeholders($info['subject'], $param);
        $body = email_replace_placeholders($info['body'], $param);
        send_smtp_mail($email, $subject, $body);
    }
}

function generate_data_table($data)
{
    $table = "<table>";
    $table .= "<tr><td>Campaign Name</td><td>Article ID</td><td>Keyword</td></tr>";
    foreach ($data as $k => $row)  {
        $table .= "<tr><td>{$row['campaign_name']}</td><td>{$row['article_id']}</td><td>{$row['keyword']}</td></tr>";
    }
    $table .= "</table>";
    return $table;
}
?>