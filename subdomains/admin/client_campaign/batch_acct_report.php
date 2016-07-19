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
require_once CMS_INC_ROOT.'/article_type.class.php';

$_GET['show_all'] = isset($_GET['show_all']) && strlen($_GET['show_all'])? true : false;
$role = isset($_GET['user_type']) && !empty($_GET['user_type']) ? $_GET['user_type'] : 'copy writer';
$smarty->assign('role', $role);
$p = $_GET;
if (!isset($p['bstatus'])) $p['batch_status'] = 'cpc';
else $p['batch_status'] = $p['bstatus'];
$smarty->assign('bstatus', $p['batch_status']);
$p['perPage'] = 25;
$search = User::getAllAccountingReport($p);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

// added by snug xu 2006-10-17 14:30
// get all possible month from cp payment history
$monthes = CpPaymentHistory::getPaymentMonthByParam($_GET);
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0)
{
    $month = changeTimeToPayMonthFormat(getDelayTime());
}
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
// change month to time
$smarty->assign('now', changeTimeFormatToTimestamp($month));
// end

$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);

$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$smarty->assign('user_status', $status);
$smarty->assign('users_status', array('All') + $g_tag['status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
$smarty->assign('g_article_types', $g_tag['article_type']);
$smarty->assign('payment_preferences', $g_tag['payment_preference']);
// added by snug xu 2007-05-21 11:37 - STARTED
$smarty->assign('total_type', count($g_tag['article_type']));
// added by snug xu 2007-05-21 11:37 - FINISHED

//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->assign('actionurl', $_SERVER['REQUEST_URI']);
$smarty->display('client_campaign/batch_acct_report.html');
?>