<?php
require_once('../pre.php');//加载配置信息
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/article_type.class.php';
// get all monthes
$user_id = $_GET['user_id'];
$role = $_GET['user_type'];
$monthes = CpPaymentHistory::getPaymentMonthByParam($_GET);
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0)
{
	$month = changeTimeToPayMonthFormat(getDelayTime());
}

$smarty->assign('role', $role);
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
//e nd
$search = User::getAccountingByUserID($_GET);
$item = $search + User::getInfo($user_id);
$smarty->assign('item', $item);
$smarty->assign('user_statuses', $g_tag['status']);
$smarty->assign('g_article_types', $g_tag['article_type']);
$smarty->assign('pay_plugin', $g_pay_plugin);
$smarty->assign('index_iteration', $_GET['index']);
$smarty->assign('payment_preferences', $g_tag['payment_preference']);
$smarty->display('client_campaign/get_pay_report.html');
?>