<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$user_info = User::getInfo(User::getID());
if (!empty($_POST)) {
    $user_info = array_merge($user_info, $_POST);
    $user_info['role'] = $user_info['permission'];
    if (User::setInfo($user_info)) {
        // echo "<script>alert('".$feedback."');</script>";
    }
}
$smarty->assign('user_info', $user_info);
$smarty->assign('feedback', $feedback);
// Added by nancy xu 2009-12-11 17:52
$payment_preference= array(''=>'[choose]')+ $g_tag['payment_preference'];
$smarty->assign('payment_preference', $payment_preference);
//End
$smarty->display('user/payment_preference.html');
?>