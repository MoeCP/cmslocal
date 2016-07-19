<?php

$g_current_path = "suggestions";
require_once('../pre.php');//load config
require_once('../cms_menu.php');

if (!user_is_loggedin())
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Bug.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (Bug::report($_POST)) {
        $feedback = 'Thank you for submitting.';
    } else {
        $smarty->assign('info', $_POST);
    }
}
//$smarty->assign('info', $_POST);
$choose = array('' => "[choose]");
$browsers = $choose + $g_tag['browsers'];
$operating_systems = $choose + $g_tag['operating_systems'];
$smarty->assign('browsers', $browsers);
$campaigns = $choose + Campaign::getCampaignList();
$smarty->assign('campaigns', $campaigns);
$smarty->assign('os', $operating_systems);
$smarty->assign('feedback', $feedback);
$smarty->display('suggestions/report_bugs.html');
?>