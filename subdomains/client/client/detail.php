<?php
//$g_current_path = "preference";
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/Client.class.php';

if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

$client_id = Client::getID();

$client_info = Client::getInfo($client_id);
$smarty->assign('client_info', $client_info);
$smarty->assign('referrer_types', $g_referrer_types);
$smarty->assign('feedback', $feedback);
$smarty->assign('client_id', $client_id);
$smarty->assign('login_role', 'client');
$smarty->display('client/client_detail.html');
?>