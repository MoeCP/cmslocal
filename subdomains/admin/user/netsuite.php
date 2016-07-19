<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/UserEsign.class.php';


if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$user_id = $_GET['user_id'];
//$conn->debug =true;
if (!empty($_POST)) {
    $p = $_POST;
    /*$oNetSuite = new NetSuite($g_netsuite_user);
    $result = $oNetSuite->saveVendor($p);
    $internal_id = $result['internalId'];*/
    $g_pay_plugin = 'NetSuit';
    $result = add_vendor_plugin($p);
    $internal_id = $result['internalId'];
    //pr($result, true);
    if (empty($user_id)) $user_id = $p['user_id'];
    if ($internal_id > 0) {
        $feedback = 'SUCCESS';
        $arr = array('user_id' => $user_id);
        $arr['vendor_id'] = $internal_id;
        $arr['vaddresses'] = implode(";", $result['addresses']);
        User::setByID($arr);
        $feedback = 'SUCCESS';
        echo '<script>alert(\'' . $feedback. '\')</script>';
        echo "<script>window.location.href='/user/list.php';</script>";
        exit();
    } else {
        $feedback = preg_replace("/[\r\n]+/", " ", $feedback);
    }
}


$info = User::getInfo($user_id);
$result = UserEsign::getInfoByUserId($user_id);
$info = update_user_info($info, $result);
$smarty->assign('user_info', $info);
$smarty->assign('feedback', $feedback);
$smarty->assign('vendor_id', $vendor_id);
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->display('user/netsuite.html');
?>