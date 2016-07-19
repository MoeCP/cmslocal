<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// let users who role is agency access this page
if (!user_is_loggedin() ||  User::getPermission() < 1) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT. DS . 'article_ranking.class.php';


if (count($_POST)) {
    if (isset($_POST['c_id']) && !empty($_POST['c_id'])) {
        $_POST['campaign_id'] = $_POST['c_id'];
    }

    if (isset($_POST['cp_id']) && !empty($_POST['cp_id'])) {
        $_POST['user_id'] = $_POST['cp_id'];
    }

    if (ArticleRanking::storeArticleRanking($_POST)) {
        $info = ArticleRanking::getAllArticleRankingInfo($_POST);
        if (!empty($info)) {
            $info = $info[0];
            $info['article_id'] = $_POST['article_id'];
            $info['repost_action'] = $_POST['post_action'];
            $info['repost_url'] = $_POST['post_url'];
            echo json_encode($info);
        }
    } 
}
?>