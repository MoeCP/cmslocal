<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/article_type.class.php';
require_once CMS_INC_ROOT.'/payment_bill.class.php';

$user_id = $_POST['user_id'];
$role = $_POST['role'];
$p = $_POST;
if (trim($_POST['payment_flow_status']) != '' && trim($_POST['user_id']) != '') {
    if (isset($p['_'])) unset($p['_']);
    $result = User::setPaymentFlowStatus($p);
    $payment_flow_status = $p['payment_flow_status'];
    if ($result === false) {
        if ($payment_flow_status == 'cbill') {
            $p['payment_flow_status'] = 'cpc';
        }
        $feedback = addslashes($feedback);
    } else {
        $feedback = 'SUCCESS';
    }
}

$smarty->assign('item', $p);
$smarty->assign('role', $role);
$smarty->assign('pay_plugin', $g_pay_plugin);
$smarty->assign('old_status', $payment_flow_status);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/status_change.html');
?>