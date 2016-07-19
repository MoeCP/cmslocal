<?php 
require_once('../pre.php');//加载配置信息
$_REQUEST['url_part'] = 'client';

if (strlen($_REQUEST['user']) == 0 && strlen($_REQUEST['pass']) == 0) {
	if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
        header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
        exit;
    }
}

if (trim($_REQUEST['cid']) == '' ) {
    echo "<script>alert('Please choose a campaign');window.close();</script>";
    exit;
}
getClientXML($_REQUEST);
//echo "<script>window.close();</script>";
exit;
?>