<?php
$g_current_path = "preference";
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 4) { // 2=>3
	    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
		exit;
}
//require_once CMS_INC_ROOT.'/Campaign.class.php';
//require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/system_mails.class.php';
set_time_limit(0);
if (trim($_POST['mailbody']) != '' && trim($_POST['newRight']) != '') {
    $data = array(
        'to_ids' => $_POST['newRight'],
        'email_event' => $_POST['email_event'],
        'subject' => $_POST['subject'],
        'mailbody' => $_POST['mailbody'],
    );

    if ($_POST['cc_email'] > 0) {
        $cc_info = User::getInfo($_POST['cc_email']); 
        $data['cc_email'] = $cc_info['email'];
    }

    if ($_POST['from_email'] > 0) {
        $from_info = User::getInfo($_POST['from_email']); 
        $data['from_email'] = $from_info['email'];
    }
    
    $filenames = $_POST['filenames'];
    $attachments = $_POST['attachments'];
    $files = array();
    if (!empty($attachments) && is_array($attachments)) {
        foreach ($attachments as $k => $name) {
            $files[] = array(
                'file' => $g_article_storage . $name,
                'filename' => $filenames[$k],
            );
        }
        $data['attachments'] = serialize($files);
    }
    $data['login_link'] = 'http://' . $_SERVER['HTTP_HOST'];

    SystemMails::splitReceivers($data);

    echo "<script>alert('Succeed');</script>";
    echo "<script>window.location.href='/mail/ck_mailer.php';</script>";
    exit();
}

$roles = $g_tag['user_permission'];
$users = array();

foreach ($roles as $k => $role) {
    if ($role != 'agency') {
        $users[$k] = User::getAllUsers('id_name_only', $role, true);
        if (empty($users[$k])) $users[$k] = array();
    }
}
$all_editor = $users[5] + $users[4] + $users[3];
$all_copy_writers = $users[1];
$all_system = $all_editor + $all_copy_writers;
$smarty->assign('all_system', $all_system);
$smarty->assign('all_editor', $all_editor);
$ccm = $_GET['ccm'];
if ($ccm == 'all_user') {
   $all_list = $all_system;
} elseif ($ccm == 'all_e') {
    $all_list = $all_editor;
} elseif ($ccm == 'all_ewaa') {
    $all_list = array();
    foreach ($roles as $k => $role) {
        if ($role != 'agency' && $role != 'copy writer') {
            $arr = User::getAllUsers('id_active_only', $role, true);
            if (!empty($arr) && is_array($arr)) $all_list += $arr;
        }
    }
} elseif ($ccm == 'all_cp') {
    $all_list = $all_copy_writers;
} elseif ($ccm == 'all_waa') {
    $all_list = $arr = User::getAllUsers('id_active_only', 'copy writer', true);;
} else {
    $all_list = $all_system;
}
$smarty->assign('maxsize', ini_get('upload_max_filesize'));
$smarty->assign('email_event', $g_tag['email_event']);
$smarty->assign('all_list', $all_list);
$list1 = isset($_POST['list1']) ? $_POST['list1'] : (isset($_GET['list1'])?$_GET['list1']:null);
$smarty->assign('list1', $list1);
$smarty->assign('feedback', $feedback);
$smarty->assign('onload', 'onLoad="opt.init(document.forms[0])"');
$smarty->display('mail/ck_mailer.html');
?>
