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
$_GET['show_all'] = isset($_GET['show_all']) && strlen($_GET['show_all']) ? true :false; 
if (empty($_GET['user_type'])) $role = 'copy writer';
else $role = $_GET['user_type'];
$smarty->assign('role', $role);
$users = User::forecastPayrollNoPagination($_GET);
$total_payment = 0;
foreach ($users as $row) {
    $total_payment+= $row['pay_amount'];
}
$smarty->assign('result', $users);
$smarty->assign('total_payment', $total_payment);
/*$search = User::forecastPayroll($_GET);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}*/

$smarty->assign('pay_plugin', $g_pay_plugin);

// added by snug xu 2006-10-17 14:30
// get all possible month from cp payment history
$monthes = User::forecastMonthes();
$month = trim($_GET['month']);
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
// change month to time
$smarty->assign('now', changeTimeFormatToTimestamp($month));
// end

$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);
$client_list = Client::getAllClients('id_name_only', '');
$smarty->assign('client_list', $client_list);

$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$smarty->assign('user_status', $status);
$smarty->assign('users_status', array('All') + $g_tag['status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
$g_article_types = $g_tag['article_type'];
$smarty->assign('g_article_types', $g_article_types);
$smarty->assign('payment_preferences', $g_tag['payment_preference']);
// added by snug xu 2007-05-21 11:37 - STARTED
$smarty->assign('total_type', count($g_article_types));
// added by snug xu 2007-05-21 11:37 - FINISHED

//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->assign('url', "/client_campaign/forecast_payroll.php?is_ajax=1".$query_string);
$smarty->assign('actionurl', $_SERVER['REQUEST_URI']);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('client_campaign/forecast_payroll.html');
?>