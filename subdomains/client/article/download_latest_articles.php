<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';
if (client_is_loggedin()) {
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
}
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->display('article/download_latest_articles.html');
?>