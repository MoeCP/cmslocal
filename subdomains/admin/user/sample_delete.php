<?php
$g_current_path = "preference";
 ini_set("display_errors", "1");
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT . '/Candidate.class.php';
$cid = $_GET['cid'];
if (empty($cid)) {
    echo '<script>alert("Invalid Candidate");</script>';
    exit();
} 
$file_name = $_GET['f'];
$folder = isset($_GET['fd']) ? $_GET['fd'] : 'candidate_samples';
$path = $g_article_storage . $folder. DS . $cid . DS;
$info = Candidate::getCandidateInfo($cid);
switch ($folder) {
case 'candidate_samples':
    $field = 'samples' ;
    $hint = 'Deleted writing samples';
    break;
case 'candidate_categories':
    $hint = 'Deleted category samples';
    $field = 'categories' ;
    break;
}
$data = $info[$field];
unlink($path . $file_name);
foreach ($data['fileField'] as $k => $row) {
    if ($row['filename'] == $file_name) {
        $data['fileField'][$k] = array();
        break;
    }
}
$info[$field] = $data;
$data = array('candidate_id' => $cid,  $field=> $data);
Candidate::saveInfo($data);
echo '<script>alert("' . $hint .'");window.top.window.location.reload();</script>';
exit();
?>