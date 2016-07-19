<?php
$g_current_path = "user";
//$g_current_path = "candidates";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/User.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';

if (!empty($_POST['isUpdate'])) {
	//以下构造是为了防止hacker伪造数据提交
    $post_checkbox_array = implode(",", $_POST['isUpdate']);

    $candidate_id = array();
    foreach ($_POST['isUpdate'] AS $k_isUpdate => $v_isUpdate) {
        $k = $v_isUpdate - 1;
        $candidate_id = $_POST['candidate_id'][$k];
        if ($_POST['status'] == 'resend') {
            $domain = 'http://' . $_SERVER['HTTP_HOST']; 
            $info = User::getInfoByCandidateId($candidate_id);
            $email = $info['email'];
            $arr = array(
                "%%LOGIN_LINK%%" => $domain,
                "%%FIRST_NAME%%" => $info['first_name'],
                "%%USER_NAME%%" => $info['user_name'],
                "%%USER_PW%%" => $info['user_pw'],
            );
            $info = getEmailSubjectAndBody(18, $arr);
            send_smtp_mail($email, $info['subject'], $info['body'], $mailer_param);
            $feedback = 'Success';
        } else {
            $p = array('candidate_id' => $candidate_id,
                       'status' => $_POST['status']);
            $result = Candidate::update($p);
            if ($result && $_POST['status']=='hired') {
                header("Location: /user/user_add.php?candidate_id=" . $candidate_id);
            }
        }
    }

    $p = array();
}

$search = Candidate::search($_GET);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('is_show_operate', $search['is_show_operate']);
}
//End Added
$pref = Preference::getPref("client", 'country');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
$pref = Preference::getPref("candidates", 'education');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
$pref = Preference::getPref("candidates", 'experience');
if ($pref) {
    while (list($key, $val) = each($pref)) {
        $smarty->assign($key, $val);
    }
}
//$smarty->assign('result', $result);
$smarty->assign('user_levels', $g_tag['candidate_levels']);
$smarty->assign('sample_types', $g_tag['candidate_sample_types']);
$smarty->assign('candidate_plinks', $g_tag['candidate_plinks']);
$all_cat = Category::getAllCategoryByCategoryId();
$smarty->assign('cp_interests', array(0=>array('name' => 'All')) + $all_cat);
$smarty->assign('cpermissions', $g_tag['candiate_permission']);
$smarty->assign('candidate_statuses', $g_tag['candidate_status']);
$smarty->assign('login_role', User::getRole());
$smarty->assign('feedback', $feedback);
$smarty->display('user/candidates.html');
?>