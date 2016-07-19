<?php
$g_current_path = "client";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/DomainTag.class.php';
$source = $_GET['source'];
if ($source > 0) {
    $tags = DomainTag::getAllTagsBySource($source);
    $smarty->assign('tags', $tags);
}
$smarty->display('client/tags.html');
?>