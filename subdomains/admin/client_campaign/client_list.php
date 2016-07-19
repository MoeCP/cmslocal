<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
if (User::getPermission() == 3) { // 2=>3
   //$g_current_path = "article";
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
    $client_id = $_POST['client_id'];
    $total_row = $_POST['total_row'];
    $query_string = $_POST['query_string'];
    switch ($status) {
    case '1':
        $fields = array(
            'completed_date' => date("Y-m-d"),
            'archived' => 1
        );
        Campaign::setCampaignFieldsById($fields, $campaign_id);
        echo "<script>alert('This Campaign was archived.');appendRsToObj($('tr{$client_id}'), $('ahref{$client_id}'), {$total_row}, '/client_campaign/client_campaign_list.php?client_id={$client_id}{$query_string}')</script>";
        exit();
        break;
    }
}

if (!isset($_GET['archived'])) {
    $_GET['archived'] = 0;
}
$archived = $_GET['archived'] ;
if (empty($_GET)) $_GET['status'] = 'A';
$search = Client::search($_GET);
if ($search) {
    
    if (!empty($search['result'])) {
        $ids = array();
        foreach ($search['result'] as $k => $v) {
            $ids[$k] = $v['client_id'];
        }
        $campaigns = Campaign::getAllCampaigns($mode = 'id_name_only', $ids, $archived);
        $smarty->assign('campaigns', $campaigns);
    }
   
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

// added by snug xu 2007-05-29 9:52 - STARTED
// Does the total spend show for current user
$pref = Preference::getPref("users", 'user_id');
$is_show = is_array($pref['user_id']) ? in_array(User::getID(), $pref['user_id']) : false;
$smarty->assign('is_show', $is_show);
// added by snug xu 2007-05-29 9:52 - FINISHED

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//
$smarty->assign('login_role', User::getRole());
$smarty->assign('feedback', $feedback);
$smarty->assign('archived', $archived);
$smarty->assign('campaign_limit', $g_tag['campaign_limit']);
$smarty->assign('total_status', array('All'=>'All') + $g_tag['status']);
$smarty->assign('archived_status', array(-1=> 'All', 0=>'Active', 1=> 'Archived'));
if (User::getPermission() != 2) {
    $all_agency = User::getAllUsers($mode = 'id_name_only', $user_type = 'agency', false);
    $smarty->assign('all_agency', array(''=>'[All]') + $all_agency);
}

if (User::getRole() == 'editor') {
    //$g_current_path = "article";
    $smarty->display('client_campaign/editor_client_list.html');
} else {
    $query_string = $_SERVER['QUERY_STRING'];
    if( strlen( $query_string )) {
        $query_string = '&'.$query_string;
    } else {
        $query_string = '&archived=' . $archived;
    }
    $smarty->assign('query_string', $query_string);
    $smarty->display('client_campaign/client_list.html');
}
?>
