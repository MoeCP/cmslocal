<?php
$g_current_path = "help";
require_once('../pre.php');//load config
require_once('../cms_client_menu.php');
require_once CMS_INC_ROOT . "/Suggestion.class.php";
require_once CMS_INC_ROOT . "/Campaign.class.php";
if (!client_is_loggedin())
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $campaign_id = $_POST['campaign_id'];
    if (!empty($subject) && !empty($content)) {
         Suggestion::save($_POST);
        $feedback = 'Thank you for submitting.';
    } else {
        $feedback = 'Plase fill subject and content!';
        $smarty->assign('subject', $subject);
        $smarty->assign('content', $content);
        $smarty->assign('campaign_id', $campaign_id);
    }
} else {
    $smarty->assign('subject', '');
    $smarty->assign('content', '');
}
$campaigns = array('' => '[choose]') + Campaign::getAllCampaigns('id_name_only');
$smarty->assign('campaigns', $campaigns);


$smarty->assign('feedback', $feedback);
$smarty->assign('role', 'client');
$smarty->display('suggestions/suggestions.html');

?>