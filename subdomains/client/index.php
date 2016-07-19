<?php
require_once 'pre.php';
require_once CMS_INC_ROOT.'/Client.class.php';
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}
$g_current_path = 'home';
require_once 'cms_client_menu.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';


$reports = Campaign::reportCampaignByRole();
$smarty->assign('reports', $reports);
$client_info = Client::getInfo(Client::getID());
$user_info = User::getInfo($client_info['project_manager_id']);
$p = array('article_status' => 4, 'sort' => 'ar.approval_date DESC' , 'perPage' =>20);
$search = Article::search($p);
$smarty->assign('articles', $search['result']);
$smarty->assign('user_info', $user_info);
$smarty->assign('feedback', $feedback);
$smarty->assign('login_role', 'client');
$smarty->assign('agency_id', Client::getAgencyId());
$smarty->assign('client_id', Client::getID());
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('noflow_status', $g_tag['noflow_status']);
$smarty->display('client_campaign/index.html');
?>