<?php
	require_once 'pre_cron.php';//parameter settings

	$info = array('status'=>'new assign', 
						   'interval'=> 2 );
	$users = User::getAllCopyWritersByParameters( $info );

    global $mailer_param;

	echo "cronjob start";
	if( count( $users ) )
	{
		foreach( $users as $k => $user)
		{
			$subject = "Dear {$user['first_name']}, don't forget your new assigment!";
			$body = "<div>Hey {$user['first_name']}!</div><br /><div>We’re waiting for you to log in and pick up your assignment. If you haven’t already logged in, please visit the CMS and confirm your assignment and availability. <br /><br />Thanks,<br /><br />I9CMS Group</div>";
			send_smtp_mail($user['email'], $subject, $body, $mailer_param);
		}
	}
	echo "cronjob over";
?>