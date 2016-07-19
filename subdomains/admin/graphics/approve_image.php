<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/campaign_notes.class.php';

if( !$is_ajax )
{
	if (client_is_loggedin()) {
		$g_current_path = "client_campaign";
		require_once('../client/cms_client_menu.php');
	} else {
		require_once('../cms_menu.php');
	}
}

if ((!user_is_loggedin() || User::getPermission() < 3) && !client_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_comment.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';
$query_string = $_SERVER['QUERY_STRING'];
$current_url = "/graphics/approve_image.php?is_ajax=1&" . $query_string;
$from_page = $_GET['fmp'];
$role = User::getRole();
if (trim($_POST['image_id']) != ''  && trim( $_POST['approve_action'] ) != '') {
    // pr($_GET, true);
    $image_id = $_POST['image_id'];
    $_SESSION['autocomment'] = array($image_id=>'');
    $campaign_id = !empty($_GET['campaign_id']) ? $_GET['campaign_id'] : $_POST['campaign_id'];
    $p = $_POST;
	$action = $_POST['approve_action'];
    $is_ajax = $_GET['is_ajax'];
    $result = Image::approveImage($_POST);
    if ($is_ajax) {
        if (!$result) {
            echo $feedback;
        } else {
            if ($action == 'save') {
                echo $feedback;
            } else if ($action == 'reject') {
                $str = '<script language="JavaScript">';
                $str .= "$('div_button').innerHTML = '";
                $str .= '<input type="button"  class="button" value="Save" onclick="doAction(';
                $str .= "\'save\', \'{$current_url}\'";
                $str .= ');">&nbsp;';
                if (user_is_loggedin()) {
                    $str .= '<input type="button"  class="button" value="Request Edit" onclick="doAction(';
                } else {
                    $str .= '<input type="button"  class="button" value="Reject" onclick="doAction(';
                }
                $str .= "\'reject\', \'{$current_url}\'";
                $str .= ');">&nbsp;';
                $str .= '<input type="button"  class="button" value="Force Approve" onclick="doAction(';
                $str .= "\'force\', \'{$current_url}\'";
                $str .= ');">&nbsp;';
                $str .= "';";
                $str .= "$('image_status').value = '2' ";
                $str .= '</script>';
                $str .= 'Edit Request Submitted';
                echo $str;
            } else {
                echo "<script>alert('".$feedback."');</script>";
                echo locationImageString($role, $from_page, $campaign_id);
            }
        }
    } else {
        echo "<script>alert('".$feedback."');</script>";
        echo locationImageString($role, $from_page, $campaign_id);
    }
    exit();
}


if (trim($_GET['image_id']) == '' || trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an image');</script>";
    echo locationImageString($role, $from_page);
    exit;
}

$keyword_info = ImageKeyword::getInfo($_GET['keyword_id']);
//$smarty->assign('keyword_info', $keyword_info);

$keyword_info += Image::getInfo($_GET['image_id'], true);;
// added by nancy xu 2011-03-15 17:45
$image_id = $keyword_info['image_id'];
$smarty->assign('image_id', $image_id);
//end

//---------------------quick pane-------------------//
if (user_is_loggedin()) {
    if (User::getPermission() >= 4) { // 2=>3
        $quick_pane[0][lable] = "Campaign Management";
        $quick_pane[0][url] = "/client_campaign/client_list.php";
    } else {
        $quick_pane[0][lable] = "Campaign & Image Management";
        $quick_pane[0][url] = "/graphics/designer_campaign_list.php";
    }
    $campaign_id = $_GET['campaign_id'];
    if (empty($campaign_id)) $campaign_id = $keyword_info['campaign_id'];
    if ($campaign_id > 0) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $campaign_info = Campaign::getInfo($campaign_id);
        $header = substr($header,0,strlen($header)-1);
        if (User::getPermission() > 3) {
            if (User::getPermission() >= 3) { // 2=>3
                $quick_pane[1][lable] = $campaign_info['company_name'];
                if (User::getPermission() == 3) {
                    $quick_pane[1][url] = '/graphics/designer_campaign_list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
                } else {
                    $quick_pane[1][url] = '/client_campaign/list.php?client_id='.$campaign_info['client_id'].'&company_name='.$campaign_info['company_name'];
                }
            }
        }

        $_SESSION['campaign_lable'] = $campaign_info['campaign_name'];
        if ($_GET['keyword_id'] == '') {
            $_SESSION['campaign_url'] = $header;
        }
    }
    if (isset($_SESSION['campaign_lable'])) {
        $quick_pane[2][lable] = $_SESSION['campaign_lable'];
        $quick_pane[2][url] = $_SESSION['campaign_url'];
        if (!empty($_SESSION['campaign_url'])) {
            if (User::getPermission() == 3) { 
                $quick_pane[2][url] = '/graphics/image_list.php?campaign_id='.$campaign_info['campaign_id'];
            } elseif (User::getPermission() > 3) { 
                $quick_pane[2][url] = '/client_campaign/image_keyword_list.php?campaign_id='.$campaign_info['campaign_id'];
            }
        }
    }

    if ($_GET['keyword_id']) {
        //$header = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
        $header = $_SERVER['PHP_SELF']."?";
        reset($_GET);
        for ($i=0; $i<count($_GET); $i++)
        {
            $header .= key($_GET)."=".$_GET[key($_GET)]."&";
            next($_GET);
        }
        $quick_pane[3][lable] = $keyword_info['keyword'];
        $quick_pane[3][url] = $header;
    }
}
$smarty->assign('quick_pane', $quick_pane);
//----------------------quick pane----------------------//

$smarty->assign('feedback', $feedback);
$login_role = User::getRole();
if (client_is_loggedin()) {
    $smarty->assign('login_role', 'client');
} else {
    $smarty->assign('login_role', $login_role);
}


$general_note_subjects = CampaignNotes::getGeneralNotes(array('single_column'=>'subject'));
$general_note_bodies = CampaignNotes::getGeneralNotes(array('single_column'=>'body'));

$smarty->assign('general_note_subjects', $general_note_subjects);
$smarty->assign('autocomment', $_SESSION['autocomment'][$image_id]);
$smarty->assign('general_note_bodies', $general_note_bodies);
$smarty->assign('url', $current_url);
//add 
$smarty->assign('number', 1);
$smarty->assign('comment_count', count($keyword_info['comment']));
$smarty->assign('image_type', ImageType::getAllLeafNodes());

// added by nancy xu 2012-04-20 16:26
require_once CMS_INC_ROOT.'/custom_field.class.php';
$client_id = $keyword_info['client_id'];
$optional_fields = CustomField::getFieldLabels($client_id, 'optional');
$smarty->assign('optional_fields', $optional_fields);
$keyword_info = showLinkForOptionalFields($optional_fields, $keyword_info);
$smarty->assign('keyword_info', $keyword_info);
// end
$smarty->assign('search_choice', $g_tag['search_choice']);
//
if( !$is_ajax )
	$smarty->display('graphics/approve_image.html');
?>