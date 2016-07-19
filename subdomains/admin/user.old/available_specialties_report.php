<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

$search = User::getCopyWriterAvailableAndSpecialty($_GET);
//pr($search, true);
if (count($search))
{
	$smarty->assign('users', $search['result']);
	$smarty->assign('total', $search['total']);
	$smarty->assign('pager', $search['pager']);
	$smarty->assign('count', $search['count']);
}
$user_types = array(
    '0' => 'Both',
    '1' => 'Copy Writer',
    '3' => 'Editor',
);
$smarty->assign('user_types', $user_types);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$all_cat = Category::getAllCategoryByCategoryId();
$smarty->assign('cp_interests', array(0=>array('name' => 'All')) + $all_cat);
$smarty->assign('g_user_levels', $g_user_levels);
$smarty->display('user/available_specialites_report.html');
?>