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

if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose an campaign');window.close();</script>";
    exit;
}
$campaign_info = Campaign::getInfo($_GET['campaign_id']);
//echo $campaign_info['client_id'];
//die();
$p = $_GET;
$allowed_statuses = $client_downloaded_statuses;
//end
$article_status = $p['article_status'];

if (!in_array($article_status, $allowed_statuses)) {
    $p['article_status'] = $allowed_statuses;
    if ($login_role != 'client' && $article_status) {
        array_push($p['article_status'], $article_status);
    }
}

//PM can download all of the articles;
if ($login_role != 'client' && isset($p['dlall']) && $p['dlall'] == 1) {//except for ebay!!!
    $p['article_status'] = array(0,1,2,3,4,5,6,'1gd','1gc','99');
}

$all_article = Article::downloadArticleByCampaignID($_GET['campaign_id'], $p);
//print_r($all_article);exit();
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
    $username = Client::getName();
} else {
    $username = User::getName();
}
$reg_str = array('/\//', '/\\\/', '/\*/', '/\'/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/');

$campaign_name = $campaign_info['campaign_name'];
$client_user_name = preg_replace( '#\s+#', '-', trim($campaign_info['user_name']) );
$campaign_name = preg_replace( '#\s+#', '-', trim($campaign_name) );
$campaign_name = preg_replace( $reg_str, '-', $campaign_name );//windows valid file name,
$file_name  = $campaign_name.".xls";
$clientDir = $g_article_storage.$client_user_name;
if (!file_exists($clientDir)) {
    mkdir($clientDir, 0777);
}
$org_file = $clientDir."/". $file_name;
//echo $org_file;exit();
require_once CMS_INC_ROOT.'/custom_field.class.php';
if (client_is_loggedin()) {
    $client_id = Client::getID();
} else {
    $client_id = $campaign_info['client_id'];
}
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$custom_fields = CustomField::getFieldLabels($client_id, 'custom_field', 'custom');
$field_labels = array_merge($optional_fields, $custom_fields);
$field_labels['title']['label'] = 'Product Title';
$field_labels['html_title']['label'] = 'Html Title Tag';
$field_labels['body']['label'] = 'Description';
$field_labels['content_link']['label'] = 'Content Link';
$field_labels['small_image']['label'] = 'Small Image';
$field_labels['large_image']['label'] = 'Large Image';
$field_labels['image_credit']['label'] = 'Image credit';
$field_labels['image_caption']['label'] = 'Image Caption';
$field_labels['meta_description']['label'] = 'Meta Description';
$field_labels['blurb']['label'] = 'blurb';
if ($campaign_info['template']) {
    $fields = array('optional1', 'optional2', 'optional3', 'optional4', 'optional5', 'optional6', 'optional7','optional8','optional9','optional10', 'title','html_title', 'custom_field1', 'custom_field2', 'custom_field3', 'custom_field4', 'custom_field5', 'richtext_body', 'content_link','small_image', 'large_image', 'image_credit', 'image_caption', 'meta_description','blurb');
    $columns = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K','L','M','N','O','P','Q','R','S','U','V','W','X','Y','Z'
    );
} else {
    $fields = array('optional1', 'optional2', 'optional3', 'optional4', 'optional5', 'optional6', 'optional7','optional8','optional9','optional10', 'title','html_title','custom_field1', 'custom_field2', 'custom_field3','custom_field4', 'custom_field5',  'body');
    $columns = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K','L','M','N','O','P','Q','R'
    );
}
require_once 'Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator($username)
							 ->setLastModifiedBy($username)
							 ->setTitle($campaign_name);
// Add some data
$objActSheet  = $objPHPExcel->setActiveSheetIndex(0);
foreach ($fields as $k=> $field) {
    $objActSheet->setCellValue($columns[$k] . '1', $field_labels[$field]['label']);
}
$i=2;
foreach ($all_article as $k => $item) {
    foreach ($fields as  $key => $field) {
        $value = $item[$field];
        if ($field == 'richtext_body' || $field == 'title') {
            $value = html_entity_decode($value);
        } else if ($field == 'body') {
            $value = stripslashes(change_richtxt_to_paintxt($value));
        }
        $value = change2EQuote($value);
        $objActSheet->setCellValue($columns[$key] . $i, $value);
    }
    $i++;
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Articles');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($org_file);
if (file_exists($org_file)) readfile($org_file);
exit();
?>