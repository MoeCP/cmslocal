<?php
$g_current_path = "preference";
global $feedback;
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

// let users who role is agency access this page
if (!user_is_loggedin() && (User::getPermission() < 5)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/cp_campaign_ranking.class.php';

if (count($_POST))
{
    $opt = $_POST['operation'];
    if ($opt == 'save')
    {  
        if (CpCampaignRanking::storeAllRankingQuotiety($_POST)) {
            $feedback = "Succeed";
            echo "<script>window.location.herf='/client_campaign/modify_cp_ranking_quotiety.php';</script>";
        } else {
            $feedback = "Failed!";
        }
    }
}

$readability_result = CpCampaignRanking::getQuotietyAllInfo('readability');
$info_result = CpCampaignRanking::getQuotietyAllInfo('informational_quality');
$timeliness_result = CpCampaignRanking::getQuotietyAllInfo('timeliness');

$smarty->assign('feedback', $feedback);
$smarty->assign('readability', $readability_result);
$smarty->assign('informational_quality', $info_result);
$smarty->assign('timeliness', $timeliness_result);
$smarty->display('client_campaign/modify_cp_ranking_quotiety.html');
?>