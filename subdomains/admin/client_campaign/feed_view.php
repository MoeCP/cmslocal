<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/feed_url.class.php';
require_once CMS_INC_ROOT.'/feed_article.class.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$id = $_GET['id'];
$info = FeedArticle::getInfo($id);
$smarty->assign('info', $info);
$smarty->display('client_campaign/feed_view.html');
?>