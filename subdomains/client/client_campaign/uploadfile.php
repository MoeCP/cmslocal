<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_client_menu.php');

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
define('ORDERCAMPAIGN_UPLOAD_PATH', WEB_PATH . DS . 'ordercampaign' . DS );
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
}
if (!empty($_POST)) {
    $order_id = $_POST['order_id'];
    if (!empty($_FILES) && $_FILES["download_file"]["error"] == UPLOAD_ERR_OK) {
        
        $file_name = $_FILES["download_file"]["name"];
        $arr = explode(".", $file_name);
        $ext = $arr[count($arr) - 1];
        if ($ext == 'csv') {
            $file_name = $order_id . '.' . $ext;
            $import_file = ORDERCAMPAIGN_UPLOAD_PATH . $file_name;
            move_uploaded_file($_FILES["download_file"]["tmp_name"], $import_file);
            $arr = array(
                'order_campaign_id' => $order_id, 
                'download_file' => $import_file,
            );
            OrderCampaign::store($arr);
            echo "<script>window.location.href='/client_campaign/fieldmapping.php?order_id={$order_id}';</script>";
            exit();
        } else {
            $feedback = 'Uploaded invailid files';
        }
    }  else {
        if ($_POST['uploadfile']) {
            echo "<script>window.location.href='/client_campaign/fieldmapping.php?order_id={$order_id}';</script>";
        } else {
            $feedback = 'Failed, please try again';
        }
    }
    if (!empty($feedback)) {
        echo "<script>alert('".$feedback."');</script>";
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

$order_campaign_id = $_GET['order_campaign_id'];
$info = OrderCampaign::getInfo($order_campaign_id);
$smarty->assign('order_id', $order_campaign_id);
$download_file = empty($info['download_file']) ? '' : 1;
$smarty->assign('uploadfile', $download_file);
$smarty->display('client_campaign/uploadfile.html');
?>