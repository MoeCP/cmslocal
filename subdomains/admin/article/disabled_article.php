<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
if( !$is_ajax )
{
	require_once('../cms_menu.php');
}
$permission = User::getPermission();
$role = User::getRole();
if (!user_is_loggedin() || $permission < 1) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
$query_string = $_SERVER['QUERY_STRING'];
if ($permission == 1 || $permission == 3) {
    $return_link = '/article/article_list.php';
} else if ($permission == 4 || $permission == 5) {
    $return_link  = '/client_campaign/keyword_list.php';
}
//$conn->debug = true;
if (!empty($_POST)&& $_POST['article_id'] && $_POST['keyword_id']) {
    $campaign_id = Article::disabledAarticle($_POST);
    echo "<script>alert('" . $feedback. "');</script>";
    if ($campaign_id > 0) {
        $return_link .= '?campaign_id=' . $campaign_id;
    }
    echo "<script>window.location.href='{$return_link}';</script>";
}  else {
    echo "<script>alert('Please choose an article');</script>";
    echo "<script>window.location.href='{$return_link}';</script>";
}
exit;
?>