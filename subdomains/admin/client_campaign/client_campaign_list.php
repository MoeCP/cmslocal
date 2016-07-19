<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (User::getPermission() == 3) { // 2=>3
   $g_current_path = "article";
   //echo $g_current_path;
}
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission() != 2)) {// 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
$p = $_GET;
$p['limit'] = $g_tag['campaign_limit'];
$result = Client::getCampaignReportByClient($_GET['client_id'], $p);
$smarty->assign('result', $result);
$smarty->assign('total', count($result));
$smarty->assign('client_id', $_GET['client_id']);
$smarty->assign('archived', $_GET['archived']);
// added by snug xu 2007-05-29 9:52 - STARTED
// Does the total spend show for current user
$pref = Preference::getPref("users", 'user_id');
$is_show = is_array($pref['user_id']) ? in_array(User::getID(), $pref['user_id']) : false;
$smarty->assign('is_show', $is_show);
$smarty->assign('is_home', $_GET['is_home']);
// added by snug xu 2007-05-29 9:52 - FINISHED

$smarty->display('client_campaign/client_campaign_list.html');
?>