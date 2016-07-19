<?php
$g_current_path = "client";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 13:55
// let users who role is agency access this page
if (!user_is_loggedin() && (User::getPermission() < 4)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/article_cost.class.php';
require_once CMS_INC_ROOT.'/article_type.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

if (count($_POST))
{
    $opt = $_POST['operation'];
    if ($opt == 'save')
    {
        if (ArticleCost::store($_POST))
        {
            echo "<script>alert('Success');</script>";
            echo "<script>window.opener.location.reload();window.close();</script>";
            exit;
        }
    }
}
if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose a campaign');</script>";
    echo "<script>window.close();</script>";
    exit;
}

// get querty string from $_SERVER
$query_string = $_SERVER['QUERY_STRING'];
$smarty->assign('query_string', $query_string);

// get campaign info by campaign_id
$campaign_id  = $_GET['campaign_id'];
$campaign_info = Campaign::getInfo($campaign_id);

$selected_types  = ArticleCost::getTypesByCampaignID($campaign_id);
$all_types     = $g_tag['leaf_article_type'];
// excluded the existed article types
$all_types = array_diff($all_types, $selected_types);

// get article type info from article_type table
$article_type   = $_GET['article_type'];
if (strlen($article_type))
{
    $type_info = ArticleType::getInfo($article_type);
    $smarty->assign('type_info', $type_info);
}

$all_types = array('' => '[choose]') + $all_types;

$smarty->assign('all_types', $all_types);
$smarty->assign('selected_types', $selected_types);
$smarty->assign('total_selected', count($selected_types));
$smarty->assign('campaign_info', $campaign_info);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/add_article_cost.html');
?>