<?php
if( isset( $_GET['is_ajax'] ) )
{
	$is_ajax = $_GET['is_ajax'];
}

$g_current_path = "article";
require_once('../pre.php');//加载配置信息
if( !$is_ajax )
{
	require_once('../cms_menu.php');
}
$permission = User::getPermission();
if (!user_is_loggedin() || $permission < 1) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/DomainTag.class.php';
if (!empty($_POST)) {
    $p = $_POST;
    $opt = $p['opt'];
    unset($p['opt']);
    $article_id = $p['article_id'];
    $p['source'] = ArticleTag::getSourceByArticleId($article_id);
    
    if ($opt == 'add') {
        ArticleTag::save($p);
    } else {
        ArticleTag::del($p);
    }
}
exit();
?>