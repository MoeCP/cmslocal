<?php
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}


global $feedback;
if (!empty($_POST)) {
    unset($_POST['check_all']);
    $_POST['writing_background'] = implode("|", $_POST['writing_background']);
    Candidate::update($_POST);
    echo "<script>alert('" . $feedback . "');</script>";
    echo "<script>window.location.href='/user/candidates.php';</script>";
}
$candidate_id = $_REQUEST['candidate_id'];
$user_id = $_REQUEST['user_id'];

$info = ($candidate_id > 0) ? Candidate::getInfo($candidate_id) : array();
$fields = array('samples','plinks');
foreach ($info as $field => $arr) {
    if (in_array($field, $fields)) {
        $data = array();
        foreach ($arr as $k => $item) {
            $data[$item['type']] = $item;
        }
        $smarty->assign($field, $data);
    }
}
$smarty->assign('cid', $candidate_id);
$smarty->assign('states', $g_tag['states']);
$smarty->assign('user_id', $user_id);



$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}

$pref = Preference::getPref("candidates");
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $arr = array('' => 'Select') +  array_combine($pref[$key], $pref[$key]);
        $smarty->assign($key, $arr);
        $smarty->assign('total' .$key, count($arr));
    }
}

$cate = new Category();
$categories = $cate->getAllCategoryByUserid(0,0);
$smarty->assign('total', count($cate));
$list = array('' => 'Job Category');
$all_pids = array();
foreach ($categories as $k => $row) {
    $category_id = $row['category_id'];
    $list[$category_id] = $row['category'];
    $all_pids[] = $category_id;
}

if ($info['categories']) {
    $pids = array();
    foreach ($info['categories'] as $k => $row) {
        $pids[] = $row['parent_id'];
    }
    if (!empty($pids)) {
        $subcategeries = $cate->getAllCategoryByParentId($pids);
    }
    $smarty->assign('subcategeries', $subcategeries);
}

$categories = $list;
$smarty->assign('categories', $categories);
$scategories = $categories;
$scategories[''] = 'Category';
$smarty->assign('scategories', $scategories);

$candiate_permission = array();
$candiate_permission[1] = 'writer';
$candiate_permission[3] = 'editor';
$candiate_permission[0] = 'both';
$candidate_levels = array(''=> 'Select') + $g_tag['candidate_levels'];
$smarty->assign('candidate_levels', $candidate_levels);
// added by nancy xu 2012-10-03 9:16
$first_languages = array(''=>'Select') + $g_first_languages;
$smarty->assign('first_languages', $first_languages);
$weekly_hours = array(''=>'Select') + $g_weekly_hours;
$smarty->assign('weekly_hours', $weekly_hours);
// end
$candidate_writers = $g_tag['candidate_writers'];
$smarty->assign('candidate_writers', $candidate_writers);
if (!empty($info['writer_level'])) {
    $info['writer_level_checked'] = Candidate::writerLevelChecked($info['writer_level'], $candidate_writers);
}
$smarty->assign('info', $info);

$smarty->assign('sample_types', $g_tag['candidate_sample_types']);
$smarty->assign('candidate_plinks', $g_tag['candidate_plinks']);

if (empty($default_position) && $default_position == '') $default_position = 1;
$smarty->assign('default_position', $default_position);

$this_year = date("Y");
$smarty->assign('this_year', $this_year);
// added by nancy xu 2013-05-21 17:02
// added locations to candidate
$smarty->assign('locations', $g_locations);
// end
$smarty->assign('cpermissions', $g_tag['candiate_permission']);
$payment_preference= array(''=>'[choose]')+ $g_tag['payment_preference'];
$smarty->assign('payment_preference', $payment_preference);
$smarty->assign('default_date', date("Y-m-d", strtotime("-20 years")));
$smarty->display('user/candidate_edit.html');
?>