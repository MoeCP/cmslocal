<?php
class CampaignFile{

	private $campaign_file_id;
	private $campaign_id;
	private $client_id;
    private $_table;
    public $oLog = null;
    public $filebasedir;

	function __construct()
	{
        global $g_article_storage;
		$this->campaign_file_id = 0;
		$this->campaign_id = 0;
		$this->client_id = 0;
        $this->_table = '`campaign_files`';
        $this->oLog = new CampaignLog();
        $this->filebasedir = $g_article_storage  . 'bcampaignfile' . DS;
        if (!file_exists($this->filebasedir)) {
            mkdir($this->filebasedir, "0777");
        }
	}

    function CampaignFile()
    {
        $this->__construct();
    }

    function getInfo($campaign_file_id)
    {
        global $conn;
        $sql  = "SELECT * FROM {$this->_table} AS cf ";
        $sql .= "WHERE cf.campaign_file_id={$campaign_file_id}";
        return $conn->getRow($sql);
    }

    function save($data, $upload)
    {
        global $feedback, $conn;
        $files = $upload['filename'];
        foreach ($data as $k => $v) {
            $data[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }
        extract($data);
        if (empty($upload)) {
            $feedback = 'please choose the data file';
            return false;
        }

        if ($client_id == '') {
            $feedback = "Please Choose a client";
            return false;
        }
        if ($ordered_by == '') {
            $feedback = 'Please Specify the ordered by';
            return false;
        }
        if ($category_id == 0) {
            $feedback = "Please Choose a category";
            return false;
        }

        if ($date_start == '') {
            $feedback = "Please provide the start date of campaign";
            return false;
        }

        if ($date_end == '') {
            $feedback = "Please provide the Due Date of campaign";
            return false;
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }

        if (checkUploadFile($files, array('csv'))) {
            $conn->StartTrans();
            if (empty($campaign_file_id)) {
                $id = $conn->GenID('seq_campaign_files_campaign_file_id');
            } else {
                $id = $campaign_file_id;
            }
            $filename = $this->filebasedir . $id . '.csv';
            $data['filename'] = mysql_escape_string(htmlspecialchars(trim($filename)));
            if (empty($campaign_file_id)) {
                $data['campaign_file_id'] = $id;
                $data['date_created'] = date("Y-m-d H:i:s");
                $data['creation_user_id'] = User::getID();
                $data['creation_role'] = User::getRole();
                $this->insert($data);
            } else{
                $this->update($data);
            }
            
            $result = uploadFile($files, $filename);
            $logs = getDataFromCsv($filename);
            if (!empty($logs)) {
                $this->oLog->storeFileData($logs, $id);
                $this->update(array('campaign_file_id' => $id, 'is_parsed' => 1));
            }
            $ok = $conn->CompleteTrans();
            if ($ok) {
                return true;
            } else {
                return false;
            }
        } else{
            return false;
        }
    }

	function store( $hash )
	{
		global $conn, $feedback;
        foreach ($hash as $k => $v) {
            $hash[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }

        extract($hash);
        if (empty($filename)) {
            $feedback = 'please upload the data file';
        }

        if ($client_id == '') {
            $feedback = "Please Choose a client";
            return false;
        }
        if ($ordered_by == '') {
            $feedback = 'Please Specify the ordered by';
            return false;
        }
        if ($category_id == 0) {
            $feedback = "Please Choose a category";
            return false;
        }

        if ($date_start == '') {
            $feedback = "Please provide the start date of campaign";
            return false;
        }

        if ($date_end == '') {
            $feedback = "Please provide the Due Date of campaign";
            return false;
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }

		// assembled sql - START
		$sql = '';
        $bind = $hash;
		if (count($bind))
		{
            unset($bind['campaign_file_id']);
			if ($campaign_file_id > 0) {
                $sql = "UPDATE  " . $this->_table . " SET  ";
                $sets = array();
                foreach ($bind as $key => $value) {
                    $sets[] = $key . '=\'' . $value .'\'';
                }
                $sql .= implode(', ', $sets);
                $sql .= 'WHERE campaign_file_id=' . $campaign_file_id;
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
        $campaign_file_id = $data['campaign_file_id'];
        foreach ($data as $key => $value) {
            $sets[] = $key . '=\'' . $value .'\'';
        }
        $sql .= implode(', ', $sets);
        $sql .= 'WHERE campaign_file_id=' . $campaign_file_id;
        $conn->Execute($sql);
        return true;
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
                $conditions[] = 'cf.is_parsed=' . $is_parsed;
            }
            if (isset($campaign_file_id) && $campaign_file_id > 0) {
                $conditions[] = 'cf.campaign_file_id=' . $campaign_file_id;
            }
            if (isset($campaign_id) && strlen($campaign_id) && $campaign_id != '') {
                $conditions[] = 'cf.campaign_id=' . $campaign_id;
            }
        }

        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM {$this->_table} AS cf ";
        $sql  = "SELECT * " . $from . $where ;
        if (isset($limit)) {
            $sql .= " LIMIT  "  . $limit;
        }
        return $conn->GetAll($sql);
    }
}
?>