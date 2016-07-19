<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS .  'article_type.class.php';//parameter settings
require_once CMS_INC_ROOT . DS .  'g_tag_map.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'Notification.class.php';
require_once CMS_INC_ROOT . DS . 'article_action.class.php';
require_once CMS_INC_ROOT . DS . 'OrderCampaign.class.php';
$conn->debug= true;
$time = time() - $g_assign_interval*3600;
$date = date("Y-m-d H:i:s", $time);
$end_time = $time - 3*$g_assign_interval*3600;
$end_date = date("Y-m-d H:i:s", $end_time);
$sql = "SELECT ck.keyword, ck.keyword_id, cc.campaign_name, cc.campaign_id, pm.user_id as pm_id, pm.role AS pm_role ,ue.user_name as ue_name, uc.user_name AS uc_name, ck.date_assigned,ck.copy_writer_id, ck.editor_id, ck.editor_status, ck.cp_status ";
$sql .= 'FROM campaign_keyword AS ck ';
$sql .= 'LEFT JOIN client_campaigns AS cc ON ck.campaign_id=cc.campaign_id ';
$sql .= "LEFT JOIN client AS cl ON cl.client_id=cc.client_id ";
$sql .= "LEFT JOIN users AS pm ON pm.user_id=cl.project_manager_id ";
$sql .= 'LEFT JOIN users AS  ue ON (ck.editor_id=ue.user_id)';
$sql .= 'LEFT JOIN users AS  uc ON (ck.copy_writer_id=uc.user_id) ';
// $sql .= 'WHERE (ck.editor_status=-1 OR ck.cp_status=-1 ) AND ck.editor_id > 0 AND ck.copy_writer_id >0 AND ck.date_assigned <= \''. $date . '\'';
 $sql .= 'WHERE (ck.editor_status=-1 OR ck.cp_status=-1 ) AND ck.editor_id > 0 AND ck.copy_writer_id >0 AND (ck.date_assigned <= \''. $date . '\' AND ck.date_assigned>= \'' . $end_date . '\')';
$result = $conn->GetAll($sql);
$today = date("Y-m-d H:i:s");
foreach ($result as $k => $item) {
    save_notification($item, $today);
}

function save_notification($data, $date)
{
    global $conn, $g_note_fields;
    $field_name = 'noaction_within24';
    $note = $g_note_fields[$field_name];
    $campaign_id = $data['campaign_id'];
    $keyword_id = $data['keyword_id'];
    $keyword_link = '<a href="/client_campaign/assign_keyword.php?keyword_id=' . $keyword_id . '&frm=acceptance" target="_blank">' . $data['keyword'] . '</a>';
    $campaign_link = '<a href="/article/acceptance.php?campaign_id=' . $campaign_id . '" target="_blank">' .  $data['campaign_name'] . '</a>';
    $hash = array(
        'keyword_id' => $keyword_id,
        'campaign_id' => $campaign_id,
        'campaign_name' => $data['campaign_name'],
        'user_id' => $data['pm_id'],
        'role' => $data['pm_role'],
        'total' => 1,
        'generate_date' => $date,
        'field_name' => $field_name,
    );
    if ($data['cp_status'] == -1 && $data['copy_writer_id']) {
        $hash['notes'] = sprintf($note, $keyword_link, $campaign_link,$data['uc_name']);
        $hash['from_user'] = $data['copy_writer_id'];
        storeNote($hash, $field_name);
    }
    if ($data['editor_status'] == -1 && $data['editor_id']) {
        $hash['notes'] = sprintf($note, $keyword_link, $campaign_link,$data['ue_name']);
        $hash['from_user'] = $data['editor_id'];
        storeNote($hash, $field_name);
    }
}
function storeNote($hash, $field)
{
   global $conn;
   $sql = 'SELECT notification_id FROM  notifications where is_hidden=0 AND user_id= '. $hash['user_id'] . ' AND keyword_id=' . $hash['keyword_id'] . ' AND field_name=\'' . $field. '\' AND from_user=' . $hash['from_user'];
   $notification_id = $conn->GetOne($sql);
   if (empty($notification_id)) {
       Notification::save($hash);
   }
}
?>