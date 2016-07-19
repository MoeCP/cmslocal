<?php
require_once('../pre.php');//╪стьеДжцпео╒
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

if (!user_is_loggedin() ||  User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (isset($_GET['candidate_id']) && !empty($_GET['candidate_id'])) {
    $candidate_res = Candidate::getInfo($_GET['candidate_id']);
}

//$smarty->assign('user_levels', $g_user_levels);
$smarty->assign('user_levels', $g_tag['candidate_levels']);
$smarty->assign('sample_types', $g_tag['candidate_sample_types']);
$smarty->assign('candidate_plinks', $g_tag['candidate_plinks']);
$smarty->assign('login_role', User::getRole());
$smarty->assign('cpermissions', $g_tag['candiate_permission']);
$smarty->assign('candidate_res', $candidate_res);
$smarty->display('user/candidate_detail_info.html');
?>