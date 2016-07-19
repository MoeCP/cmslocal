<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Article.class.php';
$month = trim($_GET['month']);

if( strlen( $month )==0 )
{
	$month = changeTimeToPayMonthFormat();
}
$role = User::getRole();
$user_id = User::getID();
$report = User::getArticleAmountReport( array('user_id' => $user_id, 'month' => $month), $role );
$smarty->assign('report', $report);
$p = array('user_id' => $user_id);
$monthes = CpPaymentHistory::getPaymentMonthByParam($p);
// pr($monthes, true);
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$monthes += genPayMonthList(1, 2);//add 2 months as future pay dates 
$smarty->assign('monthes', $monthes);
$smarty->assign('user_type', User::getUserType());
$result = Article::getAllClientApprovedArticle($user_id, $role, $month, false, true);
$smarty->assign('result', $result['result']);
$smarty->assign('total', $result['total']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('count', $result['count']);

if (!empty($_POST)) {
    $p = $_POST;
    $p['user_id'] = User::getID();
    $p['role'] = User::getRole();
    if (isset($p['_'])) unset($p['_']);
    $pf_status = $p['payment_flow_status'];
    if (User::setPaymentFlowStatus($p)) {
        $feedback = 'Success';
    } else {
        $feedback = "<font color='red'>" .  'Failure, Please try agian' . "</font>";
    }
    if ($feedback == 'Success') {
        if ($pf_status == 'cpc') {
            //$feedback = "<font color='red'>Thank you for approving your invoice. You should receive your payment by the 15th.</font>";
            $feedback = "<font color='red'>Thank you for approving your invoice. You will receive payment on the next pay day. Please refer to the Writer's Guide section on payment for more information: <a href=\"http://community.copypress.com/copypress-writers-guide/payment/\" >http://community.copypress.com/copypress-writers-guide/payment/</a>.</font>";
        } else {
            $feedback = "<font color='red'>We will investigate your concerns about your invoice and will get back to you shortly.</font>";
        }
    }
    echo $feedback;
    exit();
}

$payment_info = User::getPaymentInfoByIDAndMonth(User::getID(), $month);
$smarty->assign('uri', getURI());
$smarty->assign('payment_info', $payment_info);

$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/client_approval_list.html');
?>