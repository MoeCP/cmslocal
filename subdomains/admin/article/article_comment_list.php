<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/article_score.class.php';
require_once CMS_INC_ROOT.'/ArticleVersionHistory.class.php';
require_once CMS_INC_ROOT. DS . 'article_ranking.class.php';

//echo $_GET['article_id'];
//print_r($_GET);
if (trim($_GET['article_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}

$article_info = Article::getInfo($_GET['article_id'], true);

// added by nancy xu 2010-03-12 13:25
if (!empty($_POST)) {
    $query_string = $_SERVER['QUERY_STRING'];
    $url = "/article/article_comment_list.php";
    if (!empty($query_string)) {
        $url .= '?' . $query_string;
    }
    
    if (isset($_POST['score'])) {
        ArticleScore::storeArticleScore($_POST);
        echo "<script>window.location.href='" . $url . "';</script>";
    } else {
        if (Article::sentComments($article_info,$_POST['comment'])) {
            echo "<script>window.location.href='" . $url . "';</script>";
        }
    }
}
// end
if (count($article_info) <= 2)
{
    echo "<script>alert('Invalid Article, Please choose currect article.');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}
$versions = ArticleVersionHistory::getVersionListByArticleID($_GET['article_id']);
if (!empty($versions)) {
    $versions = array(0=>'Latest') + $versions;
    $smarty->assign('versions', $versions);
}

$posted_by = unserialize($article_info['posted_by']);
$smarty->assign('posted_by', $posted_by);

$param = array('copy_writer_id'=>$article_info['copy_writer_id'], 'campaign_id'=>$article_info['campaign_id'], 'keyword_id' => $article_info['keyword_id']);
$info = ArticleScore::getAllArticleScoreInfo($param);
if (empty($info)) {
    $info = $param;
} else {
    $info = $info[0];
}
$smarty->assign('info', $info);
//########quick pane########//
if (User::getPermission() >= 4) {
    $quick_pane[0][lable] = "Campaign Management";
    $quick_pane[0][url] = "/client_campaign/client_list.php";
} else {
    $quick_pane[0][lable] = "Campaigns";
    $quick_pane[0][url] = "/client_campaign/ed_cp_campaign_list.php";
}
$campaign_id = isset($_GET['campaign_id']) && !empty($_GET['campaign_id']) ? $_GET['campaign_id'] : $article_info['campaign_id'];
if ($campaign_id) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $campaign_info = Campaign::getInfo($campaign_id);
    if (User::getPermission() >= 4) {
        $quick_pane[1][lable] = $campaign_info['company_name'];
        if (User::getPermission() ==  3) {
            $quick_pane[1][url] = '/client_campaign/ed_cp_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
        } else {
            $quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
        }
    }
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
    if (empty($_SESSION['campaign_url'])) {
        if (User::getPermission() == 3) { 
            $quick_pane[2][url] = '/article/article_list.php?campaign_id='.$campaign_info['campaign_id'];
        } elseif (User::getPermission() > 3) { 
            $quick_pane[2][url] = '/client_campaign/keyword_list.php?campaign_id='.$campaign_info['campaign_id'];
        }
    }
}

if ($_GET['article_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    reset($_GET);
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $header = substr($header,0,strlen($header)-1);
	$quick_pane[3][lable] = $article_info['keyword'];
	$quick_pane[3][url] = $header;
}
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
} else {
    $smarty->assign('login_role', User::getRole());
}

// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $article_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$g_custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$smarty->assign('custom_fields', $g_custom_fields);
$smarty->assign('total_custom', count($g_custom_fields));
$smarty->assign('optional_fields', $optional_fields);
$smarty->assign('total_optional', count($optional_fields));
$article_info = showLinkForOptionalFields($optional_fields, $article_info);
$smarty->assign('article_info', $article_info);
// end

$smarty->assign('feedback', $feedback);
$smarty->assign('comment_count', count($article_info['comment']));
$smarty->assign('rankings', $g_tag['ranking']);
$smarty->display('article/article_comment_list.html');
?>