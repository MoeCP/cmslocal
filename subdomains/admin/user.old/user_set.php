<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['user_name']) != '') {
    if (User::setInfo($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
    }
    echo "<script>window.location.href='/user/list.php';</script>";
    exit;
} else {
    if (empty($_POST) && trim($_GET['user_id']) == '') {
        echo "<script>alert('Please choose an user');</script>";
        echo "<script>window.location.href='/user/list.php';</script>";
        exit;
    }
}

$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
$user_info = User::getInfo($_GET['user_id']);
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
//End
// added by nancy xu 2012-10-03 12:00
$first_languages = array(''=>'Select') + $g_first_languages;
$smarty->assign('first_languages', $first_languages);
$smarty->assign('user_types', $g_tag['user_type']);
// end
$smarty->display('user/user_form.html');
?>