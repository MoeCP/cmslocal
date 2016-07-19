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

if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
// $smarty->assign('qstring',  '?' . $_SERVER['QUERY_STRING']);
$smarty->assign('data', $_GET);
if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
}
// added by nancy xu 2012-08-06 11:07
$optional_fields = CustomField::getFieldLabels(Client::getID(), 'optional');
$smarty->assign('optional_fields', $optional_fields);
// end
$smarty->display('article/download_option.html');
?>