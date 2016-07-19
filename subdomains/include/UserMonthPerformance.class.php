<?php

class UserMonthPerformance
{
	function __construct()
	{

	}

    function UserMonthPerformance()
    {
        $this->__construct();
    }

    public function add($bind)
    {    
        global $conn, $feedback;
        foreach($bind as $k => $value) {
            $bind[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        $conn->StartTrans();
        $id = $conn->GenID('seq_user_month_performance_performance_id');
        $bind['performance_id'] = $id;
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $sql  = "INSERT INTO `user_month_performance` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }

    function getMaxMonthUserList($user_id = null)
    {
        $list = array();
        if (!empty($user_id)) {
            $result = self::__getResult(array('user_id' => $user_id , 'columns' => 'MAX(report_month) AS month, user_id', 'groupby' => 'user_id'));
        }
        foreach($result as $row) {
            $list[$row['user_id']] = $row['month'];
        }
        return $list;
    }

    public function update($data)
    {
        global $conn;
        foreach($data as $k => $value) {
            $data[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        if (isset($data['performance_id']) && $data['performance_id']) {
            $performance_id = $data['performance_id'];
            unset($data['performance_id']);
            $sql = ' UPDATE `user_month_performance` SET ';
            $sets = array();
            foreach ($data as $field => $value) {
                $sets[] = "{$field}='{$value}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE performance_id=' . $performance_id;
            $rs = &$conn->Execute($sql);
        }
    }

    public function getInfo($performance_id)
    {
       $p = array('performance_id'=>$performance_id);
       $list = self::__getResult($p);
       return $list[0];
    }


    public function getIdByUserIDAndMonth($user_id, $month, $role)
    {
        $result = self::__getResult(array('user_id' => $user_id, 'report_month' =>$month , 'role'=> $role, 'columns' => 'performance_id'));
        return $result[0]['performance_id'];
    }

    public function store($data)
    {
        if ((!isset($data['performance_id'])||empty($data['performance_id'])) && isset($data['user_id']) && !empty($data['user_id'])) {
            $month = $data['report_month'];
            $role = $data['role'];
            $performance_id = self::getIdByUserIDAndMonth($data['user_id'], $month, $role);
            $data['performance_id'] = $performance_id;
        }
        if (isset($data['performance_id']) && !empty($data['performance_id'])) {
            self::update($data);
        } else {
            self::add($data);
        }
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        if (isset($p['performance_id'])) {
            $performance_id = addslashes(htmlspecialchars(trim($p['performance_id'])));
            if(is_numeric($performance_id) && $performance_id > 0)
                $condition[] = "performance_id={$performance_id}";
        }
        if (isset($p['user_id'])) {
            $user_id = $p['user_id'];
            if(is_numeric($user_id) && $user_id > 0) {
                $user_id = addslashes(htmlspecialchars(trim($user_id)));
                $condition[] = "user_id={$user_id}";
            } else if (!empty($user_id) && is_array($user_id)) {
                $condition[] = "user_id IN ('" . implode("','", $user_id) . "')";
            }
        }

        if (isset($p['report_month'])) {
            $report_month = $p['report_month'];
            if(is_numeric($report_month) && $report_month > 0) {
                $condition[] = "report_month={$report_month}";
            }
        }
        if (isset($p['role'])) {
            $role = $p['role'];
            if(!empty($role)) {
                $condition[] = "role='{$role}'";
            }
        }

        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        $groupby = isset($p['groupby']) ? addslashes(htmlspecialchars(trim($p['groupby']))) : '';
        if (!empty($groupby)) $qw .= ' GROUP BY ' . $groupby;
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        else 
            $qw .= " ORDER BY performance_id DESC ";
        $sql = " SELECT {$query} FROM `user_month_performance` ";
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

    function getAllMonthes()
    {
        global $conn;
        $sql = "SELECT DISTINCT report_month FROM user_month_performance ORDER By report_month DESC";
        $result = $conn->GetAll($sql);
        $monthes = array();
        foreach ($result as $k => $v) {
            $month = $v['report_month'];
            $monthes[$month] = splitMonth($month);
        }
        return $monthes;
    }
    
    function getPerformanceReport($p = array())
    {
        global $conn;
        global $g_pager_params;
        $qw = "AND up.role = 'copy writer' ";
        if (isset($p['search_keyword']) && !empty($p['search_keyword'])) {
            $search_keyword = $p['search_keyword'];
            $qw .= ' AND (up.user_name LIKE \'%' . $search_keyword . '%\' OR CONCAT(up.first_name, \' \', up.last_name) LIKE \'%' . $search_keyword. '%\' ) ';
        }
        if (isset($p['rmonth']) && !empty($p['rmonth'])) {
            $qw .= ' AND up.report_month=' . $p['rmonth'] . ' ';
        }
        $rs = &$conn->GetRow("SELECT COUNT(up.performance_id) AS count FROM user_month_performance as  up, users  as u WHERE up.user_id=u.user_id AND u.status != 'D' ".$qw);

        if (!isset($rs['count']) || $rs['count'] == 0) {
            return false;
        }
        $count = $rs['count'];

        if (trim($p['limit']['number']) > 0) {
            $perpage= $p['limit']['number'];
        } else {
            $perpage= 50;
        }
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $q = "SELECT up.* FROM user_month_performance AS up, users AS u WHERE u.user_id=up.user_id AND  u.status != 'D' ".$qw;
        if (trim($p['top']['number']) > 0) {
            $q .= "ORDER BY up.ranking DESC";
        } else {
            $q .= "ORDER BY up.report_month DESC, up.user_name ASC";
        }
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));

        if ($rs) {
            $users = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
                $users[$user_id]['month'] = splitMonth($rs->fields['report_month']);
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
    }
}
?>
