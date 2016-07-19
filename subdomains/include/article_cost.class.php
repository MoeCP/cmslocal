<?php
class ArticleCost{

	private $cost_id;
	private $campaign_id;
	private $article_type;
	private $cost_per_article;
	private $invoice_status;

	function __construct()
	{
		$this->cost_id = 0;
		$this->campaign_id = 0;
		$this->article_type = 0;
		$this->cost_per_article = 0;
		$this->invoice_status = 0;
	}

    function ArticleCost()
    {
        $this->__construct();
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	
        
		$bind['cost_id'] = mysql_escape_string(htmlspecialchars(trim($hash['cost_id'])));
        $cost_id = $bind['cost_id'];
		$bind['campaign_id'] = mysql_escape_string(htmlspecialchars(trim($hash['campaign_id'])));
		$bind['article_type'] = mysql_escape_string(htmlspecialchars(trim($hash['article_type'])));
		$bind['cost_per_article'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_cost'])));
		$bind['cp_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_cost'])));
		$bind['cp_article_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_article_cost'])));
		$bind['pay_by_article'] = mysql_escape_string(htmlspecialchars(trim($hash['pay_by_article'])));
        if (empty($bind['pay_by_article'] )) $bind['pay_by_article']  = 0;
		$bind['editor_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['editor_cost'])));
		$bind['editor_article_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['editor_article_cost'])));
		$bind['invoice_status'] = trim($hash['invoice_status']) ? mysql_escape_string(htmlspecialchars(trim($hash['invoice_status']))) : 0;

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
			case 'article_type':
				if (strlen($value)==0 || !is_numeric($value))
				{
					$feedback  = "please specify article type";
					return false;
				}
				break;
			case 'cost_per_article':
				if (strlen($value) === 0 || !is_numeric($value))
				{
					$feedback  = "please input cost";
					return false;
				}
				break;
			case 'cp_cost':
				if (strlen($value) === 0 || !is_numeric($value))
				{
					$feedback  = "please input copywriter cost";
					return false;
				}
				break;
			case 'editor_cost':
				if (strlen($value) === 0 || !is_numeric($value))
				{
					$feedback  = "please input editor cost";
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
            unset($bind['cost_id']);
			if ($cost_id > 0) {
                $sql = "UPDATE  `article_cost` SET  ";
                $sets = array();
                foreach ($bind as $key => $value) {
                    $sets[] = $key . '=\'' . $value .'\'';
                }
                $sql .= implode(', ', $sets);
                $sql .= 'WHERE cost_id=' . $cost_id;
            } else {
                $values = "'". implode("', '", $bind) . "'";
                $bind_keys = array_keys($bind);
                $fields = "`" . implode("`, `", $bind_keys) . "`";
                $sql = "INSERT INTO  `article_cost` ({$fields}) VALUES ({$values}) ";
            }
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

	public static function storeArticleCost( $p )
	{
		global $conn, $feedback;
		if(count($p['campaign_id']))
		{
			$conn->StartTrans();
			foreach  ($p['campaign_id'] as $k => $campaign_id) 
			{
				$hash['cost_id']       =$p['cost_id'][$k];
				$hash['article_type']    = $p['article_type'][$k];
				$hash['campaign_id']   = $p['campaign_id'][$k];
				$hash['invoice_status']  =$p['invoice_status'][$k];
				$hash['cp_cost'] =$p['cp_cost'][$k];
                $hash['cp_article_cost'] =$p['cp_article_cost'][$k];
                $hash['pay_by_article'] =$p['pay_by_article'][$k];
				$hash['editor_cost'] =$p['editor_cost'][$k];
				$hash['editor_article_cost'] =$p['editor_article_cost'][$k];
                $hash['cost_per_article'] =$p['cp_cost'][$k];
				self::store($hash);
			}
			$ok = $conn->CompleteTrans();
		}
		if ($ok) 
		{
            $feedback = 'Success';
            return true;
        } 
		else 
		{
            $feedback = 'Failure,Please try again';
            return false;
        }
	}

    public static function getArticleCostByCampaignID($campaign_id)
    {
        global $feedback;
        if (empty($campaign_id))
        {
            $feedback = 'Please specify a Campaign';
            return false;
        }
        $p['campaign_id']  = $campaign_id;
        $p['columns']     = " ac.* ";
        $result = self::getList($p);
        return $result;
    }

    public static function getTypeIDsByCampaignID($campaign_id)
    {
        global $feedback;
        if (empty($campaign_id))
        {
            $feedback = 'Please specify a Campaign';
            return false;
        }
        $p['campaign_id']  = $campaign_id;
        $p['columns']     = " ac.article_type ";
        $p['fields']       = "article_type";
        $result = self::getList($p);
        return $result;
    }

    public static function getTypesByCampaignID($campaign_id)
    {
        global $feedback;
        if (empty($campaign_id))
        {
            $feedback = 'Please specify a Campaign';
            return false;
        }
        $p['campaign_id']  = $campaign_id;
        $p['columns']     = " ac.article_type, at.type_name ";
        $p['fields']       = "type_name";
        $p['index']       = "article_type";
        $result = self::getList($p);
        return $result;
    }

	public function getList($p)
	{
		global $conn, $feedback;
		// initialized values - START
        // sql filter conditon
        $condition = array();
        $campaign_id = $p['campaign_id'];
        if ($campaign_id > 0)
            $condition[] = "`ac`.`campaign_id`={$campaign_id}";
        // extra condition
        $where = trim($p['where']);
        if (isset($p['where']) && strlen($where))
            $condition[] = $where;
        // query fields
        if (isset($p['columns']) && strlen(trim($p['columns'])))
            $columns = $p['columns'];
        else 
            $columns = " * ";
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
		$sql = '';
		// initialized values - FINISHED
		$sql = "SELECT {$columns} " . 
              "FROM `client_campaigns` AS cc, `article_cost` AS ac  " .
              "LEFT JOIN `article_type` AS at ON at.type_id=ac.article_type " . 
              "WHERE `cc`.`campaign_id`=`ac`.`campaign_id` ";
        $where = count($condition) ? " AND " . implode(" AND ", $condition) : '';
        $sql .= $where;
		$rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            $i = 0;
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