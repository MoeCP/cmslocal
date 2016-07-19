<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/user_note_category.class.php';
$search = UserNoteCategory::search($_GET);
$smarty->assign('pager', $search['pager']);
$smarty->assign('total', $search['total']);
$smarty->assign('count', $search['count']);
$smarty->assign('result', $search['result']);
$smarty->assign('feedback', $feedback);
$smarty->display('user/note_category.html');
?>