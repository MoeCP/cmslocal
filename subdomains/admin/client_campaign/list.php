<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//╪стьеДжцпео╒
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if ((!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (user_is_loggedin() && (User::getPermission() > 4 || User::getPermission() == 2)) { // 3=>4
    if (trim($_POST['campaign_id']) != '' && $_POST['form_refresh'] == "D") {
        if (Campaign::setStatus($_POST['campaign_id'], 'D')){
            $feedback = "Delete Success";
        }
        //sql_log();
        header("Location:list.php");
        exit;
    }
    //$smarty->assign('user_role', User::getRole());
} else {//client sign in
    //$smarty->assign('user_role', 'client');
}
$_GET['sort'] = 'cc.campaign_id DESC'; 
$search = Campaign::search($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$archived = isset($_GET['archived']) ? $_GET['archived'] : 0;
$smarty->assign('archived', $archived);

// added by snug xu 2007-05-29 9:52 - STARTED
// Does the total spend show for current user
$pref = Preference::getPref("users", 'user_id');
$is_show = is_array($pref['user_id']) ? in_array(User::getID(), $pref['user_id']) : false;
$smarty->assign('is_show', $is_show);
$smarty->assign('campaign_type', $g_tag['campaign_type']);
// added by snug xu 2007-05-29 9:52 - FINISHED

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
if ($_GET['client_id']) {
    $quick_pane[1][lable] = $_GET['company_name'];
    $quick_pane[1][url] = "/client_campaign/list.php?client_id=".$_GET['client_id']."&company_name=".$_GET['company_name'];
}

$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

$smarty->assign('feedback', $feedback);
if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_id', User::getID());//ADD BY cxz 2006-7-28 16:15
    $smarty->assign('login_permission', User::getPermission());
    $smarty->display('client_campaign/list.html');
} else {
    $smarty->assign('login_role', 'client');
    $smarty->display('client_campaign/client_login_list.html');
}
?>