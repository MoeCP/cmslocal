<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 1) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';

if (trim($_POST['keyword_id']) != '') {
    $p = $_POST;
    if (trim($_POST['temp_body']) != '') {
        $p['body'] = $_POST['temp_body'];
    }
    if (Article::add($p)) {
        //sql_log();
        //echo "<script>alert('".$feedback."');</script>";
        //echo "<script>window.location.href='/article/article_list.php';</script>";
        //exit;
    }
}

if (trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose a keyword');</script>";
    echo "<script>history.back();</script>";
    exit;
}

$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
$smarty->assign('keyword_info', $keyword_info);

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
        $_SESSION['campaign_url'] = $header;
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }

    if ($_GET['keyword_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        reset($_GET);//reset array pointer on the first element
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
            //echo $header;
        }
        $quick_pane[3][lable] = $keyword_info['keyword'];
        $quick_pane[3][url] = $header;
    }
}
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//

$smarty->assign('article_info', $_POST);
$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('feedback', $feedback);
$smarty->assign('languages', $g_tag['language']);
$smarty->display('article/article_form.html');
?>