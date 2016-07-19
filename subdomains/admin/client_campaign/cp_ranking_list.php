<?php
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/cp_campaign_ranking.class.php';
$res = CpCampaignRanking::getAllRankingInfo();
if (!empty($res)) {
    foreach ($res as $rs) {
        $info[$rs['copywriter_id']]['cp_ids'] = $rs['copywriter_id'];
        $info[$rs['campaign_id']]['c_id']  = $rs['campaign_id'];
    }
}
?>