<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';

if (trim($_POST['campaign_id']) != '' && trim($_POST['keyword']) != '') {
    if (Campaign::addKeyword($_POST)) {
        //sql_log();
        //echo "<script>alert('".$feedback."');</script>";
        //echo "<script>window.location.href='/client_campaign/keyword_list.php?campaign_id=".$_POST['campaign_id']."';</script>";
        //exit;
    }
}
if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose a campaign');</script>";
    echo "<script>window.location.href='/client_campaign/list.php';</script>";
    exit;
}

//************************* quick pane **********************************//
$quick_pane[0][lable] = "Client Campaign Management";
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
    $header = substr($header,0,strlen($header)-1);

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
//************************* end quick pane ******************************//

//$campaign_info = Campaign::getInfo($_GET['campaign_id']);
$smarty->assign('campaign_info', $campaign_info);

$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$smarty->assign('all_editor', $all_editor);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->assign('keyword_info', $_POST);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/keyword_form.html');
?>