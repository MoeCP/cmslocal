<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
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
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_comment.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_version_history.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

//echo $_GET['image_id'];
//print_r($_GET);
if (trim($_GET['image_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/graphics/image_list.php';</script>";
    exit;
}

$image_info = Image::getInfo($_GET['image_id'], true);

// added by nancy xu 2010-03-12 13:25
if (!empty($_POST)) {
    if (Image::sentComments($image_info,$_POST['comment'])) {
        $query_string = $_SERVER['QUERY_STRING'];
        $url = "/graphics/image_comment_list.php";
        if (!empty($query_string)) {
            $url .= '?' . $query_string;
        }
        echo "<script>window.location.href='" . $url . "';</script>";
    }
}
// end
if (count($image_info) <= 2)
{
    echo "<script>alert('Invalid Image, Please choose currect image.');</script>";
    echo "<script>window.location.href='/graphics/image_list.php';</script>";
    exit;
}
$versions = ImageVersionHistory::getVersionListByImageID($_GET['image_id']);
if (!empty($versions)) {
    $versions = array(0=>'Latest') + $versions;
    $smarty->assign('versions', $versions);
}

$posted_by = unserialize($image_info['posted_by']);
$smarty->assign('posted_by', $posted_by);
//########quick pane########//
if (User::getPermission() >= 4) {
    $quick_pane[0][lable] = "Campaign Management";
    $quick_pane[0][url] = "/client_campaign/client_list.php";
} else {
    $quick_pane[0][lable] = "Campaigns";
    $quick_pane[0][url] = "/graphics/designer_campaign_list.php";
}
$campaign_id = isset($_GET['campaign_id']) && !empty($_GET['campaign_id']) ? $_GET['campaign_id'] : $image_info['campaign_id'];
if ($campaign_id) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $campaign_info = Campaign::getInfo($campaign_id);
    if (User::getPermission() >= 4) {
        $quick_pane[1][lable] = $campaign_info['company_name'];
        if (User::getPermission() ==  3) {
            $quick_pane[1][url] = '/graphics/designer_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
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
            $quick_pane[2][url] = '/graphics/image_list.php?campaign_id='.$campaign_info['campaign_id'];
        } elseif (User::getPermission() > 3) { 
            $quick_pane[2][url] = '/graphics/image_keyword_list.php?campaign_id='.$campaign_info['campaign_id'];
        }
    }
}

if ($_GET['image_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    reset($_GET);
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $header = substr($header,0,strlen($header)-1);
	$quick_pane[3][lable] = $image_info['keyword'];
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
$client_id = $image_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$smarty->assign('optional_fields', $optional_fields);
$smarty->assign('total_optional', count($optional_fields));
$image_info = showLinkForOptionalFields($optional_fields, $image_info);
$smarty->assign('image_info', $image_info);
// end

$smarty->assign('feedback', $feedback);
$smarty->assign('comment_count', count($image_info['comment']));
$smarty->display('graphics/image_comment_list.html');
?>