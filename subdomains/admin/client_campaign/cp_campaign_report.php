<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//load configuration information
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
if (trim($_POST['payment_flow_status']) != '' && trim($_POST['user_id']) != '') {
    User::setCPPaymentFlowStatus($_POST);
}
$search = Campaign::getAllCampaign($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);//['result']
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

// get all possible month from cp payment history
$monthes = User::getMonthesFromCpPaymentHistory();
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0){
	$month = date("Ym" );//,(time() - 15*86400)
}
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
// change month to time
$smarty->assign('now', changeTimeFormatToTimestamp($month));
// end

$page = isset($_GET['page'])? $_GET['page'] : 1;
$_GET['perPage'] = isset($_GET['perPage'])? $_GET['perPage'] : 50;
$per_page = $_GET['perPage'];
$count_start = ($page - 1) * $per_page;
$smarty->assign('count_start', $count_start);
$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);

$status = isset($_GET['status']) ? trim($_GET['status']) : 'A';
$smarty->assign('user_status', $status);
$smarty->assign('total_status', array('All'=>'All') + $g_tag['status']);
$smarty->assign('g_article_types', $g_tag['article_type']);
$smarty->assign('total_type', count($g_tag['article_type']));
$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->display('client_campaign/cp_campaign_report.html');
?>