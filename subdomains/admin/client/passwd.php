<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../client/cms_client_menu.php');
$logout_folder .= '/client';

require_once CMS_INC_ROOT.'/Client.class.php';
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

//##注意这里的$_POST['user_id']其实就是client_id
if (trim($_POST['user_id']) != '' && trim($_POST['new_pw1']) != '' && trim($_POST['user_pw']) != '') {
    Client::setPasswd($_POST['user_id'], $_POST['new_pw1'], $_POST['new_pw2'], true, $_POST['user_pw']);
}

if (Client::getID() == 0) {
    echo "<script>alert('User ID isn't exist, Please enter this system again');</script>";
    exit();
}

$smarty->assign('user_id', $_SESSION['client_id']);
//$smarty->assign("user_data", $user);
$smarty->assign('feedback', $feedback);
$smarty->display('user/passwd.html');
?>