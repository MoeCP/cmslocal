<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
if (!user_is_loggedin() || User::getPermission() < 4 && User::getPermission() > 1 ) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_GET)) {
    $file = $g_article_storage . 'img_profile/' . $_GET['g'];
    echo file_get_contents($file);
}
?>