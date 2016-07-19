<?php
$g_current_path = "user";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
if (!empty($_POST)) {
    $new_pw1 = $_POST['new_pw1'];
    $new_pw2 = $_POST['new_pw2'];
    $user_pw = $_POST['user_pw'];
    $user_id = $_POST['user_id'];
    // if (User::setPasswd($user_id, $new_pw1, $new_pw2, true, $user_pw) || empty($new_pw1) && empty($new_pw2) && empty($user_pw))  User::setUserInfo($_POST);
    User::setUserInfo($_POST);
}

$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
if (!empty($_GET['user_id'])) $user_id = $_GET['user_id'];
else $user_id = User::getID();
$user_info = User::getInfo($user_id);
$smarty->assign('user_info', $user_info);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('feedback', $feedback);
// Added by nancy xu 2009-12-11 17:52
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$payment_preference= array(''=>'[choose]')+ $g_tag['payment_preference'];
$smarty->assign('payment_preference', $payment_preference);
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('user_types', $g_tag['user_type']);
//End
$smarty->display('user/user_edit.html');
?>