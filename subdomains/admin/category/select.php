<?php
//$g_current_path = "preference";
$g_current_path = "my_account";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/UserCategory.class.php';

if (!user_is_loggedin()) { 
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}



$role = User::getRole();

if (empty($current_user_id)) {
    if ($role == 'copy writer' || $role == 'editor' || $role == 'designer') {
        $current_user_id = User::getID();
    } else {
        $current_user_id = $_GET['user_id'];
        $info = User::getInfo($current_user_id);
        $role = $info['role'];
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    UserCategory::delete(array('user_id' => $current_user_id));
    UserCategory::storeCategories($_POST, $current_user_id, $role);
    $feedback = 'Update success!';
    //send email to ALL editor / Admin 
    $cates = User::getCategories($current_user_id);
    $mailbody = '';
    $mailbody .= '<table>';
    $mailbody .= '<tr><td>Selected category</td></tr>';

    foreach ($cates as $cate) {
        $mailbody .= '<tr><td align="center"><strong>'.$cate['category'].'<strong></td></tr>';
        $mailbody .= '<tr><table>';
        for ($j = 0; $j < count($cate['children']); $j++) {
            if ($j % 5 == 0) $mailbody .= '<tr>';
            $mailbody .= '<td>'.$cate['children'][$j]['category'].'</td>';
            if ($j % 5 == 4) $mailbody .= '<tr>';
        }
        $mailbody .= '<tr></tr>';
    }
    $mailbody .= '</table>';
   
    $mailto = '';
    $editor = User::getAllUsers('all_infos', 'project manager');
    $admin  = User::getAllUsers('all_infos', 'admin');
    $u = array_merge($editor, $admin);
    for ($i = 0; $i < count($u); $i++) {
        $mailto .= $u[$i]['email'] . ";";
    }
    $from = '';
    $subject = 'User '.User::getName().' has updated his profile';
    #sendAnnouceMail($mailto, $mailbody, '', $from, $subject);
    $frm = $_GET['frm'];
    if (isset($_GET['frm']) && !empty($frm)) {
        unset($_GET['frm']);
        if ($frm=='user_detail') {
            echo "<script>window.location.href='/user/user_detail.php?user_id={$current_user_id}#education'</script>";
        }
    }
}


$cate = new Category();
//$categories = $cate->getAll();
//print_r($categories);
//$user_id = User::getID();
$all = $cate->getAllCategoryByUserid($current_user_id);

foreach ($all as $k => $row) {
    $total = count($row['children']) + 2;
    $all[$k]['total_row'] = $total;
    $all[$k]['area_row'] = ceil($total *1.25);
}


$smarty->assign('categories', $all);//$categories);
$smarty->assign('feedback', $feedback);
$user_levels = array('' => 'Select') + $g_user_levels;
$smarty->assign('user_levels', $user_levels);
$smarty->assign('user_id', $current_user_id);
$smarty->assign('role', $role);
$smarty->display('category/select.html');



function sendAnnouceMail($mail_to, $mailbody, $cc_email, $from, $subject)
{
    global $conn, $mailer_param;//, $feedback;

    $feedback = "";
    if (!empty($from))
    {
        $mailer_param['from'] = $from;
//        $mailer_param['reply_to'] = $from;
    }
    $mailer_param['cc'] = $cc_email;

    $all_user = explode(";", $mail_to);
    if ($mailbody == '') {
        $feedback = "Please provide mail body";
        return false;
    }

    if (!empty($all_user)) {
        foreach ($all_user AS $ku => $vu) {
            $address = $vu;
            if (!send_smtp_mail($vu, $subject, $mailbody, $mailer_param)) {
                $feedback .= $vu."";
            }
        }
    }

    if (trim($feedback) == '') {
        $feedback = "Send success";
    } else {
        $feedback = "Failuer£º".$feedback.". please try again.";
    }
}
?>