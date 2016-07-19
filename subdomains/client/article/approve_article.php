<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
// $conn->debug = true;
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

if ((!user_is_loggedin() || User::getPermission() < 3) && !client_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';
//Added by snug 15:34 2006-9-10
$query_string = $_SERVER['QUERY_STRING'];
$is_ajax = $_GET['is_ajax'];
$from_page = $_GET['fmp'];
//Ended Added
if (trim($_POST['article_id']) != ''  && trim($_POST['approve_action']) != '') {
    $p = $_POST;
    if (trim($_POST['temp_body']) != '') {
        $p['body'] = $_POST['temp_body'];
    }
	//Modifed by snug 11:40 2006-09-10
	$action = $_POST['approve_action'];
    $p['body'] = html_entity_decode($p['body']);
    $opt = $_POST['opt'];
    unset($_POST['opt']);
    $role = 'client';
    $campaign_id = !empty($_GET['campaign_id']) ? $_GET['campaign_id'] : $_POST['campaign_id'];
    if (Article::approveArticle($p)) 
	{
		switch( $action )
		{
			case 'approval'://if editor approval or client approval, redirect to keyword list
				$feedback = "Success";
				echo "<script>alert('".$feedback."');</script>";
                echo locationString($role, $from_page, $campaign_id);
                exit();
				// echo "<script>history.back();self.location.reload();</script>";
				exit;
				break;
			case 'temp':
			case 'autotemp':
				if( $is_ajax )
					echo "Saved";
				break;
			case 'reject':
				if( $is_ajax ) {
                    echo 'Edit Request Submitted';
                }
				break;			
		}
    }
	else
	{
		if( $is_ajax )
		{
			global $feedback;
			echo "$feedback";
		}
	}
	//Ended Modifed
}

if (trim($_GET['article_id']) == '' || trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}

$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
//$smarty->assign('keyword_info', $keyword_info);

$keyword_info = array_merge($keyword_info, Article::getInfo($_GET['article_id'], true));
$smarty->assign('keyword_info', $keyword_info);
$smarty->assign('noflow_statuses', array("0"=>"Select Noflow Status") + $g_tag['noflow_status']);
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
        if (User::getPermission() >= 3) { // 2=>3
            $quick_pane[1][lable] = $campaign_info['company_name'];
            $quick_pane[1][url] = '/client_campaign/ed_cp_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
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
if ($_GET['opt']) $smarty->assign('opt', $_GET['opt']);
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
} else {
    $smarty->assign('login_role', User::getRole());
}
$smarty->assign('languages', $g_tag['language']);
$smarty->assign('url', "/article/approve_article.php?is_ajax=1&".$query_string);

$smarty->assign('comment_count', count($keyword_info['comment']));

// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
if (empty($campaign_id)) $campaign_id = $keyword_info['campaign_id'];
$campaign_info = Campaign::getInfo($campaign_id);
$client_id = $campaign_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$g_custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$smarty->assign('custom_fields', $g_custom_fields);
$smarty->assign('optional_fields', $optional_fields);
$smarty->assign('client_id', $client_id);

// end
$smarty->assign('image_categories', $g_2image_categories);
if( !$is_ajax )
	$smarty->display('article/approve_article.html');
?>