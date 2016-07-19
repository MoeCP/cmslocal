<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/article_cost_history.class.php';
require_once CMS_INC_ROOT.'/article_cost.class.php';

if (trim($_GET['user_id']) == '') {
    echo "<script type='text/javascript'>alert('Please choose an user');window.close();</script>";
}

$operation = $_POST['operation'];
$invoice_status = $_POST['invoice_status'];

if( count($_POST ) && strlen( $operation ) )
{
	// modified by snug xu 2006-10-27 17:10 - START
	if ($operation == 'paid')
	{
		if (trim($_POST['payment_flow_status']) != '' && trim($_POST['user_id']) != '')
		{
			  User::setPaymentFlowStatus($_POST);
		}
		echo "<script>window.opener.location.reload();</script>";
	}
	else
	{
        User::updatePaymentHistory( $_POST );
	}
	// modified by snug xu 2006-10-27 17:10 - FINISHED
	$query_string = $_POST['query_string'];
	if ($operation == 'save') 
	{
		echo "<script>alert('".$feedback."');</script>";
	}
    echo "<script>window.location.href='/client_campaign/cp_invoice.php?{$query_string}';</script>";
	exit;
}

$user_info = User::getInfo($_GET['user_id']);
if ($conn->debug) pr($user_info);
$smarty->assign('user_info', $user_info);

$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$smarty->assign('all_editor', $all_editor);

//Start:Added By Snug 18:10 2006-08-18
$cp_payment_info = User::getPaymentHistoryInfo( $_GET );
if ($cp_payment_info['approved_user'] == 0) 
{
	$cp_payment_info['approved_user']  = 2; // set default approve name is spatton
}
$smarty->assign('cp_payment_info', $cp_payment_info);
/***get the amount we will pay to writer for each article type and total***/
$role = $_GET['role'];
$report = User::getArticleAmountReport( $_GET, $role );
$smarty->assign('report', $report);
$smarty->assign('campaign_costs', $report['campaign']);
/***get all  campaign_id, campaign_name and article type ***/
if ($cp_payment_info['invoice_status'] == 1)
{
	$article_types = ArticleCostHistory::getArticleTypesByUserIDAndMonth($_GET);
}
else
{
	$article_types =  Article::getCampaignIDAndArticleType($_GET, $role);
}

$smarty->assign('article_types', $article_types);
//End Added
//Start:Added By Snug 21:54 2006-08-28
/***获得当前copywriter所有支付历史时间**/
$monthes = User::getPaymentHistoryMonthGroupByUserID( $_GET );
//End Added
if (empty($_GET['role'])) {
    $role = 'copy writer';
} else {
    $role = $_GET['role'];
}
$results = Article::getAllClientApprovedArticle($_GET['user_id'], $role , $_GET['month'] , false, true);

$smarty->assign('role', $role);
$smarty->assign('result', $results['result']);
$smarty->assign('pager', $results['pager']);
$smarty->assign('total', $results['total']);
$smarty->assign('count', $results['count']);
// get the paramters from $_GET - START
$smarty->assign('user_id', $_GET['user_id']);
$smarty->assign('month', $_GET['month']);
//// added by snug xu 2006-10-27 18:45
//$smarty->assign('article_ids', $_GET['article_ids']);
//$smarty->assign('flow_status', $cp_payment_info['payment_flow_status']);
//// get the paramters from $_GET - FINISHED
$smarty->assign('monthes', $monthes);
$smarty->assign('query_string', $_SERVER['QUERY_STRING']);
$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/cp_invoice.html');
?>