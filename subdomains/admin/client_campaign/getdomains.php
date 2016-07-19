<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//╪стьеДжцпео╒
if (!user_is_loggedin() || User::getPermission() < 2 &&  User::getPermission() != 3) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$domains = array('0' =>  '[choose domain]');
$client_id = $_GET['cid'];
$source = $_GET['s'];
if ($client_id > 0) {
    require_once CMS_INC_ROOT.'/client_user.class.php';
    $oClientUser = new ClientUser();
    $domains = $domains + $oClientUser->getDomains(array('client_id' => $client_id));
}
$smarty->assign('domains', $domains);
$smarty->assign('source', $source);
$smarty->display('client_campaign/getdomains.html');
?>