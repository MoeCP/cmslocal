<?php
$g_current_path = "help";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!user_is_loggedin() ) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/manual_content.class.php';

if (isset($_GET['content_id']) && !empty($_GET['content_id'])) {
    $content = ManualContent::getManualContent($_GET);
    if (!empty($content)) {
        $res = $content[0];
    }else {
        $res = array();
    }
}

if ($res['category'] > 0) {
    $category = Preference::getPrefById($res['category']);
}

$res['full_text'] = stripslashes($res['full_text']);
$res['full_text'] = htmlspecialchars_decode($res['full_text']);

$res['title'] = stripslashes($res['title']);
$res['title'] = htmlspecialchars_decode($res['title']);
$smarty->assign('content', $res);
$smarty->assign('feedback', $feedback);
$smarty->assign('category', $category);
$smarty->display('manual_content/view_manual_content.html');
?>