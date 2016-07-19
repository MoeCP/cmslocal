<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT . '/user_note_category.class.php';

if (!empty($_POST)) {
    $result = UserNoteCategory::store($_POST);
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/user/note_category.php';</script>";
    exit();
}

$category_id = isset($_GET['category_id'])&&!empty($_GET['category_id']) ? $_GET['category_id'] : '';
$category = $_POST;
$today = date("Y-m-d H:i:s");
$loginId = User::getID();
$category['modified'] = $today;
$category['modified_by'] = $loginId;
$smarty->assign('info', $category);
$smarty->assign('category_id', $category_id);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_id', $user_id);
$smarty->assign('feedback', $feedback);
$smarty->assign('request_uri', $_SERVER['REQUEST_URI']);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->display('user/ajax_cat_form.html');
?>