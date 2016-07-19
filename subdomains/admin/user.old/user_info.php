<?php
//$g_current_path = "preference";
$g_current_path = "account";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['user_id']) != '') {
    if ($_SESSION['user_id'] != $_POST['user_id']) {
        header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
        exit();
    }

    User::setInfo($_POST);
}

if (User::getID() == 0) {
    echo "<script>alert('User ID isn't exist, Please enter this system again');</script>";
    exit();
}

$user_info = User::getInfo(User::getID());
$smarty->assign('user_info', $user_info);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$smarty->assign('payment_preference', $g_tag['payment_preference']);
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('feedback', $feedback);
$smarty->assign('user_types', $g_tag['user_type']);
$smarty->display('user/user_form.html');
?>