<?php
$g_current_path = "article";
require_once('../pre.php');//╪стьеДжцпео╒
//require_once CMS_INC_ROOT.'/ArticleExtraInfo.class.php';
//require_once CMS_INC_ROOT.'/GeographicName.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/cp_campaign_ranking.class.php';
$logout_folder = '';

if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
if (isset($_GET['type']))
{
    $action = '';
    $type = $_GET['type'];// geo level
    switch ($type)
    {
    case 1:
        $name = 'campaign_id';
        $selected = trim($_GET['cid']);
        $cp_id = trim($_GET['cp_id']);
        $action = 'onchange="ajaxAction(\'/client_campaign/cp_campaign_ranking_extra.php?cid=\' + this.value + \'&cp_id='. $cp_id . '&type=2\', \'copywriter\');"';
        $all = Campaign::getCampaignList();
        break;
    case 2:
        $name = 'copywriter_id';
        $selected = trim($_GET['cp_id']);
        $cid = trim($_GET['cid']);
        $action = 'onchange="ajaxAction(\'/client_campaign/cp_campaign_ranking_extra.php?cid=' . $cid . '&cp_id=\' + this.value + \'&type=3\', \'ranking\');"';
        $p = array('campaign_id'=>$cid);
        if (User::getRole() == 'editor') {
            $p['editor_id'] = User::getID();
        }
        $all = User::getAllCpByCampaignId($p);
    case 3:
        $copywriter_id = trim($_GET['cp_id']);
        $campaign_id = trim($_GET['cid']);
        if (!empty($copywriter_id) && !empty($campaign_id)) {
            $r = CpCampaignRanking::getAllCpCampaignRankingInfo(array('copywriter_id'=>$copywriter_id, 'campaign_id'=>$campaign_id));
        }
    }

    $smarty->assign('ranking_info', $r[0]);
    $smarty->assign('type', $type);
    $smarty->assign('name', $name);
    $smarty->assign('action', $action);
    $smarty->assign('all', $all);
    $smarty->assign('selected', $selected); // selected item
    $smarty->display('client_campaign/cp_campaign_ranking_extra.html');
}
?>