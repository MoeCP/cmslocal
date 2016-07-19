<?php
$g_current_path = "client";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';

if (trim($_POST['user_name']) != '' && trim($_POST['user_pw']) != '') {
    if (Client::setInfo($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client/list.php';</script>";
        exit;
    }
}

if (trim($_GET['client_id']) == '') {
    echo "<script>alert('Please choose a client');</script>";
    echo "<script>window.location.href='/client/list.php';</script>";
    exit;
}

$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}

$all_pm = User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_pm += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$smarty->assign('all_pm', $all_pm);
$agencies = User::getAllUsers($mode = 'id_name_only', $user_type = 'agency', false);
$smarty->assign('agencies', $agencies);
$client_info = Client::getInfo($_GET['client_id']);
$smarty->assign('client_info', $client_info);
$smarty->assign('referrer_types', $g_referrer_types);
$smarty->assign('feedback', $feedback);
$smarty->assign('login_role', User::getRole());
$smarty->display('client/ajax_client_form.html');
?>