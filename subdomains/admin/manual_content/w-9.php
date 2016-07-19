<?php
$g_current_path = "home";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
$pdf = '/doc/W-9.pdf';
$len = filesize($pdf);
header('Content-type: application/pdf');
header("Content-Length: $len");
header('Content-Disposition: inline; filename="'.$pdf.'"');
readfile($pdf);
?>