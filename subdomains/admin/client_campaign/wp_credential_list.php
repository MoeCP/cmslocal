<?php
$g_current_path = "client_campaign";
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 5) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/WPCredentials.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';

$clients = Client::getAllClients('id_name_only');
$smarty->assign('clients', $clients);

$p = $_GET;
$result = WPCredentials::search($p);

$is_main_sites = array("0"=>"No", "1"=>"Yes");
$smarty->assign('is_main_sites', $is_main_sites);

$smarty->assign('result', $result['result']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('total', $result['total']);
$smarty->assign('count', $result['count']);
$smarty->assign('startNo', getStartPageNo());


$login_role = User::getRole();
$smarty->assign('login_role', $login_role);

$smarty->display('client_campaign/wp_credential_list.html');
?>