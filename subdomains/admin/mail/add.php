<?php
$g_current_path = "preference";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['event_id']) != '' && trim($_POST['subject']) != '') {
    if (Email::add($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/mail/list.php';</script>";
        exit;
    }
}

$smarty->assign('template_info', $_POST);
$smarty->assign('feedback', $feedback);
$smarty->assign('email_event', $g_tag['email_event']);
$smarty->display('mail/mail_form.html');
?>