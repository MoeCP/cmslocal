<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 5) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

// loading classes
require_once CMS_INC_ROOT.'/Pref.class.php';

// store preference info;
if (isset($_POST) && count($_POST)) {
     $pref_values = explode(";", trim($_POST['user_ids'], "; "));
     $params = $_POST;
     unset($_POST['user_ids']);
     $params['pref_values'] = $pref_values;
     Preference::storeBatch($params);
}

// get all system user
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');

// get user id that have priviledge to see client total spend
$pref = Preference::getPref("users", 'user_id');

// get all users that have priviledge to see client total spend
if (is_array($pref) &&  count($pref) )
{
    $selected_users = User::getAllUsersByUserIDs('id_name_only', $pref['user_id']);
}

$smarty->assign('all_editor', $all_editor);
$smarty->assign('selected_users', $selected_users);
//$smarty->assign("user_data", $user);
$smarty->assign('feedback', $feedback);
$smarty->display('user/preference.html');
?>