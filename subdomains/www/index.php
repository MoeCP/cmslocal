<?php
require_once 'pre.php';
$g_current_path = 'home';
require_once 'cms_menu.php';
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Email.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
global $feedback;
$default_country  = 'United States of America';
$pref = Preference::getPref("candidates", 'writing_background');
$backgrounds = $pref['writing_background'];
$smarty->assign('backgrounds', $backgrounds);
$checked_backgrounds = array();


if (!empty($_POST)) {
    $p = $_POST;
    $cid = Candidate::saveBasic($p);
    if (!$cid) {
        $smarty->assign('info', $_POST);
        $default_country = $p['country'];
        $default_position = $p['cpermission'];
    } else {
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/education.php?cid={$cid}';</script>";
        exit();
    }
}

$candidate_writers = array(
    1=>'Copywriter', 
    2=>'Blog Writer',
    3=>'Technical Writer',
    4=>'Journalist'
);
$smarty->assign('default_country', $default_country);
$states = array(
	"AL"=>"Alabama",
	"AK"=>"Alaska",
	"AZ"=>"Arizona",
	"AR"=>"Arkansas",
	"CA"=>"California",
	"CO"=>"Colorado",
	"CT"=>"Connecticut",
	"DE"=>"Delaware",
	"FL"=>"Florida",
	"GA"=>"Georgia",
	"HI"=>"Hawaii",
	"ID"=>"Idaho",
	"IL"=>"Illinois",
	"IN"=>"Indiana",
	"IA"=>"Iowa",
	"KS"=>"Kansas",
	"KY"=>"Kentucky",
	"LA"=>"Louisiana",
	"ME"=>"Maine",
	"MD"=>"Maryland",
	"MA"=>"Massachusetts",
	"MI"=>"Michigan",
	"MN"=>"Minnesota",
	"MS"=>"Mississippi",
	"MO"=>"Missouri",
	"MT"=>"Montana",
	"NE"=>"Nebraska",
	"NV"=>"Nevada",
	"NH"=>"New Hampshire",
	"NJ"=>"New Jersey",
	"NM"=>"New Mexico",
	"NY"=>"New York",
	"NC"=>"North Carolina",
	"ND"=>"North Dakota",
	"OH"=>"Ohio",
	"OK"=>"Oklahoma",
	"OR"=>"Oregon",
	"PA"=>"Pennsylvania",
	"RI"=>"Rhode Island",
	"SC"=>"South Carolina",
	"SD"=>"South Dakota",
	"TN"=>"Tennessee",
	"TX"=>"Texas",
	"UT"=>"Utah",
	"VT"=>"Vermont",
	"VA"=>"Virginia",
	"WA"=>"Washington",
	"DC"=>"Washington D.C.",
	"WV"=>"West Virginia",
	"WI"=>"Wisconsin",
	"WY"=>"Wyoming");
$smarty->assign('states', $states);

$cid = $_GET['cid'];
$smarty->assign('cid', $cid);
$info = Candidate::getCandidateInfo($cid);
if (!empty($info['writer_level'])) {
    $info['writer_level_checked'] = Candidate::writerLevelChecked($info['writer_level'], $candidate_writers);
}
$smarty->assign('info', $info);
//$smarty->assign('cateParents', $info['categories']['parent_id']);
$smarty->assign('feedback', $feedback);
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
$pids = array();
foreach ($categories as $k => $row) {
    $list[$row['category_id']] = $row['category'];
}

if ($info['categories']) {
    $pids = $info['categories']['parent_id'];
    $subcategeries = $cate->getAllCategoryByParentId($pids);
    $smarty->assign('subcategeries', $subcategeries);
}

$categories = $list;
$smarty->assign('categories', $categories);
$scategories = $categories;
$scategories[''] = 'Category';
$smarty->assign('scategories', $scategories);
$g_user_levels = array(
    '' => 'Select',
    '1' => 'Casual/Basic Interest',
    '3' => 'Writing Experience',
    '4' => 'Work Experience',
    '2' => 'Expert/Certified',
);


$smarty->assign('user_levels', $g_user_levels);
$payment_preference= array(''=>'[choose]') + $g_payment_preference;
unset($payment_preference[1]);
$smarty->assign('payment_preference', $payment_preference);
$first_languages = array(''=>'Select') + $g_first_languages;
$smarty->assign('first_languages', $first_languages);
$weekly_hours = array(''=>'Select') + $g_weekly_hours;
$smarty->assign('weekly_hours', $weekly_hours);


$smarty->assign('categories', $categories);
$smarty->assign('total_cate', count($categories));
$this_year = date("Y");
$smarty->assign('this_year', $this_year);
//$smarty->assign('default_date', date("Y-m-d", strtotime("-28 years")));
$smarty->assign('default_date', "1990-01-01");

$candiate_permission = array();
$candiate_permission[1] = 'writer';
$candiate_permission[3] = 'editor';
//$candiate_permission[0] = 'both';
$plinks = array(
    //''=>'Select',
    5=>'Website',
    2=>'Blog',
    1=>'Portfolio',
//    3=>'Linkedin',
//    4=>'Other'
);
$sample_types = array(
    1=> 'Product Copy',
    2=> 'Website Landing Page Copy',
    3=> 'Blog Post',
    4=> 'Technical Writing',
    5=> "Buyer's Guide",
    6=> 'Press Release',
    7=> 'Journalistic Article',
    8=> 'White Paper',
);
$candidate_levels = array(
    '' => 'Select',
    '4' => 'Work Experience',
    '2' => 'Higher Education'
);
// added by nancy xu 2013-05-21 17:02
// added locations to candidate
$smarty->assign('locations', $g_locations);
// end
$smarty->assign('plinks', $plinks);
$smarty->assign('candidate_writers', $candidate_writers);
$smarty->assign('candidate_levels', $candidate_levels);
$smarty->assign('sample_types', $sample_types);
//added by nancy xu 2012-10-08 16:03
$smarty->assign('experience_types', $g_experience_types);
//end
if (empty($default_position) && $default_position == '') $default_position = 1;
$smarty->assign('default_position', $default_position);
$page_title = 'CopyPress - Job Application Form for Writers and Editors';
$description = 'Thank you for your interest in
freelance writing and editing opportunities with CopyPress. Our freelance team is made up of the
brightest and best talent in the industry. Every author and editor at
CopyPress understands the value of quality content, teamwork and
community.';
$smarty->assign('page_title', $page_title);
$smarty->assign('description', $description);
$smarty->assign('permissions', $candiate_permission);
$smarty->assign('default_start_date', date("Y-m-d"));
$smarty->display('index.html');
?>