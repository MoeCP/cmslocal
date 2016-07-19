<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/KeywordFile.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once COMMON_PATH.'/mycsvparser.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_POST)) {
    $file_id = $_POST['file_id'];
} else {
    $file_id = $_GET['file_id'];
}
$info = KeywordFile::getInfo($file_id);
$oMyCSV = new MyCSVParser(array('file' => $info['filename']));
$fields = $oMyCSV->getFirstLine();

$smarty->assign('fields', $fields);

if (!empty($_POST)) {
    $fields = $_POST['fieldnames'];
    $labels = $_POST['fieldlabels'];
    $data = array('file_id' => $file_id, 'fields' => array(), 'data' => array());
    $optinals = array();
    $import_data = $oMyCSV->getAllData();
    foreach ($fields as $k => $field) {
        if ($field == 'skip') continue;
        $label = $labels[$k];
        $data['fields'][$field] = $labels[$k];
        $value = preg_replace("/[\s]+/ims"," ", $import_data[$label]);
        $data['data'][$field] = $value;
    }
    if (!empty($data['fields']) && KeywordFile::store($data)) {
        echo "<script>window.location.href='/client_campaign/fadd_keyword.php?file_id={$file_id}'</script>";
       // exit();
    } else {
        echo "<script>window.location.href='/client_campaign/kfilemapping.php?file_id={$file_id}'</script>";
        exit();
    }
}

$info = KeywordFile::getInfo($file_id);
$campaign_id = $info['campaign_id'];
if ($info['is_parsed']) {
    echo "<script>alert('This keyword file had uploaded, please upload other keyword file');</script>";
    echo "<script>window.location.href='/client_campaign/uploadkeywordfile.php?campaign_id={$campaign_id}';</script>";
    exit;
}

if (!empty($info) && !empty($info['fields'])) {
    $fields = array_keys($info['fields']);
} else {
    $options = $g_keyword_fields;
    unset($options['skip']);
    $fields = array_keys($options);
}

$smarty->assign('info', $fields);
$campaign = Campaign::getInfo($info['campaign_id']);
//added by nancy xu 2012-04-23 10:51
require_once CMS_INC_ROOT.'/custom_field.class.php';
$optional_fields = CustomField::getFieldLabels($campaign['client_id'], 'optional');
foreach ($optional_fields as $k => $item) {
    $g_keyword_fields[$k] = $item['label'];
}
$smarty->assign('gfields', $g_keyword_fields);
// end
$smarty->assign('campaign_name', $campaign['campaign_name']);
$smarty->assign('file_id', $file_id);
$smarty->assign('login_role', User::getRole());
$smarty->display('client_campaign/kfieldmapping.html');
?>