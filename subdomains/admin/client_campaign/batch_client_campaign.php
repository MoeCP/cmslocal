<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 4)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/campaign_files.class.php';
require_once CMS_INC_ROOT.'/campaign_logs.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

if (!empty($_POST)) {
    $oFile = new CampaignFile();
    if ($oFile->save($_POST, $_FILES)){
        $feedback = 'Successful, The data will dispose later';
        echo "<script>alert('" . $feedback. "');window.location.href='/client_campaign/batch_client_campaign.php'; </script>";
    }
}

// get all client
$all_client = Client::getAllClients('id_name_only');
// get all category
$arr = Category::getAllCategoryByCategoryId();
if (empty($arr)) {
    $arr = array();
}

$category = array(0 => array('name' => 'Please select a category')) + $arr;
$smarty->assign('all_client', $all_client);

if (empty($_POST)) {
    $client_campaign_info = array(
        'title_param' =>  1,
        'date_start' => date("Y-m-01"),
        'date_end' => getMonthLastDate()
     );
} else {
    $client_campaign_info = $_POST;
}

$smarty->assign('client_name', $all_client[$client_campaign_info['client_id']]);
$smarty->assign('client_campaign_info', $client_campaign_info);
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
if ($client_campaign_info['client_id'] > 0)  {
    $domains += $oClientUser->getDomains(array('client_id' => $client_campaign_info['client_id']));
}
$smarty->assign('domains', $domains);
//End
$smarty->assign('expertises', $g_user_levels);

$smarty->assign('category', $category);
$smarty->assign('feedback', $feedback);
//$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_inactive' => 0)));
$smarty->display('client_campaign/batch_client_campaign.html');
?>