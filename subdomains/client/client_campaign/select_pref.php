<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Pref.class.php';

$result = Preference::getPref('campaign_keyword', 'keyword_category');
$smarty->assign('result', $result);
//print_r($result);
$smarty->assign('pref_info', $pref_info);
$smarty->display('client_campaign/select_pref.html');
?>