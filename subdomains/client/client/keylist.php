<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

if (!client_is_loggedin()) {
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
        } else if ( $opt == 'generate' ) {
            $oClientUser->generateKey($info);
        }
        echo $feedback;
    } else {
        echo 'Invalid Parameter';
    }
    exit();
}

$p = empty($_GET) ? array() : $_GET;
$p['client_id'] = Client::getID();
$search = $oClientUser->search($p);
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
$smarty->assign('login_role', 'client');
$smarty->display('client/keylist.html');
?>