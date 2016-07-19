<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignPayment.class.php';
require_once CMS_INC_ROOT.'/ClientArticlePrices.class.php';
define('ORDERCAMPAIGN_UPLOAD_PATH', WEB_PATH . DS . 'ordercampaign' . DS );

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (trim($_POST['campaign_name']) != '') {
    $operation = $_POST['operation'];
    unset($_POST['operation']);
    $is_new = empty($_POST['order_campaign_id']);
    
    $data = $_POST;

    if ($operation == 'confirm') {
        $data['status'] = 4;
        $price = array(
            'status' => $data['status'], 'is_confirm' => $data['is_confirm'],
            'qty' => $data['qty'], 'order_id' => $data['order_campaign_id'],
            'discount' => $data['discount'], 'fees' => $data['fees'],
        );
        $price['price_id'] = $data['price_id'];
        $price['article_price'] = $data['article_price'];
        if (OrderCampaignPayment::save($price)) {
        }
    } else if ($operation == 'deny') {

        $data['status'] = -3;
    }
    unset($data['price_id']);
    unset($data['article_price']);
    
    if (!isset($data['is_confirm'])) $data['is_confirm'] = 0;
    unset($data['discount']);
    unset($data['fees']);
    unset($data['subtotal']);
    unset($data['total']);
    if ($order_campaign_id = OrderCampaign::save($data, $operation)) {
        if ($operation == 'confirm' && $data['status'] == 4) {
            $info = Client::getInfo($data['client_id']);
            $data['client_name'] = $info['user_name'];
            OrderCampaign::sendAnnouceMail($data, $info['email'], 30);
        }
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/order_list.php';</script>";
        exit;
    }
}  

//************************* quick pane **********************************//
$quick_pane[0][lable] = "Order Campaign Management";
$quick_pane[0][url] = "/client_campaign/order_list.php";
$order_campaign_id = $_GET['order_campaign_id'];

if ($order_campaign_id > 0) {
    $info = OrderCampaign::getInfo($order_campaign_id);
} else {
    $parent_id = $_GET['parent_id'];
    if ($parent_id > 0) {
        $data = OrderCampaign::getInfo($parent_id);
        $info = array('parent_id' => $parent_id);
        $info['campaign_name'] = $data['campaign_name'];
        $info['campaign_requirement'] = $data['campaign_requirement'];
        $info['article_type'] = $data['article_type'];
        $info['category_id'] = $data['category_id'];
        $info['sample_content'] = $data['sample_content'];
        $info['keyword_instructions'] = $data['keyword_instructions'];
        $info['special_instructions'] = $data['special_instructions'];
    } else {
        $info = $_POST;
    }
}
if ($order_campaign_id) {
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $_SESSION['order_campaign_label'] = $info['campaign_name'];
    $_SESSION['order_campaign_url'] = $header;
} else {
    $_SESSION['order_campaign_label'] = 'Order New Campaign';
}
if (isset($_SESSION['order_campaign_label'])) {
	$quick_pane[2][lable] = $_SESSION['order_campaign_label'];
	$quick_pane[2][url] = $_SESSION['order_campaign_url'];
}
$smarty->assign('quick_pane', $quick_pane);


if (empty($info['date_start'])) {
    $info['date_start'] = date("Y-m-d");
    $info['ordered_by'] = Client::getContactName();
    $info['is_confirm'] = 1;
}


$smarty->assign('order_campaign_info', $info);
$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);
$category = array(0 => array('name' => 'Please select a category')) + Category::getAllCategoryByCategoryId();
$smarty->assign('category', $category);
$tones = array(0 => '[choose]') + $g_tag['article_tones'];
$is_confirm = $_GET['is_confirm'];
$smarty->assign('is_confirm', $is_confirm);
$smarty->assign('tones', $tones);
$smarty->assign('category', $category);
// added by nancy xu 2011-01-21 
$smarty->assign('sale_types', $g_tag['sale_types']);
$smarty->assign('yesorno', $g_tag['yesorno']);
// end
$smarty->assign('all_levels', $g_tag['content_levels']);
$smarty->assign('expertises', $g_user_levels);
$smarty->assign('feedback', $feedback);
// added by nancy xu 2010-12-28 11:00
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
if ($info['client_id'] > 0) {
    $domains += $oClientUser->getDomains(array('client_id' => $info['client_id']));
}
$smarty->assign('domains', $domains);
$smarty->assign('price', OrderCampaignPayment::getInfoByOrderId($order_campaign_id));
//end
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0)));
$smarty->assign('word_options', $g_word_options);
$smarty->assign('prices', json_encode(ClientArticlePrice::getAllPrice()));
if (user_is_loggedin()) {
    $smarty->assign('client_is_loggedin', 0);
}
$smarty->display('client_campaign/order_campaign_form.html');
?>