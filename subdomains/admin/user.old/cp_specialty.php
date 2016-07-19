<?php
$g_current_path = "user";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/UserCategory.class.php';

if (!user_is_loggedin() || User::getPermission() <= 3) { 
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$search = UserCategory::userCategories($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$all_cat = Category::getAllCategoryByCategoryId();
$smarty->assign('cp_interests', array(0=>'All') + $all_cat);
$smarty->assign('g_user_levels', $g_user_levels);
$smarty->assign('feedback', $feedback);
$smarty->display('user/cp_specialty.html');
exit();

if (count($_GET)) {
    if (isset($_GET['cp_category']) && !empty($_GET['cp_category'])) {
        $cat_selected = $_GET['cp_category'];
    }
    $smarty->assign('cat_selected', $cat_selected);
}
//get all the records in user_categories table
$cp_cat_res = Category::getUserCategoryInfo(array('category_id'=>$cat_selected));

if ($cp_cat_res) {
    foreach ($cp_cat_res as $key => $cat) {
        $cp_ids[] = $key;
        $info[$key] = $cat;
    }
}
//get all copywriter information
$cp_res = User::getAllUsers('all_infos', 'copy writer');
if ($cp_res && $cp_ids) {
    foreach ($cp_res as $cp) {
        //check the copywriter has category in user_categories table
        if (in_array($cp['user_id'], $cp_ids)) { 
            $cat_id = $info[$cp['user_id']];
            //get all the categoies belongs to that copywriter
            //$catogies is an array
            $catogies = Category::getAllCategoryByCategoryId($cat_id, true);
            if (empty($catogies)) {
                continue;
            } else {
                $copywriter[$cp['user_id']]['category'] = $catogies;
                $copywriter[$cp['user_id']]['user_id']    = $cp['user_id'];
                $copywriter[$cp['user_id']]['user_name']  = $cp['user_name'];
                $copywriter[$cp['user_id']]['first_name'] = $cp['first_name'];
                $copywriter[$cp['user_id']]['last_name']  = $cp['last_name'];
                $copywriter[$cp['user_id']]['sex']        = $cp['sex'];
                $copywriter[$cp['user_id']]['email']      = $cp['email'];
            }
           
        }
    }
}
$all_cat = Category::getAllCategoryByCategoryId();
//fliter: cp interests, this is a drop-down list
$smarty->assign('cp_interests', array(0=>'All') + $all_cat);
$smarty->assign('copywriter', $copywriter);
$smarty->assign('g_user_levels', $g_user_levels);
$smarty->assign('feedback', $feedback);
$smarty->display('user/cp_specialty.html');
?>