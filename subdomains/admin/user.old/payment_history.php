<?php
$g_current_path = "my_account";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/article_type.class.php';
$g_article_types = $g_tag['article_type'];
$smarty->assign('g_article_types', $g_article_types);
$smarty->assign('total_type', count($g_article_types));
$p = array('user_id' => User::getID(), 'payment_flow_status' => 'paid', 'invoice_status' => 1);
$result =  User::getAllPaymentHistory($p);
$smarty->assign('result', $result['result']);
$smarty->assign('stats', $result['stats']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('total', $result['total']);
$smarty->assign('count', $result['count']);
$smarty->display('user/payment_history.html');
?>