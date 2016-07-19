<?php
$g_current_path = "preference";
 ini_set("display_errors", "1");
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

$cid = $_GET['cid'];
if (empty($cid)) {
    echo '<script>alert("Invalid Candidate");window.close();</script>';
    exit();
} 
$file_name = $_GET['f'];
$type = $_GET['type'];
$folder = isset($_GET['fd']) ? $_GET['fd'] : 'candidate_samples';
$path = $g_article_storage . $folder . DS . $cid . DS;

if (empty($file_name)) {
    require_once CMS_INC_ROOT.'/Candidate.class.php';
    $info = Candidate::getCandidateInfo($cid);
    $is_multi = false;
    switch ($folder) {
    case 'candidate_samples':
        $field = 'samples';
        $hint = 'No writing samples documents to download, please to check';
        break;
    case 'candidate_categories':
        $field = 'categories';
        $hint = 'No category documents to download, please to check';
        break;
    case 'all':
        $field = array('samples', 'categories');
        $hint = 'No documents to download, please to check';
        $path = $fields = array();
        foreach ($field as $v) {
            $path[$v] = $g_article_storage . 'candidate_' . $v . DS . $cid . DS;
            $fields[$v] = $info[$v]['fileField'];
        }
        $is_multi = true;
        break;
    }
    if (!$is_multi) {
         $fields = $info[$field]['fileField'];
    } 
    if (empty($fields)) {
        echo '<script>alert(' . $hint . ');window.close();</script>';
        exit();
    }
    foreach ($fields as $k => $file) {
        if (empty($file)) {
            unset($fields[$k]);
        } elseif ($is_multi) {
            foreach ($file as $subk => $item) {
                if (empty($item)) unset($fields[$k][$subk]);
            }
        }
    }
    downloadZipFiles($fields, $path);
} else {
    download_file(rawurldecode($file_name), $path, $type);
}
?>