<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/CommentsOnArticle.class.php';

$search = CommentsOnArticle::search($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$choose = array('' => '[choose]');
$campaigns = $choose + Campaign::getCampaignList();
if (User::getPermission() > 3 ||  User::getPermission() == 2) {
    $clients = $choose + Client::getAllClients('username_id_only');
    $smarty->assign('clients', $clients);
}
if (User::getPermission() <> 3) {
    $editors = $choose + User::getUserList('username_id_only', 'editor');
    $smarty->assign('editors', $editors);
}
if (User::getPermission() <> 1) {
    $writers = $choose + User::getUserList('username_id_only', 'copy writer');
    $smarty->assign('writers', $writers);
}
$smarty->assign('campaigns', $campaigns);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('client_campaign/comments.html');
?>