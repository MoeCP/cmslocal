<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';

if (trim($_POST['keyword_id']) && trim($_POST['keyword']) != '') {//只针对单个assign
    if (Campaign::setKeyword($_POST)){
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/article/article_report.php';</script>";
        exit;
    }
}

$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
$smarty->assign('keyword_info', $keyword_info);

$pref_info = Preference::getPrefById($keyword_info['keyword_category']);
$smarty->assign('pref_info', $pref_info);
$fields = CustomField::getFieldLabels($campaign_info['client_id'], 'optional');
$smarty->assign('fields', $fields);
//$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_inactive' => 0)));
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/change_due_date.html');
?>