<?php
$g_current_path = "client";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() || (User::getPermission() < 5)) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$client_id = $_GET['client_id'];
if (empty($client_id)) {
    echo '<script type="text/javascript">';
    echo "alert('Invalid Client, please to check');";
    echo "window.location.href='/client/list.php';";
    echo "</script>";
    exit();
}
require_once CMS_INC_ROOT.'/custom_field.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
$clinetInfo = Client::getInfo($client_id);
$oField = new CustomField();
if (!empty($_POST)) {
    $oField->batchStore($_POST);
    echo '<script type="text/javascript">';
    echo "alert('" . $feedback ."');";
    echo "window.location.href='/client/list.php';";
    echo "</script>";
    exit();    
}

$fields = CustomField::showCustomFieldFromTable();
$result = $oField->getDataByParam($_GET);
$defaultFields = CustomField::fieldMapping($fields);
$result = array_merge($defaultFields, $result);
$roles = array(4 => 'project manager', 3=> 'editor', 1 => 'copywriter');
$smarty->assign('roles', $roles);
$smarty->assign('clientName', $clinetInfo['user_name']);
$smarty->assign('fields', $fields);
$smarty->assign('result', $result);
$smarty->assign('table', $oField->getCTable());
$smarty->assign('client_id', $client_id);
$smarty->assign('feedback', $feedback);
$smarty->display('client/keyword_fields.html');
?>