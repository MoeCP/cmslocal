<?php
$g_current_path = "help";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
if (!user_is_loggedin()){
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$type = $_GET['t'];
if (!empty($type)) $url = '/' . $type;
else $url = '/bbs';
$smarty->assign('url', $url);
$smarty->display('manual_content/cmsf.html');
?>