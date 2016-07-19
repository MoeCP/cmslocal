<?php
$g_current_path = "help";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/manual_content.class.php';

if (trim($_POST['pref_value']) != '') {
    if (ManualContent::addContentCategory($_POST)) {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/manual_content/add_content_category.php';</script>";
        exit;
    }
}


if (isset($_GET['pref_id']) && !empty($_GET['pref_id'])) {
    $content = ManualContent::getContentCategory($_GET);
    if (!empty($content)) {
        $res = $content[0];
    }else {
        $res = array();
    }
}

$param = array('pref_table' => 'manual_content',
               'pref_field' => 'category');
$cat_result = ManualContent::getContentCategory($param);

$smarty->assign('content', $res);
$smarty->assign('categories', $cat_result);
$smarty->assign('feedback', $feedback);
$smarty->assign('table', "manual_content");
$smarty->assign('field', "category");
$smarty->display('manual_content/add_content_category.html');
?>