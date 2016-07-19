<?php
class TrackEmail{
    private $email_id;
    private $subject;
    private $content;
    private $sender;
    private $error;
    private $sent;
    private $created_by;
    private $permission;

	private function __construct()
	{
        $this->email_id = null;
        $this->subject = null;
        $this->content = null;
        $this->to_ids = null;
        $this->cc_email = 0;
        $this->from_email = 0;
        $this->attachments = 0;
	}

    function TrackEmail()
    {
        $this->__construct();
    }

    public function store($bind)
    {    
        global $conn, $feedback;
        foreach($bind as $k => $value) {
            //if ($k == 'subject' || $k == 'content') continue;
            $bind[$k] = addslashes(htmlspecialchars(trim($value)));
        }
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $conn->StartTrans();
        $sql  = "INSERT INTO `track_emails` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }

    public function storeEmailFromSystem($address, $subject, $body, $mailer_param)
    {
        global $feedback;
        $hash = array();
        $hash['subject'] = $subject;
        $hash['content'] = $body;
        $hash['sender'] = $mailer_param['smtp_username'];
        $hash['error'] = $feedback;
        if (is_array($address)) {
            $str = implode(',', $address);
        } else {
            $str = $address;
        }
        if (is_array($mailer_param['cc'])) {
            $cc = implode(',', $mailer_param['cc']);
        } else {
            $cc = $mailer_param['cc'];
        }
        if (is_array($mailer_param['bcc'])) {
            $bcc = implode(',', $mailer_param['bcc']);
        } else {
            $bcc = $mailer_param['bcc'];
        }
        $hash['receiver'] = $str;
        $hash['cc'] = $cc;
        $hash['bcc'] = $bcc;
        $hash['sent'] = date("Y-m-d H:i:s");
        if (function_exists(user_is_loggedin) && user_is_loggedin()) {
            $user_id = User::getID();
            $permission = User::getPermission();
        } else if (function_exists(client_is_loggedin) && client_is_loggedin()) {
            $user_id = Client::getID();
            $permission = -1;
        } else {
            $user_id = 0;// cronjob
            $permission = 0;
        }
        $hash['permission'] = $permission;
        $hash['created_by'] = $user_id;
        TrackEmail::store($hash);
    }
}
?>