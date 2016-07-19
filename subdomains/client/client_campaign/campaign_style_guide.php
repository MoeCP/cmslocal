<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/campaign_style_guide.class.php';


if ($_GET['campaign_id'])
    $info = CampaignStyleGuide::getInfoByCampaignID($_GET['campaign_id']);
else if ($_GET['style_id'])
    $info = CampaignStyleGuide::getInfo($_GET['style_id']);

$smarty->assign('info', $info);

$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/campaign_style_guide.html');
?>