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


if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose an campaign');window.close();</script>";
    exit;
}
$campaign_info = Campaign::getInfo($_GET['campaign_id']);


$all_image = Image::downloadImageByCampaignID($_GET['campaign_id'], $_GET);
if (empty($all_image)) {
    if ($_GET['cp_completed'] == 1) {
        echo "<script>alert('There is no copywriter complete article in this campagin');window.close();</script>";
    } else {
        echo "<script>alert('No finished article in this campagin');window.close();</script>";
    }
    exit;
}

//windows valid file name,
$reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/');
$g_image_storage = $g_article_storage . 'images' . DS;
$client_user_name = preg_replace( '#\s+#', '_', trim($campaign_info['user_name']) );
$campaign_name = preg_replace( '#\s+#', '_', trim($campaign_info['campaign_name']) );
$campaign_name = preg_replace( $reg_str, '_', $campaign_name );//windows valid file name,
$org_file = $g_image_storage.$client_user_name."/".$campaign_name.".zip";
$dest = File_Archive::toArchive($org_file, File_Archive::toFiles()); 
foreach ($all_image as $kar => $var) {
    if (!empty($var['image_name'])) {
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
}
$dest->close(); //

header("Cache-Control: private, must-revalidate");

header("Content-Type: application/zip");

header("Content-Disposition: attachment; filename=" . basename($org_file) );

header("Pragma: private");

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
readfile($org_file);
?>