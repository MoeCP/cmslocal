<?php
$g_current_path = "my_account";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
$smarty->assign('pay_pref', User::getPayPref());
$smarty->display('manual_content/contractor.html');
?>