<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
if (User::getPermission() == 3) { // 2=>3
   $g_current_path = "article";
   //echo $g_current_path;
}
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['user_id']) != '' && $_POST['form_refresh'] == "D") {
    if (User::setStatus($_POST['user_id'], 'D')){
        $feedback = "Delete Success";
    }
    //sql_log();
    header("Location:list.php");
    exit;
}
//$result = User::getAllUsers($mode = 'all_infos');
$p = $_GET;
$p += array('role' => 'copy writer');
$search = User::search($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('pager', $search['total']);
    $smarty->assign('count', $search['count']);
}

$smarty->assign('users_status', $g_tag['users_status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/list_cpw.html');
?>