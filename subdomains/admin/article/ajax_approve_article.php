<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT. DS . 'article_ranking.class.php';
require_once CMS_INC_ROOT.'/campaign_notes.class.php';
require_once CMS_INC_ROOT.'/article_type.class.php';
require_once CMS_INC_ROOT.'/DomainTag.class.php';

if( !$is_ajax )
{
	if (client_is_loggedin()) {
		$g_current_path = "client_campaign";
		require_once('../client/cms_client_menu.php');
	} 
	else 
	{
		require_once('../cms_menu.php');
	}
}

if ((!user_is_loggedin() || User::getPermission() < 3) && !client_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';
$query_string = $_SERVER['QUERY_STRING'];
$current_url = "/article/ajax_approve_article.php?is_ajax=1&" . $query_string;
$from_page = $_GET['fmp'];
$role = User::getRole();
if (trim($_POST['article_id']) != ''  && trim( $_POST['approve_action'] ) != '') {
    $campaign_id = !empty($_GET['campaign_id']) ? $_GET['campaign_id'] : $_POST['campaign_id'];
    $p = $_POST;
	$action = $_POST['approve_action'];
    if (Article::approveArticle($p)) 
    {
        switch ($action)
        {
        case 'approval'://if editor approval or client approval, redirect to keyword list
            $feedback = "Success";
            echo "<script>alert('".$feedback."');</script>";
            echo locationString($role, $from_page, $campaign_id, null, $_GET);
            exit;
            break;
        case 'temp':
        case 'autotemp':
            if ($is_ajax)
                echo "Saved";
            break;
        case 'reject':
            $feedback = 'Edit Request Submitted';
            echo "<script>alert('".$feedback."');</script>";
            echo locationString($role, $from_page, $campaign_id, null, $_GET);
            exit();
            break;			
        }
    } else {
        echo $feedback;
        exit();
    }
}

if (trim($_GET['article_id']) == '' || trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}
// added by nancy xu 2011-01-20 16:14
require_once CMS_INC_ROOT.'/zemanta_api.class.php';
$api_key = 'mdty8258ev2gchp3hwcb74sa';
ZemantaApi::minusUnusedByApiKey($api_key);
$smarty->assign('api_key', $api_key);
//end
$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
//$smarty->assign('keyword_info', $keyword_info);
$keyword_info += Article::getInfo($_GET['article_id'], true);
$keyword_info['richtext_body'] = htmlspecialchars($keyword_info['richtext_body']);
// added by nancy xu 2011-03-15 17:45
$article_id = $keyword_info['article_id'];
$tags = ArticleTag::showTags4Article($keyword_info['article_id']);
$smarty->assign('tags', $tags);
$smarty->assign('article_id', $article_id);
//end
// modified by nancy xu 2011-7-18 18:02
$param = array('user_id'=>$keyword_info['copy_writer_id'], 'campaign_id'=>$keyword_info['campaign_id'], 'keyword_id' => $keyword_info['keyword_id']);
$rank = ArticeRanking::getRankingValue($param);
if (!empty($rank)) {
    foreach ($rank as $k => $v) {
        $ranking_id = $k;
        $ranking = $v['ranking'];
    }
}
$smarty->assign('ranking_id', $ranking_id);
$smarty->assign('ranking', $ranking);
//end

$smarty->assign('keyword_info', $keyword_info);
// added by snug xu 2007-08-10 11:04 - STARTED
$is_show_extra_info = false;
$is_show_url_category = false;
if (preg_match('/AOL/', $keyword_info['campaign_name']))
{
    $is_show_extra_info = true;
}
elseif (preg_match('/AcademicInfo\.net/', $keyword_info['campaign_name']))
{
    $is_show_url_category = true;
}

$smarty->assign('is_show_extra_info', $is_show_extra_info);
$smarty->assign('is_show_url_category', $is_show_url_category);
// added by snug xu 2007-08-10 11:04 - FINISHED

//////////////////ADD BY cxz 2006-7-28 18:29
$sql = "SELECT en.*, u.user_name FROM editor_notes as en, users as u WHERE en.keyword_id={$keyword_info['keyword_id']} AND en.campaign_id={$keyword_info['campaign_id']} AND en.copy_writer_id={$keyword_info['copy_writer_id']} AND en.editor_id={$keyword_info['editor_id']} AND en.editor_id=u.user_id ";
//$rs = $conn->GetRow($sql);
//$notes = $rs;
$rs = &$conn->Execute($sql);
$notes = array();
if ($rs) {
	$notes = $rs->fields;
	$rs->Close();
}
//start:modifed by snug 23:50 2006-07-31
$notes['notes'] = nl2br( $notes['notes'] );
//end
$smarty->assign('notes', $notes);
//////////////////ADD END
//---------------------quick pane-------------------//
if (user_is_loggedin()) {
    if (User::getPermission() >= 3) { // 2=>3
        $quick_pane[0][lable] = "Campaign Management";
        $quick_pane[0][url] = "/client_campaign/client_list.php";
    } else {
        $quick_pane[0][lable] = "Campaign & Article Management";
        $quick_pane[0][url] = "/client_campaign/ed_cp_campaign_list.php";
    }
    if ($_GET['campaign_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $campaign_info = Campaign::getInfo($_GET['campaign_id']);
        $header = substr($header,0,strlen($header)-1);
        if (User::getPermission() > 3) {
            if (User::getPermission() >= 3) { // 2=>3
                $quick_pane[1][lable] = $campaign_info['company_name'];
                $quick_pane[1][url] = '/client_campaign/ed_cp_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            }
        }

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        if ($_GET['keyword_id'] == '') {
            $_SESSION['campaign_url'] = $header;
        }
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }

    if ($_GET['keyword_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        reset($_GET);
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $quick_pane[3][lable] = $keyword_info['keyword'];
        $quick_pane[3][url] = $header;
    }
}
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//

$smarty->assign('feedback', $feedback);
if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
} else {
	// modifed by snug xu 2006-10-24 13:28
	// start
	$login_role = User::getRole();
	if ($login_role == 'project manager' || $login_role == 'admin')
	{
		$smarty->assign('rating', $g_tag['rating']);
		$smarty->assign('rating_keys', array_keys($g_tag['rating']));
	}
    $smarty->assign('login_role', $login_role);
	// end
}

$quotiety = ArticleRanking::getRankingQuotietyByField();
if (empty($quotiety)) { 
    $quotiety_is_empty = '';
} else {
    $quotiety_is_empty = 'not empty';
}


$general_note_subjects = CampaignNotes::getGeneralNotes(array('single_column'=>'subject'));
$general_note_bodies = CampaignNotes::getGeneralNotes(array('single_column'=>'body'));

$smarty->assign('general_note_subjects', $general_note_subjects);
$smarty->assign('general_note_bodies', $general_note_bodies);
$smarty->assign('quotiety_is_empty', $quotiety_is_empty);
$smarty->assign('languages', $g_tag['language']);
$smarty->assign('url', $current_url);
// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $keyword_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$g_custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$smarty->assign('custom_fields', $g_custom_fields);
$smarty->assign('optional_fields', $optional_fields);
// end
//add 
$smarty->assign('number', 1);
$smarty->assign('comment_count', count($keyword_info['comment']));
$smarty->assign('article_type', $g_tag['leaf_article_type']);
if( !$is_ajax )
	$smarty->display('article/ajax_approve_article.html');
?>