<?php
// 改cronjob已经作废
require_once 'pre_cron.php';//parameter settings

echo "cronjob start\n";
$p = array("month"=>date("Ym"));
$users = User::getAllCPPaymentComfirm($p,  "u.status != 'D'");
if( count( $users ) )
{
	$body = "<div>List of Copywriter That has Approved the Count:</div><div>";
	foreach( $users as $key => $user )
	{
		$body .= $user['user_name']."<br />";
	}
	$body .= "</div>";
	$subject = "Daily Summary of CopyWriter Approved";

    global $mailer_param;

	$address = "listerine@gmail.com";
	send_smtp_mail($address, $subject, $body, $mailer_param);	
}
echo "cronjob over\n";
?>