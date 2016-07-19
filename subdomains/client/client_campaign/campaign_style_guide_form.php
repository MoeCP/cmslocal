<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/campaign_style_guide.class.php';

if (count($_POST)) {
    if (CampaignStyleGuide::store($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.opener.location.reload();window.close();</script>";
        exit;
    }
}

if ($_GET['campaign_id'])
    $info = CampaignStyleGuide::getInfoByCampaignID($_GET['campaign_id']);
else if ($_GET['style_id'])
    $info = CampaignStyleGuide::getInfo($_GET['style_id']);

$smarty->assign('info', $info);

$smarty->assign('client_campaign_info', $_POST);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/campaign_style_guide_form.html');
?>