<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
$logout_folder = '';//the folder of logout.php in
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin()) { 
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/article_payment_log.class.php';
$p = $_GET;
if (!isset($p['user_id']) || empty($p['user_id'])) {
    $p['user_id'] = User::getID();
    $p['role'] = User::getRole();
    $p['pay_month'] = $p['month'];
    $p['is_canceled'] = 0;
    unset($p['month']);
}

$result = ArticlePaymentLog::getArticles($p);
$smarty->assign('role', User::getRole());
$smarty->assign('result', $result);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->display('client_campaign/payment_log.html');
?>