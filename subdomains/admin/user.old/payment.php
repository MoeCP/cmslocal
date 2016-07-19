<?php
$g_current_path = "user";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

$smarty->assign('feedback', $feedback);
$smarty->display('user/payment.html');
?>