<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
$search = User::getAllAvailableCopyWriter($_GET);

if (count($search))
{
	$smarty->assign('users', $search['result']);
	$smarty->assign('total', $search['total']);
	$smarty->assign('pager', $search['pager']);
	$smarty->assign('count', $search['count']);
}

$smarty->display('user/available_users_report.html');
?>