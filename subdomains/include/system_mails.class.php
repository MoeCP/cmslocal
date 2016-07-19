<?php
class SystemMails{
    private $mail_id;
    private $subject;
    private $mailbody;
    private $to_ids;
    private $cc_email;
    private $from_email;
    private $attachments;

	private function __construct()
	{
        $this->mail_id = null;
        $this->subject = 0;
        $this->mailbody = 0;
        $this->to_ids = null;
        $this->cc_email = 0;
        $this->from_email = 0;
        $this->attachments = 0;
	}

    function SystemMails()
    {
        $this->__construct();
    }

    public function store($bind)
    {    
        global $conn, $feedback;
        foreach($bind as $k => $value) {
            $bind[$k] = addslashes(htmlspecialchars(trim($value)));
        }
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $conn->StartTrans();
        $sql  = "INSERT INTO `system_mails` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }

    function splitReceivers($data)
    {
        $user_ids = explode(";", $data['to_ids']);
        $user_ids = array_unique($user_ids);
        sort($user_ids);
        $group_ids = array_chunk($user_ids, 5);
        if (strlen($data['email_event']) == 0) $data['email_event'] = 0;
        foreach ($group_ids as $ids) {
            $data['to_ids'] = implode(';', $ids);
            SystemMails::store($data);
        }
        return true;
    }

    

    function setStatusById($status, $id) 
    {
        global $conn;
        $sql = 'update `system_mails` set status = ' . $status . ' where mail_id = ' . $id;
        $conn->Execute($sql);
        return true;
    }

    function getAllPendingMails()
    {
        return self::__getResult(array('status' => 0, 'limit' => 10));
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        $subject = addslashes(htmlspecialchars(trim($p['subject'])));
        $mailbody = addslashes(htmlspecialchars(trim($p['mailbody'])));
        $cc_email = addslashes(htmlspecialchars(trim($p['cc_email'])));
        $from_email = addslashes(htmlspecialchars(trim($p['from_email'])));
        $status = addslashes(htmlspecialchars(trim($p['status'])));
        $email_event = addslashes(htmlspecialchars(trim($p['email_event'])));

        if(strlen($subject) > 0)
            $condition[] = "subject like '%{$subject}%'";
        if(strlen($mailbody) > 0)
            $condition[] = "mailbody like '%{$mailbody}%'";
        if(strlen($cc_email) > 0)
            $condition[] = "cc_email like '%{$cc_email}%'";
        if(strlen($cc_email) > 0)
            $condition[] = "from_email like '%{$from_email}%'";
        if(is_numeric($status) && strlen($status) > 0)
            $condition[] = "status={$status}";
        if(is_numeric($email_event) && strlen($email_event) > 0)
            $condition[] = "email_event={$email_event}";
        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        else 
            $qw .= " ORDER BY mail_id DESC ";
        $sql = " SELECT {$query} FROM `system_mails` ";
        $sql .= " WHERE {$qw} ";
        if (isset($p['limit']) && $p['limit'] > 0) {
            $sql .= ' LIMIT ' . $p['limit'];
        }
        $rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
                $fields = strlen($single_column) ? $rs->fields[$single_column] : $rs->fields;
                if(strlen($index))
                    $result[$index] = $fields;
                else 
                    $result[] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
			return $result;
        }
        else
        {
            return false;
        }
    }
}
?>