<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/BulkArticle.class.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_POST)) {
    $client_id = $_POST['client_id'];
    if (!empty($_FILES) && $_FILES["filename"]["error"] == UPLOAD_ERR_OK) {
        $file_name = $_FILES["filename"]["name"];
        $arr = explode(".", $file_name);
        $ext = $arr[count($arr) - 1];
        if ($ext == 'csv') {
            $file_path = $g_article_storage . "bulkarticle" . $client_id . DS;
            if (!file_exists($file_path)) mkdir($file_path, 0777);
            $import_file = $file_path . time() . '-' . $file_name;
            move_uploaded_file($_FILES["filename"]["tmp_name"], $import_file);
            $arr = array(
                'client_id' => $client_id, 
                'filename' => $import_file,
            );
            if ($file_id = BulkArticle::save($arr)) {
                $feedback = "Uploaded successfully. " . $feedback;
            }
        } else {
            $feedback = 'Uploaded invailid files';
        }
    } else {
        $feedback = 'Failed, please try again';
    }
}

$smarty->assign('feedback', $feedback);
$smarty->display('article/bulkuploadarticle.html');
?>