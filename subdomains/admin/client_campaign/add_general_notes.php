<?php
$g_current_path = "preference";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/campaign_notes.class.php';

if (trim($_POST['subject']) != '' && trim($_POST['body']) != '') {
    if (CampaignNotes::addGeneralNotes($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/general_notes.php';</script>";
        exit;
    }
}
if (isset($_GET['general_note_id']) && !empty($_GET['general_note_id'])) {
    $notes = CampaignNotes::getGeneralNotes($_GET);
    if (!empty($notes)) {
        $res = $notes[$_GET['general_note_id']];
    }else {
        $res = array();
    }
}
$created_by = User::getID();
$created_role = User::getRole();
$res['created_by'] = $created_by;
$res['created_role'] = $created_role;
$smarty->assign('notes', $res);
$smarty->assign('feedback', $feedback);
$smarty->assign('email_event', $g_tag['email_event']);
$smarty->display('client_campaign/add_general_notes.html');
?>