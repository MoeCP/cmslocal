<?php
require_once '../pre.php';
require_once CMS_INC_ROOT . '/OrderCampaignPayment.class.php';
require_once CMS_INC_ROOT . '/OrderCampaign.class.php';
require_once CMS_INC_ROOT . '/Email.class.php';
if (substr($g_http_host, -13) == 'copypress.com') {
    $environment = 'live';
    $g_pay_pal_email = 'billing@copypress.com';
} else {
    $environment = 'sandbox';	// or 'beta-sandbox' or 'live'
    $g_pay_pal_email = 'techni_1297287905_biz@blueglass.com';
}
/*$conn->debug = true;
$_POST = array (
  'mc_gross' => '0.01',
  'protection_eligibility' => 'Ineligible',
  'payer_id' => 'XBDARQEN3URXC',
  'tax' => '0.00',
  'payment_date' => '19:11:23 Feb 23, 2011 PST',
  'payment_status' => 'Completed',
  'charset' => 'windows-1252',
  'first_name' => 'Test',
  'mc_fee' => '0.01',
  'notify_version' => '3.0',
  'custom' => '',
  'payer_status' => 'verified',
  'business' => 'techni_1297287905_biz@blueglass.com',
  'quantity' => '1',
  'verify_sign' => 'AQyQpLEBDM0SUd6tQ6o630Bj2J.CANsuJAbSvw7ANbZBoN2JVyQcENHd',
  'payer_email' => 'techni_1297288135_per@blueglass.com',
  'txn_id' => '83241555EU587870W',
  'payment_type' => 'instant',
  'last_name' => 'User',
  'receiver_email' => 'techni_1297287905_biz@blueglass.com',
  'payment_fee' => '0.01',
  'receiver_id' => '83MAR8NLZHPXL',
  'txn_type' => 'web_accept',
  'item_name' => 'CopyPress Articles',
  'mc_currency' => 'USD',
  'item_number' => '100',
  'residence_country' => 'US',
  'test_ipn' => '1',
  'handling_amount' => '0.00',
  'transaction_subject' => 'CopyPress Articles',
  'payment_gross' => '0.01',
  'shipping' => '0.00',
  'cmd'=> '_notify-validate',
);*/
if (!empty($_POST)) {
    // read the post from PayPal system and add 'cmd'
    //file_put_contents(getcwd().'/debug_paypal.txt', rand(11111,99999).var_export($_POST, true));
    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $order_id = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $payment_date = $_POST['payment_date'];
    $payer_id = $_POST['payer_id'];
    $payment_status = strtolower($_POST['payment_status']);
    if ($payment_status == 'completed') {
        $info = OrderCampaignPayment::getInfoByOrderId($order_id);
        if (!empty($info) && $txn_id != $info['trans_num']) {
            $payment_id = $info['payment_id'];
            if ($payment_amount != $info['total']) {
                $order_info = OrderCampaign::getInfo($order_id);
                $info = array_merge($info, $order_info);
                $str  = 'Client:' . $info['client_name'] ."\n";
                $str .= 'Campaign Name:' . $info['campaign_name'] ."\n";
                $str .= 'Campaign OrderID:' . $info['order_campaign_id'] ."\n";
                $str .= 'Amount:' . $info['total'] ."\n";
                $str .= 'PayPal Amount:' . $payment_amount ."\n";
                $str .= 'Receiver email:' . $receiver_email ."\n";
                Email::sendAnnouceMail(36, $g_to_email, array('datastring' => $str));
            } else {
                $p = $_POST;
                if ($g_pay_pal_email != $receiver_email && !empty($g_pay_pal_email)) {
                    $p['receiver_email'] = $g_pay_pal_email;
                }
                $p['cmd'] = '_notify-validate';
                $prefix_url = '';
                if ($environment == 'sandbox') {
                    $prefix_url = '.' . $environment . '.';
                }
                $url = 'https://www' . $prefix_url . 'paypal.com/cgi-bin/webscr';
                require_once "HTTP/Client.php";
                $client = new HTTP_Client();
                $client->post($url, $p);
                $result = $client->currentResponse();
                $res = trim($result['body']);
                if (strcmp ($res, "VERIFIED") == 0) {
                    $data = array(
                        // 'token' => $token,
                        'payer_id' => $payer_id,
                        'status' => 10,
                        'trans_date' => date("Y-m-d", strtotime($payment_date)),
                        'trans_num' => $txn_id,
                        'account' => 'PayPal',
                        'payment_id' => $payment_id,
                        'detail_data' => serialize($p)
                    );
                    OrderCampaignPayment::store($data);
                    $data = array(
                        'status' => 10,
                        'order_campaign_id' => $order_id,
                    );
                    OrderCampaign::store($data);
                    echo 'SUCCESS';
                    exit();
                } else if (strcmp ($res, "INVALID") == 0) {
                  // log for manual investigation
                }
            }
        }
    }
    echo 'FAILED';
} else {
    echo 'INVALID';
}

?>