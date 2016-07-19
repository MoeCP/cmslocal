<?php
$g_current_path = "preference";
//$g_current_path = "account";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Category.class.php';

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$cate = new Category();
if (trim($_GET['category_id']) != '') {
    $cate_id = addslashes(trim($_GET['category_id']));
}
if (trim($_POST['category']) != '') {
    if (Category::store($_POST)) {
        $feedback = 'Success';
        echo "<script>alert('".$feedback."');</script>";
        if (!isset($cate_id) && !empty($_POST['category_id'])) {
            $cate_id = $_POST['category_id'];
        }
        $url = '/category/add.php' . (isset($cate_id) ? '?category_id=' . $cate_id : '');
        echo "<script>window.location.href='{$url}';</script>";
        exit;
    }
}

if (isset($cate_id)) {
    $category_info = $cate->getInfo($cate_id);
}

if (empty($category_info)) {
    $category_info = $_POST;
}
$result = $cate->search();
$smarty->assign('categories', $result['result']);
$smarty->assign('pager', $result['pager']);
$smarty->assign('total', $result['total']);
$smarty->assign('count', $result['count']);
$parents = array(0=> 'No Parent') + $cate->getAllCategoryByParentId(0);
$smarty->assign('parents', $parents);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('category_info', $category_info);
$smarty->assign('feedback', $feedback);
$smarty->display('category/store.html');
?>