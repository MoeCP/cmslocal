<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
//pr($_POST, true);
if (trim($_POST['user_name']) != '') {
    $link = '/user/list.php';
    if (!empty($_POST['frompage'])) {
        $frompage = $_POST['frompage'];
        if ($frompage == 'detail') {
            $link = "/user/user_detail.php?user_id=" . $_POST['user_id'];
        }
    }
    $p = $_POST;
    if (isset($p['filenames'])) {
        unset($p['filenames']);
        unset($p['attachments']);
    }
    User::setInfo($p);
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='{$link}';</script>";
    exit;
}

if (empty($_POST) && trim($_GET['user_id']) == '') {
    echo "<script>alert('Please choose an user');</script>";
    echo "<script>window.location.href='/user/list.php';</script>";
    exit;
}
$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
$user_info = User::getInfo($_GET['user_id']);
// pr($user_info);
$smarty->assign('user_info', $user_info);
$smarty->assign('frompage', $_GET['f']);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('login_id', User::getID());
$smarty->assign('user_permission', $g_tag['user_permission']);
// Added by nancy xu 2009-12-11 17:52
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$payment_preference= array(''=>'[choose]')+ $g_tag['payment_preference'];
$smarty->assign('payment_preference', $payment_preference);
// end
// added by nancy xu 2012-10-03 12:00
$first_languages = array(''=>'Select') + $g_first_languages;
$smarty->assign('first_languages', $first_languages);
// end
$smarty->assign('feedback', $feedback);
$smarty->assign('maxsize', ini_get('upload_max_filesize'));
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('user_types', $g_tag['user_type']);
$smarty->display('user/ajax_user_form.html');
?>