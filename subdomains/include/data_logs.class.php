<?php
class DataLog{

	var $log_id;
	var $sssdata;
	var $ip_address;
	var $referer;
	var $others;
	var $created;
	var $parsed;
	var $sssreply;
	var $datatype;

	function __construct()
	{
		$this->log_id = 0;
		$this->sssdata = 0;
		$this->ip_address = 0;
		$this->referer = 0;
		$this->others = null;
		$this->created = 0;
		$this->parsed = null;
		$this->sssreply = null;
        $this->datatype = 'xml';
	}

    function DataLog()
    {
        $this->__construct();
    }

    function dataDispose($p, $datatype = 'xml')
    {
        $data = $p['sssdata'];
        unset($p['sssdata']);
        if (isset($p['sssreply'])) {
            $hash['sssreply'] = $p['sssreply'];
            unset($p['sssreply']);
        }
        if (isset($p['parsed'])) {
            $hash['parsed'] = $p['parsed'];
            unset($p['parsed']);
        }
        if ($datatype == 'xml') {
            $hash['sssdata'] = htmlentities(stripslashes($data), ENT_QUOTES);
        } else {
            $hash['sssdata'] = stripslashes($data);
        }
        $hash['others'] = serialize($p);
        $hash['created'] = date("Y-m-d H:i:s");
        $hash['ip_address'] = getIP();
        $hash['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $hash['datatype'] = $datatype;
        $this->store($hash);
        return $data;
    }

	function store( $hash )
	{
		global $conn, $feedback;
		$bind['log_id'] = mysql_escape_string(htmlspecialchars(trim($hash['log_id'])));
        $log_id = $bind['log_id'];
        if (isset($hash['datatype'])) 
            $bind['datatype'] = $hash['datatype'];
		if (isset($hash['others'])) 
            $bind['others'] = $hash['others'];
		if (isset($hash['created'])) 
            $bind['created'] = $hash['created'];
        if (isset($hash['referer'])) 
            $bind['referer'] = mysql_escape_string(htmlspecialchars(trim($hash['referer'])));
        if ($hash['datatype'] == 'json') {
            if (isset($hash['sssreply'])) 
                $bind['sssreply'] = mysql_escape_string($hash['sssreply']);
            if (isset($hash['sssdata'])) 
                $bind['sssdata'] = mysql_escape_string($hash['sssdata']);
        } else {
            if (isset($hash['sssreply'])) 
                $bind['sssreply'] = mysql_escape_string(htmlspecialchars(trim($hash['sssreply'])));
            if (isset($hash['sssdata'])) 
                $bind['sssdata'] = mysql_escape_string(htmlspecialchars(trim($hash['sssdata'])));
        }
		if (isset($hash['ip_address']))
            $bind['ip_address'] = mysql_escape_string(htmlspecialchars(trim($hash['ip_address'])));
        if (isset($hash['parsed'])) 
            $bind['parsed'] = $hash['parsed'];

		// assembled sql - START
		$sql = '';
		if (count($bind))
		{
            unset($bind['log_id']);
			if ($log_id > 0) {
                $sql = "UPDATE  `data_logs` SET  ";
                $sets = array();
                foreach ($bind as $key => $value) {
                    $sets[] = $key . '=\'' . $value .'\'';
                }
                $sql .= implode(', ', $sets);
                $sql .= 'WHERE log_id=' . $log_id;
            } else {
                $values = "'". implode("', '", $bind) . "'";
                $bind_keys = array_keys($bind);
                $fields = "`" . implode("`, `", $bind_keys) . "`";
                $sql = "INSERT INTO  `data_logs` ({$fields}) VALUES ({$values}) ";
            }
		}
		// assembled sql - FINISHED
		if (strlen($sql))
		{
			$conn->Execute($sql);
			$feedback = 'Success';
            if (empty($log_id)) {
                $sql = "SELECT MAX(log_id) FROM `data_logs`";
                $log_id= $conn->GetOne($sql);
            }
            $this->log_id = $log_id;
			return true;
		}
		else
		{
			 $feedback = 'Failure, Please try again';
			return false;
		}
	}
}
?>