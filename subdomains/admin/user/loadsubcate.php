<?php
require_once '../pre.php';
$g_current_path = 'home';
require_once '../cms_menu.php';
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Email.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
global $feedback;
$cate = new Category();
$pid = $_GET['pid'];
$selected = $_GET['selected'];
$categories = $cate->getAllCategoryByUserid(0,$pid);
//pr($categories, true);
$list = array('0' => 'Select');
foreach ($categories[$pid]['children'] as $k => $row) {
    $list[$row['category_id']] = $row['category'];
}

$smarty->assign('list', $list);
$smarty->assign('selected', $selected);
$smarty->display('user/loadsubcate.html');
?>