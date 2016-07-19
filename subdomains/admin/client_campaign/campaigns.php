<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (User::getPermission() == 3) { // 2=>3
   $g_current_path = "article";
   //echo $g_current_path;
}
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission() != 2)) {// 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!empty($_POST)) {
    $status = $_POST['status'];
    $campaign_id = $_POST['campaign_id'];
    switch ($status) {
    case '1':
        $fields = array(
            'completed_date' => date("Y-m-d"),
            'archived' => 1
        );
        Campaign::setCampaignFieldsById($fields, $campaign_id);
        exit('<strong>Archived</strong>');
        break;
    }
}

$result = Client::getAllCampaignReport($_GET);
if (User::getPermission() != 2) {
    $all_agency = User::getAllUsers($mode = 'id_name_only', $user_type = 'agency', false);
    $smarty->assign('all_agency', array(''=>'[All]') + $all_agency);
}
$smarty->assign('archived_status', array(-1=> 'All', 0=>'Active', 1=> 'Archived'));
$smarty->assign('result', $result['result']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('total', $result['total']);
$smarty->assign('count', $result['count']);
$smarty->display('client_campaign/campaigns.html');
?>