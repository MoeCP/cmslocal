<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission()!=2)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image.class.php';

if (!empty($_POST['keyword_id']) && !empty($_POST['isUpdate'])) {//其实可以只用其中一个来判断
    //echo "<pre>";
    //print_r($_POST);
	//以下构造是为了防止hacker伪造数据提交
    $post_checkbox_array = implode(",", $_POST['isUpdate']);

    $keyword_id = array();
    $keyword = array();
    $old_notes = array();
    $note_id = array();
    foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
        $k = $v_isUpdate - 1;
        $keyword_id = $keyword_id + array($k_isUpdate => $_POST['keyword_id'][$k]);
        $keyword = $keyword + array($k_isUpdate => $_POST['keyword'][$k]);
		//start modified by snug at 20:53 2006-07-29
        $old_notes = $old_notes + array($k_isUpdate => $_POST['old_notes'][$k]);
        $note_id = $note_id + array($k_isUpdate => $_POST['note_id'][$k]);
		//end
        //$copy_writer_id = $copy_writer_id + array($k_isUpdate => $_POST['copy_writer_id_'.$v_isUpdate]);
        //$editor_id = $editor_id + array($k_isUpdate => $_POST['editor_id_'.$v_isUpdate]);
    }

    $p = array();
    $p = array('keyword_id' => $keyword_id,
               'keyword' => $keyword,
               'article_type' => $_POST['article_type'],
               'date_start' => $_POST['date_start'],
               'date_end' => $_POST['date_end'],
               'copy_writer_id' => $_POST['copy_writer_id'],
				//start modified by snug at 20:53 2006-07-29
               'note_id' => $note_id,
               'old_notes' => $old_notes,
               'notes' => $_POST['notes'],
               'is_forced' => $_POST['is_forced'],
               'is_reserve_content' => $_POST['is_reserve_content'],
               'campaign_id' => $_GET['campaign_id'],
				//end
               'new_or_append' => $_POST['new_or_append'],
               'editor_id' => $_POST['editor_id']);
    if ($_POST['is_forced_not_free']) $p['is_forced_not_free'] = $_POST['is_forced_not_free'];
    if (ImageKeyword::batchAssignKeyword($p)) {
        $query_string = $_SERVER['QUERY_STRING'];
        echo "<script>alert('".$feedback."');window.location.href='/graphics/batch_assign_keyword.php?$query_string';</script>";
        exit();
    }
}

if (!empty($_GET)) {
    $search = ImageKeyword::search($_GET, true);

    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
        $smarty->assign('kb', $search['kb']);
        $smarty->assign('show_assign_cb', $search['show_assign_cb']);
    }
}
//print_r($search['kb']);
/* add by liu shu fen 12:43 2008-1-2 */
//get campaign date_start, date_end, category_id 

$cond = array('campaign_id'=>$_GET['campaign_id']);
$fields = array('category_id', 'date_start', 'date_end');
$condition_res = Campaign::getCampaignsByParam($cond, $fields);
if ($condition_res) {
    foreach ($condition_res as $c) {
        $categories[] = $c['category_id'];
        $c_date_start = $c['date_start'];
        $c_date_end = $c['date_end'];
    }
}

// modified by snug xu 2008-01-11 20:36 - STARTED
// mark all busy users and Move up the matched Users
$p = array(
    'category_id'=>$categories,
    'c_date_end'=>$c_date_end,
    'c_date_start'=>$c_date_start,
);
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'designer');
$result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_copy_writer, $_POST['copy_writer_id']);
$smarty->assign('all_copy_writer', $all_copy_writer);
$smarty->assign('copy_writer_options', $result['html']);
// modified by snug xu 2008-01-11 20:36 - END
/* add end */

$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_editor, $_POST['editor_id']);
$smarty->assign('editor_options', $result['html']);
asort($all_editor);  ///ADD BY cxz 2006-8-2 10:26上午
$smarty->assign('all_editor', $all_editor);

$smarty->assign('all_copy_writer', $all_copy_writer);
$all_campaigns = Campaign::getAllCampaigns('campaign_name', '', -1, 2);
$smarty->assign('all_campaigns', $all_campaigns);
$image_types = ImageType::getAllLeafNodes();
$smarty->assign('leaf_types', $image_types);

//**************quick pane**********************//
$campaign_info = Campaign::getInfo($_GET['campaign_id']);
$smarty->assign('campaign_info', $campaign_info);
if (User::getPermission() == 3) { // 2=>3
    $quick_pane[0][lable] = "Reassign Keyword";
    $quick_pane[0][url] = "/client_campaign/list_cpw.php";

    if ($_GET['copy_writer_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $user_info = User::getInfo($_GET['copy_writer_id']);
        $header = substr($header,0,strlen($header)-1);

        $quick_pane[1][lable] = $user_info['user_name'];
        $quick_pane[1][url] = "/graphics/batch_assign_keyword.php";

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        $_SESSION['campaign_url'] = $header;
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }
} else {
    $quick_pane[0][lable] = "Client Campaign Management";
    $quick_pane[0][url] = "/client_campaign/list.php";
    if ($_GET['campaign_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        
        $header = substr($header,0,strlen($header)-1);

        $quick_pane[1][lable] = $campaign_info['company_name'];
        $quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        $_SESSION['campaign_url'] = $header;
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
    }
}
$smarty->assign('quick_pane', $quick_pane);
//**************************quick pane************//


$smarty->assign('image_type', $image_types);
$smarty->assign('image_status', $g_tag['image_status']);
$smarty->assign('campaign_id', $_GET['campaign_id']);
$smarty->assign('feedback', $feedback);
$smarty->assign('result_count', count($search['result']));
$smarty->assign('post_checkbox_array', $post_checkbox_array);
$smarty->display('graphics/batch_assign_keyword.html');
?>