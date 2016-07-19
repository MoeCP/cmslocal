<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/client/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

$p = $_GET;
//$p += array('article_status' => 5);
// added by snug xu 2007-07-24 19:26 - STARTED
$p += array('article_status' => array(3, 4,5,6));
// added by snug xu 2007-07-24 19:26 - FINISHED

$search = Campaign::searchKeyword($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$campaigns = Campaign::getAllCampaigns('id_name_only');
$smarty->assign('campaigns', $campaigns);

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/past_article_list.html');
?>