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

if (trim($_POST['title']) != '' && trim($_POST['full_text']) != '') {
    $_POST['state'] = (isset($_POST['state']) && !empty($_POST['state'])) ? $_POST['state'] : 0;
    if (ManualContent::addManualContent($_POST)) {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/manual_content/manual_content_list.php';</script>";
        exit;
    }
}
if (isset($_GET['content_id']) && !empty($_GET['content_id'])) {
    $content = ManualContent::getManualContent($_GET);
    if (!empty($content)) {
        $res = $content[0];
    }else {
        $res = array();
    }
}

$param = array('pref_table' => 'manual_content',
               'pref_field' => 'category');
$categories = ManualContent::getContentCategory($param);

if (!empty($categories)) {
    foreach ($categories as $c) {
        $cat[$c['pref_id']] = $c['pref_value'];
    }
} 

$res['state'] = isset($res['state'])  ? $res['state'] : 1;
$checked = ($res['state'] == 1) ? 'checked="checked"' : '';
$res['created_by'] = empty($res['created_by']) ? User::getID() : $res['created_by'];
$res['created'] = empty($res['created']) ? date("Y-m-d H:i:s") : $res['created'];
$smarty->assign('content', $res);
$smarty->assign('checked', $checked);
$smarty->assign('feedback', $feedback);
$smarty->assign('categories', $cat);
$smarty->display('manual_content/add_manual_content.html');
?>