<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_comment.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_version_history.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once 'sdk.class.php';
require_once 'MIME/Type.php';

//echo $_GET['image_id'];
//print_r($_GET);
if (trim($_GET['image_id']) == '') {
    echo "<script>alert('Please choose an image');</script>";
    echo "<script>window.location.href='/graphics/image_list.php';</script>";
    exit;
}

$image_info = Image::getInfo($_GET['image_id'], true);
$url = $image_info['image_name'];
$image_param = $image_info['image_param'];
if (!empty($url)) {
    $filename = replaceSpaceToLine(replace_specialChar($image_info['title']));
    if (!empty($image_param)) {
        $filename .= '.' . $image_param['extension'];
       if (!empty($image_param['type'])) $type = $image_param['type'];
    } else {
        $file_ext = strtolower(substr($url, strrpos($image_name, '.') + 1));
        $filename .= '.' . $file_ext;
    }
    if (!isset($type)) $type = MIME_Type::autoDetect($url);
    header('Content-Description: File Transfer');
    header("Content-Type: {$type}");
    header("Content-Disposition: attachment; filename=\"". $filename."\"");
    readfile($url);
} else {
    echo "<script language='javascript'>window.close();</script>";
}
?>