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

if (!empty($_POST['keyword_id']) && !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
    //echo "<pre>";
    //print_r($_POST);
	//以下构造是为了防止hacker伪造数据提交
    $post_checkbox_array = implode(",", $_POST['isUpdate']);

    $keyword_id = array();
    $keyword = array();
    foreach ($_POST['isUpdate'] as $k_isUpdate => $v_isUpdate) {
        $k = $v_isUpdate - 1;
        $keyword_id = $keyword_id + array($k_isUpdate => $_POST['keyword_id'][$k]);
        $keyword = $keyword + array($k_isUpdate => $_POST['keyword'][$k]);
    }

    $p = array();
    $p = array('keyword_id' => $keyword_id,
               'keyword' => $keyword,
     );
	//echo "<pre>";
	//print_r($p);
    Campaign::batchApprovalKeyword($p);
}

$p = $_GET;
// added by snug xu 2007-07-24 19:26 - STARTED
$p += array('article_status' => 0);
$p += array('keyword_status' => 0);
// added by snug xu 2007-07-24 19:26 - FINISHED

$search = Article::listKeywordByRole($p);
$result_count = 0;
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $result_count = count($search['result']);
    $smarty->assign('total', $search['total']);
}
$smarty->assign('result_count', $result_count);

//O show all keyword under this cp O show only incomplete articles

if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $campaigns = Campaign::getAllCampaigns($mode = 'id_name_only', Client::getID());
    $campaigns = array('' => "All Campaigns") + $campaigns;
    $smarty->assign('campaigns', $campaigns);
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$smarty->assign('login_permission', User::getPermission());
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('article/pending_keyword_list.html');
?>