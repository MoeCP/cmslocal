<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}


require_once CMS_INC_ROOT . '/CommentsOnOrderCampaign.class.php';
require_once CMS_INC_ROOT . '/OrderCampaign.class.php';


$order_id = isset($_GET['order_id'])&&!empty($_GET['order_id']) ? $_GET['order_id'] : '';
if (!empty($_POST)) {
    $comment = $_POST['comment'];
    $result = CommentsOnOrderCampaign::addComments($comment, 'en', $order_id);
}
$comments = CommentsOnOrderCampaign::getCommentsByOrderId($order_id);
$smarty->assign('order_id', $order_id);
$smarty->assign('comments', $comments);
$info = OrderCampaign::getInfo($order_id);
$smarty->assign('campaign_name', $info['campaign_name']);
$smarty->assign('total_comments',count($comments));
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('users', $users);
$smarty->assign('user_id', $user_id);
$smarty->assign('categories', $categories);
$smarty->assign('feedback', $feedback);
$smarty->assign('request_uri', $_SERVER['REQUEST_URI']);
$smarty->display('client_campaign/ajax_comment_add.html');
?>