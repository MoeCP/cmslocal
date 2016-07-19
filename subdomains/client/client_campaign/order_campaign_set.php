<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/ClientArticlePrices.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignPayment.class.php';

define('ORDERCAMPAIGN_UPLOAD_PATH', WEB_PATH . DS . 'ordercampaign' . DS );
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
}
// added by nancy xu 2010-12-28 11:00
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
$domains += $oClientUser->getDomains(array('client_id' => Client::getID()));
//end
if (trim($_POST['campaign_name']) != '') {
    $operation = $_POST['operation'];
    unset($_POST['operation']);
    $is_new = empty($_POST['order_campaign_id']);
    $data = $_POST;
    if ($operation=='confirm') {
        $data['status'] = 7;
    } else if ($operation == 'deny') {
        $data['status'] = -1;
    }

    if (!isset($data['is_confirm']) || empty($data['is_confirm'])) $data['is_confirm'] = 0;
    $price = array(
        'is_confirm' => $data['is_confirm'],
        'qty' => $data['qty'], 'order_id' => $data['order_campaign_id'],
        'discount' => $data['discount'], 'fees' => $data['fees']
    );
    if (!empty($data['status'])) $price['status'] = $data['status'];
    $price['price_id'] = $data['price_id'];
    $price['article_price'] = $data['article_price'];
    unset($data['discount']);
    unset($data['fees']);
    unset($data['subtotal']);
    unset($data['total']);
    unset($data['price_id']);
    unset($data['article_price']);

    if ($order_campaign_id = OrderCampaign::save($data, $operation)) {
        //sql_log();
        if ($is_new) {
            $info = $_POST;
            // set  create campaign order email to client pm
            $clientname = Client::getName();
            $info['client_name'] = $clientname;
            if ($info['source'] > 0) $info['campaign_site_url'] = $domains[$info['source']];
            $pm_info = Client::getPMInfo(array('client_id'=> Client::getID()));
            OrderCampaign::sendAnnouceMail($info, $pm_info['email'], 23, $g_to_email);
        }
        $price['order_id'] = $order_campaign_id;
        if (OrderCampaignPayment::save($price)) {
            echo "<script>alert('".$feedback."');</script>";
            if ($operation == 'N') {
                echo "<script>window.location.href='/client_campaign/uploadfile.php?order_campaign_id={$order_campaign_id}';</script>";
                exit;
            } else {
                echo "<script>window.location.href='/client_campaign/order_list.php';</script>";
            }
        }
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
// added by nancy xu 2011-01-21 15:27
$smarty->assign('sale_types', $g_tag['sale_types']);
$smarty->assign('yesorno', $g_tag['yesorno']);
//end
$smarty->assign('tones', $tones);
$smarty->assign('category', $category);
$smarty->assign('is_confirm', $_GET['is_confirm']);
$smarty->assign('all_levels', $g_tag['content_levels']);
$smarty->assign('expertises', $g_user_levels);
$smarty->assign('feedback', $feedback);
$smarty->assign('domains', $domains);
$smarty->assign('price', OrderCampaignPayment::getInfoByOrderId($order_campaign_id));
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0,'is_inactive' => 0)));
$smarty->assign('word_options', $g_word_options);
$smarty->assign('prices', json_encode(ClientArticlePrice::getAllPrice()));
if (client_is_loggedin()) {
    $smarty->assign('client_is_loggedin', 1);
}
$smarty->display('client_campaign/order_campaign_form.html');
?>