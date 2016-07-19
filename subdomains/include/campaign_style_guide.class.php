<?php
class CampaignStyleGuide{

    private $style_id;
    private $campaign_id;
    private $contact;
    private $date;
    private $background;
    private $launch_feature;
    private $audience;
    private $challenge;
    private $objective;
    private $message;
    private $talking_point;
    private $mandatory;
    private $style_influence;
    private $others;

	function __construct()
	{
		$this->style_id = 0;
		$this->campaign_id = 0;
		$this->contact = '';
		$this->date = '';
		$this->background = '';
		$this->launch_feature = '';
		$this->audience = '';
		$this->challenge = '';
		$this->objective = '';
		$this->message = '';
		$this->talking_point = '';
		$this->mandatory = '';
		$this->style_influence = '';
		$this->others = '';
	}

    function CampaignStyleGuide()
    {
        $this->__construct();
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	

		$bind['style_id'] = mysql_escape_string(htmlspecialchars(trim($hash['style_id'])));
		$bind['campaign_id'] = mysql_escape_string(htmlspecialchars(trim($hash['campaign_id'])));
		$bind['contact'] = mysql_escape_string(htmlspecialchars(trim($hash['contact'])));
		$bind['date'] = mysql_escape_string(htmlspecialchars(trim($hash['date'])));
		$bind['background'] = mysql_escape_string(htmlspecialchars(trim($hash['background'])));
		$bind['objective'] = mysql_escape_string(htmlspecialchars(trim($hash['objective'])));
		$bind['message'] = mysql_escape_string(htmlspecialchars(trim($hash['message'])));
		$bind['talking_point'] = mysql_escape_string(htmlspecialchars(trim($hash['talking_point'])));
		$bind['launch_feature'] = mysql_escape_string(htmlspecialchars(trim($hash['launch_feature'])));
		$bind['audience'] = mysql_escape_string(htmlspecialchars(trim($hash['audience'])));
		$bind['challenge'] = mysql_escape_string(htmlspecialchars(trim($hash['challenge'])));
		$bind['mandatory'] = mysql_escape_string(htmlspecialchars(trim($hash['mandatory'])));
		$bind['style_influence'] = mysql_escape_string(htmlspecialchars(trim($hash['style_influence'])));
		$bind['others'] = mysql_escape_string(htmlspecialchars(trim($hash['others'])));
		// check the required fields - START
		foreach ($bind as $k => $value)
		{
            switch ($k) {
            case 'contact':
            case 'style_id':
            case 'others':
                break;
            default:
                if (empty($value))
                {
                    $feedback  = "please input " . str_replace("_", " ", $k);
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
            $conn->StartTrans();
			$values   = "'". implode("', '", $bind) . "'";
			$bind_keys = array_keys($bind);
			$fields    = "`" . implode("`, `", $bind_keys) . "`";
			$sql      = "REPLACE INTO  `campaign_style_guide` ({$fields}) VALUES ({$values}) ";
			$conn->Execute($sql);
            $ok = $conn->CompleteTrans();
		}
		// assembled sql - FINISHED

		if ($ok)
		{
			$feedback = 'Success';
			return true;
		}
		else
		{
		    $feedback = 'Failure, Please try again';
			return false;
		}
	}
    

    public static function getInfo($style_id)
    {
        $p['style_id'] = $style_id;
        $style = self::getList($p);
        return $style[0];
    }

    public static function getInfoByCampaignID($campaign_id)
    {
        $p['campaign_id'] = $campaign_id;
        $p['columns']       = "cc.campaign_name, cc.date_start, csg.* , cc.campaign_id, cc.campaign_requirement, cc.editor_grading_rubric, cc.sample_content ";
        $style = self::getList($p);
        return $style[0];
    }

    /**
     * @param array $p
     * @return array
     */
    function getList($p = array())
    {   
        global $conn;
        $condition = array();
        $style_id = trim($p['style_id']);
        if (isset($p['style_id']) && $style_id > 0)
            $condition[] = "csg.style_id={$style_id}";
        $campaign_id = trim($p['campaign_id']);
        if (isset($p['campaign_id']) && $campaign_id > 0)
            $condition[] = "cc.campaign_id='{$campaign_id}'";
        // extra condition
        $where = trim($p['where']);
        if (isset($p['where']) && strlen($where))
            $condition[] = $where;
        // type is string
        // return array index
        if (isset($p['index']) && strlen(trim($p['index'])))
            $index = trim($p['index']);
        // type is string, sperated by ";"
        // return fields
        if (isset($p['fields']) && strlen(trim($p['fields'])))
            $fields = explode(";", trim($p['fields']));
        else 
            $fields = array();
        // type is string
        // select fields
        if (isset($p['columns']) && strlen(trim($p['columns'])))
            $columns = trim($p['columns']);
        else 
            $columns = " * ";
        $sql = "SELECT {$columns} FROM client_campaigns as cc ";
        $sql .= "LEFT JOIN campaign_style_guide AS csg ON csg.campaign_id=cc.campaign_id ";
        if (count($condition))
            $sql .= "WHERE " . implode(" AND ", $condition);
        else
            $sql .= "WHERE 1=1 ";
		$rs = &$conn->Execute($sql);
		$result = array();
        $i    = 0;
        if ($rs)
		{
            while (!$rs->EOF) 
			{
                if (strlen($index))
				    $key = $rs->fields[$index];
                else
                    $key = $i;
                switch (count($fields))
                {
                 case 0:
                     $result[$key] = $rs->fields;
                     break;
                 case 1:
                     $result[$key] = $rs->fields[$fields[0]];
                     break;
                 default:
                     foreach($fields as $key => $val)
                     {
                        $result[$key][$val] = $rs->fields[$val];
                     }
                     break;
                }
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
			return $result;
        }

        return false;
    }
}
?>