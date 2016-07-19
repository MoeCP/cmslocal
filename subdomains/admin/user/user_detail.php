<?php
//$g_current_path = "preference";
$g_current_path = "my_account";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$login_role = User::getRole();
$login_id = User::getID();
$user_id = isset($_GET['user_id']) && $_GET['user_id'] > 0?$_GET['user_id']:User::getID();
if ($login_role == 'copy writer' || $login_role == 'editor') {
    $user_id = User::getID();
}
require_once CMS_INC_ROOT.'/Candidate.class.php';
$user_info = User::getInfo($user_id);

$form_submitted = $user_info['form_submitted'];
$selected = array();
foreach ($form_submitted as $v) {
    $selected[] = $g_tag['forms_submitted'][$v];
}
$user_info['form_submitted'] = implode('|', $selected);
// get candidate
if ($user_info['candidate_id'] > 0) {
    $candidate = Candidate::getInfo($user_info['candidate_id']);
    $smarty->assign('candidate', $candidate);
    $smarty->assign('cpermissions', $g_tag['candiate_permission']);
    $smarty->assign('user_levels', $g_user_levels);
}
// get notes
require_once CMS_INC_ROOT . '/user_note_category.class.php';
require_once CMS_INC_ROOT . '/user_note.class.php';
$categories = UserNoteCategory::getList();
$notes = UserNote::search($_GET, false);
$smarty->assign('ucategories', $categories);
if (!empty($notes)) {
    $smarty->assign('notes', $notes);
}
// get payment history
require_once CMS_INC_ROOT.'/article_type.class.php';
$g_article_types = $g_tag['article_type'];
$smarty->assign('g_article_types', $g_article_types);
$smarty->assign('total_type', count($g_article_types));
$user_id =  $user_info['user_id'];
$p = array('user_id' => $user_id, 'payment_flow_status' => 'paid', 'invoice_status' => 1);
$payment_histories =  User::getAllPaymentHistory($p, false);
$smarty->assign('histories', $payment_histories['result']);
$smarty->assign('stats', $payment_histories['stats']);
// get education & certification
require_once CMS_INC_ROOT.'/Category.class.php';
$cate = new Category();
$categories = $cate->getAllSelectedCategoryByUserid($user_id);
foreach ($categories as $k => $row) {
    $total = 1;
    if (!empty($row['children'])) {
        $total  += count($row['children']);
    }
    $categories[$k]['total_row'] = $total;
}

$smarty->assign('categories', $categories);
$smarty->assign('g_user_levels', $g_user_levels);
// get availability
$available = User::getAllAvailableCopyWriterByUserID($user_id);
$smarty->assign('available', $available);
// get peformance
require_once CMS_INC_ROOT.'/UserMonthRanking.class.php';
$peformance = UserMonthRanking::getPerformanceReportNoPage(array('user_id' => $user_id));

// get e-sign details
require_once CMS_INC_ROOT.'/UserEsign.class.php';
require_once CMS_INC_ROOT.'/UserEsignGroup.class.php';
require_once CMS_INC_ROOT.'/UserEsignLog.class.php';
$egroups = UserEsignGroup::search(array('user_id' => $user_id), false);
$smarty->assign('estatuses', $g_estatuses);
$smarty->assign('egroups', $egroups);

$smarty->assign('peformance', $peformance);
$smarty->assign('user_info', $user_info);
$smarty->assign('login_role', $login_role);
$smarty->assign('login_id', $login_id);
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$smarty->assign('payment_preference', $g_tag['payment_preference']);
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->assign('maxsize', ini_get('upload_max_filesize'));
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('user_types', $g_tag['user_type']);
$smarty->display('user/user_detail.html');
?>