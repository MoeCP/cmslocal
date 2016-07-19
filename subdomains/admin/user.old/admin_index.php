<?php
require_once '../pre.php';
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}
$g_current_path = 'home';
require_once '../cms_menu.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
$archived = 0;
$smarty->assign('campaign_limit', $g_tag['campaign_limit']);
$smarty->assign('archived', $archived);
$p = $_GET;
if (!isset($p['month'])) $p['month'] = date("Ym");
$p['archived'] = $archived;
$search = Client::search($p, true);
if ($search) {
    /*if (!empty($search['result'])) {
        foreach ($search['result'] as $k => $v) {
            $search['result'][$k]['id_name_campaign'] = Campaign::getAllCampaigns($mode = 'id_name_only', $v['client_id'], $archived);
        }
    }*/
    $smarty->assign('result', $search['result']);
    $smarty->assign('month', $p['month']);
}
$smarty->display('user/admin_report.html');
?>