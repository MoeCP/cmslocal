<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 16:19
// let the user who role is agency add keywords
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
if (isset($_FILES["keywords_file"]) && is_uploaded_file($_FILES["keywords_file"]["tmp_name"]) && $_FILES["keywords_file"]["error"] == 0) {
    echo rand(1000000, 9999999);	// Create a pretend file id, this might have come from a database.
    exit(0);
}

$smarty->display('client_campaign/import.html');
?>