<?php

class CampaignLog{

	private $campaign_log_id;
    private $_table;

	function __construct()
	{
		$this->campaign_log_id = 0;
        $this->_table = '`campaign_logs`';
	}

    function CampaignLog()
    {
        $this->__construct();
    }

    function deleteAllByFileId($file_id)
    {
        global $conn;
        $sql = 'DELETE FROM ' . $this->_table .' WHERE campaign_file_id=' . $file_id;
        $conn->Execute($sql);
        return true;
    }

    function getCampaignIdByID($log_id, $is_parsed = 1)
    {
        global $conn;
        $sql = "SELECT campaign_id FROM " . $this->_table . " where campaign_log_id=" . $log_id . ' and is_parsed=' . $is_parsed;
        return $conn->GetOne($sql);
    }

    function storeFileData($data, $campaign_file_id)
    {
        global $conn;
        $this->deleteAllByFileId($campaign_file_id);
        $fields = array(
            'campaign_name' => $data['Campaign Name'],
            'campaign_id' => $data['Campaign ID'],
            'domain' => $data['Domain'],
            'keyword' => $data['Campaign Keywords'],
            'repeat_time' => $data['Number of Keywords'],
            'max_word' => $data['Number of Words'],
            'template' => $data['Template'],
            'mapping_id' => $data['Mapping-ID'], 
            'style_guide_url' => $data['Style Guide URL'],
            'ordered_by' => $data['Ordered By'],
            'optional1' => $data['Optional Field 1'],
            'optional2' => $data['Optional Field 2'],
            'optional3' => $data['Optional Field 3'], 
            'optional4' => $data['Optional Field 4'],
            'optional5' => $data['Optional Field 5'],
            'optional6' => $data['Optional Field 6'],
            'optional7' => $data['Optional Field 7'],
            'optional8' => $data['Optional Field 8'],
            'optional9' => $data['Optional Field 9'],
            'optional10' => $data['Optional Field 10']
        );
        extract($fields);
        $total = count($campaign_name);
        $data = array(
            'creation_user_id' => User::getID(), 
            'campaign_file_id' => $campaign_file_id, 
            'creation_role' => User::getRole(),
            'date_created' => date("Y-m-d H:i:s"));
        $rows = array();

        for ($i=0;$i<$total;$i++) {
            foreach ($fields as $k => $values) {
                if ($k == 'campaign_id' && empty($values[$i])) {
                    $values[$i] = 0;
                }
                $data[$k] = mysql_escape_string(htmlspecialchars(trim($values[$i])));
                //
            }
            $rows[] = $data;
            $id = $conn->GenID('seq_campaign_logs_campaign_log_id');
            $data['campaign_log_id'] = $id;
            $this->insert($data);
        }
    }

   function insert($data)
    {
        global $conn;
        $values = "'". implode("', '", $data) . "'";
        $bind_keys = array_keys($data);
        $fields = "`" . implode("`, `", $bind_keys) . "`";
        $sql = "INSERT INTO  " . $this->_table . " ({$fields}) VALUES ({$values}) ";
        $conn->Execute($sql);
        return true;
    }

    function update($data)
    {
        global $conn;
        $sql = "UPDATE  " . $this->_table . " SET  ";
        $sets = array();
        $campaign_log_id = $data['campaign_log_id'];
        foreach ($data as $key => $value) {
            $sets[] = $key . '=\'' . $value .'\'';
        }
        $sql .= implode(', ', $sets);
        $sql .= 'WHERE campaign_log_id=' . $campaign_log_id;
        $conn->Execute($sql);
        return true;
    }

	function store( $hash )
	{
		global $conn, $feedback;
        foreach ($hash as $k => $v) {
            $hash[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }

        extract($hash);

        if ($campaign_name == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }

        if ($keyword == '') {
            $feedback = "Please enter the keyword";
            return false;
        }

        if ($domain == '') {
            $feedback = "Please enter the domain";
            return false;
        }


		// assembled sql - START
		$sql = '';
        $bind = $hash;
		if (count($bind))
		{
            unset($bind['campaign_log_id']);
			if ($campaign_log_id > 0) {
                $sql = "UPDATE  " . $this->_table . " SET  ";
                $sets = array();
                foreach ($bind as $key => $value) {
                    $sets[] = $key . '=\'' . $value .'\'';
                }
                $sql .= implode(', ', $sets);
                $sql .= 'WHERE campaign_log_id=' . $campaign_log_id;
            } else {
                $values = "'". implode("', '", $bind) . "'";
                $bind_keys = array_keys($bind);
                $fields = "`" . implode("`, `", $bind_keys) . "`";
                $sql = "INSERT INTO  " . $this->_table . " ({$fields}) VALUES ({$values}) ";
            }
		}
		// assembled sql - FINISHED
		if (strlen($sql))
		{
			$conn->Execute($sql);
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';
                return true;
            } else {
                return false;
            }
		}
		else
		{
			 $feedback = 'Failure, Please try again';
			return false;
		}
	}

    function search($p = array())
    {
        global $conn, $feedback;
        $conditions = array("1");
        if (!empty($p)) {
            foreach ($p as $k => $v) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            }            
            extract($p);
            if (strlen($is_parsed)) {
                $conditions[] = 'cl.is_parsed=' . $is_parsed;
            }
            if (isset($campaign_file_id) && $campaign_file_id > 0) {
                $conditions[] = 'cl.campaign_file_id=' . $campaign_file_id;
            }
        }

        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM {$this->_table} AS cl "; 
        $from .= "LEFT JOIN campaign_files AS cf  ON (cf.campaign_file_id=cl.campaign_file_id)";
        $sql  = "SELECT  cf.*, cl.*  " . $from . $where ;
        if (isset($limit)) {
            $sql .= " LIMIT  "  . $limit;
        }
        return $conn->GetAll($sql);
    }
}
?>