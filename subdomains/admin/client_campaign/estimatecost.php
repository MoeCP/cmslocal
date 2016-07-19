<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/AddtionalReports.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

$login_role = User::getRole();
// let admin user access this page
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}


//if (!empty($_GET)) {
    $estcost = AddtionalReports::EstimateCost($_GET);

    if ($estcost) {
        $smarty->assign('estcost', $estcost);
    }
//}

$monthes = genPayMonthList(-3, 4);
$smarty->assign('monthes', $monthes);
if (!isset($_GET["starttime"])) $_GET["starttime"] = date("Ym1");

$smarty->display('client_campaign/estimatecost.html');
?>