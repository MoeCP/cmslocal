<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';
if (client_is_loggedin()) {
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
// $smarty->assign('qstring',  '?' . $_SERVER['QUERY_STRING']);
$smarty->assign('data', $_GET);
$campaign_info = Campaign::getInfo($_GET['campaign_id']);
$optional_fields = CustomField::getFieldLabels($campaign_info['client_id'], 'optional');
$smarty->assign('optional_fields', $optional_fields);
$smarty->display('article/download_option.html');
?>