<?php
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';

$role = $_GET['role'];
$user_id = $_GET['user_id'];
$users = User::getCampaignReportByUser($user_id, $role , $_GET);
$smarty->assign('users', $users);
$smarty->assign('role', $role);
$smarty->assign('user_id', $user_id);

$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/campaign_production_report.html');
?>