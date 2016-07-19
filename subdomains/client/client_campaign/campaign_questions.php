<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (client_is_loggedin()) {
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/Campaign.class.php';
require_once CMS_INC_ROOT . '/article_type_question.class.php';

if (!empty($_POST)) {
    if ($campaign_id = Campaign::saveQuestions($_POST)) {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/campaign_set.php?campaign_id=" . $campaign_id . "';</script>";
    } else {
        echo "<script>alert('".$feedback."');</script>";
    }
}
$campaign_id = $_GET['campaign_id'];
if (empty($campaign_id)) {
    echo "<script>alert('Please specify the campaign');</script>";
    echo "<script>window.location.href='/client_campaign/campaign_type.php';</script>";
    exit();
}
$info = Campaign::getInfo($campaign_id);
if ($info['client_id'] != Client::getID()) {
    echo "<script>alert('Have not the permission add/edit the question for this campaign');</script>";
    echo "<script>window.location.href='/client_campaign/campaign_type.php';</script>";
    exit();
};

if (!empty($info['questions'])) {
    $info['questions'] = unserialize($info['questions']);
    $questions = $info['questions'];
} else {
    $questions = Campaign::getLatestQuestionsByParam($info);
    if (empty($questions)) {
        $questions = Campaign::getLatestQuestionsByParam(array('source' => $info['source']));
        unset($questions['article_type']);
    }
    if (empty($questions)) $questions = array();
}

// added by nancy xu 2013-01-18 19:51
// get the article type questions from DB
$result = ArticleTypeQuestion::getQuestionsByParam(array('type_id' => $info['article_type']));
$g_questions['article_type'] = array();
foreach ($result as $item) {
    $qid = $item['qid'];
    if (!isset($questions['article_type'][$qid])) {
        $questions['article_type'][$qid] = array('v' => '', 'q'  => $item['question']);
    }
    $g_questions['article_type'][$qid] = $item['question'];
}
unset($questions['article_type']['link']);
unset($questions['article_type']['idea']);
unset($questions['article_type']['topic']);
unset($questions['article_type']['addition']);
// end
$smarty->assign('client_campaign_info', $info);
$smarty->assign('questions', $questions);
$smarty->assign('q_titles', $g_questions);
$smarty->assign('campaign_id', $campaign_id);
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
$client_id = Client::getID();
if ($client_id > 0)  {
    $domains += $oClientUser->getDomains(array('client_id' => $info['client_id']));
}
$smarty->assign('domains', $domains);

$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0)));
$smarty->display('client_campaign/campaign_questions.html');
?>