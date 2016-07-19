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
    case 'D':
        $conditions = array(
            "ck.copy_writer_id=0",
            "ar.article_status=0",
        );
        break;
    case 'A':
        $conditions = array(
            "ck.status='D'",
        );        
        break;
    }
    if (!empty($conditions)) {
        Campaign::setKeywordStatusByConditions($status,  $campaign_id, $conditions);
    }
}

$result = Client::getAllCampaigns($_GET);
$smarty->assign('feedback', $feedback);
$smarty->assign('result', $result['result']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('total', $result['total']);
$smarty->assign('count', $result['count']);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('client_campaign/end_campaigns.html');
?>