<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if( count( $_POST ) )
{
	global $feedback;
	$operation = htmlspecialchars(trim($_POST['operation']));
	switch( $operation )
	{
		case "welcome_email":
			User::sendWelcomeEmail($_POST['user_id']);
			echo "<script>window.location.href='/user/user_last_login_list.php';</script>";
			exit;
			break;
	}
}
//$result = User::getAllUsers($mode = 'all_infos');
$search = User::getAllUserLastLoginTime($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}


$smarty->assign('login_role', User::getRole());
$smarty->assign('users_status', $g_tag['users_status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$smarty->display('user/user_last_login_list.html');
?>