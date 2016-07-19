<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}


require_once CMS_INC_ROOT . '/user_note_category.class.php';
require_once CMS_INC_ROOT . '/user_note.class.php';


$user_id = isset($_GET['user_id'])&&!empty($_GET['user_id']) ? $_GET['user_id'] : '';
if (!empty($_POST)) {
    $result = UserNote::store($_POST);
    echo "<script>alert('".$feedback."');</script>";
    $from = isset($_GET['f']) &&  !empty($_GET['f']) ? $_GET['f'] : '';
    if (empty($from)) {
        if ($user_id > 0) {
            echo "<script>window.location.href='/user/list.php';</script>";
        } else {
            echo "<script>window.location.href='/user/notes.php';</script>";
        }
    } else if ($from=='detail') {
        echo "<script>window.location.href='/user/user_detail.php?user_id=" . $user_id . "#notes';</script>";
    }
    exit();
}

$categories = UserNoteCategory::getList();
$users = User::getAllUsers($mode = 'id_name_only', $user_type = 'editor');
$users += User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer');
$note = $_POST;
$today = date("Y-m-d H:i:s");
$loginId = User::getID();
$note['created'] = $today;
$note['modified'] = $today;
$note['created_by'] = $loginId;
$note['modified_by'] = $loginId;
$smarty->assign('user_info', $note);
$smarty->assign('user_id', $user_id);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('users', $users);
$smarty->assign('user_id', $user_id);
$smarty->assign('categories', $categories);
$smarty->assign('feedback', $feedback);
$smarty->assign('request_uri', $_SERVER['REQUEST_URI']);
$smarty->display('user/ajax_note_form.html');
?>