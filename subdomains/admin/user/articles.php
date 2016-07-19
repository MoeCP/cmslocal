<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/request_extension.class.php';
$result = RequestExtension::getArticles($_GET);
$smarty->assign('result', $result);
$smarty->assign('article_types', $g_tag['article_type']);
$smarty->assign('feedback', $feedback);
$smarty->display('user/articles.html');
?>