<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
//require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

$search = Article::searchArticleHistory($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/article_history_list.html');
?>