<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (!user_is_loggedin() || User::getPermission() < 2 &&  User::getPermission() != 3) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/image_type.class.php';

$types = array('-1' =>  '[default]');
$campaign_type = $_GET['tid'];
if ($campaign_type== 1) {
    $types = $types + ArticleType::getAllLeafNodes(array('is_inactive' => 0));
} else if ($campaign_type == 2) {
    $types = $types + ImageType::getAllLeafNodes();
}
if ($campaign_type== 1) {
    $str = 'Default Article Type';
} else if ($campaign_type == 2) {
    $str = 'Default Image Type';
}

$smarty->assign('types', $types);
$smarty->assign('spanstr', $str);
$smarty->display('client_campaign/gettypes.html');
?>