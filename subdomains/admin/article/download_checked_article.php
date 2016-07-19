<?php
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
//require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';

require_once "File/Archive.php"; 

// $files is an array of path to the files that must be added to the archive 

 File_Archive::setOption("zipCompressionLevel", 0); 

if (!empty($_POST['article_id']) || !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
    //echo "<pre>";
    //print_r($_POST);
	//以下构造是为了防止hacker伪造数据提交
    //$post_checkbox_array = implode(",", $_POST['isUpdate']);
    $article_id = array();
    if (is_array($_POST['isUpdate'])) {
        foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
            $k = $v_isUpdate - 1;
            $article_id = $article_id + array($k_isUpdate => $_POST['article_id'][$k]);
        }
    }
    $p = array();
    $p = array('article_id' => $article_id);
}

if (empty($p)) {
    echo "<script>alert('Please choose articles');window.close();</script>";
    exit;
}

$all_article = Article::getCheckedArticle($p);
if (empty($all_article)) {
    echo "<script>alert('No finished article');window.close();</script>";
    exit;
}

$mode = $_POST['mode'];
$allowd_modes = array('html_zip', 'text_zip', 'doc_zip', 'docx_zip');
if (client_is_loggedin()) {
    foreach ($all_article as $k_ar_id => $v_ar_id) {
        $ar_arr['article_id'][$v_ar_id['article_id']] = $v_ar_id['article_id'];
    }
    Article::setDownLoadTime($ar_arr);
}
//windows valid file name,
$reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/');

$org_file = $g_article_storage."/".date('YmdHis', time())."_checked.zip";
$dest = File_Archive::toArchive($org_file, File_Archive::toFiles()); 

if (in_array($mode, $allowd_modes)) {
    $suffix = ($mode == 'doc_zip') ? '.doc' : (($mode == 'html_zip') ? '.html' : '.txt');
    if ($mode == 'docx_zip') $suffix = ".docx";
    // there will be language->charset (en-->'iso-8859-1')
    $sys_charset = 'iso-8859-1';
    foreach ($all_article as $kar => $var) {
//        if ($mode == 'doc_zip') {
//            $var['richtext_body'] = str_replace("&nbsp;"," ", $var['richtext_body'] );
//        }
        // $var['richtext_body'] = html_entity_decode($var['richtext_body']);
        $var['richtext_body'] = change2EQuote($var['richtext_body']);
        $var['richtext_body'] = html_entity_decode($var['richtext_body'], ENT_COMPAT, 'UTF-8');        
        $var['cp_bio'] = html_entity_decode($var['cp_bio'], ENT_COMPAT, 'UTF-8');
        $var['category_id'] = $g_2image_categories[$var['category_id']];
        if (empty($var['body']))     
            $var['body'] = stripslashes(change_richtxt_to_paintxt($var['richtext_body'],ENT_QUOTES));
        $var['body'] = change2EQuote($var['body']);

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

        //pr($content);
        
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
header("Pragma: private");header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
readfile($org_file);

?>