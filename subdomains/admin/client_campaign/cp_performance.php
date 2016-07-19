<?php
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// let users who role is agency access this page
if (!user_is_loggedin() && (User::getPermission() < 5)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/article_score.class.php';
require_once CMS_INC_ROOT.'/UserMonthScore.class.php';
  
$search = ArticleScore::search($_GET);
if (!empty($search)) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('total', $search['total']);
}

$monthes = UserMonthScore::getAllMonthes();
$monthes = array('' => '[choose month]') + $monthes;
$smarty->assign('monthes', $monthes);
$all_cp = array(''=>'[choose copy writer]') + User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$smarty->assign('all_cp', $all_cp);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/cp_performance.html');
?>