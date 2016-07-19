<?php
//$g_current_path = "preference";
$g_current_path = "my_account";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (User::getID() == 0) {
    echo "<script>alert('User ID isn't exist, Please enter this system again');</script>";
    exit();
}

if (trim($_POST['googleplus_url']) != '') {
    User::setAddtionalInfo($_POST);
}

$user_info = User::getInfo($_SESSION['user_id']);

$smarty->assign('user_id', $_SESSION['user_id']);
$smarty->assign("user_info", $user_info);
$smarty->assign('feedback', $feedback);
$smarty->display('user/addtionalinfo.html');
?>