<?php
$g_current_path = "client";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 13:58
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2 )) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
global $feedback;
if (!empty($_POST)) {
    $opt = $_POST['operation'];
    $client_user_id = trim($_POST['cu_id']);
    if ($client_user_id > 0) {
        $info = $oClientUser->getInfo($client_user_id);
        if ($opt == 'sent') {
            $oClientUser->sentAPINotice($info);
        } else if ( $opt == 'delete' ) {
            $data = array('client_user_id' => $client_user_id, 'is_active'=>0);
            $oClientUser->store($data);
        } else if ( $opt == 'generate' ) {
            $oClientUser->generateKey($info);
        }
        if ($opt == 'delete') {
            echo '<script type="text/javascript">';
            echo "alert('" . $feedback. "');";
            echo "window.location.href='/client/keylist.php?client_id=" . $info['client_id']. "';";
            echo "</script>";
        } else {
            echo $feedback;
        }
    } else {
        echo 'Invalid Parameter';
    }
    exit();
}



$search = $oClientUser->search($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$clients = Client::getAllClients('id_name_only');
$smarty->assign('login_role', User::getRole());
$smarty->assign('feedback', $feedback);
$smarty->assign('clients', $clients);
$smarty->assign('types', $g_api_types);
$smarty->display('client/keylist.html');
?>