<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT . '/article_type_question.class.php';

if (!empty($_POST)) {
    if (ArticleTypeQuestion::storeBatchData($_POST)) {
        echo "<script>alert('" .$feedback. "');window.location.href='/article/article_type_questions.php'</script>";
        exit();
    } else {
        $smarty->assign('feedback' , $feedback);
    }
    $smarty->assign('info', $_POST);
}
//$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_inactive' => 0)));
$smarty->display('article/type_question_add.html');
?>