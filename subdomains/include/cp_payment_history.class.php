<?php
class CpPaymentHistory{

	private $payment_flow_status;
	private $user_id;
	private $month;
	private $set_flow_status_time;
	private $date_pay;
	private $payment;
	private $total;
	private $memo;
	private $notes;
	private $check_no;
	private $approved_user;
	private $reference_no;
	private $invoice_no;
	private $invoice_date;
	private $invoice_status;
	private $types;
    private $_table;

	function __construct()
	{
        $this->_table = 'cp_payment_history';
	}
    
    function CpPaymentHistory()
    {
        $this->__construct();
    }

    function getTable()
    {
        return 'cp_payment_history';
    }

    public static function getSqlByData($hash, $is_update = true)
    {
        if (isset($hash['_'])) unset($hash['_']);
        foreach ($hash as $k=> $value) {
            $bind[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
        }
		// check the required fields - START
		foreach ($bind as $k => $value)
		{
			switch ($k)
			{
			case 'user_id':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please choose a user";
					return false;
				}
				break;
			case 'month':
				if (strlen($value)==0 || $value ==0)
				{
					$feedback  = "please  specify a month";
					return false;
				}
				break;
			}
		}
        // check the required fields - END
		$sql = '';
		if (count($bind))
		{
            if ($is_update) {
                $sets = array();
                $sql = 'UPDATE ' . self::getTable() ;
                foreach ($bind as $k => $v) {
                    $sets[] = "{$k}='{$v}'";
                }
                $sql .= ' SET ' . implode(", ", $sets);
                $sql .= ' WHERE user_id=\'' . $bind['user_id'] . '\' AND month=\'' .  $bind['month'] . '\'';
                $sql .= ' AND role=\'' . $bind['role'] . '\'';
            } else {
                $values = "'". implode("', '", $bind) . "'";
                $bind_keys = array_keys($bind);
                $fields = "`" . implode("`, `", $bind_keys) . "`";
                $sql = "INSERT INTO  `". self::getTable() . "` ({$fields}) VALUES ({$values}) ";
            }
		}

        return $sql;
    }

    function getAllByParam($p)
    {
        global $conn;
        $conditions = array('1');
        if (isset($p['where']) && !empty($p['where'])) {
            $where = ' AND ' . trim($p['where']);
            unset($p['where']);
        }
        foreach ($p as $k => $v) {
            $v = addslashes(trim($v));
            $conditions[] = "{$k}='{$v}'";
        }
        $sql = 'SELECT cph.*, u.vendor_id  FROM ' . self::getTable() . ' AS cph ';
        $sql .= 'LEFT JOIN users AS u on (u.user_id = cph.user_id ) ';
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions) . $where;
        }

        return $conn->GetAll($sql);
    }

    function getAllData($p, $keyField = 'user_id')
    {
        global $conn;
        $qw = ' 1 ';
        $user_id = '';
        if (isset($p['user_id']) && !empty($p['user_id'])) {
            $user_id = $p['user_id'];
            if (is_array($p['user_id'])) {
                $qw .= ' AND user_id IN (' . implode(',', $user_id). ') ';
            } else {
                $user_id = trim($user_id);
                $qw .= ' AND user_id=' . $user_id . ' ';
            }
        }
        if (isset($p['user_type']) && !empty($p['user_type'])) {
            $p['role'] = $p['user_type'];
        } 

        if (isset($p['role']) && !empty($p['role'])) {
            $role = trim($p['role']);
            $qw .= ' AND role = \'' . $role . '\'';
        } else if (empty($user_id)) {
            $qw .= ' AND role=\'copy writer\'';
        }

        if (isset($p['where']) && !empty($p['where'])) {
            $where = trim($p['where']);
            $qw .= $where;
        }

        $sql = 'SELECT user_id, month FROM ' . self::getTable() . ' WHERE ' . $qw;

        if (isset($p['order']) && !empty($p['order'])) {
            $order = trim($p['order']);
            $sql .= ' ORDER BY ' . $order;
        } else {
            $sql .= ' ORDER BY month, user_id';
        }
        $data = $conn->GetAll($sql);
        
        $result = array();
        foreach ($data as $row) {
            // added by nancy xu 2011-05-05 11:16
            $month = showMonth($row['month']);
            // payment twice per month
            if (!empty($keyField)) {
                $user_id = $row[$keyField];
                if (!isset($result[$user_id])) {
                    $result[$user_id] = array();
                }
                $result[$user_id][$row['month']] = $month;
            } else {
                $result[$row['month']] = $month;
            }
        }
        return $result;
    }

    function getPaymentMonthByParam($p = array())
    {
        if (!isset($p['user_type']) || empty($p['user_type'])) {
            $p['user_type'] = 'copy writer';
        }
        $result = self::getAllData($p, null);
        return $result;
    }

    function getMMonth($func = 'MIN')
    {
       global $conn;
       $sql = 'SELECT ' . $func . '(month) FROM ' . self::getTable();
      return $conn->GetOne($sql);
    }

    function getPaymentMonths($p, $users, $key = 'monthes')
    {
        $result = self::getAllData($p);
        foreach ($users as $user_id => $row) {
            $monthes = isset($result[$user_id]) ? $result[$user_id] : array();
            if (!empty($key)) {
                $users[$user_id][$key] = addFirstMonthAndLastMonthToCurrentMonthes($monthes);
            } else {
                $users[$user_id] = addFirstMonthAndLastMonthToCurrentMonthes($monthes);
            }
        }
        foreach ($result as $user_id => $monthes) {
            if (!empty($key)) {
                $users[$user_id][$key] = addFirstMonthAndLastMonthToCurrentMonthes($monthes);
            } else {
                $users[$user_id] = addFirstMonthAndLastMonthToCurrentMonthes($monthes);
            }
        }
        return $users;
    }

	public static function store( $hash )
	{
		global $conn, $feedback;
        if (isset($hash['user_id']) && $hash['month']) 
            $total = self::getCountByParam(array('user_id' => $hash['user_id'], 'month' => $hash['month']));
        else
            return false;
        $sql = self::getSqlByData($hash, $total);
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

    function getCountByParam($param)
    {
        global $conn, $feedback;
        foreach ($param as $k=> $value) {
            $param[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
        }
        if (!empty($param)) {
            $sql = "SELECT COUNT(*) AS num FROM " . self::getTable() . ' ';
            $conditions = array();
            foreach ($param as $k => $v) {
                $conditions[] = "{$k}='{$v}'";
            }
            $sql .= ' WHERE ' . implode(" AND ", $conditions);
            $result = $conn->GetOne($sql);
            return $result['num'];
        }
        return false;
    }
}
?>