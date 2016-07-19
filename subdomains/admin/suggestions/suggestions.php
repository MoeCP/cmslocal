<?php

$g_current_path = "suggestions";
require_once('../pre.php');//load config
require_once('../cms_menu.php');
require_once CMS_INC_ROOT . "/Suggestion.class.php";
// added by snug xu 2006-11-24 13:49 - START
if (User::getRole() == 'agency' || !user_is_loggedin())
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
// added by snug xu 2006-11-24 13:49 - END

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    if (!empty($subject) && !empty($content)) {
         Suggestion::save($_POST);
        $feedback = 'Thank you for submitting.';
    } else {
        $feedback = 'Plase fill subject and content!';
        $smarty->assign('subject', $subject);
        $smarty->assign('content', $content);
    }
} else {
    $smarty->assign('subject', '');
    $smarty->assign('content', '');
}


$smarty->assign('feedback', $feedback);
$smarty->display('suggestions/suggestions.html');

?>