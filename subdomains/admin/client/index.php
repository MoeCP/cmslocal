<?php
require_once '../pre.php';
require_once CMS_INC_ROOT.'/Client.class.php';
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/client/login.php");
    exit;
}
require_once 'cms_client_menu.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
$g_current_path = 'client_campign';

$reports = Campaign::reportCampaignByRole();
$smarty->assign('reports', $reports);

//Total Articles in Queue:  56    //this means still working on
//Total Articles delivered: 1120   // this means completed to this point 
//Articles Pending review: 59    // this means client needs to login and approve


//$search = Campaign::search($_GET);
//if ($search) {
//    $smarty->assign('result', $search['result']);
//    $smarty->assign('pager', $search['pager']);
//    $smarty->assign('total', $search['total']);
//}
//phpinfo();
$smarty->assign('feedback', $feedback);
$smarty->assign('client_id', Client::getID());
$smarty->display('client_campaign/index.html');
?>