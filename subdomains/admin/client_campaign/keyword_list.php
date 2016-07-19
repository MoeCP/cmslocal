<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if ((!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';


if (user_is_loggedin()) {
    if (trim($_POST['skeyword_id']) != '' && $_POST['form_refresh'] == "D") {
        if (Campaign::setKeywordStatus($_POST['skeyword_id'], 'D')){
            $feedback = "Delete Success";
            //sql_log();
            //header("Location:keyword_list.php");
            //exit;
        }
    }
    if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "P") {
        $article_id = trim($_POST['article_id']);
        Article::setArticleStatus($article_id, 6, 5);
    } else if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "Deliver") {
        Article::deliverArticle($_POST);
    }
    //$smarty->assign('user_role', User::getRole());
} else {//client sign in
    //$smarty->assign('user_role', 'client');
}
//Start: Added By Snug 0:06 2006-08-14
if( !empty( $_POST ) )
{
	$operation = $_POST['operation'];
    $p = $_POST;
	switch( $operation )
	{
		case 'move_to_next_pay_peried':
			$query_string = $_SERVER['QUERY_STRING'];
			if(Article::setTargetPayMonth($p))
			{
					$feedback = "Success";
					echo "<script>alert('".$feedback."');</script>";
					echo "<script>window.location.href='/client_campaign/keyword_list.php?$query_string';</script>";
					exit;
			}
			break;
		case 'cancel_keyword':
			$query_string			= $_SERVER['QUERY_STRING'];
			foreach ($p as $k => $value) 
			{
				if (strlen($value) == 0 || $k == 'form_refresh' || $k == 'is_forced_adjust') 
				{
					unset($p[$k]);
				}
			}
            $p['target_pay_month'] = 0;
			if(Article::updateArticleInfoByArticleID( $p ))
			{
					$feedback = "Success";
					echo "<script>alert('".$feedback."');</script>";
					echo "<script>window.location.href='/client_campaign/keyword_list.php?$query_string';</script>";
					exit;
			}
			break;
        case '1gc':
        case '1gd':
            $p['article_status'] = $operation;
            Article::batchApproveArticles($p);
            break;
        case 'client_approve':
            $p['article_status'] = 5;
            $keywords = $p['isUpdate'];
            if (count($keywords) == 0) {
                $feedback = "Please select articles to Force Client Approve";
            } else {
                //##Article::batchApproveArticles($p);
                //## modified by leo @11/20/2014 ##
                if (Article::batchApproveArticles($p)) {
                    ArticlePaymentLog::batchUpdatePaymentMonth($p);
                }
            }
            break;
       case 'editor_approve':
            $p['article_status'] = 4;
            Article::batchApproveArticles($p);
        	break;
        case 'editor_rejected':
            $p = $_POST;
            $p['article_status'] = 2;
            Article::batchApproveArticles($p);
        	break;
	}
}
//End Added
if (isset($_GET['is_googlecheck'])) {
   $is_googlecheck = $_GET['is_googlecheck'];
} else {
    $is_googlecheck = 0;
}
$smarty->assign('is_googlecheck', $is_googlecheck);
if (isset($_GET['is_forceclientapprove'])) {
   $is_forceclientapprove = $_GET['is_forceclientapprove'];
} else {
    $is_forceclientapprove = 0;
}
$smarty->assign('is_forceclientapprove', $is_forceclientapprove);
if (!empty($_GET)) {
    
    $search = Campaign::searchKeyword($_GET);
   
    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
        $smarty->assign('show_cb', $search['show_cb']);
    }
}

//########quick pane########//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
if ($_GET['campaign_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $campaign_info = Campaign::getInfo($_GET['campaign_id']);
    $smarty->assign('campaign_info', $campaign_info);
    $header = substr($header,0,strlen($header)-1);

    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
	$quick_pane[1][lable] = $campaign_info['company_name'];
	$quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];

    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
 
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[2][lable] = $_SESSION['campaign_lable'];
	$quick_pane[2][url] = $_SESSION['campaign_url'];
}
$smarty->assign('quick_pane', $quick_pane);
$archived = $_GET['archived'];
$smarty->assign('archived', $archived);
//print_r($quick_pane);
//echo phpinfo();
//########quick pane########//

if (user_is_loggedin()) {
    // ------------------------ Start Added By Leo 11/12/2014 -------------------- //
    $login_role = User::getRole();
    if (isset($login_role) && $login_role == 'admin' && $_GET["article_status"] == 4) {
        $currentmonth = time();
        $currentmonth = changeTimeToPayMonthFormat($currentmonth);
        $monthes = genPayMonthList(-1, 4);

        $smarty->assign('onemonthlater', $currentmonth);//remove the net 30 days for now
        $smarty->assign('monthes', $monthes);
    }
    // ------------------------ End Added By Leo 11/12/2014 -------------------- //

    $smarty->assign('user_id', User::getID());
    //##$smarty->assign('login_role', User::getRole());
    $smarty->assign('login_role', $login_role);
    $smarty->assign('login_permission', User::getPermission());
} else {
    $smarty->assign('login_role', 'client');
}
//$smarty->assign('login_permission', User::getPermission());

$keyword_categorys = Campaign::getPrefByCampaignID($_GET['campaign_id']);
$smarty->assign('keyword_categorys', $keyword_categorys);
//echo "<pre>";
//print_r($keyword_categorys);
////////BEGIN ADD BY cxz 2006-8-2 10:09上午
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$smarty->assign('all_editor', $all_editor);
$all_qaers = User::getAllUsers($mode='id_name_only', $user_type = 'admin');
$smarty->assign('all_qaers', User::getAllUsers($mode='id_name_only', $user_type = 'admin', false));
////////END ADD
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_copy_writer', $all_copy_writer);

$smarty->assign('noflow_statuses', $g_tag['noflow_status']);//Client Ready

//$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('g_qa', $g_qa);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('query_string', http_build_query($_GET));
$smarty->assign('is_pay_adjust', $_GET['is_pay_adjust']);
$client_id = isset($_GET['client_id'])&&$_GET['client_id'] > 0? $_GET['client_id'] : '';
$all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id);
$smarty->assign('all_campaigns', $all_campaigns);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
// added by nancy xu 2011-05-31 12:10
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_clients', $clients);
// end

function format_pay_period($args){return preg_replace('/(\d{4})(\d{2})(\d{1})/', '\1-\2(\3)', $args["pmonth"]);}
$smarty->register_function('formatpayperiod','format_pay_period');

$smarty->display('client_campaign/keyword_list.html');
?>
