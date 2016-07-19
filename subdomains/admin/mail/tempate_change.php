<?php
$g_current_path = "preference";
require_once('../pre.php');
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
	    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
		    exit;
}
$info = Email::getInfoByEventId($_GET['tpl_id']);
$str =  '<script>';
$str .= "$('subject').value=" . json_encode($info['subject'])."; ";
$str .= "tinyMCE.execCommand('mceSetContent',false," . json_encode(nl2br($info['body']))."); ";
$str .= '</script>';
echo $str;
exit();
?>
