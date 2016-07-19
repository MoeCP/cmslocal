<?php
$g_current_path = "preference";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

if (User::getPermission() >= 5) { // 4=>5
    if (trim($_POST['template_id']) != '' && $_POST['form_refresh'] == "D") {
        if (Email::del($_POST['template_id'])){
            header("Location:list.php");
            exit;
        }
    }
}

$all_templates = Email::getAll('all_templates');

$smarty->assign('email_event', $g_tag['email_event']);
$smarty->assign('all_templates', $all_templates);
$smarty->assign('feedback', $feedback);
$smarty->display('mail/list.html');
?>