<?php
$g_current_path = "preference";
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$user_id = $_GET['user_id'];
$f = $_GET['f'];
$file = $g_article_storage . 'esign' . DS . $user_id . DS . $f;
download($file, $f);
?>