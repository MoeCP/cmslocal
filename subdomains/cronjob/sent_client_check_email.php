<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . 'Client.class.php';
require_once CMS_INC_ROOT . DS . 'Email.class.php';
require_once CMS_INC_ROOT . DS . 'Campaign.class.php';
$conn->debug = true;
$result = Client::getAllEditorFinishedCampaign(array('is_sent_client' => 0));
if (!empty($result)) {
    $campaigns = array();
    $ids = array();
    foreach ($result as $k=> $row) {
        //$campaigns[$row['campaign_id']] = $row;
        $ids[$k] = $row['campaign_id'];
    }
    $conditions = array("cc.campaign_id IN ('" . implode("', '", $ids) . "')");
    Client::getCountGroupByClients($result, 'total_editor_approval', $ids, array_merge($conditions, array("ar.article_status REGEXP  '^(4|5|6|99)$'")), ' WHERE 1 ', 'campaign_id');
    $event_id = 24;
    $mail_template = Email::getInfoByEventId(25);
    $mail_template['body'] = nl2br($mail_template['body']);
    $login_link = "https://" . $client_host;
    foreach ($result as $campaign) {
        if ($campaign['total'] > 0 && $campaign['total_editor_approval'] == $campaign['total']) {
            $campaign['login_link'] = $login_link;
            $body = email_replace_placeholders($mail_template['body'], $campaign);
            $subject = email_replace_placeholders($mail_template['subject'], $campaign);
            $email = trim($campaign['email']);
            $mailer_param['cc'] = trim($campaign['project_manager_email']);
            if (valid_email($email)) send_smtp_mail($email, $subject, $body, $mailer_param);
            Campaign::setCampaignFieldByID('is_sent_client', 1, $campaign['campaign_id']);
        }
    }
}
?>