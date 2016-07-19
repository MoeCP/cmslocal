<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/user_note_category.class.php';
require_once CMS_INC_ROOT . '/user_note.class.php';
if (empty($_GET)) $_GET['status'] = 'A';
$search = UserNote::search($_GET);
$categories = UserNoteCategory::getList();
$users = User::getAllUsers('id_name_only', 'editor', false);
$users += User::getAllUsers('id_name_only','copy writer', false);
$user_id = isset($_GET['user_id'])&&!empty($_GET['user_id']) ? $_GET['user_id'] : 0;
$smarty->assign('pager', $search['pager']);
$smarty->assign('total', $search['total']);
$smarty->assign('result', $search['result']);
$smarty->assign('count', $search['count']);
$smarty->assign('categories', $categories);
$smarty->assign('total_status', array(''=>'All') + $g_tag['status']);
$smarty->assign('user_id', $user_id);
$smarty->assign('users', $users);
$smarty->assign('feedback', $feedback);
$smarty->display('user/notes.html');
?>