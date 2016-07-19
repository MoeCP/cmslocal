<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Notes.class.php';

if (trim($_GET['keyword_id'])) {//只针对单个assign
	if (trim($_POST['editor_id']) != '' && trim($_POST['keyword_id']) != '' 
	 && trim($_POST['copy_writer_id']) != '' &&trim($_POST['campaign_id']) != '' ) {
		if (Campaign::assignKeyword($_POST)){
//			//sql_log();
//            echo "<script>alert('".$feedback."');</script>";
//            echo "<script>window.location.href='/client_campaign/keyword_list.php';</script>";
//            exit;		
            if( Notes::store($_POST) )
            {
                echo "<script>alert('".$feedback."');</script>";
                $from_page = isset($_GET['frm']) && $_GET['frm'] ? $_GET['frm']:'';
                if ($from_page == 'acceptance') {
                    $link ="/article/acceptance.php?campaign_id={$_POST['campaign_id']}";
                } else {
                    $link ="/client_campaign/keyword_list.php?campaign_id={$_POST['campaign_id']}";
                }
                echo "<script>window.location.href='{$link}';</script>";
                exit;
            }
		}

	}
}

$keyword_info = Campaign::getKeywordInfo($_GET['keyword_id']);
$smarty->assign('keyword_info', $keyword_info);
$note_info = Notes::getNotesInfoByKeywordID($_GET['keyword_id']);
$smarty->assign('note_info', $note_info);

//$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
//$smarty->assign('all_editor', $all_editor);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$smarty->assign('all_editor', $all_editor);
$all_qaer = User::getAllUsers($mode = 'id_name_only', $user_type = 'admin', false);
$smarty->assign('all_qaer', $all_qaer);
$result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_qaer, $keyword_info['qaer_id']);
$smarty->assign('qaer_options', $result['html']);
$p = array(
    'c_date_start' => $keyword_info['date_start'], 
    'c_date_end' => $keyword_info['date_end'], 
);

$result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_editor, $keyword_info['editor_id']);
$smarty->assign('editor_options', $result['html']);
// modified by snug xu 2008-01-12 0:13  - STARTED
// $all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$smarty->assign('all_copy_writer', $all_copy_writer);
$result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_copy_writer, $keyword_info['copy_writer_id']);
$smarty->assign('copy_writer_options', $result['html']);
// modified by snug xu 2008-01-12 0:13  - END

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
    $header = substr($header,0,strlen($header)-1);
    //setcookie("campaign_label", $campaign_info['campaign_name'], time()+36000, '/');
    //setcookie("campaign_url", $header, time()+36000, '/');
    //echo $header;
    $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
    $_SESSION['campaign_url'] = $header;
}
if (isset($_SESSION['campaign_lable'])) {
	$quick_pane[1][lable] = $_SESSION['campaign_lable'];
	$quick_pane[1][url] = $_SESSION['campaign_url'];
}

if ($_GET['keyword_id']) {
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
	$quick_pane[2][lable] = $keyword_info['keyword'];
	$quick_pane[2][url] = $header;
}
$smarty->assign('quick_pane', $quick_pane);
//print_r($quick_pane);
//########quick pane########//

$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('feedback', $feedback);
$fields = CustomField::getFieldLabels($campaign_info['client_id'], 'optional');
$smarty->assign('fields', $fields);
$smarty->display('client_campaign/assign_keyword.html');

//$search = Campaign::search($_GET);
//if ($search) {
//    $smarty->assign('result', $search['result']);
//    $smarty->assign('pager', $search['pager']);
//    $smarty->assign('total', $search['total']);
//}
//
//$smarty->assign('keyword_info', $keyword_info);
//$smarty->assign('feedback', $feedback);
//$smarty->display('client_campaign/assign_keyword.html');
?>