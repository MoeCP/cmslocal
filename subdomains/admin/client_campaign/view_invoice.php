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

$login_role = User::getRole();
if (trim($_GET['user_id']) == '') {
    if ($login_role == 'editor' || $login_role == 'copy writer') {
        $_GET['user_id'] = User::getID();
    } else {
        echo "<script type='text/javascript'>alert('Please choose an user');window.close();</script>";
    }
}

if (empty($_GET['role'])) {
    if ($login_role == 'editor' || $login_role == 'copy writer') {
        $role = $login_role;
    } else {
        $role = 'copy writer';
    }
    $_GET['role'] = $role;
} else {
    $role = $_GET['role'];
}

$user_info = User::getInfo($_GET['user_id']);
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

$results = Article::getAllClientApprovedArticle($_GET['user_id'], $role , $_GET['month'] , false, true);
$smarty->assign('role', $role);
$smarty->assign('result', $results['result']);
$smarty->assign('pager', $results['pager']);
$smarty->assign('total', $results['total']);
$smarty->assign('count', $results['count']);
// get the paramters from $_GET - START
$smarty->assign('user_id', $_GET['user_id']);
$smarty->assign('month', $_GET['month']);
//// get the paramters from $_GET - FINISHED
$monthes = User::getPaymentHistoryMonthGroupByUserID( $_GET );
$smarty->assign('monthes', $monthes);
$smarty->assign('query_string', $_SERVER['QUERY_STRING']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/view_invoice.html');
?>