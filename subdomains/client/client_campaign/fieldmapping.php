<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignKeyword.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once COMMON_PATH.'/mycsvparser.php';
define('ORDERCAMPAIGN_UPLOAD_PATH', WEB_PATH . DS . 'ordercampaign' . DS );
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
}

if (!empty($_POST)) {
    $order_id = $_POST['order_id'];
} else {
    $order_id = $_GET['order_id'];
}
$info = OrderCampaign::getInfo($order_id);
$oMyCSV = new MyCSVParser(array('file' => $info['download_file']));
$fields = $oMyCSV->getFirstLine();
$smarty->assign('fields', $fields);

if (!empty($_POST)) {
    $fields = $_POST['fieldnames'];
    $labels = $_POST['fieldlabels'];
    $order_id = $_POST['order_id'];
    $data = array('order_id' => $order_id, 'fields' => array());
    $optinals = array();
    $import_data = $oMyCSV->getAllData();    
    foreach ($fields as $k => $field) {
        if ($field == 'skip') continue;
        $label = $labels[$k];
        $data['fields'][$field] = $labels[$k];
        $data[$field] = $import_data[$label];
    }
    //pr($import_data, true);
    if (!empty($data['fields']) && OrderCampaignKeyword::save($data)) {
        echo "<script>alert('{$feedback}')</script>";
        echo "<script>window.location.href='/client_campaign/uploadresult.php?order_id={$order_id}'</script>";
        exit();
    } else if (empty($data['fields'])) {
        echo "<script>window.location.href='/client_campaign/order_list.php?order_id={$order_id}'</script>";
        exit();

    }
}

$keywordinfo = OrderCampaignKeyword::getInfoByOrderId($order_id);

if (!empty($keywordinfo)) {
    $fields = array_keys($keywordinfo['fields']);
} else {
    $options = $g_option_fields;
    unset($options['skip']);
    $fields = array_keys($options);
}
$smarty->assign('info', $fields);
$smarty->assign('download_file', $download_file);

$smarty->assign('gfields', $g_option_fields);
//************************* quick pane **********************************//
$quick_pane[0][lable] = "Order Campaign Management";
$quick_pane[0][url] = "/client_campaign/order_list.php";
$smarty->assign('login_role', "client");
$smarty->assign('order_id', $order_id);
$smarty->display('client_campaign/fieldmapping.html');
?>