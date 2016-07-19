<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

if (!client_is_loggedin()) {// 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

$search = OrderCampaign::getList($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$smarty->assign('login_role', 'client');
$smarty->display('client_campaign/order_list.html');
?>