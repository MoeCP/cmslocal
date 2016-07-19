<?php
$g_current_path = "client";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission()!=2)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
if (!empty($_POST)) {
    $pfrom = $_GET['pfrom'];
    
    if ($oClientUser->generateKey($_POST)) {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.opener.document.location.reload();window.close();</script>";
        exit();
    }
}
$smarty->assign('feedback',  $feedback);
if (!empty($_POST)) {
    $client_user = $_POST;
} else {
    $client_id = $_GET['client_id'];
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
$smarty->assign('login_role', User::getRole());
$smarty->display('client/key_quick_add.html');
?>