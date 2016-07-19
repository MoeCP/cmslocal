<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if ((!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/image.class.php';


if (user_is_loggedin()) {
    if (trim($_POST['skeyword_id']) != '' && $_POST['form_refresh'] == "D") {
        if (ImageKeyword::setStatus('D', $_POST['skeyword_id'])){
            $feedback = "Delete Success";
            //sql_log();
            //header("Location:keyword_list.php");
            //exit;
        }
    }
    if (trim($_POST['image_id']) != '' && $_POST['form_refresh'] == "P") {
        $image_id = trim($_POST['image_id']);
        Image::setImageStatus($image_id, 6, 5);
    }
    //$smarty->assign('user_role', User::getRole());
} else {//client sign in
    //$smarty->assign('user_role', 'client');
}
//Start: Added By Snug 0:06 2006-08-14
/*if( !empty( $_POST ) )
{
	$operation = $_POST['operation'];
    $p = $_POST;
	switch( $operation )
	{
        case '1':
            $p['article_status'] = $operation;
            Image::batchApproveArticles($p);
            break;
        case 'client_approve':
            $p['article_status'] = 5;
            $keywords = $p['isUpdate'];
            if (count($keywords) == 0) {
                $feedback = "Please select articles to Force Client Approve";
            } else {
                Image::batchApproveArticles($p);
            }
            break;
       case 'editor_approve':
            $p['article_status'] = 4;
            Image::batchApproveArticles($p);
        	break;
        case 'editor_rejected':
            $p = $_POST;
            $p['article_status'] = 2;
            Image::batchApproveArticles($p);
        	break;
	}
}*/
//End Added

if (!empty($_GET)) {
    $search = ImageKeyword::search($_GET);
    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
        $smarty->assign('show_cb', $search['show_cb']);
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
$archived = $_GET['archived'];
$smarty->assign('archived', $archived);
//print_r($quick_pane);
//echo phpinfo();
//########quick pane########//

if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
} else {
    $smarty->assign('login_role', 'client');
}
//$smarty->assign('login_permission', User::getPermission());

//echo "<pre>";
//print_r($keyword_categorys);
////////BEGIN ADD BY cxz 2006-8-2 10:09上午
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
////////END ADD
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'designer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

//$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('image_type', ImageType::getAllLeafNodes());
$smarty->assign('image_status', $g_tag['image_status']);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('query_string', http_build_query($_GET));
$client_id = isset($_GET['client_id'])&&$_GET['client_id'] > 0? $_GET['client_id'] : '';
$all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id, -1, 2);
$smarty->assign('all_campaigns', $all_campaigns);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
// added by nancy xu 2011-05-31 12:10
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_clients', $clients);
// end
$smarty->display('client_campaign/image_keyword_list.html');
?>