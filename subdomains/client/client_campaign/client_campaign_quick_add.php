<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';

if (trim($_POST['client_id']) != '' && trim($_POST['campaign_name']) != '') {
    if (Campaign::add($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.opener.document.location.reload();window.close()</script>";
        exit;
    }
}

$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);

$smarty->assign('client_campaign_info', $_POST);
$smarty->assign('feedback', $feedback);
$smarty->assign('expertises', $g_user_levels);
//$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0,'is_inactive' => 0)));
$smarty->display('client_campaign/client_campaign_quick_add');
?>