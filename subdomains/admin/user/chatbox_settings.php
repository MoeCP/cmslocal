<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_POST)) {
	$p = array();
    $p["pref_value"] = $_POST["status"];
	$p["pref_table"] = "chatbox_setting";
	$p["pref_field"] = "status";
	$p["pref_id"] = $_POST["pref_id"];

    Preference::storePref($p);
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/user/chatbox_settings.php';</script>";
    exit();
}

$settings = Preference::getPrefAllInfo("chatbox_setting", "status");
if (empty($settings)) {
	$settings["pref_id"] = 0;
	$settings["status"] = 0;
} else {
	$settings = $settings[0];
}
$smarty->assign('settings', $settings);

$smarty->display('user/chatbox_settings.html');
?>