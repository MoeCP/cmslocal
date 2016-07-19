<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    //$g_current_path = "client_campaign";
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
$p = $_GET;
//$p += array('article_status' => 5);

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

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/list.php";
if ($_GET['campaign_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $campaign_info = Campaign::getInfo($_GET['campaign_id']);
    $header = substr($header,0,strlen($header)-1);
    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
    //$_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    //$_SESSION['campaign_url'] = $header;

	$quick_pane[1][lable] = $campaign_info['company_name'];
	$quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];

    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
}
/*
if ($_GET['keyword_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
	$quick_pane[2][lable] = $keyword_info['keyword'];
	$quick_pane[2][url] = $header;
}
*/
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

$keyword_categorys = Campaign::getPrefByCampaignID($_GET['campaign_id']);
$smarty->assign('keyword_categorys', $keyword_categorys);

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}
// added by snug xu 2007-10-09 15:36
$cp_completed = isset($_GET['cp_completed']) && $_GET['article_status'] == 1 ? $_GET['cp_completed'] : 0;
$smarty->assign('cp_completed', $cp_completed);
// end
$smarty->assign('cr_options', array(0=>"No",1=>"Yes"));//Client Ready
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('qstring', '&' . $_SERVER['QUERY_STRING']);
$smarty->assign('feedback', $feedback);
// added by nancy xu 2012-04-06 14:47
$smarty->assign('startNo', getStartPageNo());
// end
$smarty->display('article/download_article_list.html');
?>