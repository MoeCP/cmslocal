<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/UserCategory.class.php';

$info = UserCategory::getInfo($_GET['cid'], $_GET['user_id']);
$smarty->assign('info', $info);
$smarty->display('user/show_specialite.html');
?>