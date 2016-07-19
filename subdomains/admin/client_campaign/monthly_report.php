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

if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}

if (trim($_POST['payment_flow_status']) != '' && trim($_POST['user_id']) != '') {
    User::setPaymentFlowStatus($_POST);
    echo '<script language="JavaScript">alert(\'' . $feedback . '\')</script>';
    echo '<script language="JavaScript">window.location.href=\'' . $_SERVER['REQUEST_URI'] . '\'</script>';
    exit();
}

if (isset($_GET['show_all']) && strlen($_GET['show_all'])) 
{
	$_GET['show_all'] = true;
}
else 
{
	$_GET['show_all'] = false;
}
if (empty($_GET['user_type'])) $role = 'copy writer';
else $role = $_GET['user_type'];
$smarty->assign('role', $role);
$search = User::getAllAccountingReport($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
}

// added by snug xu 2006-10-17 14:30
// get all possible month from cp payment history
$monthes = CpPaymentHistory::getPaymentMonthByParam($_GET);
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0)
{
	$month = date("Ym", (time() - 15*86400));
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
$smarty->assign('url', "/client_campaign/cp_acct_report.php?is_ajax=1".$query_string);
$smarty->assign('actionurl', $_SERVER['REQUEST_URI']);
$smarty->display('client_campaign/cp_acct_report.html');
?>