<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 1) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/request_extension.class.php';
if (!empty($_POST)) {
    $info = RequestExtension::getInfoByExtensionID($_POST['extension_id']);
    RequestExtension::reject($info);
    $query_string = $_SERVER['QUERY_STRING'];
    header("Location: /user/extension_requests.php?" . $query_string);
}
$search = Campaign::searchByCpAndEditor($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('statuses', $g_tag['extension_statuses']);
$smarty->assign('all_editor', $all_editor);
$smarty->assign('feedback', $feedback);
$smarty->assign('role', User::getRole());
$smarty->assign('startNo', getStartPageNo());
$smarty->display('user/request_list.html');
?>