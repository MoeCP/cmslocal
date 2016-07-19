<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignKeyword.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignPayment.class.php';

if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
}

$token = $_GET['token'];
if (!empty($token)) {
    $info = OrderCampaignPayment::getInfoByToken($token);
    if (!empty($info)) {
        $order_id = $info['order_id'];
         echo "<script>window.location.href='/client_campaign/vieworder.php?order_id={$order_id}&is_confirm=1'</script>";
         exit();
    }
}
echo "<script>window.location.href='/client_campaign/order_list.php'</script>";
?>