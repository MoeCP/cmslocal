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

$permission = User::getPermission();
if (!user_is_loggedin() ||  $permission < 1) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
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
    echo "<script>alert('Please choose an image');</script>";
    echo "<script>window.location.href='/graphics/image_list.php';</script>";
    exit;
}

$image_info = Image::getInfo($_GET['image_id'], true);
if (empty($image_info)) {
    echo "<script>alert('No images about this keyword');window.close();</script>";
    exit;
}
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $image_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$smarty->assign('optional_fields', $optional_fields);
$smarty->assign('total_optional', count($optional_fields));
$smarty->assign('image_info', $image_info);
$keyword_info = ImageKeyword::getInfo($image_info['keyword_id']);
$smarty->assign('keyword_info', $keyword_info);

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/list.php";
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
    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[1][lable] = $_SESSION['campaign_lable'];
	$quick_pane[1][url] = $_SESSION['campaign_url'];
}

if ($_GET['image_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
	$quick_pane[2][lable] = $keyword_info['keyword'];
	$quick_pane[2][url] = $header;
}
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
} else {
    $smarty->assign('login_role', User::getRole());
}
$smarty->assign('login_role', User::getRole());

$smarty->assign('feedback', $feedback);
$smarty->assign('from', $_GET['from']);
$smarty->assign('permission', $permission);
$smarty->assign('comment_count', count($image_info['comment']));
$smarty->display('graphics/image_review.html');
?>