<?php
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
$logout_folder = '';//the folder of logout.php in
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || User::getPermission() < 3) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

$p = $_GET;
$p['article_status'] = 0;
$search = Campaign::getEditRequest($p);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);
$all_campaigns = Campaign::getAllCampaigns('campaign_name', '');
$smarty->assign('all_campaigns', $all_campaigns);

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('result', $search['result']);
$smarty->assign('pager', $search['pager']);
$smarty->assign('total', $search['total']);
$smarty->assign('count', $search['count']);
$smarty->assign('feedback', $feedback);
$smarty->assign('login_role', User::getRole());
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/edit_request_report.html');
?>