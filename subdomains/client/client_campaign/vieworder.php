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
if (!empty($_POST)) {
    $operation = $_POST['operation'];
    $order_id = $_POST['order_id'];
    $data = array(
        'order_campaign_id' => $order_id
    );
    if ($operation=='confirm') {
        $data['status'] = 7;
    } else if ($operation == 'deny') {
        $data['status'] = -1;
    } 
    if ($operation == 'checkout') {
        $payment = OrderCampaignPayment::getInfoByOrderId($order_id);
        $param = $g_api_param;
        $param['AMT'] = $payment['total'];
        $param['PAYMENTREQUEST_0_PAYMENTACTION'] = $g_paymentaction;
        $param['CURRENCYCODE'] = $g_currencycode;
        $param['CURRENCYCODE'] = $g_currencycode;
        $param['RETURNURL'] = $g_returnurl;
        $param['CANCELURL'] = $g_cancelurl;
        $result = PPHttpPost('SetExpressCheckout', $param);
        if (checkPaypalResult($result)) {
            $token = $result['TOKEN'];
            $arr = array(
                'token' => $token, 
                'payment_id' => $payment['payment_id'], 
            );
            OrderCampaignPayment::store($arr);
            $url = getPaypalURL($token);
            echo "<script>window.location.href='{$url}'</script>";
            exit();
        }
    } else {
        if (OrderCampaign::store($data)) {
            if ($operation=='confirm' && $data['status'] == 7) {
                OrderCampaignPayment::updateByOrderId(array('status' => 7), $order_id);                
                $info = OrderCampaign::getInfo($order_id);
                $info['client_name'] = Client::getName();
                // sent client confir campaign order email to client pm
                $pm_info = Client::getPMInfo(array('client_id'=> Client::getID()));
                $info = array_merge( $info, OrderCampaignPayment::getInfoByOrderId($order_id));
                OrderCampaign::sendAnnouceMail($info, $pm_info['email'], 31, $g_to_email);
                // $all_admin = User::getAllUsers('id_email_only', 'admin');
                //OrderCampaign::sendAnnouceMail($info, $all_admin, 31);
            }
            echo "<script>alert('" . $feedback . "')</script>";
            echo "<script>window.location.href='/client_campaign/order_list.php'</script>";
            exit();
        }
    }
}

//************************* quick pane **********************************//
$quick_pane[0][lable] = "Order Campaign Management";
$quick_pane[0][url] = "/client_campaign/order_list.php";
$order_campaign_id = $_GET['order_campaign_id'];
if ($order_campaign_id) {
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
}
$smarty->assign('quick_pane', $quick_pane);

$order_campaign_id = $_GET['order_id'];
if ($order_campaign_id > 0) {
    $info = OrderCampaign::getInfo($order_campaign_id);
} else {
    echo "<script>alert('Invailid Campaing order,please try again!')</script>";
    echo "<script>window.location.href='/client_campaign/order_list.php'</script>";
    exit();
}
$keywords = OrderCampaignKeyword::getInfoByOrderId($order_campaign_id);
$smarty->assign('info', $info);
if (!empty($keywords)) {
    $smarty->assign('keywords', $keywords);
}
$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);
$category = array(0 => 'Please select a category') + Category::getAllCategoryByCategoryId('', true);
$smarty->assign('category', $category);
$tones = array(0 => '[choose]') + $g_tag['article_tones'];
$smarty->assign('tones', $tones);
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
$domains += $oClientUser->getDomains(array('client_id' => Client::getID()));
$smarty->assign('priceinfo', OrderCampaignPayment::getInfoByOrderId($order_campaign_id));
$smarty->assign('domains', $domains);
$smarty->assign('category', $category);
$smarty->assign('all_levels', $g_tag['content_levels']);
$smarty->assign('expertises', $g_user_levels);
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0)));
$smarty->assign('word_options', $g_word_options);
if (client_is_loggedin()) {
    $smarty->assign('client_is_loggedin', 1);
}
// added by nancy xu 2011-01-21 15:44
$smarty->assign('sale_types', $g_tag['sale_types']);
$smarty->assign('yesorno', $g_tag['yesorno']);
//end
if (isset($_GET['is_confirm']) && $_GET['is_confirm'] == 1) {
    $is_confirm = $_GET['is_confirm'];
    $smarty->assign('is_confirm', $is_confirm);
    $smarty->assign('title', 'Comfirm Campaign Order');
} else {
    $smarty->assign('title', 'View Campaign Order');
}
$smarty->assign('article_types', $g_tag['leaf_article_type']);
$smarty->assign('environment', $environment);
$smarty->display('client_campaign/vieworder.html');
?>