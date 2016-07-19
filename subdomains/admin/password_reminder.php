<?php

// no cache
/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

require_once 'pre.php';
require_once CMS_INC_ROOT.'/User.class.php';
if(count( $_POST)&&strlen( $_POST['email']) )
{
	$is_ajax = $_GET['is_ajax'];
	$user = User::getUserByEmail($_POST['email'], $_POST['user_name']) ;
	if (count( $user ) )
	{
		if(User::sendPasswordReminderToUser( $user ))
		{
			$feedback = 'Email Send Success<br />Please to check your email';
		}
		else
		{
			$feedback = 'Email Send Failed<br />Please Try again';
		}
	}
	else
	{
		$feedback='This Email is not existed';
	}	
	if( $is_ajax )
	{
		echo $feedback;
	}
}
$query_string = $_SERVER['QUERY_STRING'];
$smarty->assign('url', "/password_reminder.php?is_ajax=1");
$smarty->assign('feedback', $feedback);
if( !$is_ajax )
	$smarty->display('password_reminder.html');
?>