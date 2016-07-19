<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/esignlib.php';
require_once CMS_INC_ROOT.'/UserEsignConfig.class.php';
require_once CMS_INC_ROOT.'/UserEsign.class.php';
require_once CMS_INC_ROOT.'/UserEsignLog.class.php';
require_once CMS_INC_ROOT.'/UserEsignGroup.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$group_id = $_GET['group_id'];
if ($group_id > 0) {
    $data = UserEsignGroup::getInfoById($group_id);
    $smarty->assign('esigngroup', $data);
}
$smarty->assign('estatuses', $g_estatuses);
$smarty->display('user/esign_detail.html');
?>