<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';


if (user_is_loggedin()) {
    if (trim($_POST['keyword_id']) != '' && $_POST['form_refresh'] == "D") {
        if (Campaign::setKeywordStatus($_POST['keyword_id'], 'D')){
            $feedback = "Delete Success";
            //sql_log();
            //header("Location:keyword_list.php");
            //exit;
        }
    }
    //$smarty->assign('user_role', User::getRole());
} else {//client sign in
    //$smarty->assign('user_role', 'client');
}

$p   = $_GET;
// added by snug xu 2007-07-24 19:26 - STARTED
$p += array('article_status' => array(3, 4, 5, 6));
// added by snug xu 2007-07-24 19:26 - FINISHED
$search = Campaign::searchKeyword($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
//    $smarty->assign('show_cb', $search['show_cb']);
}

//########quick pane########//
$quick_pane[0][lable] = "Client Campaign Management";
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
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/keyword_list.html');
?>