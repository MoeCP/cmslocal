<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/KeywordFile.class.php';

if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin() ) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_POST)) {
    $campaign_id = $_POST['campaign_id'];
    if (!empty($_FILES) && $_FILES["filename"]["error"] == UPLOAD_ERR_OK) {
        $file_name = $_FILES["filename"]["name"];
        $arr = explode(".", $file_name);
        $ext = $arr[count($arr) - 1];
        if ($ext == 'csv') {
            $file_path = CAMPAIGN_KEYWORD_FILE_PATH . $campaign_id . DS;
            if (!file_exists($file_path)) mkdir($file_path, 0777);
            $import_file = $file_path . time() . '-' . $file_name;
            move_uploaded_file($_FILES["filename"]["tmp_name"], $import_file);
            $arr = array(
                'campaign_id' => $campaign_id, 
                'filename' => $import_file,
            );
            if (Campaign::saveKeywordField($arr)) {
                $feedback = 'Thank you, someone will be in contact with you within the next business day.';
                echo "<script>alert('".$feedback."');</script>";
                echo "<script>window.location.href='/client_campaign/list.php';</script>";
                exit();
            }
        } else {
            $feedback = 'Uploaded invailid files';
        }
    } else {
        Campaign::sendClientAddCampaignEmail($campaign_id);
        $feedback = 'Thank you, someone will be in contact with you within the next business day.';
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/list.php';</script>";
        exit();
    }
    if (!empty($feedback)) {
        echo "<script>alert('".$feedback."');</script>";
    }
}

$campaign_id = $_GET['campaign_id'];
if (empty($campaign_id)) {
    echo "<script>alert('Please specify the campaign');</script>";
    echo "<script>window.location.href='/client_campaign/campaign_type.php';</script>";
    exit();
}
$campaign = Campaign::getInfo($campaign_id);
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('feedback', $feedback);
$smarty->assign('campaign_name', $campaign['campaign_name']);
$smarty->assign('info', $info);
$smarty->assign('total', count($info['fields']));
$smarty->display('client_campaign/cuploadkeywordfile.html');
?>