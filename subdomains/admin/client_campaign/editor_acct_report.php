<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';

if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}

$search = User::getUsersAccountingReport($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

// added by snug xu 2006-10-17 14:30
// get all possible month from cp payment history
$monthes = User::getMonthesFromCpPaymentHistory();
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0)
{
	$month = date("Ym", (time()));
}
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
// end

$user_role = trim($_GET['user_role']); 
if (strlen($user_role) == 0) 
{
    $user_role = 'project manager';
}
$smarty->assign('user_role', $user_role);

$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->display('client_campaign/editor_acct_report.html');
?>