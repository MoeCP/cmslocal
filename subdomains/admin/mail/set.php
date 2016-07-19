<?php
$g_current_path = "preference";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['event_id']) != '' && trim($_POST['template_id']) != '') {
    if (Email::setInfo($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/mail/list.php';</script>";
        exit;
    }
}

if (trim($_GET['template_id']) == '') {
    echo "<script>alert('Please choose a email template')</script>";
    echo "<script>window.location.href='/mail/list.php';</script>";
    exit;
}

$template_info = Email::getInfo($_GET['template_id']);
$smarty->assign('template_info', $template_info);
$smarty->assign('feedback', $feedback);
$smarty->assign('email_event', $g_tag['email_event']);
$smarty->display('mail/mail_form.html');
?>