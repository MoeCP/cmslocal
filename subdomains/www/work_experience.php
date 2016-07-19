<?php
require_once 'pre.php';
$g_current_path = 'home';
require_once 'cms_menu.php';
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Email.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
global $feedback;
$cate = new Category();
$categories = $cate->getAllCategoryByUserid(0,0);
$cid = $_GET['cid'];
// pr($_POST, true);
if (!empty($_POST)) {
    $p = $_POST;
    if (empty($cid)) $cid = $p['candidate_id'];
    $cid = Candidate::saveInfo($p);
    if (!$cid) {
        $info = $_POST['experience'];
        $cid = $p['candidate_id'];
    } else {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/writing.php?cid={$cid}';</script>";
        exit();
    }
}
if (empty($cid)) {
//    echo "<script>alert('Invailid Candidate');</script>";
//    echo "<script>window.location.href='/';</script>";
} else {
    $data = Candidate::getCandidateInfo($cid);
    if (!empty($data)) {
        $info = $data['experience'];
    }
    $smarty->assign('info', $info);
    $smarty->assign('cid', $cid);
}
$total = empty($info['job']) ? 0:count($info['job']);
$total++;
$smarty->assign('total', $total);
$list = array('' => 'Job Category');
foreach ($categories as $k => $row) {
    $list[$k] = $row['category'];
}
$smarty->assign('maxrow', count($list));
$smarty->assign('list', $list);
$page_title = 'CopyPress - Job Application Form for Writers and Editors';
$description = 'Thank you for your interest in
freelance writing and editing opportunities with CopyPress, a division
of BlueGlass Interactive Inc. Our freelance team is made up of the
brightest and best talent in the industry. Every author and editor at
CopyPress understands the value of quality content, teamwork and
community.';
$smarty->assign('page_title', $page_title);
$smarty->assign('cid', $_GET['cid']);
$smarty->assign('default_start_date', date("Y-m-d"));
$smarty->assign('feedback', $feedback);
$smarty->display('work_experience.html');
?>