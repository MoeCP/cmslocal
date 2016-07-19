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
if ((!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

if (user_is_loggedin()) {
    if (trim($_POST['keyword_id']) != '' && $_POST['form_refresh'] == "D") {
        if (Campaign::setKeywordStatus($_POST['keyword_id'], 'D')){
            $feedback = "Delete Success";
            //sql_log();
            //header("Location:keyword_list.php");
            //exit;
        }
    }
    if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "P") {
        $article_id = trim($_POST['article_id']);
        Article::setArticleStatus($article_id, 6, 5);
    }
}

$search = Campaign::searchKeyword($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('show_cb', $search['show_cb']);
}
$titile_perfix = isset($_GET['editor_id']) ? 'Editor' : 'Writer';
$smarty->assign('titile_perfix', $titile_perfix);
if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
} else {
    $smarty->assign('login_role', 'client');
}
$all_campaigns = Campaign::getAllCampaigns('campaign_name', '');
$smarty->assign('all_campaigns', $all_campaigns);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('feedback', $feedback);

function format_pay_period($args){return preg_replace('/(\d{4})(\d{2})(\d{1})/', '\1-\2(\3)', $args["pmonth"]);}

$smarty->register_function('formatpayperiod','format_pay_period');
$smarty->display('article/articles.html');
?>