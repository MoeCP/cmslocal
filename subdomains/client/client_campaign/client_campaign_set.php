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

if (trim($_POST['campaign_id']) != '' && trim($_POST['campaign_name']) != '') {
    if (Campaign::setInfo($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/list.php';</script>";
        exit;
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

//$client_campaign_info = Campaign::getInfo($_GET['campaign_id']);
$smarty->assign('client_campaign_info', $campaign_info);


$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);
$smarty->assign('expertises', $g_user_levels);
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0,'is_inactive' => 0)));
$smarty->display('client_campaign/client_campaign_form.html');
?>