<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
if (!empty($_POST)) {
    if ($oClientUser->generateKey($_POST)) {
        echo "<script>alert('{$feedback}');window.location.href='/client/keylist.php'</script>";
        exit();
    }
}
$smarty->assign('feedback',  $feedback);
if (!empty($_POST)) {
    $client_user = $_POST;
} else {
    $client_id = Client::getID();
    $client_user_id = $_GET['cu_id'];
    if (empty($client_id) && empty($client_user_id))  {
        echo "<script>alert('Please specify the client');window.location.href='/client/list.php'</script>";
        exit();
    } else if ($client_user_id > 0) {
        if ($client_user_id > 0) {
            $client_user = $oClientUser->getInfo($client_user_id);
            if (empty($client_user['email'])) {
                $client_id = $client_user['client_id'];
                $info = Client::getInfo($client_id);
                $client_user['email'] = $info['email'];
            }
        }
    } else if ($client_id > 0) {
        $info = Client::getInfo($client_id);
        $client_user = array('user' => $info['user_name']);
        $client_user['email'] = $info['email'];
        $client_user['client_id'] = $info['client_id'];
    }
}

$smarty->assign('info',  $client_user);
$types = array_merge(array('' => "choose API Type"), $g_api_types);
$smarty->assign('api_types',  $types);
$smarty->assign('login_role', 'client');
$smarty->display('client/generatekey.html');
?>