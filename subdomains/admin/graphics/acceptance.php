<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/image.class.php';
require_once CMS_INC_ROOT.'/image_type.class.php';
require_once CMS_INC_ROOT.'/image_keyword.class.php';
$logout_folder = '';//the folder of logout.php in
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
    $logout_folder .= '/client';
} else {
    require_once('../cms_menu.php');
}

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if ((!user_is_loggedin() || User::getPermission() == 2) && !client_is_loggedin()) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

if (!empty($_POST)) {
    $permission = User::getPermission();
    if ($permission >= 4) {
        if (!empty($_POST['isUpdate'])) {
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
                       'image_type' => $_POST['image_type'],
                       'date_start' => $_POST['date_start'],
                       'date_end' => $_POST['date_end'],
                       'copy_writer_id' => $_POST['copy_writer_id'],
                        //start modified by snug at 20:53 2006-07-29
                       'note_id' => $note_id,
                       'old_notes' => $old_notes,
                       'notes' => $_POST['notes'],
                       'is_forced' =>0,
                       'is_reserve_content' => $_POST['is_reserve_content'],
                       'campaign_id' => $_GET['campaign_id'],
                        //end
                       'new_or_append' => $_POST['new_or_append'],
                       'editor_id' => $_POST['editor_id']);
            if ($_POST['is_forced_not_free']) $p['is_forced_not_free'] = $_POST['is_forced_not_free'];
            if (Campaign::batchAssignKeyword($p)) {
                $query_string = $_SERVER['QUERY_STRING'];
                if (!empty($query_string)) $query_string = '?' . $query_string;
                echo "<script>alert('".$feedback."');window.location.href='/article/acceptance.php$query_string';</script>";
                exit();
            }
        } else {
            $p = $_POST;
            $operation = $p['operation'];
            if ($operation == 'editorback' ||  $operation == 'writerback' ||  $operation == 'allback') {
                $data = array('keyword_id' => $p['single_keyword_id']);
                $user_status = $p['user_status'];
                $now = date("Y-m-d H:i:s");
                if ($operation == 'editorback') {
                    $data['cp_accept_time'] = $now;
                    $data['editor_status'] = $user_status;
                } else if ($operation == 'writerback') {
                    $data['cp_status'] = $user_status;
                    $data['date_assigned'] = $now;
                    if ($user_status == -1) $data['cp_accept_time'] = null;
                } else if ($operation == 'allback') {
                    $data['cp_status'] = $user_status;
                    $data['editor_status'] = $user_status;
                    $data['date_assigned'] = $now;
                    if ($user_status == -1) $data['cp_accept_time'] = null;
                }
                Campaign::updateKeyword($data);
                $query_string = $_SERVER['QUERY_STRING'];
                if (!empty($query_string)) $query_string = '?' . $query_string;
                echo "<script>alert('".$feedback."');window.location.href='/article/acceptance.php$query_string';</script>";
                exit();
            }
        }
    } else if ($permission == 1.2 || $permission == 3) {
        $p = $_POST;
        if ($p['operation'] == 'assignedAction' ) {
            $data['keyword_id'] = $p['single_keyword_id'];
            if ($permission == 1.2 ) {
                $data['cp_status'] = $p['user_status'];
                $data['cp_accept_time'] = date("Y-m-d H:i:s");
            } else if ($permission == 3) {
                $data['editor_status'] = $p['user_status'];
            }
            Imagekeyword::updateKeyword($data);
            $query_string = $_SERVER['QUERY_STRING'];
            if (!empty($query_string)) $query_string = '?' . $query_string;
            echo "<script>alert('".$feedback."');window.location.href='/graphics/acceptance.php$query_string';</script>";
            exit();
        } else {
            $keyword_id = array();
            foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
                $k = $v_isUpdate - 1;
                $keyword_id = $keyword_id + array($k_isUpdate => $_POST['keyword_id'][$k]);
            }
            $p = array(
                'keyword_id' => $keyword_id
            );
            $user_status=  $_POST['user_status'];
            if ($permission == 1.2) {
                $p['cp_status'] = $user_status;
                $p['cp_accept_time'] = date("Y-m-d H:i:s");
            } else {
                $p['editor_status'] = $user_status;
            }
            Imagekeyword::batchUpdateKeyword($p);
            $query_string = $_SERVER['QUERY_STRING'];
            if (!empty($query_string)) $query_string = '?' . $query_string;
            echo "<script>alert('".$feedback."');window.location.href='/graphics/acceptance.php$query_string';</script>";
            exit();
        }
    }
}

if (!empty($_GET) || User::getPermission() == '1.2' || User::getPermission() == 3) {
    $search = ImageKeyword::getAssignedKeywords($_GET);
    if ($search) {
        $smarty->assign('result', $search['result']);
        $smarty->assign('pager', $search['pager']);
        $smarty->assign('total', $search['total']);
        $smarty->assign('count', $search['count']);
        $smarty->assign('show_cb', $search['show_cb']);
    }
}

if (user_is_loggedin()) {
    $smarty->assign('user_id', User::getID());
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_permission', User::getPermission());
} 

$smarty->assign('assign_statuses', $g_assign_status);
$smarty->assign('image_type', ImageType::getAllLeafNodes());

if (User::getPermission() >= 4) { 
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

    //########quick pane########//
 
    $all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
    asort($all_editor);
    $smarty->assign('all_editor', $all_editor);
    $all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'designer', false);
    $smarty->assign('all_copy_writer', $all_copy_writer);
    $smarty->assign('campaign_id', $_GET['campaign_id']);
    $smarty->assign('query_string', http_build_query($_GET));
    $client_id = isset($_GET['client_id'])&&$_GET['client_id'] > 0? $_GET['client_id'] : '';
    $all_campaigns = Campaign::getAllCampaigns('campaign_name', $client_id,-1, 2);
    $smarty->assign('all_campaigns', $all_campaigns);
    $clients = Client::getAllClients('id_name_only', false);
    asort($clients);
    $smarty->assign('all_clients', $clients);
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
    $p = array(
        'category_id'=>$categories,
        'c_date_end'=>$c_date_end,
        'c_date_start'=>$c_date_start,
    );
    $result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_copy_writer, $_POST['copy_writer_id']);
    $smarty->assign('copy_writer_options', $result['html']);
    $result = User::markBusyUsersAndMoveUpMatchedUsers($p, $all_editor, $_POST['editor_id']);
    $smarty->assign('editor_options', $result['html']);
}
$smarty->assign('image_statuses', $g_tag['image_status']);
$smarty->display('graphics/acceptance.html');
?>