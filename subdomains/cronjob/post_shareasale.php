<?php
require_once 'pre_cron.php';
require_once 'HTTP/Client.php';
require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
$url = 'https://shareasale.com/q.cfm';
$param = array(
    'transtype' => 'sale',
    'merchantID' => '30131',
);
$conn->debug = true;
$result = OrderCampaign::getPaidOrderCampaigns();

if (!empty($result)) {
    foreach ($result as $row) {
        extract($row);
        if (empty($userID)) {
            continue;
        }
        $p = array_merge($param, $row);
        //$p['userID'] = 178;
        //$p['amount'] = 0.01;
        //pr($p, true);
        $client  =& new HTTP_Client();
        $result = $client->get($url, $p);
        $response = $client->currentResponse();
        if ($response['code'] == 200) {
            $data = array(
                'shareasale' => $response['body'],
                'order_campaign_id' => $row['tracking'],
            );
            OrderCampaign::store($data);
        }
    }
}
?>