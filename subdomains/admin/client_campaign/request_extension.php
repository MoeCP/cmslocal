<?php
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!user_is_loggedin()) 
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/request_extension.class.php';
require_once CMS_INC_ROOT.'/User.class.php';

$campaign_name = $_GET['cname'];
$extension_id = $_GET['extension_id'];

if( $extension_id > 0 )
{
	$info = RequestExtension::getInfoByExtensionID($extension_id);
} else {
    $info['copy_writer_id'] = empty($_GET['cpid']) ? User::getID() : $_GET['cpid'];
    $info['editor_id'] = $_GET['eid'];
	$info['campaign_id'] = $_GET['campaign_id'];
}

$smarty->assign('info', $info);
if( count( $_POST ) )
{
    $hash = array_merge($info, $_POST);
    $count = empty($extension_id) ? (RequestExtension::getCountByParam($info)) : 0;
    if ($count) {
        echo "<script>alert('You have submitted the form, please close the window')</script>";
    } else {
        $total = RequestExtension::getArticles($info, true);
        $hash['total'] = $total;
        if ( RequestExtension::store($hash))
        {
            global $conn;
            $user_name = User::getName();
            $subject = $hash['subject'];
            $reason = nl2br($hash['reason']);
            $copy_writer_id = trim($hash['copy_writer_id']);
            $campaign_id = trim($hash['campaign_id']);
            $editor_id = trim($hash['editor_id']);
            $days_asked = $hash['days_asked'];
            $data = array(
                'campaign_name' => $campaign_name,
                'ask_days' => $days_asked,
                'user_name' => $user_name,
                'datastring' => $reason,
                'article_number' => $total,
                'login_link' => $domain . '/user/articles.php?copy_writer_id=' . $copy_writer_id . '&campaign_id=' . $campaign_id,
            );
            $editor = User::getInfo($editor_id);
            RequestExtension::sendAnnouceMail(35, $editor['email'], $data, null, $g_er_cc);
            $from = User::getEmail();
            RequestExtension::sendAnnouceMail(22, $from, $data);
        }
		//header("Location: /client_campaign/request_extension.php?" . $query_string);
        echo "<script>window.opener.location.reload();window.close();</script>";
	}
}
$smarty->assign('feedback', $feedback);
$smarty->assign('campaign_name', $campaign_name);
$smarty->assign('copy_writer_name', User::getName());

$smarty->display('client_campaign/request_extension.html');


function sendAnnouceMail($mail_to, $mailbody, $from, $subject, $cc = null)
{
    global $conn, $feedback, $mailer_param;
    if (!empty($cc)) {
        $mailer_param['cc'] = $cc ; 
    }

    $feedback = "";

    if (!empty($from))
    {
        $mailer_param['from'] = $from;
    }

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