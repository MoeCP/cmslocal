<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/image.class.php';

$_GET['perPage'] = 25;
$_GET['direction'] = 'desc';
$search = ImageKeyword::searchCampaign($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

//########quick pane########//
if (User::getPermission() >= 4) { // 2=>3
    $quick_pane[0][lable] = "Campaign & Image Management";
    $quick_pane[0][url] = "/client_campaign/client_list.php";
    if ($_GET['client_id']) {
        $quick_pane[1][lable] = $_GET['company_name'];
        $quick_pane[1][url] = "/client_campaign/ed_cp_campaign_list.php?client_id=".$_GET['client_id']."&company_name=".$_GET['company_name'];
    }
} else {
    $quick_pane[0][lable] = "Campaign & Image Management";
    $quick_pane[0][url] = "/graphics/designer_campaign_list.php";
    if ($_GET['client_id']) {
        $quick_pane[1][lable] = $_GET['company_name'];
        $quick_pane[1][url] = "/graphics/designer_campaign_list.php?client_id=".$_GET['client_id']."&company_name=".$_GET['company_name'];
    }
}

$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

$smarty->assign('feedback', $feedback);

$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->display('graphics/designer_campaign_list.html');
?>