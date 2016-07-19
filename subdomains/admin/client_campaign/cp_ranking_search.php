<?php
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

// let users who role is agency access this page
if (!user_is_loggedin() && (User::getPermission() < 5)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/cp_campaign_ranking.class.php';

if (count($_REQUEST)) {
    if (isset($_REQUEST['s_choice']) && !empty($_REQUEST['s_choice'])) 
        $s_choice = trim($_REQUEST['s_choice']);
}
$param = array();
if ($s_choice > 0) {
    switch ($s_choice) {
        case 1: $number = -1;  break;
        case 2: $number = 5;   break;
        case 3: $number = 10;  break;
        case 4: $number = 20;  break;
        case 5: $number = 50;  break;
        case 6: $number = 100; break;
        case 7: $number = 200; break;
        default: $number = -1;  break;
    }
    $param['limit'] = array('number'=>$number);
    $smarty->assign('s_choice', $s_choice);
}    
$ranking_r = CpCampaignRanking::getAllCpCampaignRankingInfo($param+$_REQUEST);

$clients = Client::getAllClients('id_name_only');
$smarty->assign('clients', $clients);
$smarty->assign('all_cp', $all_cp);
$smarty->assign('search_choice', $g_tag['search_choice']);
$smarty->assign('ranking_info', $ranking_r);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/cp_ranking_search.html');
?>