<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (User::getPermission() == 3) { // 2=>3
   $g_current_path = "article";
   //echo $g_current_path;
}
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 5)) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

// pr($_POST);
if (!empty($_POST)) {
    Article::rollbackArticle($_POST);
    echo "<script>alert('" . $feedback . "');window.location.href='" . $_SERVER['REQUEST_URI']. "'; </script>";
    exit();
}
$result = Article::getUndoList($_GET);

if (!empty($result)) {
    $smarty->assign('result', $result['result']);
    $smarty->assign('pager', $result['pager']);
    $smarty->assign('total', $result['total']);
    $smarty->assign('count', $result['count']);
}
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);
$smarty->assign('article_status', $g_tag['article_status']);
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_clients', $clients);
$all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id);
$smarty->assign('all_campaigns', $all_campaigns);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('client_campaign/undo_list.html');
?>