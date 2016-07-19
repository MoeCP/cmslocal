<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if ((!user_is_loggedin() || User::getPermission()) < 5 && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
if (!empty($_POST)) {
    $operation = $_POST['operation'];
    if ($operation == 'Delivered') {
        Article::deliverArticle($_POST);
    }
}

if (!empty($_GET)) {
    $p = $_GET;
    if (!isset($p['article_status']) || isset($p['article_status']) && $p['article_status'] == '') {
        $p['article_status'] = array(4,5,6);
    }
    $search = Campaign::searchKeyword($p);
    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
        $smarty->assign('show_cb', $search['show_cb']);
        $smarty->assign('show_deliver', $search['show_deliver']);
    }
}

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
if ($_GET['campaign_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $campaign_info = Campaign::getInfo($_GET['campaign_id']);
    $smarty->assign('campaign_info', $campaign_info);
    $header = substr($header,0,strlen($header)-1);

    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
	$quick_pane[1][lable] = $campaign_info['company_name'];
	$quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];

    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}

if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
}
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//echo phpinfo();
//########quick pane########//

if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
} else {
    $smarty->assign('login_role', 'client');
}
//$smarty->assign('login_permission', User::getPermission());

$keyword_categorys = Campaign::getPrefByCampaignID($_GET['campaign_id']);
$smarty->assign('keyword_categorys', $keyword_categorys);
//echo "<pre>";
//print_r($keyword_categorys);
////////BEGIN ADD BY cxz 2006-8-2 10:09上午
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
////////END ADD
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

//$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_type', $g_tag['leaf_article_type']);

$article_statuses = array_slice($g_tag['article_status'],8,null,true);
$smarty->assign('article_status', $article_statuses);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('query_string', http_build_query($_GET));
$client_id = isset($_GET['client_id'])&&$_GET['client_id'] > 0? $_GET['client_id'] : '';
$all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id);
$smarty->assign('all_campaigns', $all_campaigns);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
// added by nancy xu 2011-05-31 12:10
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_clients', $clients);
// end
$smarty->display('client_campaign/deliver_keywords.html');
?>