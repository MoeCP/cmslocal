<?php

class UserPerformance
{
	function __construct()
	{

	}

    function UserPerformance()
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
        $id = $conn->GenID('seq_user_performance_user_performance_id');
        $bind['user_performance_id'] = $id;
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $sql  = "INSERT INTO `user_performance` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }

    public function update($data)
    {
        global $conn;
        foreach($data as $k => $value) {
            $data[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        if (isset($data['user_performance_id']) && $data['user_performance_id']) {
            $user_performance_id = $data['user_performance_id'];
            unset($data['user_performance_id']);
            $sql = ' UPDATE `user_performance` set ';
            $sets = array();
            foreach ($data as $field => $value) {
                $sets[] = "{$field}='{$value}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE user_performance_id=' . $user_performance_id;
            $rs = &$conn->Execute($sql);
        }
    }

    public function getInfo($user_performance_id)
    {
       $p = array('user_performance_id'=>$user_performance_id);
       $list = self::__getResult($p);
       return $list[0];
    }


    public function getIdByUserID($user_id)
    {
        $result = self::__getResult(array('user_id' => $user_id, 'columns' => 'user_performance_id'));
        return $result[0]['user_performance_id'];
    }

    public function store($data)
    {
        if ((!isset($data['user_performance_id'])||empty($data['user_performance_id'])) && isset($data['user_id']) && !empty($data['user_id'])) {
            $user_performance_id = self::getIdByUserID($data['user_id']);
            $data['user_performance_id'] = $user_performance_id;
        }
        if (isset($data['user_performance_id']) && !empty($data['user_performance_id'])) {
            self::update($data);
        } else {
            self::add($data);
        }
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        if (isset($p['user_performance_id'])) {
            $user_performance_id = addslashes(htmlspecialchars(trim($p['user_performance_id'])));
            if(is_numeric($user_performance_id) && $user_performance_id > 0)
                $condition[] = "user_performance_id={$user_performance_id}";
        }
        if (isset($p['user_id'])) {
            $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
            if(is_numeric($user_id) && $user_id > 0)
                $condition[] = "user_id={$user_id}";
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
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        else 
            $qw .= " ORDER BY user_performance_id DESC ";
        $sql = " SELECT {$query} FROM `user_performance` ";
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
    
    function getPerformanceReport($p = array())
    {
        global $conn;
        global $g_pager_params;
        $qw = "AND up.role = 'copy writer' ";
        if (isset($p['search_keyword']) && !empty($p['search_keyword'])) {
            $search_keyword = $p['search_keyword'];
            $qw .= ' AND (up.user_name LIKE \'%' . $search_keyword . '%\' OR CONCAT(up.first_name, \' \', up.last_name) like \'%' . $search_keyword. '%\' ) ';
        }
        $rs = &$conn->GetRow("SELECT COUNT(up.user_performance_id) AS count FROM user_performance as  up, users  as u WHERE up.user_id=u.user_id AND u.status != 'D' ".$qw);

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
        $q = "SELECT up.* FROM user_performance AS up, users AS u WHERE u.user_id=up.user_id AND  u.status != 'D' ".$qw;
        if (trim($p['top']['number']) > 0) {
            $q .= "ORDER BY up.ranking DESC";
        } else {
            $q .= "ORDER BY u.user_name ASC";
        }
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));

        if ($rs) {
            $users = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
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
