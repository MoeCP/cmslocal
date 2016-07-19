<?php
$g_current_path = "client";
require_once('../pre.php');//����������Ϣ
require_once('../cms_menu.php');
// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 5 && User::getPermission() != 2)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
if (trim($_POST['user_name']) != '' && trim($_POST['user_pw']) != '') {
    if (Client::add($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client/list.php';</script>";
        exit;
    }
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

// added by snug xu 2006-11-24 12:15 - START
// get the login user id and role to database, and let the system know who created this client 
$_POST['creation_user'] = User::getID();
$_POST['creation_role'] = User::getRole();
// added by snug xu 2006-11-24 12:15 - END
$agencies = User::getAllUsers($mode = 'id_name_only', $user_type = 'agency',false);
$smarty->assign('agencies', $agencies);
$smarty->assign('referrer_types', $g_referrer_types);
$smarty->assign('client_info', $_POST);
$smarty->assign('feedback', $feedback);
$smarty->assign('login_role', User::getRole());
$smarty->display('client/client_form.html');
?>