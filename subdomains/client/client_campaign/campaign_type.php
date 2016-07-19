<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (client_is_loggedin()) {
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/Campaign.class.php';

if (!empty($_POST)) {
    if ($campaign_id = Campaign::addByClient($_POST)) {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/campaign_questions.php?campaign_id=" . $campaign_id . "';</script>";
    } else {
        echo "<script>alert('".$feedback."');</script>";
    }
}

require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
$client_id = Client::getID();
if ($client_id > 0)  {
    $domains += $oClientUser->getDomains(array('client_id' => $client_id));
}
$smarty->assign('domains', $domains);
$smarty->assign('client_id', $client_id);
$smarty->assign('templates', $g_templates);

$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0,'is_inactive' => 0)));
$smarty->display('client_campaign/campaign_type.html');
?>