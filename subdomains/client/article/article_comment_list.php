<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 3) && !client_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
//require_once CMS_INC_ROOT.'/Campaign.class.php';

//echo $_GET['article_id'];
//print_r($_GET);
if (trim($_GET['article_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/article/article_list.php';</script>";
    exit;
}

$article_info = Article::getInfo($_GET['article_id'], true);
$smarty->assign('article_info', $article_info);
// added by nancy xu 2010-03-12 13:25
if (!empty($_POST)) {
    if (Article::sentComments($article_info,$_POST['comment'])) {
        $query_string = $_SERVER['QUERY_STRING'];
        $url = "/article/article_comment_list.php";
        if (!empty($query_string)) {
            $url .= '?' . $query_string;
        }
        echo "<script>window.location.href='" . $url . "';</script>";
    }
}
// end
//########quick pane########//
$quick_pane[0][lable] = "Client Campaign Management";
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

if ($_GET['article_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
	$quick_pane[2][lable] = $article_info['keyword'];
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

// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $article_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$g_custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$smarty->assign('custom_fields', $g_custom_fields);
$smarty->assign('optional_fields', $optional_fields);
// end

$smarty->assign('feedback', $feedback);
$smarty->assign('comment_count', count($article_info['comment']));
$smarty->display('article/article_comment_list.html');
?>