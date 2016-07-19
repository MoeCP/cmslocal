<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
if (User::getPermission() == 3) { // 2=>3
   $g_current_path = "article";
   //echo $g_current_path;
}
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

$search = Client::search($_GET);
if ($search) {
    if (!empty($search['result'])) {
        foreach ($search['result'] as $k => $v) {
            $search['result'][$k]['id_name_campaign'] = Campaign::getAllCampaigns($mode = 'id_name_only', $v['client_id']);
        }
    }
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

//########quick pane########//
$quick_pane[0][lable] = "Client Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

$smarty->assign('feedback', $feedback);
if (User::getRole() == 'editor') {
    //$g_current_path = "article";
    $smarty->display('client_campaign/editor_client_list.html');
} else {
    $smarty->display('client_campaign/client_list.html');
}
?>