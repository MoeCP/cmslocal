<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
$file_name = rawurldecode($_GET['f']);
download_file($file_name);
?>