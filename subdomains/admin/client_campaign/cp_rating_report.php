<?php
//ini_set('max_excute_time', 0);
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// check the user is login or not
// check permission of user is enough or not
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';

// get Copywriter Rating Report - START
$search = User::getCpRatingReport($_GET);
if ($search)
{
	$smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
// get Copywriter Rating Report - FINISHED

if (strlen($_GET['start_date']) && strlen($_GET['end_date']))
{
	$start_date = trim($_GET['start_date']);
	$end_date  = trim($_GET['end_date']);
}
else
{
	$start_date = "1970-01-01";
    $next_day   = time() + (1 * 24 * 60 * 60);

	$end_date = date("Y-m-d", $next_day);
}
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date', $end_date);

$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->display('client_campaign/cp_rating_report.html');
?>