<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Category.class.php';
require_once CMS_INC_ROOT . '/Campaign.class.php';

// let users who role is copywriter access this page
if (!user_is_loggedin() && (User::getPermission() != 1)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
if (isset($_REQUEST['campaign_id']) ) {
    $cid = $_REQUEST['campaign_id'];
} else {
    $cid = -1;
}
//get the copywriter id and category
$copywriter_id = isset($_GET['copywriter_id']) ? $_GET['copywriter_id'] : User::getID();
$category = Category::getUserCategoryInfo(array('copywriter_id'=>$copywriter_id));

if ($category) {
    foreach ($category as $cat) {
        $cat_arr = $cat;
    }
    $field = array('campaign_id', 'campaign_name');
    //get campaigns with the same category as copywriter
    $campaign_res = Campaign::getCampaignByCampaignId($field, array('category_id'=>$cat_arr));
    if ($campaign_res) {
        foreach ($campaign_res as $c) {
            $c_ids[] = $c['campaign_id'];
            $campaign_names[$c['campaign_id']] = $c['campaign_name'];
        }
        if (isset($_REQUEST['campaign_id']) && !empty($_REQUEST['campaign_id'])) {
            $cid = $_REQUEST['campaign_id'];
            //check whether the campaign copywriter selected have the same category as his
            if (in_array($cid, $c_ids)) {
                //not change the $cid value
            } else {
                $cid= -1;
            }
        } else {
            $cid = -1 ;
        }
        $smarty->assign('campaign_names', $campaign_names);
        $smarty->assign('camp_id', $cid);
        $keyword_res = Campaign::getUnassignedKeyword(array('campaign_id'=> $cid));
    }
} else {
    $smarty->assign('not_select',"You haven't selected a category!");
}


if (count($_POST)) {
//    echo "<pre>";
//    print_r($_POST);
//    die('as');
    if (isset($_POST['copywriter_id']) && !empty($_POST['copywriter_id']) && User::getRole() == 'copy writer') {
        
        if (isset($_POST['isUpdate']) && !empty($_POST['isUpdate'])  ) {
            $k_num = count($_POST['isUpdate']);
            if ($k_num > 25 ) {
                echo "<script>alert('Choose keywords less than 25! Please choose again!');</script>";
                echo "<script>window.location.href='/client_campaign/unassigned_keyword.php?campaign_id=" . $cid . "';</script>";
            }
        }
        //store the record
        if (Campaign::assignKeywordByCp($_POST)) {
            echo "<script>alert('succeed');</script>";
            echo "<script>window.location.href='/client_campaign/unassigned_keyword.php?campaign_id=" . $cid . "';</script>";
        }
    }
}
$smarty->assign('selected_c', $_REQUEST['campaign_id']);
$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', array(0=>'[choose]') + $campaign_list);
$smarty->assign('feedback', $feedback);
$smarty->assign('total', count($keyword_res));
$smarty->assign('copywriter_id', $copywriter_id);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->assign('keyword_info', $keyword_res);
$smarty->display('client_campaign/unassigned_keyword.html');
?>