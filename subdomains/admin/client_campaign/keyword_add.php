<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//����������Ϣ
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 16:19
// let the user who role is agency add keywords
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaignKeyword.class.php';
require_once CMS_INC_ROOT.'/OrderKeywordXrefCampaign.class.php';
require_once CMS_INC_ROOT.'/custom_field.class.php';

if (trim($_POST['campaign_id']) != '' && trim($_POST['keyword']) != '') {
    // added by nancy xu 2011-01-06 15:14
    $order_id = $_POST['order_id'];
    unset($_POST['order_id']);
    // end
    if (Campaign::addKeyword($_POST)) {
        $parent_id = isset($_POST['parent_id']) && !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
        if ($parent_id > 0) Campaign::setCampaignFieldsById(array('is_import_kw' => 1), $_POST['campaign_id']);
        if ($order_id > 0) {
            $keyword_id = OrderCampaignKeyword::getIDByOrderId($order_id);
            OrderCampaignKeyword::store(array('keyword_id' => $keyword_id, 'is_parsed' => 1));
        }
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/keyword_list.php?campaign_id=".$_POST['campaign_id']."';</script>";
        exit;
    } else {
        echo "<script>alert('".$feedback."');</script>";
    }
}

if (trim($_GET['campaign_id']) == '') {
    echo "<script>alert('Please choose a campaign');</script>";
    echo "<script>window.location.href='/client_campaign/list.php';</script>";
    exit;
}
$campaign_id = $_GET['campaign_id'];

//************************* quick pane **********************************//
$quick_pane[0][lable] = "Campaign Management";
$quick_pane[0][url] = "/client_campaign/client_list.php";
$keyword_info = array();
if ($campaign_id) {
    
    //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
    $header = $_SERVER['PHP_SELF']."?";
    for ($i=0; $i<count($_GET); $i++)
    {
        $header .= key($_GET)."=".$_GET[key($_GET)]."&";
        next($_GET);
    }
    $campaign_info = Campaign::getInfo($campaign_id);
    $keyword_info['article_type'] = $campaign_info['article_type'];
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
$smarty->assign('quick_pane', $quick_pane);
//************************* end quick pane ******************************//

$smarty->assign('campaign_info', $campaign_info);

//$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
//$smarty->assign('all_editor', $all_editor);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager');
$all_editor += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin');
$smarty->assign('all_editor', $all_editor);
$smarty->assign('all_qaer', User::getAllUsers($mode = 'id_name_only', $user_type = 'admin', false));
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$smarty->assign('all_copy_writer', $all_copy_writer);
if (!empty($_POST)) {
    $keyword_info = $_POST;
} else {
    // added by nancy xu 2010-12-28 17:49
    // if the campaign is created from order campaign and it's is first time add keyword to campaign
    $order_id = OrderCampaign::getOrderCampaignIDByCampaignId($campaign_id);
    if ($order_id > 0) {
        $keyword_id = OrderKeywordXrefCampaign::getKeywordIDByCampaignId($campaign_id);
        if (empty($keyword_id)) {
            $info = OrderCampaignKeyword::getInfoByOrderId($order_id);
            if (!empty($info) && $info['is_parsed'] == 0) {
                foreach ($info as $k => $v) {
                    if (!empty($v) && substr($k,0,8)=='optional') {
                        $keyword_info[$k] = implode("\n", $v);
                    }
                }
                /*if (!empty($info['optional1'])) $keyword_info['optional1'] = implode("\n", $info['optional1']);
                if (!empty($info['optional2'])) $keyword_info['optional2'] = implode("\n", $info['optional2']);
                if (!empty($info['optional3'])) $keyword_info['optional3'] = implode("\n", $info['optional3']);
                if (!empty($info['optional4'])) $keyword_info['optional4'] = implode("\n", $info['optional4']);*/
                $smarty->assign('order_id', $order_id);
            }
        }
    }
    // end
}
$parent_id = isset($_GET['pid']) && !empty($_GET['pid']) ? $_GET['pid'] : 0;
if (empty($_POST) && $campaign_id && $parent_id) {
    if ($parent_id == $campaign_info['parent_id'] && $campaign_info['is_import_kw'] == 0) {
        $smarty->assign('parent_id', $parent_id);
        $keyword_info = Campaign::getBatchKeywrodInfo($parent_id);
    } else {
        if ($campaign_info['is_import_kw'] == 1) {
            $feedback = 'This campaign was replicated all keywords from previous keywrods';
        } else {
            $feedback = 'You specify the wrong parent of this campaign, please to check';
        }
        echo "<script>alert('" . $feedback . "');</script>";
        echo "<script>window.location.href='/client_campaign/list.php';</script>";
        exit();
    }
}
if (!isset($keyword_info['date_end']) || empty($keyword_info['date_end'])) {
    $keyword_info['date_end'] = date("Y-m-d", strtotime("+7 days"));
}
if (empty($_POST)) {
    $keyword_info['keyword_description'] = $campaign_info['keyword_instructions'];
}
if (!isset($keyword_info['date_start']) || empty($keyword_info['date_start'])) {
    $keyword_info['date_start'] = date("Y-m-d");
}
$smarty->assign('keyword_info', $keyword_info);

// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$optional_fields = CustomField::getFieldLabels($campaign_info['client_id'], 'optional');
$smarty->assign('optional_fields', $optional_fields);
$smarty->assign('total_optional', count($fields));
// end

//$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_inactive' => 0)));
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/keyword_form.html');
?>