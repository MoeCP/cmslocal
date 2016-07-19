<?php
$g_current_path = "user";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Candidate.class.php';

if (!user_is_loggedin() || User::getPermission() < 4 
    || (User::getRole() == 'admin' && User::getUserType() == -1)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
//$conn->debug = true;

if (trim($_POST['user_name']) != '' && trim($_POST['user_pw']) != '') {
    if (User::add($_POST)) {
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        if ($_POST['candidate_id'] > 0) {
            echo "<script>window.location.href='/user/candidates.php';</script>";
        } else {
            echo "<script>window.location.href='/user/list.php';</script>";
        }
        exit;
    }
}

$pref = Preference::getPref("client", 'country');

if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
$default_country  = 'United States of America';
// added by nancy xu 2009-11-04 17:13
// if there is candidate id from get parameters, get the candidate info by candidate id
// and  show the info in the user form
if (isset($_GET['candidate_id']) && !empty($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];
    $info = Candidate::getInfo($candidate_id);
    $default_country = $info['country'];
    $info['birthday'] = $info['dob'];
    $info['permission']  = $info['cpermission']<=1  ? 1 : $info['cpermission'];
    //$info['address']['address1']  = $info['address'];
    //$info['address'] = array('address1' => $info['address'], 'address2' => '');
    $smarty->assign('user_info', $info);
    $smarty->assign('g_user_levels', $g_user_levels);
}
// end
$smarty->assign('default_country', $default_country);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
if (!empty($_POST)) {
    $smarty->assign('user_info', $_POST);
} else if (!isset($info)) {
    $smarty->assign('user_info', array('permission' => 1));
}
// Added by nancy xu 2009-12-11 17:53
$smarty->assign('forms_submitted', $g_tag['forms_submitted']);
$payment_preference= array(''=>'[choose]')+ $g_tag['payment_preference'];
$smarty->assign('payment_preference', $payment_preference);
$smarty->assign('acct_types', $g_bank_acct_types);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
// END
// added by nancy xu 2012-10-03 12:00
$first_languages = array(''=>'Select') + $g_first_languages;
$smarty->assign('first_languages', $first_languages);
// end
$smarty->assign('feedback', $feedback);
$smarty->assign('user_types', $g_tag['user_type']);
$smarty->display('user/user_form.html');
?>