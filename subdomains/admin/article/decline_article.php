<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

if (!user_is_loggedin() && !client_is_loggedin()) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
if (!empty($_POST)) {
    $p = $_POST;
    $keyword_id = explode('|', trim($p['keyword_id'],'|'));
    $p['keyword_id'] = $keyword_id;
    if (!Campaign::denyKeyword($p)) {
        echo "<script>alert('" . $feedback . "');</script>";
    }
    echo "<script>window.location.href='/article/acceptance.php';</script>";
    exit();
}

if (trim($_GET['keyword_id']) == '') {
    echo "<script>alert('Please choose an keyword');</script>";
    echo "<script>window.location.href='/article/acceptance.php';</script>";
    exit;
}
$smarty->assign('decline_reason', $g_decline_reason);
$smarty->assign('keyword_ids', $_GET['keyword_id']);
$smarty->display('article/decline_article.html');
?>