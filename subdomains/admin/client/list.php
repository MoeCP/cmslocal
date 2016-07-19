<?php
$g_current_path = "client";
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 13:58
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';

if (!empty($_POST)) {
    $form_refresh = $_POST['form_refresh'];
    $client_id = trim($_POST['client_id']);
    if ($client_id != '' && $form_refresh == "send_account_info") {
        $client_id = $_POST['client_id'];
        $data = Client::getInfo($client_id);
        Client::sendAccountInfo($data);
    } elseif ($client_id != '' && ($form_refresh == "D" || $form_refresh == "A")) {
        if (Client::setStatus($client_id, $form_refresh)){
            $feedback = $form_refresh == "A"? "Active Success":"Delete Success";
        }
        //sql_log();
        header("Location:list.php");
        exit;
    }
}
//$result = Client::getAllClients($mode = 'all_infos');
//$smarty->assign('result', $result);
if (empty($_GET)) $_GET['status'] = 'A';
$search = Client::search($_GET, false, false);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$smarty->assign('total_status', array('All'=>'All') + $g_tag['status']);
$all_agency= User::getAllUsers($mode = 'id_name_only', $user_type = 'agency', false);
$smarty->assign('all_agency', $all_agency);
$smarty->assign('login_role', User::getRole());
$smarty->assign('feedback', $feedback);
$smarty->display('client/list.html');
?>