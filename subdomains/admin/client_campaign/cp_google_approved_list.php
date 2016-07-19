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
	$month = changeTimeToPayMonthFormat(time());
}
$result = Article::getAllGoogleApprovedArticle(User::getID(), $month, false);
$smarty->assign('result', $result);


require_once "../js/xajax/xajax.inc.php";//call xajax file;
//require_once CMS_INC_ROOT.'/Campaign.class.php';
//$g_current_path = 'home';

$xajax = new xajax();
$xajax->registerFunction("setConfirm");

function setConfirm($pf_status, $memo, $month )
{
    $p = array('user_id' => User::getID(),
               'month' => $month, 
               'payment_flow_status' => $pf_status, 
               'memo' => $memo);

    if (User::setCPPaymentFlowStatus($p)) {
        $feedback = 'Success';
    } else {
        $feedback = 'Failure, Please try agian';
    }

    $objResponse = new xajaxResponse();
    if ($feedback == 'Success') {
        if ($pf_status == 'cpc') {
            $objResponse->addAssign("google_approve_div","innerHTML","<font color='red'>Thank you for your hard work last month!  You will be receiving payment for your activity by the 15th</font>");
        } else {
            $objResponse->addAssign("google_approve_div","innerHTML","<font color='red'>Thank you for your feedback, We will cantact with you as soon as posible!</font>");            
        }

    }
    $objResponse->addAlert($feedback);

    return $objResponse->getXML();
}

$xajax->processRequests();
$smarty->assign('xajax_javascript', $xajax->getJavascript('/js/xajax/'));

$payment_info = User::getPaymentInfoByIDAndMonth(User::getID(), $month);
$smarty->assign('payment_info', $payment_info);

$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/cp_google_approved_list.html');
?>