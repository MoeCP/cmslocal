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


if (!empty($_POST)) {
    $param = $_POST;
    $users = $param['isUpdate'];
    $status = $param['flow_status'];
    $bstatus = $param['bstatus'];
    $role = $param['role'];
    $month = $param['month'];
    if (!empty($users)) {
        foreach ($users as $user_id) {
            $p = array(
                'user_id' => $user_id,
                'month' => $month,
                'payment_flow_status' => $status,
                'role' => $role,
             );
            User::setPaymentFlowStatus($p);
        }
        echo $feedback;
        echo '<script>window.location.href="/client_campaign/batch_acct_report.php?month=' . $month . '&user_type=' .  $role . '&bstatus=' . $bstatus . '"</script>';
    } else {
        echo 'Please choose user to pay';
    }
    exit();
}
?>