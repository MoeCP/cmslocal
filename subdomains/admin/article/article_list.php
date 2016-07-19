<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

//if (User::getPermission() >= 3) {
//    if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "D") {
//        if (Article::setStatus($_POST['article_id'], 'D')){
//            $feedback = "Delete Success";
//        }
//        //sql_log();
//        header("Location:article_list.php");
//        exit;
//    }
//}
$from_page = $_GET['fmp'];
$campaign_id = $_GET['campaign_id'];
if (User::getPermission() == 1) {
    $operation = $_POST['operation'];
    if (!empty($_POST)) {
        switch( $operation )
        {
            case 'recall':
                $article_id = $_POST['article_id'];
                $status = 0;
                $old_status = $_POST['old_status'];
                if( Article::setArticleStatus($article_id, $status,  $old_status ) )
                {
                    echo "<script>alert('".$feedback."');</script>";
                    echo "<script>window.location.href='/article/article_list.php';</script>";
                    switch ($from_page) {
                    case 'article_list':
                        echo "<script>window.location.href='/article/article_list.php';</script>";
                        break;
                    case '1gc':
                        echo "<script>window.location.href='/article/article_list.php?article_status=1gc';</script>";
                    }
                    exit;
                }
                break;
        }
    }
}
if (user_is_loggedin()) {
    if (User::getPermission() >= 4) { // 2=>3
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
        if (User::getPermission() > 3) { // 2=>3
            $quick_pane[1][lable] = $campaign_info['company_name'];
            if (User::getPermission() == 3) {
                $quick_pane[1][url] = '/client_campaign/ed_cp_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            } else {
                $quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            }
        }
        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        $_SESSION['campaign_url'] = $header;
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
        if (!empty($_SESSION['campaign_url'])) {
            if (User::getPermission() == 3) { 
                $quick_pane[2][url] = '/article/article_list.php?campaign_id='.$campaign_info['campaign_id'];
            } elseif (User::getPermission() > 3) { 
                $quick_pane[2][url] = '/client_campaign/keyword_list.php?campaign_id='.$campaign_info['campaign_id'];
            }
        }
    }
}

//if (!isset($_GET['article_status'])) $_GET['article_status'] = 0;
$_GET['sort'] = 'ar.article_status, ck.date_end';
$search = Article::search($_GET);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$smarty->assign('tomorrow', $tomorrow);
$smarty->assign('campaign_id', $campaign_id);
if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$smarty->assign('article_statuses', $g_tag['article_status']);
unset($_GET['sort']);
$smarty->assign('query_string', http_build_query($_GET));
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/article_list.html');
?>