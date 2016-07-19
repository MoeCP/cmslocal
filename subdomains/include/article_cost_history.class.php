<?php
class ArticleCostHistory{

	private $history_id;
	private $user_id;
	private $month;
	private $campaign_id;
	private $article_type;
	private $total_article;
	private $total_cost;

	function __construct()
	{
		$this->history_id = 0;
		$this->user_id = 0;
		$this->month = 0;
		$this->campaign_id = 0;
		$this->article_type = 0;
		$this->cost_per_article = 0;
		$this->total_article = 0;
		$this->total_cost = 0;
	}
    
    function ArticleCostHistory()
    {
        $this->__construct();
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	
        foreach ($hash as $k=> $value) {
            $bind[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
        }

		// check the required fields - START
		foreach ($bind as $k => $value)
		{
			switch ($k)
			{
			case 'campaign_id':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please select a campaign";
					return false;
				}
				break;
			case 'user_id':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please choose a invoice";
					return false;
				}
				break;
			case 'month':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please  choose a invoice";
					return false;
				}
				break;
			case 'cost_per_article':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please input cost";
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
            if (empty($bind['history_id'])) {
                unset($bind['history_id']);
                $history_id = 0;
            } else {
                $history_id = $bind['history_id'];
            }
			$values = "'". implode("', '", $bind) . "'";
			$bind_keys = array_keys($bind);
			$fields = "`" . implode("`, `", $bind_keys) . "`";
            if ($history_id > 0) {
                $sql = 'UPDATE `article_cost_history` SET ';
                $sets = array();
                foreach ($bind as $k => $v) {
                    $sets[] = "`{$k}`='{$v}'";
                }
                $sql .= implode(", ", $sets);
                $sql .= ' WHERE history_id=' . $history_id;
            } else {
                $sql = "INSERT INTO  `article_cost_history` ({$fields}) VALUES ({$values}) ";
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


    function getHistoryId($param)
    {
        global $conn, $feedback;
        if (!empty($param)) {
            $sql = "SELECT history_id FROM article_cost_history ";
            $conditions = array();
            foreach ($param as $k => $v) {
                $conditions[] = "`{$k}`='{$v}'";
            }
            $sql .= ' WHERE ' . implode(" AND ", $conditions);
            $result = $conn->GetOne($sql);
            return $result;
        }
        return false;
    }
    
    public static function storeArticleCostHistoryByParam($p, $invoice_status, &$cost_arr = array(), $date_bill = null)
    {
        global $conn, $feedback;
        $role =$p['role'];
        $article_types =  Article::getCampaignIDAndArticleType($p, $role);
        $report =  User::getArticleAmountReport($p, $role);
        $campaigns = $report['campaign'];
        $ok = true;
        if (!empty($article_types)) {
            unset($article_types['num']);
            $user_id =$p['user_id'];
            $month =$p['month'];
            
            $conditions = array(
                'month' => $month, 
                'user_id'=>$user_id,
                'role'=>$role,
            );
            $conn->StartTrans();
            foreach ($article_types as $campaign => $items) {
                foreach ($items as $article_type => $item) {
                    if (!is_numeric($article_type)) continue;
                    $hash = array();
                    $campaign_id = $item['campaign_id'];
                    $cost_per_article = $item['cost_per_unit'];
                    $hash['campaign_id'] = $campaign_id;
                    $hash['qd_listid'] = $item['qd_listid'];
                    $hash['article_type'] = $article_type;
                    $hash['article_type_name'] = $item['article_type_name'];
                    $hash['cost_per_article'] = $cost_per_article;
                    $hash['pay_by_article'] = $item['checked'];
                    $conditions['article_type'] = $article_type;
                    $conditions['campaign_id'] = $campaign_id;
                    $hash['role'] = $role;
                    $hash['month'] = $month;
                    $hash['user_id'] = $user_id;
                    $arr = $campaigns[$campaign_id][$article_type];
                    $hash['total_article'] = $arr['num'];
                    $hash['total_cost'] = $arr['cost'];
                    $hash['history_id'] = self::getHistoryId($conditions);
                    $cost_arr[] = array(
                        'campaign_id' => $campaign_id,
                        'cost_id' => $item['cost_id'],
                        'article_type' => $article_type,
                        'cost_per_article' => $cost_per_article,
                        'invoice_status' => $invoice_status,
                    );
                    // added by nancy xu 2010-09-08 10:46
                    // add date time of create bill
                    if (!empty($date_bill)) $hash['date_bill'] = $date_bill;
                    // end
                    self::store($hash);
                }
            }
            $ok = $conn->CompleteTrans();
        }
        return $ok;
    }

	public static function storeArticleCostHistory( $p )
	{
		global $conn, $feedback;
		$hash['month'] = $p['month'];
		$hash['user_id'] = $p['user_id'];
		if(count($p['campaign_id']))
		{
			$conn->StartTrans();
			foreach  ($p['campaign_id'] as $k => $campaign_id) 
			{
				$hash['history_id'] = $p['history_id'][$k];
				$hash['total_cost'] = $p['total_cost'][$k];
				$hash['article_type'] = $p['article_type'][$k];
				$hash['total_article'] = $p['total_article'][$k];
				$hash['campaign_id'] = $p['campaign_id'][$k];
				$hash['cost_per_article'] = $p['type_cost'][$k];
				$hash['article_type_name'] =$p['article_type_name'][$k];
                if (empty($hash['history_id'])) {
                    $conditions = array(
                        'article_type' => $hash['article_type'], 
                        'month' => $hash['month'], 
                        'user_id'=>$hash['user_id'], 
                        'campaign_id' => $hash['campaign_id']);
                    $hash['history_id'] = self::getHistoryId($conditions);
                }
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

	public static function getArticleTypesByUserIDAndMonth($p)
	{
		global $conn, $feedback;
		// initialized values - START
		$user_id = mysql_escape_string(htmlspecialchars(trim($p['user_id'])));
		$month   = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		$role   = mysql_escape_string(htmlspecialchars(trim($p['role'])));
        if ($role == 'editor') {
            $cost_field = 'editor_cost';
            $cost_article = 'editor_article_cost';
        } else if ($role == 'copy writer') {
            $cost_field = 'cp_cost';
            $cost_article = 'cp_article_cost';
        } 
		$sql = '';
		// initialized values - FINISHED
		$sql = "SELECT `ach`.*, `cc`.`campaign_name`, IF(`ach`.`article_type_name` != '' && `ach`.`article_type_name` IS NOT NULL, `ach`.`article_type_name`, `at`.`type_name`) AS article_type_name,  \n" . 
            "at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked, ach.pay_by_article AS ach_checked, ac.invoice_status,  \n" . 
            "ac.{$cost_article} AS ac_article_cost, at.{$cost_article} AS at_article_cost, ach.cost_per_article AS ach_type_cost,  \n" . 
            "ac.{$cost_field} AS ac_word_cost, at.{$cost_field} AS at_word_cost  \n" . 
              "FROM `article_cost_history` AS ach \n" .
             " LEFT JOIN `client_campaigns` AS cc ON (cc.campaign_id=`ach`.`campaign_id`) \n" . 
              "LEFT JOIN `article_type` AS at ON at.type_id = ach.article_type \n" . 
             " LEFT JOIN `article_cost` AS ac ON  ac.article_type=at.type_id AND ac.campaign_id = cc.campaign_id  \n ".
              "WHERE `cc`.`campaign_id`=`ach`.`campaign_id` AND `ach`.`user_id` = '{$user_id}' AND `ach`.`month` = '{$month}' AND `ach`.`role`='{$role}' ";
		$rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
                $fields = $rs->fields;
                // added by nancy xu 2011-05-26 17:05
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $fields['checked'] = $checked;
                $fields['cost_per_unit'] = $cost_per_unit;
                // end
				$key = $fields['campaign_name'] . ':'. $fields['campaign_id'];
                $result[$key][$fields['article_type']] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
            // added by snug xu 2007-05-17 14:35 - STARTED
            // total types for each campaign
            foreach ($result as $key => $value)
            {
                $result[$key]['num'] = count($value);
            }
            // added by snug xu 2007-05-17 14:35 - FINISHED
			return $result;
        }
		return false;
	}
}
?>