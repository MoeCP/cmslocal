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

$quotiety = ArticleRanking::getRankingQuotietyByField();
if (empty($quotiety)) { 
    echo "<script>alert('Ranking quotiety is EMPTY! Please set it first in System Setting!');</script>";
    echo "<script>window.location.href='article_ranking_quotiety.php';</script>";
}

if (count($_GET)) {
    if (isset($_GET['ranking_id']) && !empty($_GET['ranking_id'])) {
        $ranking_id = $_GET['ranking_id'];
    }
    if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    }
    if (isset($_GET['campaign_id']) && !empty($_GET['campaign_id'])) {
        $campaign_id = $_GET['campaign_id'];
    }
    if (isset($_GET['keyword_id']) && !empty($_GET['keyword_id'])){ 
        $keyword_id = $_GET['keyword_id'];
    }
    if (isset($_GET['article_id']) && !empty($_GET['article_id'])) {
        $article_id = $_GET['article_id'];
    }
    $frm = $_GET['frm'];
    if (empty($frm)) $frm = 'approve_article';
    if (empty($ranking_id) && (empty($user_id) ||  empty($keyword_id))) {
        echo "<script>alert('Invalid article and copywriter');</script>";
        echo "<script>window.location.href='/article/" . $frm . ".php?article_id={$article_id}&keyword_id={$keyword_id}&campaign_id={$campaign_id}';</script>";
    }
}

if (count($_POST)) {
    if (isset($_POST['c_id']) && !empty($_POST['c_id'])) {
        $_POST['campaign_id'] = $_POST['c_id'];
    }

    if (isset($_POST['cp_id']) && !empty($_POST['cp_id'])) {
        $_POST['user_id'] = $_POST['cp_id'];
    }

    if (ArticleRanking::storeArticleRanking($_POST)) {
        echo "<script>alert('Success')</script>";
        if ($article_id > 0 && $keyword_id > 0 && $campaign_id > 0) {
            echo "<script>window.location.href='/article/" . $frm . ".php?article_id={$article_id}&keyword_id={$keyword_id}&campaign_id={$campaign_id}';</script>";
            exit;
            break;
        } else {
            echo "<script>window.location.href='/client_campaign/cp_ranking_search.php';</script>";
        }
    } else {
        echo "<script>alert('failed')</script>";
    }
}

//// the url parameters
//$url_param = "article_id=" . $article_id . "&keyword_id=" . $keyword_id;

//get all campaign name
$keyword_info = Campaign::keywordInfoByKeywordID($keyword_id, array('ck.keyword', 'cc.campaign_name'));
$campaign_name = $keyword_info['campaign_name'];
$keyword = $keyword_info['keyword'];

//get the copywriter user name
$cp_res = User::getAllUsersByUserIDs('user_name_only', $user_id);
$cp_username = $cp_res[$user_id];
$info = ArticleRanking::getAllArticleRankingInfo($_GET);
if (empty($info)) {
    $info = $_GET;
} else {
    $info = $info[0];
}
$info['user_name'] = $cp_username;
$info = array_merge($info, $keyword_info);

$smarty->assign('ranking_info', $info);
$smarty->assign('quotiety', $quotiety);
$smarty->assign('cp_ranking', $g_tag['cp_ranking']);
$smarty->assign('search_choice', $g_tag['search_choice']);
$smarty->assign('feedback', $feedback);
$clients = Client::getAllClients('id_name_only');
$smarty->assign('clients', $clients);
//$smarty->assign('url_param', $url_param);
$smarty->display('client_campaign/article_ranking.html');
?>