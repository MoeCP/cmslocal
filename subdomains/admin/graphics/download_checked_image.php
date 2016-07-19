<?php
ini_set('max_execution_time', 600);
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once "File/Archive.php"; 

// $files is an array of path to the files that must be added to the archive 

File_Archive::setOption("zipCompressionLevel", 0); 

$g_image_storage = $g_article_storage . 'images' . DS;

if (!empty($_POST['image_id']) || !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
    $image_id = array();
    if (is_array($_POST['isUpdate'])) {
        foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
            $k = $v_isUpdate - 1;
            $image_id = $image_id + array($k_isUpdate => $_POST['image_id'][$k]);
        }
    }
    $p = array();
    $p = array('image_id' => $image_id);
}

if (empty($p)) {
    echo "<script>alert('Please choose image');window.close();</script>";
    exit;
}

$all_image = Image::getCheckedImage($p);
if (empty($all_image)) {
    echo "<script>alert('No finished image');window.close();</script>";
    exit;
}

if (client_is_loggedin()) {
    foreach ($all_image as $k_ar_id => $v_ar_id) {
        $ar_arr['image_id'][$v_ar_id['image_id']] = $v_ar_id['image_id'];
    }
    Image::setDownLoadTime($ar_arr);
}
//windows valid file name,
$reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/');

$org_file = $g_image_storage."/".date('YmdHis', time())."_checked.zip";
$dest = File_Archive::toArchive($org_file, File_Archive::toFiles()); 

foreach ($all_image as $kar => $var) {
    $image_param = unserialize($var['image_param']);
    $suffix = '.' . $image_param['extension'];
    $content = file_get_contents($var['image_name']);
    $filename = preg_replace( '#\s+#', '_', trim($var['keyword']) );
    $filename = html_entity_decode($filename);
    $filename = preg_replace( $reg_str, '_', $filename );//windows valid file name,
    $filename = $filename . "-" . $var['image_id']; //added by leo 6/26/2010

    $dest->newFile($filename.$suffix);
    $dest->writeData($content);
}
$dest->close(); //

header("Cache-Control: private, must-revalidate");
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=" . basename($org_file) );
header("Pragma: private");header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
readfile($org_file);

?>