<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

if( !empty( $_POST ) )
{
    $keyword_ids = $_POST['isUpdate'];
    $keyword_description = $_POST['keyword_description'];
    Campaign::batchUpdateKeywordDescriptionByParam($_POST);
    echo "<script>alert('" . $feedback . "');</script>";
    $query_string = $_SERVER['QUERY_STRING'];
    echo "<script>window.location.href='/client_campaign/update_keyword_instructions.php" . (empty($query_string) ? '' : '?' . $query_string) .  "';</script>";
    exit();
}

if (empty($_GET) || empty($_GET['campaign_id'])) {
    echo "<script>alert('Please choose a campaign');</script>";
    echo "<script>window.location.href='/client_campaign/client_list.php';</script>";
    exit;
} else {
    $search = Campaign::keywords($_GET);
    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
    }
}

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
if ($_GET['campaign_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $campaign_info = Campaign::getInfo($_GET['campaign_id']);
    $smarty->assign('campaign_info', $campaign_info);
    $header = substr($header,0,strlen($header)-1);

    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
	$quick_pane[1][lable] = $campaign_info['company_name'];
	$quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];

    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
}
$smarty->assign('quick_pane', $quick_pane);
//########quick pane########//

if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
}

$keyword_categorys = Campaign::getPrefByCampaignID($_GET['campaign_id']);
$smarty->assign('keyword_categorys', $keyword_categorys);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/update_keyword_instructions.html');
?>