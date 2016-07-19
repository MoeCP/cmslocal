<?php
class Notes{
	private $note_id;
	private $notes;
	private $copy_writer_id;
	private $editor_id;
	private $campaign_id;
	private $keyword_id;
	private $date_created;

	private function __construct()
	{
		$this->note_id = 0;
		$this->notes = '';
		$this->copy_writer_id = 0;
		$this->editor_id = 0;
		$this->campaign_id = 0;
		$this->date_created = '';
	}

	public function setNotes( $notes )
	{
		$this->notes = $notes;
	}
	
	public function getNotes()
	{
		return $this->notes;
	}

	public function setCopyWriterID( $copy_writer_id )
	{
		$this->copy_writer_id = $copy_writer_id;
	}

	public function getCopyWriterID()
	{
		return $this->copy_writer_id;
	}

	public function setCampaignID( $campaign_id )
	{
		$this->campaign_id = $campaign_id;
	}

	public function getCampaignID( $campaign_id )
	{
		return $this->campaign_id;
	}

	public function setEditorID( $editor_id )
	{
		$this->editor_id = $editor_id;
	}
	
	public function getEditorID()
	{
		return $this->editor_id;
	}

	public static function getNotesInfoByKeywordID($keyword_id)
	{
		global $conn, $feedback;
		$query = "SELECT en.note_id, en.notes, en.copy_writer_id, en.editor_id, en.campaign_id, en.keyword_id, en.date_created, ue.user_name, ue.first_name, ue.last_name  \n";
        $query .= "FROM `editor_notes` AS en, `users` AS ue \n";
        $query .= "WHERE ue.user_id=en.editor_id \n";
        if (!empty($keyword_id)) {
            if (is_array($keyword_id)) {                
                if (count($keyword_id) > 1) {
                    foreach ($keyword_id as $k => $v) {
                        $keyword_id[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
                    }
                    $max_id = array_pop($keyword_id);
                    $min_id = array_shift($keyword_id);
                    $query .= " AND en.keyword_id >= {$min_id} AND en.keyword_id <= {$max_id} \n";
                }
                $query .= " AND en.keyword_id IN ('" . implode("','", $keyword_id). "')";
            } else {
                $keyword_id = mysql_escape_string(htmlspecialchars(trim($keyword_id)));
                $query .= " AND keyword_id = '{$keyword_id}'";
            }
        } else {
            $feedback = "Please Choose a campaign keyword";
            return false;
        }
        
        if (is_array($keyword_id)) {
            $rs = &$conn->Execute($query);
            if ($rs) {
                $ret = array();
                while (!$rs->EOF) {
                    $keyword_id = $rs->fields['keyword_id'];
                    $ret[$keyword_id] = $rs->fields;
                    $rs->MoveNext();
                    $i++;
                }
                $rs->Close();
            }
        } else {
            return $conn->GetRow($query);
        }
        return false; //if there is no notes info by keyword_id
	}

	public static function store( $hash )
	{
		global $conn, $feedback;
		$keyword_id = mysql_escape_string(htmlspecialchars(trim($hash['keyword_id'])));
		$campaign_id = mysql_escape_string(htmlspecialchars(trim($hash['campaign_id'])));
		$copy_writer_id = mysql_escape_string(htmlspecialchars(trim($hash['copy_writer_id'])));
		$editor_id = mysql_escape_string(htmlspecialchars(trim($hash['editor_id'])));
		$notes = mysql_escape_string(htmlspecialchars(trim($hash['notes'])));
		$note_id = mysql_escape_string(htmlspecialchars(trim($hash['note_id'])));
		if(strlen( $notes )==0)
		{
			$feedback = 'Please fill Notes';
			return false;
		}
		else
		{
			if( $note_id > 0 )
			{
				if( $keyword_id > 0 )
					$set_query[] = "keyword_id='{$keyword_id}'";
				if( $campaign_id > 0 )
					$set_query[] = "campaign_id='{$campaign_id}'";
				if( $editor_id > 0 )
					$set_query[] = "editor_id='{$editor_id}'";
				if( $copy_writer_id > 0 )
					$set_query[] = "copy_writer_id='{$copy_writer_id}'";
				if( strlen($notes) )
					$set_query[] = "notes='{$notes}'";
				$set_sub_query = implode(', ', $set_query);
				$sql = "UPDATE editor_notes SET $set_sub_query WHERE  note_id = '{$note_id}'";
			}
			else
			{
				$sql = "INSERT INTO editor_notes(notes, keyword_id, campaign_id, copy_writer_id, editor_id) VALUES ('$notes', $keyword_id, $campaign_id, $copy_writer_id, $editor_id) ";
			}
			$conn->Execute($sql);
			 $feedback = 'Success';
			return true;
		}
		 $feedback = 'Failure, Please try again';
		return false;
	}
}
?>