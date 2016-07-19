<?php
$g_current_path = "preference";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

if (!user_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/campaign_notes.class.php';

//delete note
if (User::getPermission() >= 5) { // 4=>5
    if (trim($_POST['general_note_id']) != '' && $_POST['form_refresh'] == "D") {
        //die(print($_POST['general_note_id']));
        if (CampaignNotes::delGeneralNotes(array('general_note_id'=>$_POST['general_note_id']))){
            header("Location:general_notes.php");
            exit;
        }
    }
}

$notes = CampaignNotes::getGeneralNotes(array());
//$smarty->assign('email_event', $g_tag['email_event']);
$smarty->assign('notes', $notes);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/general_notes.html');
?>