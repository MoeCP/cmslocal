<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
if (client_is_loggedin()) {
    require_once('../cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || User::getPermission() < 4) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

if (trim($_POST['campaign_id']) != '' && trim($_POST['campaign_name']) != '') {
    $_POST['status'] = 0;
    if (Campaign::setInfo($_POST)) {
        //sql_log();
        $campaign_id = $_POST['campaign_id'];
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/uploadkeywordfile.php?campaign_id=" . $campaign_id . "';</script>";
        exit;
    }
}

if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose a campaign');</script>";
    echo "<script>window.location.href='/client_campaign/campaign_type.php';</script>";
    exit;
}


$all_client = Client::getAllClients('id_name_only');
$smarty->assign('client_name',Client::getName());

$info = Campaign::getInfo($_GET['campaign_id']);
$info['ordered_by'] = 'Client';
$info['campaign_type'] = 1;
if ($info['date_start'] == '0000-00-00') $info['date_start'] = '';
if ($info['date_end'] == '0000-00-00') $info['date_end'] = '';
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
if ($info['client_id'] > 0)  {
    $domains += $oClientUser->getDomains(array('client_id' => Client::getID()));
}
$smarty->assign('domains', $domains);

$arr = Category::getAllCategoryByCategoryId();
if (empty($arr)) {
    $arr = array();
}
$category = array(0 => array('name' => 'Please select a category')) + $arr;
$smarty->assign('category', $category);
$smarty->assign('client_campaign_info', $info);
$smarty->assign('feedback', $feedback);
$smarty->assign('templates', $g_templates);

$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_hidden' => 0)));
$smarty->display('client_campaign/campaign_set.html');
?>