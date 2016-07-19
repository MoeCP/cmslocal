<?php
$g_current_path = "help";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin()) { 
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/label_field_description.class.php';
$feedback = '';
if (empty($_GET) || empty($_GET['ukey'])) {
    $feedback = 'Invalid Key, please to check';
} else { 
    $unique_key = $_GET['ukey'];
    $info = LabelFieldDescription::getInfoByUniqueKey($unique_key);
    if (empty($info)) $feedback = 'Invalid Key, please to check';
    else $smarty->assign('info', $info);
}
if (!empty($feedback)) {
    //echo "<script>alert('{$feedback}');window.close();</script>";
}
$smarty->display('manual_content/tip.html');
?>