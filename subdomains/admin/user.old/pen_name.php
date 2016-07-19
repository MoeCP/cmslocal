<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['user_id']) != '' && trim($_POST['pen_name']) != '') {
    User::setPenName( $_POST );
}

if (User::getID() == 0) {
    echo "<script>alert('User ID isn't exist, Please enter this system again');</script>";
    exit();
}
$user_info = User::getInfo($_SESSION['user_id']);
$smarty->assign('user_id', $_SESSION['user_id']);
$smarty->assign('pen_name', $user_info['pen_name']);
//$smarty->assign("user_data", $user);
$smarty->assign('feedback', $feedback);
$smarty->display('user/pen_name.html');
?>