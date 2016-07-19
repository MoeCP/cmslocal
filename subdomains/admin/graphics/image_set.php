<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}

$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
if( !$is_ajax )
{
	require_once('../cms_menu.php');
}
$permission = User::getPermission();
$role = User::getRole();
if (!user_is_loggedin() || $permission < 1) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once 'sdk.class.php';
require_once 'MIME/Type.php';
$s3 = new AmazonS3();
define('MB', 1048576);

//require_once CMS_INC_ROOT.'/Client.class.php';
$query_string = $_SERVER['QUERY_STRING'];
if (trim($_POST['image_id']) != '') {
    $p = $_POST;
    
    $allowed_ext = array('bmp','gif','jpg','jfif','ico','png','tif','tiff','jpeg', 'ai', 'pdg');
    if (!empty($_FILES) && ($_FILES['image']['error'] == 0)) {
        $image_name = $_FILES['image']['name'];
        $file_ext = strtolower(substr($image_name, strrpos($image_name, '.') + 1));
        if (in_array($file_ext, $allowed_ext)) {
            $client_id = $_POST['client_id'];
            $campaign_id = $_POST['campaign_id'];
            $image_id = $_POST['image_id'];
            $bucket = 'CopyPressImages';
            $name = md5($_POST['current_version_number']. $image_id . rand_str(). time()) . '.' . $file_ext;
            $keyname = "graphics/" . $client_id. "/" . $campaign_id . '/' . $image_id . '/' . $name;
            $response = $s3->create_mpu_object($bucket, $keyname , array(
                'fileUpload' => $_FILES['image']['tmp_name'],
                'partSize' => 5*MB, // Defaults to 50MB
                'acl' => AmazonS3::ACL_PUBLIC,
                'storage' => AmazonS3::STORAGE_REDUCED,
                'contentType' => $_FILES['image']['type'], 
            ));
           $image_param = $_FILES['image'];
           $image_param['extension'] = $file_ext;
           $image_param['image_name'] = $name;
           unset($image_param['tmp_name']);
           if ($response->status == '200') {
               $image_param['raw_url'] = $s3->request_url;
               $arr = parse_url($s3->request_url);
               $p['image_name'] = isset($arr['query']) ? str_replace('?' . $arr['query'], '', $s3->request_url) : $s3->request_url;
               $p['image_param'] = $image_param;
                if (Image::setInfo($p)) {
                    if( strlen( $campaign_id ) ===0 ) {
                        $campaign_id = ImageKeyword::getCampaignIDByKeywordID( $_GET['keyword_id'] );
                    }
                    $feedback = "Success";
                    echo "<script>alert('".$feedback."');</script>";
                    $from = $_GET['from'];
                    if (empty($from)) {
                        $from = 'image_keyword_list';
                    }
                    echo locationImageString($role, $from, $campaign_id, null, $_GET);
                    exit;
                }
           } else {
               $body = (array)$response->body;
               $feedback = $body['Message'];
           }
        } else {
            $feedback= "Invalid image format, please to check your file type";
        }
    } else {
        if (!empty($_FILES)) {
            if ($_FILES['image']['error'] > 0) {
                $feedback= "Uploaded error, please to check your file size.";
                /*$error = _FILES['image']['error'];
                switch($error) {
                case '1':
                    break;
                case '2':
                    break;
                case '3':
                }*/
            }
        } else {
            $feedback= "Select image for upload";
        }
    }
    //echo "<script>alert('" . $feedback . "');</script>";
}

if (trim($_GET['image_id']) == '' || trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='/graphics/image_list.php';</script>";
    exit;
}

$keyword_info = ImageKeyword::getInfo($_GET['keyword_id']);
//$smarty->assign('keyword_info', $keyword_info);

$image_info = Image::getInfo($_GET['image_id'], true);

if (!empty($image_info) && !empty($keyword_info)) {
    $keyword_info += $image_info;
} else {
//    echo "<script>alert('Invalid Article, Please choose currect article.');</script>";
//    echo "<script>window.location.href='/graphics/image_list.php';</script>";
//    exit;
}
$image_id = $image_info['image_id'];
$smarty->assign('image_id', $image_id);
$smarty->assign('comment_count', count($keyword_info['comment']));
//echo "<pre>";
//print_r($keyword_info);
//////////////////ADD BY cxz 2006-7-28 18:29
/*$sql = "SELECT en.*, u.user_name FROM editor_notes as en, users as u WHERE en.keyword_id={$keyword_info['keyword_id']} AND en.campaign_id={$keyword_info['campaign_id']} AND en.copy_writer_id={$keyword_info['copy_writer_id']} AND en.editor_id={$keyword_info['editor_id']} AND en.editor_id=u.user_id ";
$notes = $conn->GetRow($sql);
//start:modifed by snug 23:50 2006-07-31
$notes['notes'] = nl2br( $notes['notes'] );
//end
$smarty->assign('notes', $notes);*/
//////////////////ADD END
//---------------------quick pane-------------------//
if (user_is_loggedin()) {
    if (User::getPermission() >= 3) { // 2=>3
        $quick_pane[0][lable] = "Campaign Management";
        $quick_pane[0][url] = "/client_campaign/client_list.php";
    } else {
        $quick_pane[0][lable] = "Campaign & Image Management";
        $quick_pane[0][url] = "/graphics/designer_campaign_list.php";
    }
    if ($_GET['campaign_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $campaign_info = Campaign::getInfo($_GET['campaign_id']);
        $header = substr($header,0,strlen($header)-1);
        if (User::getPermission() > 3) {
            if (User::getPermission() >= 3) { // 2=>3
                $quick_pane[1][lable] = $campaign_info['company_name'];
                $quick_pane[1][url] = '/graphics/designer_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            }
        }

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        if ($_GET['keyword_id'] == '') {
            $_SESSION['campaign_url'] = $header;
        }
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }

    if ($_GET['keyword_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        reset($_GET);
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $quick_pane[3][lable] = $keyword_info['keyword'];
        $quick_pane[3][url] = $header;
    }
}
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $keyword_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$smarty->assign('optional_fields', $optional_fields);
$keyword_info = showLinkForOptionalFields($optional_fields, $keyword_info);
$smarty->assign('keyword_info', $keyword_info);
$smarty->assign('feedback', $feedback);
//$smarty->assign('image_type', $g_tag['image_type']);
$smarty->assign('image_type', ImageType::getAllLeafNodes());
$smarty->assign('languages', $g_tag['language']);
$smarty->assign('url', "/graphics/image_set.php?is_ajax=1&".$query_string);
$smarty->assign('login_role', User::getRole());
if( !$is_ajax )
{
	$smarty->display('graphics/image_form.html');
}
?>