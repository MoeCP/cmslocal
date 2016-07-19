<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';

$search = User::getAllCPAssignment('copy writer', $_GET['perPage'], $_GET['campaign_id']);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);

$smarty->assign('users_status', $g_tag['users_status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/cp_unfinished_assignment_report.html');
?>