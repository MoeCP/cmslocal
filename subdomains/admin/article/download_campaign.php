<?php
ini_set('max_execution_time', 600);
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

require_once "File/Archive.php"; 

// $files is an array of path to the files that must be added to the archive 

File_Archive::setOption("zipCompressionLevel", 0); 


if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose an campaign');window.close();</script>";
    exit;
}
$campaign_info = Campaign::getInfo($_GET['campaign_id']);

//$param = array('article_status' => 5);
//$param += array('campaign_id' => $_GET['campaign_id']);

$all_article = Article::downloadArticleByCampaignID($_GET['campaign_id'], $_GET);
if (empty($all_article)) {
    if ($_GET['cp_completed'] == 1) {
        echo "<script>alert('There is no copywriter complete article in this campagin');window.close();</script>";
    } else {
        echo "<script>alert('No finished article in this campagin');window.close();</script>";
    }
    exit;
}

if (client_is_loggedin()) {
    foreach ($all_article as $k_ar_id => $v_ar_id) {
        $ar_arr['article_id'][$v_ar_id['article_id']] = $v_ar_id['article_id'];
    }
    Article::setDownLoadTime($ar_arr);
}

//windows valid file name,
$reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/');

$client_user_name = preg_replace( '#\s+#', '_', trim($campaign_info['user_name']) );
$campaign_name = preg_replace( '#\s+#', '_', trim($campaign_info['campaign_name']) );
$campaign_name = preg_replace( $reg_str, '_', $campaign_name );//windows valid file name,
$org_file = $g_article_storage.$client_user_name."/".$campaign_name.".zip";
$dest = File_Archive::toArchive($org_file, File_Archive::toFiles()); 
$mode = $_GET['mode'];
$allowd_modes = array('html_zip', 'text_zip', 'doc_zip', 'docx_zip');
if (in_array($mode, $allowd_modes)) {
    $suffix = ($mode == 'doc_zip') ? '.doc' : (($mode == 'html_zip') ? '.html' : '.txt');
    if ($mode == 'docx_zip') $suffix = ".docx";
    $sys_charset = 'iso-8859-1';
    foreach ($all_article as $kar => $var) {
//        $var['richtext_body'] = html_entity_decode($var['richtext_body']);
//        if ($mode == 'doc_zip') {
//            $var['richtext_body'] = str_replace("&nbsp;"," ",$var['richtext_body'] );
//        }
        $var['richtext_body'] = change2EQuote($var['richtext_body']);
        $var['template'] = $campaign_info['template'];
        $var['category_id'] = $g_2image_categories[$var['category_id']];
        $var['richtext_body'] = html_entity_decode($var['richtext_body'], ENT_COMPAT, 'UTF-8');
        $var['cp_bio'] = html_entity_decode($var['cp_bio'], ENT_COMPAT, 'UTF-8');
        if (empty($var['body'])) 
            $var['body'] = stripslashes(change_richtxt_to_paintxt($var['richtext_body'],ENT_QUOTES));
        $var['body'] = change2EQuote($var['body']);
        //pr($var, true);

        $var['richtext_body'] = change2EQuote($var['richtext_body']);
        $var['cp_bio'] = change2EQuote($var['cp_bio']);

        ob_start();
        $smarty->assign('article_info', $var);
        if ($mode == 'doc_zip') {
            $smarty->display('article/article_doc.html');
        } elseif ($mode == 'docx_zip') {
            require_once("article_docx.php");
            createdocx($var, true);
        } elseif ($mode == 'html_zip') {
            $smarty->assign('page_title', $var['html_title']);
            $smarty->assign('sys_charset', $sys_charset);
            $smarty->display('article/article_html.html');
        } else {
            $smarty->display('article/article_text.html');
        }
        $content = ob_get_contents();
        ob_end_clean();
        
        $filename = preg_replace( '#\s+#', '_', trim($var['keyword']) );
        $filename = html_entity_decode($filename);
        $filename = preg_replace( $reg_str, '_', $filename );//windows valid file name,
        $filename = $filename . "-" . $var['article_id']; //added by leo 6/26/2010

        $dest->newFile($filename.$suffix);
        $dest->writeData($content);
    }
	$dest->close(); //
} else {
    echo "<script>alert('Mode error, Please try again');window.close();</script>";
    exit;
}

header("Cache-Control: private, must-revalidate");

header("Content-Type: application/zip");

header("Content-Disposition: attachment; filename=" . basename($org_file) );

header("Pragma: private");

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
readfile($org_file);
?>