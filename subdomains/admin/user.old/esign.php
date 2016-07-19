<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/esignlib.php';
require_once CMS_INC_ROOT.'/UserEsignConfig.class.php';
require_once CMS_INC_ROOT.'/UserEsign.class.php';
require_once CMS_INC_ROOT.'/UserEsign.class.php';
require_once CMS_INC_ROOT.'/UserEsignGroup.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$user_id = $_GET['user_id'];
$user = User::getInfo($user_id);
$config = UserEsignConfig::getDefaultConfig();
$oEsign = new ESignLib($config);

if (!empty($_POST)) {
    $p = $_POST;
    $p['config_id'] = $config['config_id'];
    UserEsignGroup::storeDocs($p, $oEsign);
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/user/list.php';</script>";
    exit();
}

$libs = $oEsign->getLibDocs();
if ($oEsign->getIsUpdated()) {
    $config['libs'] = $oEsign->getAllDoc();
    $config['params'] = array('libUpdated'=>time());
    UserEsignConfig::store($config);
}
$smarty->assign('email', $user['email']);
$smarty->assign('user', $user);
$smarty->assign('user_id', $user_id);
$smarty->assign('libs', $libs);
$smarty->assign('config', $config);
$smarty->display('user/esign.html');
?>