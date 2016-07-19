<?php
class CpStyleGuideHistory{

	private $history_id;
	private $user_id;
	private $campaign_id;

	function __construct()
	{
		$this->history_id = 0;
		$this->campaign_id = 0;
		$this->user_id = 0;
	}

    function CpStyleGuideHistory()
    {
        $this->__construct();
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	

		$bind['history_id']     = mysql_escape_string(htmlspecialchars(trim($hash['history_id'])));
		$bind['campaign_id'] = mysql_escape_string(htmlspecialchars(trim($hash['campaign_id'])));
		$bind['user_id']        = mysql_escape_string(htmlspecialchars(trim($hash['user_id'])));

		// check the required fields - START
		foreach ($bind as $k => $value)
		{
			switch ($k)
			{
			case 'campaign_id':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please specify a campaign";
					return false;
				}
				break;
			case 'user_id':
				if (strlen($value)==0 || !is_numeric($value))
				{
					$feedback  = "please specify copywriter";
					return false;
				}
				break;
			}
		}
		// check the required fields - START

		// assembled sql - START
		$sql = '';
		if (count($bind))
		{
			$values = "'". implode("', '", $bind) . "'";
			$bind_keys = array_keys($bind);
			$fields = "`" . implode("`, `", $bind_keys) . "`";
			$sql = "REPLACE INTO  `cp_style_guide_history` ({$fields}) VALUES ({$values}) ";
		}
		// assembled sql - FINISHED

		if (strlen($sql))
		{
			$conn->Execute($sql);
			$feedback = 'Success';
			return true;
		}
		else
		{
			 $feedback = 'Failure, Please try again';
			return false;
		}
	}

    function isExisted($user_id, $campaign_id)
    {
        global $conn;
        $sql = "SELECT COUNT(*) AS num FROM `cp_style_guide_history` ";
        $sql .= "WHERE user_id={$user_id} ";
        $sql .= "AND campaign_id={$campaign_id}";
        $rs = &$conn->Execute($sql);
        $num = 0;
        if ($rs)
        {
            if (!$rs->EOF)
            {
                $num = $rs->fields['num'];
            }
            $rs->close();
        }
        return $num ? true : false;
    }
}
?>