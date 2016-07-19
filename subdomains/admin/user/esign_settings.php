<?php
$g_current_path = "preference";
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

$libs = $oEsign->getLibDocs(true);
$libs = $oEsign->getAllDoc();
if (!empty($_POST)) {
    $p = $_POST;
    //$p['config_id'] = $config['config_id'];
    $p['libs'] = $libs;
    $p['params'] = array('libUpdated'=>time());
    UserEsignConfig::store($p);
    echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/user/esign_settings.php';</script>";
    exit();
}


$smarty->assign('config', $config);
$smarty->assign('libs', $libs);
$smarty->display('user/esign_settings.html');
?>