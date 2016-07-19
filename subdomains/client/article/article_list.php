<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';

//if (User::getPermission() >= 3) {
//    if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "D") {
//        if (Article::setStatus($_POST['article_id'], 'D')){
//            $feedback = "Delete Success";
//        }
//        //sql_log();
//        header("Location:article_list.php");
//        exit;
//    }
//}

$p   = $_GET;
// added by snug xu 2007-07-24 19:26 - STARTED
$p += array('article_status' => array(3, 4, 5, 6));
// added by snug xu 2007-07-24 19:26 - FINISHED

$search = Article::search($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$smarty->assign('login_permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_statuses', $g_tag['article_status']);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/article_list.html');
?>