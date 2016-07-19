<?php
	require_once 'pre_cron.php';//parameter settings
	$info = array('status'=>'never login', 
						   'interval'=> 3 );
	$users = User::getAllCopyWritersByParameters( $info );
	$subject = 'Time is running out ...';
	$body = "<div>Dear Copypress's Member:</div><br /><div>Your articles are overdue and time is running out. Unless you've made prior arrangements with your editor, the outstanding articles listed below will be reassigned in 24 hours.<br /><br />Best regards,<br /><br />Second Step Search Management</div>";

    global $mailer_param;

	echo "cronjob start";
	if( count( $users ) )
	{
		foreach( $users as $k => $user)
		{
			send_smtp_mail($user['email'], $subject, $body, $mailer_param);
		}
	}
	echo "cronjob over";
?>