<?php
class CampaignNotes{
    private $note_id;
    private $note;
    private $creator;
    private $creator_role;
    private $created_date;
    private $campaign_id;

	private function __construct()
	{
        $this->note = null;
        $this->note_id = 0;
        $this->creator = 0;
        $this->creator_role = null;
        $this->created_date = 0;
        $this->campaign_id = 0;
	}

    function CampaignNotes()
    {
        $this->__construct();
    }

    public function store($bind)
    {    
        global $conn, $feedback;
        foreach($bind as $k => $value) {
            $bind[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        $bind['created_date'] = date("Y-m-d H:i:s");
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $conn->StartTrans();
        $sql  = "INSERT INTO `campaign_notes` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }

    function deleteNotesByCampaignId($campaignId)
    {
        global $conn, $feeback;

        $conditions = array();
        if ($campaignId > 0)
        {
            $conditions[] = "`campaign_id`=$campaignId";
        }
        else
        {
            return false;
        }

        if (!empty($conditions))
        {
            $sql = "DELETE FROM `campaign_notes` WHERE  " . implode(" AND ", $conditions);
            $conn->Execute($sql);
            return $conn->Affected_Rows() ?  true : false;
        }
        
        return false;
    }

    public function getInfo($note_id)
    {
       $p = array('note_id'=>$note_id);
       $list = self::__getResult($p);
       return $list[0];
    }

    public function getNotesByCampaignID($campaign_id)
    {
       $p = array('campaign_id'=>$campaign_id);
       $p['single_column'] = 'note';
       $p['columns'] = 'note';
       $list = self::__getResult($p);
       $notes = implode("\n", $list);
       $notes = htmlspecialchars_decode($notes, ENT_QUOTES);
       $notes = stripslashes ($notes);
       return nl2br($notes);
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        $note = addslashes(htmlspecialchars(trim($p['note'])));
        $creator = addslashes(htmlspecialchars(trim($p['creator'])));
        $note_id = addslashes(htmlspecialchars(trim($p['note_id'])));
        $creator_role = addslashes(htmlspecialchars(trim($p['creator_role'])));
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        $created_date = addslashes(htmlspecialchars(trim($p['created_date'])));

        if(strlen($note) > 0)
            $condition[] = "note like '%{$note}%'";
        if(is_numeric($log_id) && $log_id > 0)
            $condition[] = "note_id={$note_id}";
        if(is_numeric($creator) && $creator > 0)
            $condition[] = "creator={$creator}";
        if(strlen($creator_role) > 0)
            $condition[] = "creator_role='{$creator_role}'";
        if(is_numeric($campaign_id) && $campaign_id > 0)
            $condition[] = "campaign_id={$campaign_id}";
        if($created_date > 0)
            $condition[] = "created_date='{$created_date}'";
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
            $qw .= " ORDER BY note_id DESC ";
        $sql = " SELECT {$query} FROM `campaign_notes` ";
        $sql .= " WHERE {$qw} ";
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


    //add by liu shufen 11:05 2007-11-27
    function getGeneralNotes($p) {
        global $conn;
        $qw[] = " WHERE 1 ";
        if (isset($p['general_note_id']) && !empty($p['general_note_id'])) {
            $qw[] = " general_note_id=". trim($p['general_note_id']);
        }
        if (isset($p['subject']) && !empty($p['subject'])) {
            $qw[] = " subject='". htmlspecialchars(addslashes(trim($p['subject']))) ."' ";
        }
        if (isset($p['body']) && !empty($p['body'])) {
            $qw[] = " body='". htmlspecialchars(addslashes(trim($p['body']))) ."'";
        }
        $sql = "SELECT * FROM general_notes ";
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $rs = &$conn->Execute($sql);
        $result = array();
        if($rs) {
            while(!$rs->EOF) {
                $result[$rs->fields['general_note_id']] =  $rs->fields;
                $result[$rs->fields['general_note_id']]['general_note_id'] = $rs->fields['general_note_id'];
                $result[$rs->fields['general_note_id']]['subject'] =  stripslashes(htmlspecialchars_decode($rs->fields['subject'], ENT_QUOTES));
                $result[$rs->fields['general_note_id']]['body'] =  stripslashes(htmlspecialchars_decode($rs->fields['body'], ENT_QUOTES));
                $rs->MoveNext();
            }
            $rs->Close();
            if(isset($p['single_column']) && !empty($p['single_column'])) {
                $arr[0] = "Select General Editorial Notes";
                $i = 1;
                foreach ($result as $key => $value) {
                    $arr[$key] =  stripslashes(htmlspecialchars_decode($value[$p['single_column']], ENT_QUOTES));
                    //stripslashes($value[$p['single_column']]);
                    //print_r($rs);
//                    foreach ($rs as $key => $value) {
//                        if ($key == $p['single_column']) {
//                            $arr[$i] = $value;//$rs[trim($p['signle_column'])]
//                            $i ++;
//                        }
//                    }
                }
                return $arr;
            }else {
                return $result;
            }
             
        }else return null;
    }//END

    //add by liushufen 12:42 2007-11-27
    function delGeneralNotes($p) {
        print_r($p);
        global $conn;
        $qw[] = " WHERE 1 ";
        if (isset($p['general_note_id']) && !empty($p['general_note_id'])) {
            $qw[] = " general_note_id=". trim($p['general_note_id']);
        }
        if (isset($p['subject']) && !empty($p['subject'])) {
            $qw[] = " subject='". htmlspecialchars(addslashes(trim($p['subject']))) ."' ";
        }
        if (isset($p['body']) && !empty($p['body'])) {
            $qw[] = " body='". htmlspecialchars(addslashes(trim($p['body']))) ."'";
        }
        $sql = "DELETE FROM general_notes ";
        if (!empty($qw)) {
            $sql .= implode("AND", $qw);
        }
        $rs = $conn->Execute($sql);
        //print($sql);
        if ($rs) {
            return true;
        }else return false;
    }//END

    //add by liu shufen 13:19 2007-11-27
    function addGeneralNotes($p) {
        global $conn, $feedback;
        $subject = htmlspecialchars(addslashes(trim($p['subject'])));
        $body = htmlspecialchars(addslashes(trim($p['body'])));
        $created_role = htmlspecialchars(addslashes(trim($p['created_role'])));
        $created_by = htmlspecialchars(addslashes(trim($p['created_by'])));
        $created = date("Y-m-d H:i:s");
        $qw = '';
        if (isset($p['general_note_id']) && !empty($p['general_note_id'])) {
            $sql = " UPDATE ";
            $qw .= " WHERE general_note_id=" . $p['general_note_id'];

        }else {
            $sql = "INSERT INTO ";
        }
        $sql .= " general_notes SET subject='". $subject ."', ".
                                  " body='". $body ."', ".
                                  " created_by=". $created_by. ", ".
                                  " created_role='". $created_role ."', ".
                                  " created='". $created ."' ";
        $sql .= $qw;
        $rs = &$conn->Execute($sql);
        if ($rs) {
            $feedback = "Succeed!";
            return true;
        }
        else {
            $feedback = "Failed,please try again!";
            return false;
        }
    }
}
?>