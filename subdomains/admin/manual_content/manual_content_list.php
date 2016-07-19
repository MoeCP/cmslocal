<?php
$g_current_path = "help";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/manual_content.class.php';

if (!user_is_loggedin() && (User::getPermission() >= 5 || User::getPermission() == 1)) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

$content_id = $_REQUEST['content_id'];
if (User::getPermission() >= 5) { // 4=>5
    if (trim($_POST['content_id']) != '' && $_POST['form_refresh'] == "D") {
//        die(print($_POST['content_id']));
        if (ManualContent::delManualByContentId(array('content_id'=>$_POST['content_id']))){
            header("Location:manual_content_list.php");
            exit;
        }
    }
}
if (count($_GET)) {
    
    if ( !isset($_GET['keyword']) || empty($_GET['keyword'])) {
        echo "<script>alert('Please enter keyword!');</script>";
        echo "<script>window.location.href='/manual_content/manual_content_list.php';</script>";
    } else {
        $cat_selected = $_GET['category'];
        $smarty->assign('cat_selected', $cat_selected);
    }
}
$contents = ManualContent::getManualContent($_GET);

$user_permission = User::getPermission();
$param = array('pref_table' => 'manual_content',
               'pref_field' => 'category');
$categories = ManualContent::getContentCategory($param);

$category[-1] = "All Category";
if (!empty($categories)) {
    foreach ($categories as $c) {
        $category[$c['pref_id']] = $c['pref_value'];
    }
} 

$smarty->assign('contents', $contents);
$smarty->assign('category', $category);
$smarty->assign('user_permission', $user_permission);
$smarty->assign('count_start',0);
$smarty->display('manual_content/manual_content_list.html');
?>