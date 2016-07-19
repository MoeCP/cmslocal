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

if (!empty($_POST)) {
    $campaign_id = $_POST['campaign_id'];
    $ids = $_POST['isUpdate'];
    if ($campaign_id > 0) {
        FeedArticle::addFeedsToArticle($ids, $campaign_id);
    }
}

$search = FeedArticle::getList($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('total_unstored', $search['total_unstored']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$campaign_id = $_GET['campaign_id'];
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/feeds.html');
?>