<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignKeyword.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignPayment.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$article_types =  ArticleType::getAllLeafNodes(array('is_hidden' => 0));
if (!empty($_POST)) {
    $operation = $_POST['operation'];
    if ($operation == 'paid' || $operation == 'save') {
        $p = $_POST;
        $order_id = $_POST['order_id'];
        unset($p['order_id']);
        unset($p['payment_id']);
        unset($p['operation']);
        if ($operation == 'paid' || $operation== 'save') {
            if ($operation == 'paid') {
                $p['status'] = 10;
                $result = OrderCampaignPayment::updateByOrderId($p, $order_id);
                if (!$result) {
                    $feedback = 'please try again';
                }
            }
            if ($result || $operation== 'save') {
                $info = OrderCampaign::getInfo($order_id);
                $client =Client::getInfo($info['client_id']);
                $info['client_name'] = $client['user_name'];
                $info = array_merge( $info, OrderCampaignPayment::getInfoByOrderId($order_id));
                $info['content_type'] = $article_types[$info['article_type']];
                OrderCampaign::sendAnnouceMail($info, $client['email'], 32);
                $feedback = 'Successful!';
                echo "<script>alert('" . $feedback . "')</script>";
                echo "<script>window.location.href='/client_campaign/order_list.php'</script>";
                exit();
            }
        } else {
            if (!OrderCampaignPayment::updateByOrderId($p, $order_id)) {
                $feedback = 'please try again';
            } else {
                echo "<script>alert('" . $feedback . "')</script>";
                echo "<script>window.location.href='/client_campaign/order_list.php'</script>";
            }
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
if (empty($keywords)) $keywords = '';
$smarty->assign('info', $info);
$smarty->assign('keywords', $keywords);
$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);
$category =  Category::getAllCategoryByCategoryId('', true);
$smarty->assign('category', $category);
$tones = array(0 => '[choose]') + $g_tag['article_tones'];
$smarty->assign('tones', $tones);
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = $oClientUser->getDomains(array('client_id' => $info['client_id']));
$smarty->assign('priceinfo', OrderCampaignPayment::getInfoByOrderId($order_campaign_id));
$smarty->assign('domains', $domains);
$smarty->assign('category', $category);
$smarty->assign('all_levels', $g_tag['content_levels']);
$smarty->assign('expertises', $g_user_levels);
// added by nancy xu 2011-01-21 15:44
$smarty->assign('sale_types', $g_tag['sale_types']);
$smarty->assign('yesorno', $g_tag['yesorno']);
//end
$smarty->assign('article_types', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
$smarty->assign('is_pay', $_GET['is_pay']);
$smarty->assign('fadjust', $_GET['fadjust']);
$smarty->assign('article_type', $article_types);
$smarty->assign('word_options', $g_word_options);
if (user_is_loggedin()) {
    $smarty->assign('client_is_loggedin', 0);
    $smarty->assign('accounts', $g_merchant_accounts);
}
$smarty->assign('title', 'View Campaign Order');
$smarty->display('client_campaign/vieworder.html');
?>