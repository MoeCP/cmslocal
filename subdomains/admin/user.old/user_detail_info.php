<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_GET['user_id']) == '') {
    echo "<script>alert('Please choose an user');</script>";
    echo "<script>window.location.href='/user/list.php';</script>";
    exit;
}

$user_info = User::getInfo($_GET['user_id']);
if (!(trim($_GET['user_id']) == User::getID() || $user_info['permission'] <= User::getPermission())) {
    echo "<script>alert('You have not permission to review user information');</script>";
    echo "<script>window.close();</script>";
    exit;
}
//////// ADD BY cxz  2006-7-28 15:09
include_once(CMS_INC_ROOT.'/Category.class.php');
$categories = User::getCategories($_GET['user_id']);
//print_r($categories);
$smarty->assign('categories', $categories);
//////// ADD END

$smarty->assign('user_info', $user_info);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_user_id', User::getID());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('feedback', $feedback);
// Added by nancy xu 2009-12-11 17:52
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$smarty->assign('payment_preference', $g_tag['payment_preference']);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('user_types', $g_tag['user_type']);
// end
$smarty->display('user/user_detail_info.html');
?>