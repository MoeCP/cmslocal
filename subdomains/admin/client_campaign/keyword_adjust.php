<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
require_once('../cms_menu.php');
// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || User::getPermission() < 4 ) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

//Start: Added By Snug 0:06 2006-08-14

if( !empty( $_POST ) )
{
	$operation = $_POST['operation'];
    
	switch( $operation )
	{
		case 'move_to_next_pay_peried':
			$query_string = $_SERVER['QUERY_STRING'];
			if(ArticlePaymentLog::setTargetPayMonth( $_POST ))
			{
					$feedback = "Success";
					echo "<script>alert('".$feedback."');</script>";
					echo "<script>window.location.href='/client_campaign/keyword_adjust.php?$query_string';</script>";
					exit;
			}
			break;
		case 'cancel_keyword':
			$query_string			= $_SERVER['QUERY_STRING'];
			if(ArticlePaymentLog::updateKeywodPaymentStatus( $_POST ))
			{
					$feedback = "Success";
					echo "<script>alert('".$feedback."');</script>";
					echo "<script>window.location.href='/client_campaign/keyword_adjust.php?$query_string';</script>";
					exit;
			}
			break;
	}
}

$p = $_GET;
if (empty($p['role'])) $p['role'] = 'copy writer';
if (empty($p['user_id'])) {
    $feedback = 'Please choose a user';
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/client_campaign/keyword_list.php';</script>";
} else {
    $user_id = $p['user_id'];
    $role = $p['role'];
}
$smarty->assign('role', $role);
$search = Campaign::searchAdjustKeyword($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
}

//echo "<pre>";
//print_r($keyword_categorys);
////////BEGIN ADD BY cxz 2006-8-2 10:09上午
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
////////END ADD
//START ADDED By Snug 15:07 2006-8-29

/***获得当前copywriter/editor所有支付历史时间**/
$month = $p['month'];
if( $month== 0 || $month=='' )
{
    $month = changeTimeToPayMonthFormat(time());
}

$monthes = User::getPaymentHistoryMonthGroupByUserID( $p );
//Added by Snug 15:40 2006-09-03
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
if (!isset($monthes[$month])) {
    $monthes = array($month => showMonth($month)) + $monthes;
}
//End Added
$smarty->assign('monthes', $monthes );
$smarty->assign('month',  $month );
$smarty->assign('current_month',  date("Ym") );
$smarty->assign('payment_flow_status',  $p['payment_flow_status'] );
//End Added

// Added by snug xu 2007-03-01 15:32 - STARTED
$users = User::getCpPaymentHistory($p);
$smarty->assign('cph_user',  $users[0]);
// Added by snug xu 2007-03-01 15:32 - FINISHED
//END ADDED
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('campaign_id', $p['campaign_id']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/keyword_adjust.html');
?>