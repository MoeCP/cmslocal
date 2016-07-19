<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
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

$search = Article::search($_GET);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
}

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$smarty->assign('article_statuses', $g_tag['article_status']);

$smarty->assign('login_permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', $g_tag['article_type']);
$smarty->display('article/article_list.html');
?>