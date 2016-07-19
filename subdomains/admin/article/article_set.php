<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}

$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
if( !$is_ajax )
{
	require_once('../cms_menu.php');
}
$permission = User::getPermission();
$role = User::getRole();
if (!user_is_loggedin() || $permission < 1) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/DomainTag.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';
$query_string = $_SERVER['QUERY_STRING'];

if (trim($_POST['article_id']) != '') {
    $p = $_POST;
    if (Article::setInfo($p)) {
		if( $is_ajax ) {
			echo "Saved" ;
		} else {
        //sql_log()
		    $campaign_id = $_GET['campaign_id'];
			if( strlen( $campaign_id ) ===0 )
			{
				$keyword_id = mysql_escape_string( trim( $_GET['keyword_id'] ) );
				$campaign_id = Campaign::getCampaignIDByKeywordID( $keyword_id );
			}
			$feedback = "Success";
			echo "<script>alert('".$feedback."');</script>";
            $from = $_GET['from'];
            if (empty($from)) {
                $from = 'article_keyword_list';
            }
            echo locationString($role, $from, $campaign_id, null, $_GET);
			exit;
		}
    } else {
		if( $is_ajax )
			echo "Failure, Please try again" ;
	}
}

if (trim($_GET['article_id']) == '' || trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}

$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
//$smarty->assign('keyword_info', $keyword_info);
$article_info = Article::getInfo($_GET['article_id'], true);

if (!empty($article_info) && !empty($keyword_info)) 
{
    $keyword_info += $article_info;
}
else
{
    echo "<script>alert('Invalid Article, Please choose currect article.');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}
$article_id = $article_info['article_id'];
$tags = ArticleTag::showTags4Article($article_info['article_id']);
$smarty->assign('tags', $tags);
$smarty->assign('article_id', $article_id);
$smarty->assign('comment_count', count($keyword_info['comment']));
//echo "<pre>";
//print_r($keyword_info);
//////////////////ADD BY cxz 2006-7-28 18:29
$sql = "SELECT en.*, u.user_name FROM editor_notes as en, users as u WHERE en.keyword_id={$keyword_info['keyword_id']} AND en.campaign_id={$keyword_info['campaign_id']} AND en.copy_writer_id={$keyword_info['copy_writer_id']} AND en.editor_id={$keyword_info['editor_id']} AND en.editor_id=u.user_id ";
$notes = $conn->GetRow($sql);
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
// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $keyword_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$g_custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$js = getJsForCustomFields($g_custom_fields);
$smarty->assign('jsCode', $js);
$smarty->assign('custom_fields', $g_custom_fields);
$smarty->assign('optional_fields', $optional_fields);
$keyword_info = showLinkForOptionalFields($optional_fields, $keyword_info);
//pr($keyword_info);
$smarty->assign('keyword_info', $keyword_info);
// end
$smarty->assign('feedback', $feedback);
//$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('languages', $g_tag['language']);
$smarty->assign('image_categories', $g_2image_categories);
//$smarty->assign('url', "http://".$_SERVER['HTTP_HOST'] . "/article/article_set.php?is_ajax=1&".$query_string);
$smarty->assign('url', "/article/article_set.php?is_ajax=1&".$query_string);
// added by snug xu 2007-03-05 10:08 - STARTED
$login_role = User::getRole();
$smarty->assign('login_role', $login_role);
$smarty->assign('image_categories', $g_2image_categories);
// added by snug xu 2007-03-05 10:08 - FINISHED
if( !$is_ajax )
{
	$smarty->display('article/article_form.html');
}
?>