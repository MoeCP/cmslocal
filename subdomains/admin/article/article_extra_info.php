<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT.'/ArticleExtraInfo.class.php';
require_once CMS_INC_ROOT.'/GeographicName.class.php';
$logout_folder = '';
if (client_is_loggedin()) {
    $logout_folder .= '/article';
} else {
    require_once('../cms_menu.php');
}

if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
if (count($_POST))
{
    ArticleExtraInfo::store($_POST);
    echo "<script>alert('{$feedback}');window.close();</script>";
    exit;
}
if (isset($_GET['article_id']))
{
    $article_id = $_GET['article_id'];
}

$info = ArticleExtraInfo::getInfoByArticleId($article_id);
$related_articles = array();
if (isset($info['related_articles']))
{
    $related_articles = unserialize($info['related_articles']);
}
$smarty->assign('related_articles', $related_articles);
$smarty->assign('info', $info);
$smarty->assign('extra_info_types', $g_tag['article_extra_info_type']);
$smarty->display('article/article_extra_info.html');
?>