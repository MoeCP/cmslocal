<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
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
if (trim($_POST['client_id']) != '' && trim($_POST['campaign_name']) != '') {
    if (Campaign::add($_POST)) {
        //sql_log();
        $feedback = 'Thank you, someone will be in contact with you within the next business day.';
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/list.php';</script>";
        exit;
    } else {
        echo "<script>alert('".$feedback."');</script>";
    }
}
if (trim($_GET['article_type']) != '' && trim($_POST['article_type']) != '') {
    echo "<script>alert('Please choose one article type');</script>";
    echo "<script>window.location.href='/client_campaign/campaign_type.php';</script>";
}
$all_client = Client::getAllClients('id_name_only');
$smarty->assign('client_name',Client::getName());
if (!empty($_POST)) {
    $info = $_POST;
} else {
    $info['client_id'] = Client::getID();
    $info['article_type'] = $_GET['article_type'];
    $info['campaign_type'] = 1;
    $info['ordered_by'] = 'Client';
}
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
$smarty->display('client_campaign/client_campaign_form.html');
?>