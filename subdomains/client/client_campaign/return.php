<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignPayment.class.php';

if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
}
$token = $_GET['token'];
$payerid = $_GET['PayerID'];
$info = OrderCampaignPayment::getInfoByToken($token);
if (!empty($info)) {
    $order_id = $info['order_id'];
    $arr = $g_api_param;
    $arr['token'] = $token;
    $result = PPHttpPost('GetExpressCheckoutDetails', $arr);
    if(checkPaypalResult($result)) {
        $hash = array();
        foreach ($result as $k => $v) {
            $hash[$k] = addslashes($v);
        }
        $payment_id = $info['payment_id'];
        $data = array(
            'token' => $token,
            'payer_id' => $payerid,
            'account' => 'PayPal',
            'payment_id' => $payment_id,
            'detail_data' => serialize($hash)
        );
        OrderCampaignPayment::store($data);
        
        $arr['PAYMENTREQUEST_0_PAYMENTACTION'] = $g_paymentaction;
        $arr['CURRENCYCODE'] = $g_currencycode;
        $arr['PAYERID'] = $payerid;
        $arr['AMT'] = $info['total'];
        $result = PPHttpPost('DoExpressCheckoutPayment', $arr);
        if(checkPaypalResult($result)) {
            foreach ($result as $k => $v){
                $hash[$k] = addslashes($v);
            }

            $data = array(
                'status' => 10,
                'trans_date' => date("Y-m-d", strtotime($result['TIMESTAMP'])),
                'trans_num' => $result['TRANSACTIONID'],
                'payment_id' => $payment_id,
                'detail_data' => serialize($hash)
            );
            OrderCampaignPayment::store($data);
            $data = array(
                'status' => 10,
                'order_campaign_id' => $order_id,
            );
            OrderCampaign::store($data);
            echo '<script>alert(\'' . $feedback . '\')</script>';
            echo "<script>window.location.href='/client_campaign/vieworder.php?order_id={$order_id}'</script>";
        } else if ($result['L_ERRORCODE0'] == '10415') {
            $data = array(
                'status' => 7,
                'payment_id' => $payment_id,
            );
            OrderCampaignPayment::store($data);
            $data = array(
                'status' => 7,
                'order_campaign_id' => $info['order_id'],
            );
            OrderCampaign::store($data);
        }
    }
    echo '<script>alert(\'' . $feedback . '\')</script>';
    echo "<script>window.location.href='/client_campaign/vieworder.php?order_id={$order_id}&is_confirm=1'</script>";
    exit();
} else {
    echo "<script>window.location.href='/client_campaign/order_list.php'</script>"; 
    exit();
}

?>