<?php
//error_reporting(E_ALL);
$g_current_path = "client_campaign";
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 5) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/WPCredentials.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';

if (trim($_POST['client_id']) != '') {
    $p = $_POST;

    $credential_id = !empty($p["credential_id"]) ? trim($p["credential_id"]) : null;
    if (WPCredentials::store($p, $credential_id)) {
        echo "<script>alert('Wordpress Credential Was Stored Successful.');</script>";
        echo "<script>window.location.href='/client_campaign/wp_credential_list.php';</script>";
        exit;
    }
    $credential_info = $p;
} else {
    if (!empty($_GET['credential_id'])) {
        $credential_info = WPCredentials::getInfo($_GET['credential_id']);
    } else {
        $credential_info = $_GET;
    }
}

/*
if (trim($_GET['client_id']) == '') {
    echo "<script>alert('Please choose a client');</script>";
    echo "<script>history.back();</script>";
    exit;
}
*/
$clients = Client::getAllClients('id_name_only');
$smarty->assign('clients', $clients);

$smarty->assign('credential_info', $credential_info);
$smarty->assign('feedback', $feedback);
//$login_role = User::getRole();
//$smarty->assign('login_role', $login_role);

$smarty->display('client_campaign/wp_credential_form.html');
?>