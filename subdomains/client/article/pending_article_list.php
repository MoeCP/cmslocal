<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
$logout_folder = '';
if (client_is_loggedin()) {
    $g_current_path = "client_campaign";
    require_once('../cms_client_menu.php');
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

if (!empty($_POST['article_id']) && !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
    //echo "<pre>";
    //print_r($_POST);
	//以下构造是为了防止hacker伪造数据提交
    $post_checkbox_array = implode(",", $_POST['isUpdate']);

    $article_id = array();
    foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
        $k = $v_isUpdate - 1;
        $article_id = $article_id + array($k_isUpdate => $_POST['article_id'][$k]);
    }

    $p = array();
    $p = array('article_id' => $article_id);
    Article::batchApproveArticle($p);
}

$p = array();
$p = $_GET;
$p += array('article_status' => 4);
$search = Article::search($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

//$p['client_id'] = User::getID();

if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
    $campaigns = Campaign::getAllCampaigns($mode = 'id_name_only', Client::getID());
    $campaigns = array('' => "All Campaigns") + $campaigns;
    $smarty->assign('campaigns', $campaigns);
} else {
    $smarty->assign('login_role', User::getRole());
}

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('noflow_status', $g_tag['noflow_status']);

$g_tag['show_keyword_type']['show_all'] = 'Show all keywords';
$g_tag['show_keyword_type']['show_active'] = 'Show all active keywords';
$g_tag['show_keyword_type']['but_active'] = 'Show all but active keywords';
//O show all keyword under this cp O show only incomplete articles
$campaign_id = $_GET['campaign_id'];
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('login_role', 'client');
$smarty->assign('result_count', count($search['result']));
$smarty->assign('show_keyword_type', $g_tag['show_keyword_type']);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/pending_article_list.html');
?>