<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission() <> 2)) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$str = file_get_contents("https://content.copypress.com/article/advancesearched.txt");
echo nl2br($str);
?>