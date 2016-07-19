<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

if (!empty($_POST['keyword_id']) && !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
	//以下构造是为了防止hacker伪造数据提交
    $post_checkbox_array = implode(",", $_POST['isUpdate']);

    $keyword_id = array();
    $keyword = array();
    //$copy_writer_id = array();
    //$editor_id = array();
    foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
        $k = $v_isUpdate - 1;
        $keyword_id = $keyword_id + array($k_isUpdate => $_POST['keyword_id'][$k]);
        $keyword = $keyword + array($k_isUpdate => $_POST['keyword'][$k]);
    }

    $p = array();
    $p = array('keyword_id' => $keyword_id,
               'keyword' => $keyword,
               'article_type' => $_POST['article_type'],
               'date_start' => $_POST['date_start'],
               'date_end' => $_POST['date_end'],
               'copy_writer_id' => $_POST['copy_writer_id'],
               'keyword_description' => $_POST['keyword_description'],
               'new_or_append' => $_POST['new_or_append'],
               'editor_id' => $_POST['editor_id']);
	//echo "<pre>";
	//print_r($p);
    Campaign::batchAssignKeyword($p);
}

$search = Article::searchCPArticlesWorkingOn($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$smarty->assign('all_editor', $all_editor);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$smarty->assign('all_copy_writer', $all_copy_writer);

$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);

$smarty->assign('copy_writer_id', $_GET['copy_writer_id']);
$smarty->assign('article_type', $g_tag['article_type']);
$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('feedback', $feedback);
$smarty->assign('result_count', count($search['result']));
$smarty->display('client_campaign/cp_working_on_articles.html');
?>