<?php
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
$logout_folder = '';//the folder of logout.php in
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

if (!empty($_POST)) {
    Campaign::sendReminderEmail($_POST);
}

$p = $_GET;
$p['article_status'] = 0;
$search = Campaign::getOverdueArticles($p);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);
$client_id = isset($_GET['client_id'])&&$_GET['client_id'] > 0? $_GET['client_id'] : '';
$all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id);
$smarty->assign('all_campaigns', $all_campaigns);

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('result', $search['result']);
$smarty->assign('pager', $search['pager']);
$smarty->assign('total', $search['total']);
$smarty->assign('count', $search['count']);
$smarty->assign('feedback', $feedback);
// added by nancy xu 2011-05-31 12:10
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_clients', $clients);
// end
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('login_role', User::getRole());
$smarty->assign('actionurl', '/article/article_report.php' );
$smarty->assign('exporturl', '/article/article_report_export.php' );
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/article_report.html');
?>