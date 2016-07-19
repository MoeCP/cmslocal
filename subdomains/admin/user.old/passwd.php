<?php
//$g_current_path = "preference";
$g_current_path = "my_account";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['user_id']) != '' && trim($_POST['new_pw1']) != '' && trim($_POST['user_pw']) != '') {
    User::setPasswd($_POST['user_id'], $_POST['new_pw1'], $_POST['new_pw2'], true, $_POST['user_pw']);
}

if (User::getID() == 0) {
    echo "<script>alert('User ID isn't exist, Please enter this system again');</script>";
    exit();
}

$smarty->assign('user_id', $_SESSION['user_id']);
//$smarty->assign("user_data", $user);
$smarty->assign('feedback', $feedback);
$smarty->display('user/passwd.html');
?>