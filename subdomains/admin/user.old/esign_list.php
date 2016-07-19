<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/esignlib.php';
require_once CMS_INC_ROOT.'/UserEsign.class.php';
require_once CMS_INC_ROOT.'/UserEsignGroup.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$result = UserEsignGroup::search($_GET);
if (!empty($result)) {
    $smarty->assign('pager', $result['pager']);
    $smarty->assign('total', $result['total']);
    $smarty->assign('result', $result['result']);
    $smarty->assign('count', $result['count']);
}
$smarty->assign('users', User::getAllUsers('id_name_only', 'all', false));
$smarty->assign('estatuses', $g_estatuses);
$smarty->display('user/esign_list.html');
?>