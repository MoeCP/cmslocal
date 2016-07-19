<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$page_url = "/article/article_type.php";
if (count($_POST))
{
    $opt = $_POST['operation'];
    if ($opt == 'save')
    {
        $selected_type = ArticleType::store($_POST);
        if (is_numeric($selected_type) && strlen($_POST['type_id']) == 0)
        {
            echo '<script language="JavaScript">window.location.href=\'' . $page_url . '?type_id=' . $selected_type . '\'</script>';
        }
    }
}
/***get all article type from database***/
$types       =  ArticleType::getAllTypes();
$roots       =  $g_tag['article_type'];
$type_num = count($types);
$types       = array('' => "[Choose Article Type]") + $types;
$roots = array('-1' => "[No Parent]") + $roots;
$smarty->assign('article_types', $types);
$smarty->assign('type_num', $type_num);
// get selected article type
$selected_type = $_GET['type_id'];
$smarty->assign('selected_type', $selected_type);
// get article type info by type id
$info = strlen($selected_type) ? ArticleType::getTypeByID($selected_type) : array();
if (!empty($info) && $info['parent_id'] >= 0) {
    $smarty->assign('parent_article_type', $roots[$info['parent_id']]);
}
$smarty->assign('info', $info);
$smarty->assign('feedback', addslashes($feedback));
$smarty->assign('page_url', $page_url);
$smarty->assign('roots', $roots);
$smarty->display('article/article_type.html');
?>