<?php
class ArticlePaymentLog{
    private $log_id;
    private $article_id;
    private $article_type;
    private $user_id;
    private $month;
    private $campaign_id;
    private $paid_time;

	private function __construct()
	{
        $this->log_id = 0;
        $this->article_id = 0;
        $this->article_type = 0;
        $this->user_id = 0;
        $this->month = 0;
        $this->campaign_id = 0;
        $this->paid_time = null;
	}

    function ArticlePaymentLog()
    {
        $this->__construct();
    }

    public function getInfo($log_id)
    {
       $p = array('log_id'=>$log_id);
       $list = self::__getResult($p);
       return $list[0];
    }

    public function getCountByArticleID($article_id)
    {
        $p['article_id'] = $article_id;
        $p['columns'] = " COUNT(log_id) AS num ";
        $p['single_column'] = 'num';
        $list = self::__getResult($p);
        return $list[0];
    }
    public function getLogIDs($p)
    {
        $p['columns'] = 'log_id';
        $p['single_column'] = 'log_id';
        $list = self::__getResult($p);
        return $list;
    }

    public function getLogID($p)
    {
        $list =self::getLogIDs($p);
        $id = 0;
        if (!empty($list)) $id = $list[0];
        return $id;
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        $log_id = addslashes(htmlspecialchars(trim($p['log_id'])));
        $month = addslashes(htmlspecialchars(trim($p['month'])));
        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        $paid_time = addslashes(htmlspecialchars(trim($p['paid_time'])));
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));

        if(is_numeric($log_id) && $log_id > 0)
            $condition[] = "log_id={$log_id}";
        if(!empty($article_id))
        {
            if(is_numeric($article_id) && $article_id > 0)
                $condition[] = "article_id={$article_id}";
            else if(is_array($article_id))
                $condition[] = "article_id in ('" . implode("', '", $article_id) . "')";
            else if(is_string($article_id))
            {
                $article_id = stripslashes($article_id);
                $condition[] = "article_id in ('{$article_id}')";
            }
        }
        if(is_numeric($article_type) && $article_type > 0)
            $condition[] = "article_type={$article_type}";
        if(is_numeric($user_id) && strlen($user_id))
            $condition[] = "user_id={$user_id}";
        if(is_numeric($campaign_id) && $campaign_id > 0)
            $condition[] = "campaign_id={$campaign_id}";
        if($paid_time > 0)
            $condition[] = "paid_time='{$paid_time}'";
        if($month > 0)
            $condition[] = "month='{$month}'";
        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        $sql = " SELECT {$query} FROM article_payment_log";
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

    function getArticles($p = array(), $pagination = true)
    {
        global $conn;
        $qw = '';
        $log_id = addslashes(htmlspecialchars(trim($p['log_id'])));
        $month = addslashes(htmlspecialchars(trim($p['month'])));
        $pay_month = addslashes(htmlspecialchars(trim($p['pay_month'])));
        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        $paid_time = addslashes(htmlspecialchars(trim($p['paid_time'])));
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        $is_canceled = addslashes(htmlspecialchars(trim($p['is_canceled'])));
        $role = addslashes(htmlspecialchars(trim($p['role'])));

        if(is_numeric($log_id) && $log_id > 0)
            $condition[] = "apl.log_id={$log_id}";
        if(!empty($article_id))
        {
            if(is_numeric($article_id) && $article_id > 0)
                $condition[] = "apl.article_id={$article_id}";
            else if(is_array($article_id))
                $condition[] = "apl.article_id in ('" . implode("', '", $article_id) . "')";
            else if(is_string($article_id))
            {
                $article_id = stripslashes($article_id);
                $condition[] = "apl.article_id in ('{$article_id}')";
            }
        }
        if(!empty($role))
            $condition[] = "apl.role='{$role}'";
        if(is_numeric($article_type) && $article_type > 0)
            $condition[] = "apl.article_type={$article_type}";
        if(is_numeric($user_id) && $user_id > 0)
            $condition[] = "apl.user_id={$user_id}";
        if(is_numeric($campaign_id) && $campaign_id > 0)
            $condition[] = "apl.campaign_id={$campaign_id}";
        if($paid_time > 0)
            $condition[] = "apl.paid_time='{$paid_time}'";
        if($month > 0)
            $condition[] = "apl.month='{$month}'";
        if($pay_month > 0)
            $condition[] = "apl.pay_month='{$pay_month}'";
        if (strlen($is_canceled)) 
            $condition[] = "apl.is_canceled='{$is_canceled}'";
        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        if (strlen($columns))  {
            $query = $columns;
        } else {
            $query = "ck.keyword, ar.article_status,CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name, CONCAT(cp.first_name, ' ', cp.last_name) AS cp_name, ar.total_words as word_count, `ck`.`date_start`, `ck`.`date_end`,`ck`.`article_type`,`cc`.`campaign_name`, at.parent_id as at_parent_id \n";
        }
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        //$qw .= " AND ck.status!='D' ";// this function no need to add to this search condition
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        $sql = " SELECT {$query} FROM article_payment_log as apl \n";
        $sql .= ' LEFT JOIN articles AS ar ON (ar.article_id=apl.article_id) ' ."\n";
        $sql .= ' LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ' . "\n";
        $sql .= "LEFT JOIN article_type AS at ON at.type_id = ck.article_type ";
        $sql .= ' LEFT JOIN users AS ue ON (ue.user_id=ck.editor_id) ' . "\n";
        $sql .= ' LEFT JOIN users AS cp ON (cp.user_id=ck.copy_writer_id) ' . "\n";
        $sql .= ' LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id)  ' . "\n";
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

    function storeFromClientApproval($datetime, $data = array())
    {
        global $conn;
        $month = changeTimeToPayMonthFormat($datetime);
        $article_id = $data['article_id'];
        $editor_id = $data['editor_id'];
        $campaign_id = $data['campaign_id'];
        $copy_writer_id = $data['copy_writer_id'];
        if (!isset($data['client_id']) || empty($data['client_id'])) {
            $sql = 'SELECT client_id FROM client_campaigns where campaign_id=' . $campaign_id;
            $client_id = $conn->GetOne($sql);
        } else {
            $client_id = $data['client_id'];
        }
        $hash = array(
            'month' => $month,
            'campaign_id' => $campaign_id,
            'is_canceled' => $data['is_canceled'],
            'article_type' => $data['article_type'],
            'client_id' => $client_id,
        );
        $p = array(
            // 'month' => $month,
            'article_id' => $article_id,
            'user_id' => $copy_writer_id,
        );
        $hash = array_merge($hash, $p);
        $logId = self::getLogID($p);
        if (!$logId) {
            $hash['pay_month'] = $month;
            $hash['approval_date'] = date("Y-m-d H:i:s", $datetime);
        }
        $hash['user_id'] = $copy_writer_id;
        $hash['role'] = 'copy writer';
        self::store($hash, $logId);
        $p['user_id'] = $editor_id;
        $logId = self::getLogID($p);
        if (!$logId) $hash['pay_month'] = $month; 
        $hash['user_id'] = $editor_id;
        $hash['role'] = 'editor';
        self::store($hash, $logId);
    }

    function store($data, $logId = null)
    {
        if ($logId) {
            $ret = self::update($data, $logId);
        } else {
            $ret = self::insert($data);
        }
        return $ret;
    }

    function insert($data)
    {
        global $conn;
        $sql = 'INSERT INTO article_payment_log (%s) VALUES (%s)';
        $fields = array_keys($data);
        $field = implode(',', $fields);
        $value = "'" . implode("','", $data) . "'";
        $sql = sprintf($sql, $field, $value);
        return $conn->Execute($sql);
    }

    function update($data, $logId)
    {
        global $conn;
        $sql = 'UPDATE article_payment_log SET %s  WHERE log_id=%s';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . $v .  '\'';
        }
        $set = implode(",", $sets);
        $sql = sprintf($sql, $set, $logId);
        // echo $sql;
        return $conn->Execute($sql);
    }

    function updatePaymentInfo($p = array(), $payment_flow_status)
    {
        global $conn;
        $conditions = array();
        if (isset($p['user_id']) && !empty($p['user_id'])) {
            $user_id = $p['user_id'];
            $conditions[] = 'user_id = ' . $user_id;
        } else  {
            return false;
        }
        if (isset($p['role']) && !empty($p['role'])) {
            $role = $p['role'];
            $conditions[] = 'role = \'' . $role . '\'';
        } else  {
            return false;
        }
        if (isset($p['pay_month']) && !empty($p['pay_month'])) {
            $pay_month = $p['pay_month'];
            $conditions[] = 'pay_month = ' . $pay_month;
        } else  {
            return false;
        }
        $now = date("Y-m-d H:i:s");
        if ($payment_flow_status == 'paid' ) {
            $hash = array('paid_time' => $now);
        } else {
            $hash = array('date_bill' => $now);
        }
        return self::updateByParam($hash, $conditions);
    }

    function updateByParam($data, $p = array())
    {
        global $conn;
        $sql = 'UPDATE article_payment_log SET ';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . addslashes($v) . '\'';
        }
        $sql .= implode(',', $sets);
        $sql .= ' WHERE ' . implode(' AND ', $p);
        return $conn->Execute($sql);
    }

    function setTargetPayMonth($p)
    {
		global $conn, $feedback;

		if( empty($p) )
		{
			$feedback="Failed";
			return false;
		}
        foreach ($p as $k => $v) {
            $p[$k] = mysql_escape_string( htmlspecialchars( trim($v)));
        }
        extract($p);
		if ( $article_id == '' || $article_id==0 ) 
		{
            $feedback = "Please Choose an article";
            return false;
        }
		if ( $user_id == '' || $user_id==0 ) 
		{
            $feedback = "Please Choose an user";
            return false;
        }
		if ( empty($role)) 
		{
            $feedback = "Please specify the role";
            return false;
        }
        //$is_delay 0 means ahead of target pay month; 1 means delay target pay month
		// end
		if( $article_id >0)
		{
			// added by nancy xu 2011-05-05 12:25
            // pay twice per month
			if ($pay_month > 0) {
				$target_time = changeTimeFormatToTimestamp($pay_month);
                $target_month = $pay_month;
			} else if ($client_approval_date > 0) {
				$target_time = strtotime($client_approval_date);
                $target_month   = changeTimeToPayMonthFormat($target_time);
			}
            $pay_per_month = getPayPerMonth($target_month);
            $next_target_month = nextPayMonth($target_month, $target_time, $pay_per_month);
            $last_target_month = lastPayMonth($target_month, $target_time, $pay_per_month);
            // end

			if ($is_delay == 1) {
				$pay_month = $next_target_month;
			} else if ($is_delay == 2) {
                $pay_month = $target_month;
			} else {
				$pay_month = $last_target_month;
			}
			// end
			if ($user_id > 0) 
			{
				// 获得$target_time这个月的历史支付信息
				$p = array(
						'user_id' => $user_id,
						'role' => $role,
						'month' => changeTimeToPayMonthFormat($target_time)
				 );			
				$current_month_info = User::getPaymentHistoryInfo($p);

				// 获得目标支付月的历史支付信息
				$p['month'] = $pay_month;
				$target_month_info = User::getPaymentHistoryInfo($p);
			}

			// 如果历史支付信息存在，
			// 并且原来目标支付月和现在目标支付月的payment_flow_status不是cp disapporve(dwe)和Infinitenine approve
			// 则不允许用户调整文章
            if (empty($log_id)) {
                $data = array(
                    'pay_month' => $pay_month,
                    'article_type' => $article_type,
                    'client_id' => $client_id,
                    'user_id' => $user_id,
                    'campaign_id' => $campaign_id,
                    'role' => $role,
                    'article_id' => $article_id,
                 );
                if (!empty($client_approval_date) && $client_approval_date > 0) {
                    $data['month'] = changeTimeToPayMonthFormat(strtotime($client_approval_date));
                } else {
                    $data['month'] = 0;
                }
            }
			if ((count($target_month_info) && $target_month_info['payment_flow_status'] != 'dwe' && $target_month_info['payment_flow_status'] != 'ap') || (count($current_month_info) && $current_month_info['payment_flow_status'] != 'dwe' && $current_month_info['payment_flow_status'] != 'ap')) {
                if ($forced_adjust == 1 && $current_month_info['payment_flow_status'] != 'paid' && $target_month_info['payment_flow_status'] != 'paid')
                {
                    if ($log_id > 0) {
                        $result = self::update(array('pay_month' => $pay_month), $log_id);
                    } else {
                        $result =  self::insert($data);
                    }
                }
                else
                {
                    $feedback = "You can\'t delay payment or add to this pay period, please check!";
                    return false;
                }
			} else {
                if ($log_id > 0) {
                    $result = self::update(array('pay_month' => $pay_month), $log_id);
                } else {
                    $result = self::insert($data);
                }
			}
            if ($result) {
                $feedback = "Success";
                return true;
            }
		}
		$feedback="Move to Next Pay Peried Failed";
		return false;
    }

    function updateKeywodPaymentStatus($p = array())
    {
		global $feedback, $conn;
        foreach ($p as $k => $v) {
            $p[$k] = mysql_escape_string( htmlspecialchars( trim($v)));
        }
        extract($p);
		if ( $article_id == '' || $article_id==0 ) 
		{
            $feedback = "Please Choose an article";
            return false;
        }
		if ( $user_id == '' || $user_id==0 ) 
		{
            $feedback = "Please Choose an user";
            return false;
        }
		if ( empty($role) ) 
		{
            $feedback = "Please spcify the role";
            return false;
        }
        $pay_month = $current_month;
        if (empty($log_id)) {
            $data = array(
                'pay_month' => $pay_month,
                'article_type' => $article_type,
                'client_id' => $client_id,
                'user_id' => $user_id,
                'campaign_id' => $campaign_id,
                'role' => $role,
                'article_id' => $article_id,
                'is_canceled' => $is_canceled,
             );
            if (!empty($client_approval_date)) {
                $data['month'] = date("Ym", strtotime($client_approval_date));
            } else {
                $data['month'] = 0;
            }
        }

		// added by Snug  Xu 2006-10-04 17:17
		$operation = mysql_escape_string(htmlspecialchars(trim($p['operation'])));
		if (strlen($operation)) {
            $temp_p = array('user_id' => $user_id, 'month' => $current_month, 'role' => $role);
		    $current_month_info = User::getPaymentHistoryInfo($temp_p);
			if (count($current_month_info) && $current_month_info['payment_flow_status'] != 'dwe') 
			{
				$feedback = "You can\'t cancel keyword, please to check!";
				return false;
			}
			unset($p['operation']);
			unset($p['current_month']);
		}
        $conn->StartTrans();
        if ($log_id > 0) {
            self::update(array('is_canceled' => $is_canceled), $log_id);
        } else {
            self::insert($data);
        }
        $ok = $conn->CompleteTrans();
        if( $ok )
        {
            $feedback = $is_canceled > 0 ? 'This Keyword was Canceled' : 'This Keyword was reactive ';
            return true;
        }
		$feedback = "Failure,Please try again";
		return false;
    }

    function getPaymentLogByParam($p)
    {
        global $conn;
        $conditions = array();
        foreach ($p as $k => $v) {
            $v = addslashes(trim($v));
            $conditions[] = "{$k}='{$v}'";
        }
        $sql = "SELECT * FROM article_payment_log ";
        if (!empty($conditions)) {
            $sql .= "WHERE "  . implode(" AND ", $conditions);
        }
        return $conn->GetAll($sql);
    }

    function getAllDataForPayment($p = array())
    {
        global $conn;
        $conditions = array();
        if (isset($p['user_id']) && !empty($p['user_id'])) {
            $user_id = $p['user_id'];
        } else  {
            return false;
        }
        if (isset($p['role']) && !empty($p['role'])) {
            $role = $p['role'];
            if ($role == 'editor') {
                $field = 'ck.editor_id';
            } else if ($role == 'copy writer') {
                $field = 'ck.copy_writer_id';
            }
            $conditions[] = $field . '=' . $user_id;
        } else {
            return false;
        }
        if (isset($p['article_id']) && !empty($p['article_id'])) {
            $conditions[] = 'ar.article_id IN (' . $p['article_id'] . ')';
        }
        if (isset($p['month']) && !empty($p['month'])) {
            $month = $p['month'];
        } else {
            return false;
        }
        $sql ='SELECT ar.article_id, ck.article_type, ar.client_approval_date,' . $field. ' AS user_id, cc.campaign_id, cc.client_id, apl.log_id, apl.month, apl.pay_month, apl.paid_time  ';
        $sql .=' FROM articles AS ar ';
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ';
        $sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ';
        $sql .= 'LEFT JOIN article_payment_log AS apl ON (apl.article_id=ar.article_id AND ' .$field. '=apl.user_id AND apl.role=\'' . $role . '\' ) ';
        $sql .= 'WHERE ';
        $conditions[] = " ck.status!='D' ";
        if (!empty($conditions)) {
            $sql .= implode(' AND ', $conditions);
        }
        $result = $conn->GetAll($sql);
        $paid_time  = date("Y-m-d H:i:s");
        foreach ($result as $data) {
            $log_id = $data['log_id'];
            if ($log_id > 0 && ($data['pay_month'] != $month || $data['paid_time'] == '0000-00-00 00:00:00')) {
                $hash = array();
                $hash['pay_month'] = $month;
                $hash['paid_time'] = $paid_time;
                self::update($hash, $log_id);
            } else {
                unset($data['log_id']);
                $client_approval_date = $data['client_approval_date'];
                if ($client_approval_date == '0000-00-00 00:00:00') {
                    $data['month'] = 0;
                } else {
                    $data['month'] = date("Ym", strtotime($client_approval_date));
                }
                unset($data['client_approval_date']);
                $data['pay_month'] = $month;
                $data['paid_time'] = $paid_time;
                $data['role'] = $role;
                self::insert($hash);
            }
        }
    }

    function getClientApprovalList($p = array())
    {
        global $conn, $feedback, $g_pager_params;
        foreach ($p as $k => $v) {
            if (!is_array($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            } else {
                foreach ($v as $sub => $subv) {
                    $p[$k][$sub] = addslashes(htmlspecialchars(trim($subv)));
                }
            }
        }
        extract($p);
        $q = "\nAND ck.status!='D' AND apl.is_canceled != 1";
        if ($campaign_id != '') {
            $q .= "\nAND ck.campaign_id = ".$campaign_id."  ";
        }
        if ($keyword_id != '') {
            $q .= "\nAND ck.keyword_id = '".$keyword_id."' ";
        }
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        if ($creation_user_id != '') {
            $q .= "\nAND ck.creation_user_id = '".$creation_user_id."' ";
        }
        if ($article_type != '') {
            $q .= "\nAND ck.article_type = '".$article_type."' ";
        }
        if ($keyword_category != '') {
            $q .= "\nAND ck.keyword_category = '".$keyword_category."' ";
        }
        if ($date_start != '') {
            $q .= "\nAND ck.date_start >= '".$date_start."' ";
        }
        if ($date_end != '') {
            $q .= "\nAND ck.date_end <= '".$date_end."' ";
        }

        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else
        {
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }
        if( strlen($month) == 0 ) {
            $now = time();
            $current_month = changeTimeToPayMonthFormat($now);
        } else {
            $current_month = $month;
            $now = changeTimeFormatToTimestamp($current_month);
        }
        if (trim($keyword) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)")." ";
            }
        }

        if ($current_month > 0) {
            $q .= "\n" . ' AND apl.pay_month=' . $current_month;
        }
        $where = "\n" . ' WHERE 1 ' . $q;
        $left  = "\nFROM articles AS ar ";
        $left .= "\nLEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id ";
        $left .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id) ";
        $left .= "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id)  ";
        $left .= "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ";
        $left .= "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id)  ";
        $left .= "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id)  ";
        $left .= "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id)  ";
        $left .= "\nLEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type)  ";
        $left .= "\nLEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  ";
        $left .= "\nLEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)   ";
        $q = "\nSELECT COUNT(ck.keyword_id) AS count ". $left . $where;
        $count = $conn->GetOne($q);
        if ($count == 0 || !isset($count)) {
            $feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }
        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "\nSELECT `ck`.`keyword_id`, `ck`.`keyword`, `ck`.`article_type`, `ck`.`keyword_description`,`ck`.`date_start`, `ck`.`date_end`,   ";
        $q .= "\n`ar`.`cp_updated`, ar.article_id, ar.article_number, ar.google_approved_time, ar.approval_date, ar.client_approval_date, ar.total_words AS word_count, ar.article_status, ";
        $q .= "\nIF (apl.log_id > 0 , apl.is_canceled, ar.is_canceled) AS is_canceled, apl.role, apl.log_id, apl.pay_month , apl.paid_time, ";
        $q .= "\ncl.client_id, cl.user_name, cl.company_name, cc.campaign_name, `cc`.`campaign_id`, ";
        $q .= "\nCONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , `ck`.`copy_writer_id`,  ";
        $q .= "\nCONCAT(ue.first_name, ' ', ue.last_name) AS ue_name,  `ck`.`editor_id`,  ";
        $q .= "\n at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked,ach.pay_by_article AS ach_checked,";
        $cost_field = getCostFields();
        $q .= "\n{$cost_field}ach.cost_per_article AS ach_type_cost, ";
        $q .= "\nIF(ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name  ";
        $q .= $left . $where . "\n ORDER BY ar.article_id ";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $result[$i] = $fields;
                $google_approved_time = $rs->fields['google_approved_time'];
                $article_status = $rs->fields['article_status'];
                if (strlen($google_approved_time) == 0) 
                {
                    $google_approved_time = $fields['approval_date'];
                }
                // added by nancy xu 2011-05-26 16:05
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $cost_per_article = ($checked == 0) ? $fields['word_count'] * $cost_per_unit : $cost_per_unit;
                // end
                
                $result[$i]['payment_flow_status'] = $users[$user_id][$current_month]['payment_flow_status'];
                $result[$i]['user_id'] = $user_id;
                $result[$i]['article_cost'] = $cost_per_article;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
         }
        return array('pager'  => $pager->links,
             'total'  => $pager->numPages(),
             'count'  => $count,
             'result' => $result,
             );
    }

    function monthlyReport($type = 'copy writer', $p = array(), $is_pagination = true)
    {
        global $conn, $g_pager_params, $g_tag;
        $g_article_types = $g_tag['article_type'];
        $conditions = array('apl.is_canceled = 0', 'ck.status!=\'D\'');
        $count_field = null;
        $first_field = null;
        $search_fields = array();
        foreach ($p as $k => $v) {
            $p[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }
        $month = $p['month'];
		if (strlen($month)) {
			$now = changeTimeFormatToTimestamp($month);
		} else {
            $now = getDelayTime();
            $month = changeTimeToPayMonthFormat($now);
		}
        $from = ' FROM articles AS ar ' . "\n";
        $left_join  = 'LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id'  . " \n";
        $left_join .= 'LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id) '  . " \n";
        if (isset($p['campaign_id']) && $p['campaign_id'] > 0) {
            $conditions[] = 'apl.campaign_id= \'' . $p['campaign_id'] . '\'';
        }
        if (isset($p['client_id']) && $p['client_id'] > 0) {
            $conditions[] = 'apl.client_id= \'' . $p['client_id'] . '\'';
        }
        $keyword = isset($p['keyword']) ? $p['keyword'] : '';
        
        if ($type == 'editor') {
            $field = 'editor_id';
        } else {
            $field = 'copy_writer_id';
        }

        $cost_field = getCostFields();

        $role = $type;
        if ($type == 'client' || $type = 'campaign') {
            $role = isset($p['role']) && !empty($p['role']) ? $p['role'] : 'copy writer';
        }

        if ($type == 'client' || $type = 'campaign') {
            $left_join .= 'LEFT JOIN client_campaigns AS cc ON cc.campaign_id = ck.campaign_id' . " \n";
            $conditions[] = 'apl.pay_month = ' . $month;
            $conditions[] = 'ck.campaign_id = apl.campaign_id';
            $pub_cond = $conditions;
            $conditions[] = 'cc.campaign_id = apl.campaign_id';
            if ($type == 'client') {
                $count_field = 'apl.client_id';
                $first_field = $id_field = 'client_id';
                $search_fields[] = 'cl.user_name, cl.status, cl.email,cl.contact_name';
                $left_join .= 'LEFT JOIN client AS cl ON cl.client_id = cc.client_id' . " \n";
                $conditions[] = 'cl.client_id = apl.client_id';
                if (isset($p['agency_id']) && $p['agency_id'] > 0) {
                    $conditions[] = 'cl.agency_id = \'' . $p['agency_id'] .'\'';
                }
                $ksearch_fields = 'cl.user_name, cl.company_name, cl.company_address, cl.city, cl.email, cl.company_url,  cl.bill_email, cl.technical_email';
            } else {
                $search_fields[] = '`cc`.`campaign_name`';
                $count_field = array('apl.campaign_id', 'ck.article_type');
                $id_field = array('campaign_id', 'article_type');
                $first_field = 'campaign_id';
                $ksearch_fields = '`cc`.`campaign_name`, `cc`.`campaign_requirement`';
            }
        } else {

            $search_fields[] = 'u.*';
            $count_field ='apl.user_id';
            $first_field =  $id_field = 'user_id';
            $left_join .= 'LEFT JOIN users AS u ON ck.' . $field . ' = u.user_id' . " \n";
            $left_join .= 'LEFT JOIN cp_payment_history AS cph ON cph.' . $field . ' = u.user_id AND cph.month=' . $month . " \n";
            $conditions[] =  'ck.' . $field . '=apl.user_id';
            $conditions[] = '(apl.month=' . $month . ' OR apl.pay_month = ' . $month . ')';
            $pub_cond = $conditions;
            $conditions[] = "u.role = '{$type}' ";
            $ksearch_fields = 'u.user_name, u.first_name, u.last_name, u.email, u.address, u.phone, u.cell_phone, u.birthday, u.degree, u.role';
        }
        // $search_fields[] = 'count(ar.article_id) AS total_article';
        if (!empty($keyword)) {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $conditions[] = $search->getLikeCondition("CONCAT(" . $ksearch_fields. ")")." ";
            }
        }
        $qw = ' WHERE ' . implode("\n AND ", $conditions);
        if (is_array($count_field)) {
            $group_by = implode(',', $count_field) ;
            $sql = 'SELECT COUNT(DISTINCT ' . $group_by . ') ' . $from . $left_join .  $qw;
        } else {
            $group_by = $count_field;
            $sql = "SELECT COUNT(DISTINCT {$count_field})  " . $from . $left_join .  $qw;
        }
        $count = $conn->GetOne($sql);
        if ($count == 0 || !isset($count)) {
            return false;
        }

        $sql = "SELECT " . implode(",", $search_fields) . ', ' . $group_by . $from . $left_join . $qw . ' GROUP BY ' . $group_by;
        if ($is_pagination) {
            if (trim($p['perPage']) > 0) {
                //这里的perpage不能变成
                $perpage = $p['perPage'];
            } else {
                $perpage= 50;
            }
            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($fromNo, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($sql, $params['perPage'], ($fromNo - 1));
        } else {
            $rs = &$conn->Execute($sql);
        }
        if ($rs) {
            $result = $ids = array();
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $id_key = self::getIdKey($id_field, $fields);
                if (is_array($id_field)) $id =  $fields[$first_field];
                else $id = $id_key;
                $ids[] = $id;
                if ($id_field == 'user_id' && isset($fields['form_submitted']) && !empty($fields['form_submitted']))
                    $fields['form_submitted'] = explode("|", $fields['form_submitted']);
                $fields['total_word'] = 0;
                $result[$id_key] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
       $permission = User::getPermission();
        if (!empty($result)) {
            $qw .= ' AND apl.pay_month=' . $month;
            if (!empty($ids)) {
                $qw .= ' AND apl.' . $first_field .' IN ('. implode(',', $ids) . ') ';
            }
            $sql  = 'SELECT SUM( ar.total_words) AS total_sum , COUNT( ar.article_id) AS total , ck.article_type, at.parent_id as at_parent_id,  ' . ($group_by == 'apl.user_id' ? '':'apl.user_id, ') . $group_by . ', ' . "\n";
            $sql .= " apl.campaign_id, at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked, ach.pay_by_article AS ach_checked, \n";
            
            $sql .= "apl.role, {$cost_field}ach. cost_per_article AS ach_type_cost,  \n";
            $sql .= "IF(ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name \n";
            $sql .= $from  . $left_join ;
            $sql .= ' LEFT JOIN article_type AS at ON at.type_id = ck.article_type ' . "\n";
            $sql .= ' LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)  ' . "\n";
            $sql .= ' LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)  ' . "\n";
            $sql .=  $qw;
            if ($count_field == 'apl.user_id') {
                $sql .= ' GROUP BY apl.user_id, apl.campaign_id,  ck.article_type  ';
            } else {
                if ($group_by != 'apl.campaign_id') {
                    $sql .= ' GROUP BY  ' . $group_by . ', apl.user_id, apl.campaign_id,  ck.article_type  ';
                } else {
                    $sql .= ' GROUP BY ' . $group_by . ', apl.user_id,   ck.article_type  ';
                }
            }
            $rs  = &$conn->Execute($sql . "\n");
            if ($rs) {
                while (!$rs->EOF) {
                    $fields = $rs->fields;
                    $id_key = self::getIdKey($id_field, $fields);
                    $key = $fields['at_parent_id'];
                    $total_words = $fields['total_sum'];
                    $total_article = $fields['total'];
                     if ($permission == 5) {
                            $role = $fields['role'];
                            if (!isset($result[$id_key]['total_word_for_editor'])) $result[$id_key]['total_word_for_editor'] = 0;
                            if (!isset($result[$id_key]['total_article_for_editor'])) $result[$id_key]['total_article_for_editor'] = 0;                     
                            if (!isset($result[$id_key]['total_word_for_writer'])) $result[$id_key]['total_word_for_writer'] = 0;
                            if (!isset($result[$id_key]['total_article_for_writer'])) $result[$id_key]['total_article_for_writer'] = 0;
                            if (!isset($result[$id_key]['pay_total_words_for_editor'])) $result[$id_key]['pay_total_words_for_editor'] = 0;
                            if (!isset($result[$id_key]['pay_total_words_for_writer'])) $result[$id_key]['pay_total_words_for_writer'] = 0;
                            if (!isset($result[$id_key]['pay_total_articles_for_editor'])) $result[$id_key]['pay_total_articles_for_editor'] = 0;
                            if (!isset($result[$id_key]['pay_total_articles_for_writer'])) $result[$id_key]['pay_total_articles_for_writer'] = 0;
                            if ($role == 'editor') {
                                $result[$id_key]['total_word_for_editor'] += $total_words;
                                $result[$id_key]['total_article_for_editor'] += $total_article;
                            } else if ($role == 'copy writer') {
                                $result[$id_key]['total_word_for_writer'] += $total_words;
                                $result[$id_key]['total_article_for_writer'] += $total_article;
                            }
                     } else {
                        if (!isset($result[$id_key]['total_word'])) $result[$id_key]['total_word'] = 0;
                        if (!isset($result[$id_key]['total_article'])) $result[$id_key]['total_article'] = 0;
                        if (!isset($result[$id_key]['pay_total_words']))$result[$id_key]['pay_total_words'] = 0;
                        if (!isset($result[$id_key]['pay_total_articles']))$result[$id_key]['pay_total_articles'] = 0;
                        $result[$id_key]['total_word'] += $total_words;
                        $result[$id_key]['total_article'] += $total_article;
                     }
                    // added by nancy xu 2011-05-27 15:36
                    $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                    extract($tmp);
                    // end
                    if ($checked > 0) {
                        $total = $total_article;
                        $pay_field = 'pay_total_articles';
                    } else {
                        $total = $total_words;
                        $pay_field = 'pay_total_words';
                    }
                    
                    if ($permission == 5) {
                        $pay_field .= ($role=='editor') ? '_for_editor' : '_for_writer';
                    }
                    $result[$id_key][$pay_field] += $total;
                    if (!isset($result[$id_key]['cost'])) $result[$id_key]['cost'] = 0;
                    $result[$id_key]['cost'] += $total * $cost_per_unit;
                    $cost = $total * $cost_per_unit;
                    if ($type == 'campaign') $result[$id_key]['article_type_name'] = $fields['article_type_name'];
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            $sql  = ' SELECT SUM(ar.total_words) AS count,  ' . $group_by . ' ';
            $sql .= $from. $left_join . $qw . ' GROUP BY ' . $group_by;
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                while (!$rs->EOF) {
                    $fields = $rs->fields;
                    $id_key = self::getIdKey($id_field, $fields);
                    if (isset($result[$id_key])) {
                        $result[$id_key]['total_word'] = $fields['count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            if ($type == 'editor' || $type == 'copy writer') {
                $param = array('user_id'=> $ids, 'user_type' => $type, 'current_month' => $month);
                $result = CpPaymentHistory::getPaymentMonths($param, $result);
            }
            if ($is_pagination) { 
                return array('pager'  => $pager->links,
                             'total'  => $pager->numPages(),
                             'count'  => $count,
                             'result' => $result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    function getIdKey($id_field, $fields)
    {
        $key = '';
        if (is_array($id_field)) {
            foreach ($id_field as $k => $v) {
                $key .= $fields[$v];
            }
        } else {
            $key = $fields[$id_field];
        }
        return $key;
    }


}
?>