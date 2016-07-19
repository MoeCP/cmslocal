<?php

class UserMonthScore
{
	function __construct()
	{

	}

    function UserMonthScore()
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
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $sql  = "INSERT INTO `user_month_score` ({$fields}) VALUES ({$values}) ";
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
        if (isset($data['score_id']) && $data['score_id']) {
            $score_id = $data['score_id'];
            unset($data['score_id']);
            $sql = ' UPDATE `user_month_score` SET ';
            $sets = array();
            foreach ($data as $field => $value) {
                $sets[] = "{$field}='{$value}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE score_id=' . $score_id;
            $rs = &$conn->Execute($sql);
        }
    }

    public function getInfo($score_id)
    {
       $p = array('score_id'=>$score_id);
       $list = self::__getResult($p);
       return $list[0];
    }


    public function getIdByUserIDAndMonth($user_id, $month, $role)
    {
        $result = self::__getResult(array('user_id' => $user_id, 'report_month' =>$month , 'role'=> $role, 'columns' => 'score_id'));
        return $result[0]['score_id'];
    }

    public function store($data)
    {
        if ((!isset($data['score_id'])||empty($data['score_id'])) && isset($data['user_id']) && !empty($data['user_id'])) {
            $month = $data['report_month'];
            $role = $data['role'];
            $score_id = self::getIdByUserIDAndMonth($data['user_id'], $month, $role);
            $data['score_id'] = $score_id;
        }
        if (isset($data['score_id']) && !empty($data['score_id'])) {
            self::update($data);
        } else {
            unset($data['score_id']);
            self::add($data);
        }
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        if (isset($p['score_id'])) {
            $score_id = addslashes(htmlspecialchars(trim($p['score_id'])));
            if(is_numeric($score_id) && $score_id > 0)
                $condition[] = "score_id={$score_id}";
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
            $qw .= " ORDER BY score_id DESC ";
        $sql = " SELECT {$query} FROM `user_month_score` ";
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
        $sql = "SELECT DISTINCT report_month FROM user_month_score ORDER By report_month DESC";
        $result = $conn->GetAll($sql);
        $monthes = array();
        foreach ($result as $k => $v) {
            $month = $v['report_month'];
            $monthes[$month] = splitMonth($month);
        }
        return $monthes;
    }

    function getPerformanceReportNoPage($p = array())
    {
        global $conn;
        $qw = array( "up.permission =1");
        if (!empty($p['user_id'])) {
            $qw[] = "up.user_id=" . $p['user_id'];
        }
        $sql  = " SELECT * FROM user_month_score AS up WHERE " . implode(" AND ", $qw);
        $sql .= " ORDER BY up.report_month DESC ";
        $result = $conn->GetAll($sql);
        foreach ($result as $k => $row) {
            $result[$k]['month'] = splitMonth($row['report_month']);
        }
        return $result;
    }
    
    function getPerformanceReport($p = array())
    {
        global $conn;
        global $g_pager_params;
        $qw = "AND up.permission =1 ";
        if (isset($p['search_keyword']) && !empty($p['search_keyword'])) {
            $search_keyword = $p['search_keyword'];
            $qw .= ' AND (up.user_name LIKE \'%' . $search_keyword . '%\' OR CONCAT(up.first_name, \' \', up.last_name) LIKE \'%' . $search_keyword. '%\' ) ';
        }
        if (isset($p['rmonth']) && !empty($p['rmonth'])) {
            $qw .= ' AND up.report_month=' . $p['rmonth'] . ' ';
        }
        $rs = &$conn->GetRow("SELECT COUNT(up.score_id) AS count FROM user_month_score as  up, users  as u WHERE up.user_id=u.user_id AND u.status != 'D' ".$qw);

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
        $q = "SELECT up.* FROM user_month_score AS up, users AS u WHERE u.user_id=up.user_id AND  u.status != 'D' ".$qw;
        if (trim($p['top']['number']) > 0) {
            $q .= "ORDER BY up.Score DESC";
        } else {
            $q .= "ORDER BY up.report_month DESC, up.user_name ASC";
        }
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));

        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
                $users[$user_id]['month'] = splitMonth($rs->fields['report_month']);
                $users[$user_id]['total'] = 0;
                $users[$user_id]['total_assigned'] = 0;
                $users[$user_id]['total_submit'] = 0;
                $users[$user_id]['total_editor_approval'] = 0;
                $users[$user_id]['total_client_approval'] = 0;
                $user_id_arr[] = $user_id;

                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        $qw = "";
        $user_field_name = 'ck.copy_writer_id';
        $qw .= "AND " .$user_field_name. " IN (".implode(",", $user_id_arr).") ";
        if (isset($p['rmonth']) && !empty($p['rmonth'])) {
            $timestamp = str_split($p['rmonth'], 4);
            $timestamp = implode("-", $timestamp);
            $timestamp = strtotime($timestamp."-08");
            $p['date_start'] = date('Y-m-01', $timestamp);
            $p['date_end'] = date('Y-m-t', $timestamp);
        } else {
            $p['date_start'] = date('Y-m-01');
            $p['date_end'] = date('Y-m-t');
        }

        // get total assigned keyword for each user
        $where = $qw . User::generateDateConditions($p, 'ck.date_assigned');
        User::getCountGroupByUsers($users, 'total', $user_field_name, $where);
        // get total submit keyword for each user
        $where = $qw . "AND ar.article_status REGEXP '^(1|1gc|3|4|5|6|99)$'" . User::generateDateConditions($p, 'ar.cp_updated');
        User::getCountGroupByUsers($users, 'total_submit', $user_field_name, $where);
        $where = $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'" . User::generateDateConditions($p, 'ar.approval_date');
        User::getCountGroupByUsers($users, 'total_editor_approval', $user_field_name, $where);
        $where = $qw . "AND ar.article_status REGEXP '^(5|6|99)$'" . User::generateDateConditions($p, 'ar.client_approval_date');
        User::getCountGroupByUsers($users, 'total_client_approval', $user_field_name, $where);
        /*
        if ($user_type == 'all editor' || $user_type == 'editor' || $user_type == 'admin' || $user_type == 'project manager') {
            $where = $qw . "AND ar.article_status REGEXP '^(1gc|3)$'" . User::generateDateConditions($p, 'ar.google_approved_time');
            User::getCountGroupByUsers($users, 'total_pending_approval', $user_field_name, $where);
        }
        if ($is_pagination) {
            $result = array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
        } else {
            $result = $users;
        }
        return $result;
        */

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);

    }
}
?>
