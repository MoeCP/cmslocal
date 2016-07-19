<?php
$g_current_path = "article";
require_once('../pre.php');//����������Ϣ
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || User::getPermission() < 1) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
//Start:Add By Snug 15:54 2006-8-16
/**cp click recall buttonʱ�ı�������article status�޸�Ϊwriting״̬**/
$operation = $_POST['operation'];

$query_string = $_SERVER['QUERY_STRING'];
if(count( $_POST ) )
{
	switch( $operation )
	{
		case 'recall':
			$article_id = $_POST['article_id'];
		     $status = 0;
			 $query_string = $_POST['query_string'];
			 $old_status = $_POST['old_status'];
			if( Article::setArticleStatus($article_id, $status,  $old_status ) )
			{
				echo "<script>alert('".$feedback."');</script>";
				echo "<script>window.location.href='/article/article_keyword_list.php?{$query_string}';</script>";
				exit;
			}
			break;
	}
}
$smarty->assign('query_string', $query_string);
//END

if (User::getPermission() >= 4) { // 3=>4
	if (trim($_POST['article_id']) != '' && $_POST['form_refresh'] == "D") {
		if (Article::setStatus($_POST['article_id'], 'D')){
			$feedback = "Delete Success";
		}
		//sql_log();
		header("Location:article_keyword_list.php");
		exit;
	}
}

$search = Article::listKeywordByRole($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

//---------------------quick pane-------------------//
if (user_is_loggedin()) {
    if (User::getPermission() >= 4) { // 2=>3
        $quick_pane[0][lable] = "Campaign Management";
        $quick_pane[0][url] = "/client_campaign/client_list.php";
    } else {
        $quick_pane[0][lable] = "Campaign & Article Management";
        $quick_pane[0][url] = "/client_campaign/ed_cp_campaign_list.php";
    }
    if ($_GET['campaign_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $campaign_info = Campaign::getInfo($_GET['campaign_id']);
        $header = substr($header,0,strlen($header)-1);
        if (User::getPermission() > 3) { // 2=>3
            $quick_pane[1][lable] = $campaign_info['company_name'];
            if (User::getPermission()==3) {               
                $quick_pane[1][url] = '/client_campaign/ed_cp_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            } else {
                $quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
            }
        }

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        $_SESSION['campaign_url'] = $header;
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }
}
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//

//echo "<pre>";
//print_r($search);
$g_tag['show_keyword_type']['show_all'] = 'Show all keywords';
$g_tag['show_keyword_type']['show_active'] = 'Show all active keywords';
$g_tag['show_keyword_type']['but_active'] = 'Show all but active keywords';
//O show all keyword under this cp O show only incomplete articles

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$keyword_categorys = Campaign::getPrefByCampaignID($_GET['campaign_id']);
$smarty->assign('keyword_categorys', $keyword_categorys);

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('query_string', http_build_query($_GET));
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('show_keyword_type', $g_tag['show_keyword_type']);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/article_keyword_list.html');
?>