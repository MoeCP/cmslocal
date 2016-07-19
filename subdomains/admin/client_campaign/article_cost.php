<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/article_type.class.php';
require_once CMS_INC_ROOT.'/article_cost.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';


$operation=$_POST['operation'];
if( count($_POST ) && strlen( $operation ) )
{	
	ArticleCost::storeArticleCost( $_POST );
	$query_string = $_POST['query_string'];
	echo "<script>alert('".$feedback."');</script>";
    echo "<script>window.location.href='/client_campaign/article_cost.php?{$query_string}';</script>";
	exit;
}

// get all campaigns from database
$campaign_list = Campaign::getAllCampaigns('id_name_only', $_GET['client_id']);
$campaign_id = $_GET['campaign_id'];
$query_string = $_SERVER['QUERY_STRING'];
if (empty($campaign_id))
{
    $campaign_ids = array_keys($campaign_list);
    $campaign_id  = $campaign_ids[0];
    $query_string  = "campaign_id={$campaign_id}";
}
$smarty->assign('query_string', $query_string);
$smarty->assign('campaign_list', $campaign_list);

/***get all  campaign_id, campaign_name and article type ***/
if ($campaign_id > 0) 
{
    $article_types = ArticleCost::getArticleCostByCampaignID($campaign_id);
}
//$article_types =  Article::getArticleCostInfo();
$all_client = Client::getAllClients('id_name_only');
$smarty->assign('all_client', $all_client);
$smarty->assign('article_types', $article_types);
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('total_type', count($article_types));
$smarty->assign('g_article_types', $g_tag['article_type']);
$all_article_types = ArticleType::getAllTypes();
$smarty->assign('all_article_types', $all_article_types);

$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/article_cost.html');
?>