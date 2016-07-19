<?php
//$g_current_path = "client_campaign";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

// let users who role is agency access this page
if (!user_is_loggedin() && (User::getPermission() < 5)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/cp_campaign_ranking.class.php';
require_once CMS_INC_ROOT.'/article_action.class.php';

if (count($_GET)) {
    $cp_id = 0;
    $c_id  = 0;
    if (isset($_GET['copywriter_id']) && !empty($_GET['copywriter_id'])) {
        $cp_id = trim($_GET['copywriter_id']);
    }
    if (isset($_GET['campaign_id']) && !empty($_GET['campaign_id'])) {
        $c_id = trim($_GET['campaign_id']);
    }
    if ($cp_id && $c_id) {
        $user_ret = User::getInfo($cp_id);
        if (!empty($user_ret)) {
            $cp_name = $user_ret['user_name'];
        }
        
        //get ranking result
        $param = array('copy_writer_id'=>$cp_id, 'campaign_id'=>$c_id);
        $ranking_ret = CpCampaignRanking::getRankingValue($param);
        if (!empty($ranking_ret)) {
            foreach ($ranking_ret as $r) {
                $cp_ranking = $r['ranking'];
            }
        } else {
            $cp_ranking = 'n/a';
        }
        
        //get total articles number
        $total_articles = Campaign::getAllKeywordsByCp(array('copywriter_id'=>$cp_id), array("COUNT(DISTINCT keyword_id) AS num"));
        $cp_total_article = $total_articles[0]['num'];
        if ($cp_total_article) {
            //get all article number of editor reject
            // $pat = array('copywriter_id'=>$cp_id, 'status'=>'1gc', 'new_status'=>'2', 'article_id'=>$article_ids);
            $param = array('copywriter_id'=>$cp_id, 'status'=>'1gc', 'new_status'=>'2');
            $editor_reject = ArticleAction::getArticleNum($param);
            if ($editor_reject) {
                $cp_editor_reject = (float)$editor_reject / (float)$cp_total_article * 100;
                $cp_editor_reject = number_format($cp_editor_reject, 2, '.', '');
                $cp_editor_reject = $cp_editor_reject . "%";
            } else {
                $cp_editor_reject = $editor_reject . "%";
            }

            //get all client reject article number
            // $pat2 = array('copywriter_id'=>$cp_id, 'status'=>'4', 'new_status'=>'3', 'article_id'=>$article_ids);
            $param = array('copywriter_id'=>$cp_id, 'status'=>'4', 'new_status'=>'3');
            $client_reject = ArticleAction::getArticleNum($param);
            if ($client_reject) {
                $cp_client_reject = (float)$client_reject / (float)$cp_total_article * 100;
                $cp_client_reject = number_format($cp_client_reject, 2, '.', '');
                $cp_client_reject = $cp_client_reject . "%";
            } else {
                $cp_client_reject = $client_reject . "%";
            }
        } else {
            $cp_editor_reject = 'n/a';
            $cp_client_reject = 'n/a';
        }
    }
}

$smarty->assign('copywriter_name', $cp_name);
$smarty->assign('ranking', $cp_ranking);
$smarty->assign('editor_reject', $cp_editor_reject);
$smarty->assign('client_reject', $cp_client_reject);
$smarty->display('client_campaign/cp_assignment_ranking.html');
?>