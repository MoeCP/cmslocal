<?php
class ArticleType{

	private $type_id;
	private $type_name;
	private $type_cost;

	function __construct()
	{
		$this->type_id = 0;
		$this->type_name = '';
		$this->type_cost = 0;
	}

    function ArticleType()
    {
        $this->__construct();
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	

        $type_id = mysql_escape_string(htmlspecialchars(trim($hash['type_id'])));
		$bind['type_id'] = $type_id;
		$bind['type_name'] = mysql_escape_string(htmlspecialchars(trim($hash['type_name'])));
		$bind['type_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_cost'])));
		$bind['cp_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_cost'])));
		$bind['editor_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['editor_cost'])));
		$bind['pay_by_article'] = mysql_escape_string(htmlspecialchars(trim($hash['pay_by_article'])));
        if (empty($bind['pay_by_article'])) $bind['pay_by_article'] = 0;
		$bind['qd_listid'] = mysql_escape_string(htmlspecialchars(trim($hash['qd_listid'])));
        if (empty($bind['qd_listid'])) $bind['qd_listid'] = 0;
		$bind['cp_article_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['cp_article_cost'])));
		$bind['editor_article_cost'] = mysql_escape_string(htmlspecialchars(trim($hash['editor_article_cost'])));
		$parent_id= mysql_escape_string(htmlspecialchars(trim($hash['parent_id'])));
		$bind['parent_id'] = $parent_id;
        if (isset($hash['is_hidden']) && $hash['is_hidden'] == 1)  {
            $bind['is_hidden'] = mysql_escape_string(htmlspecialchars(trim($hash['is_hidden'])));
        }
		// check the required fields - START
		foreach ($bind as $k => $value)
		{
			switch ($k)
			{
			case 'type_name':
				if (empty($value))
				{
					$feedback  = "please input type name";
					return false;
				}
                else
                {
                    if(!self::checkTypeNameUnique($value, $bind['type_id']))
                    {
                        $feedback = "Type Name: \\'{$value}\\' is duplicated, please to check!";
                        return false;
                    }
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
            if (strlen($bind['type_id']) == 0)
            {
                $bind['type_id'] = $conn->GenID('seq_article_type_type_id');
            } 
            else
            {
                $old_info = self::getInfo($type_id);
                $old_parent_id = $old_info['parent_id'];
            }
			$values   = "'". implode("', '", $bind) . "'";
			$bind_keys = array_keys($bind);
			$fields    = "`" . implode("`, `", $bind_keys) . "`";
			$sql      = "REPLACE INTO  `article_type` ({$fields}) VALUES ({$values}) ";
            $conn->Execute($sql);
            
            if (!isset($old_parent_id)&&$parent_id>=0 || $old_parent_id != $parent_id) {
                if (isset($old_parent_id) && $old_parent_id >= 0) {
                    $sql = "UPDATE `article_type` set total_nodes= total_nodes -1 WHERE  total_nodes > 0 and type_id=" . $old_parent_id;
                    $conn->Execute($sql);
                }
                if ($parent_id >= 0) {
                    $sql = "UPDATE `article_type` set total_nodes= total_nodes +1 WHERE   type_id=" . $parent_id;
                    $conn->Execute($sql);
                }
            }
            $ok = $conn->CompleteTrans();
		}
		// assembled sql - FINISHED

		if ($ok)
		{
			$feedback = 'Success';
			return $bind['type_id'];
		}
		else
		{
		    $feedback = 'Failure, Please try again';
			return false;
		}
	}

    function updateTotalChildren()
    {
        global $conn;
        $sql = 'SELECT count( * ) AS total, parent_id FROM `article_type` WHERE 1 GROUP BY parent_id';
        $result = $conn->GetAll($sql);
        $pids = array();
        foreach ($result as $k => $row) {
            $pids[] = $row['parent_id'];
            $sql = "UPDATE `article_type` set total total_nodes";
        }
    }
    
    function getAllTypes($p = array())
    {
        $p['index']   = 'type_id';
        $p['fields']   = 'type_name';
        $p['columns'] = 'type_id, type_name';
        $p['orderby'] = 'parent_id, type_name';
        $types = self::getList($p);
        return $types;
    }

    function getAllLeafNodes($p = array())
    {
        $p['where'] = 'parent_id >= 0';
        $p['total_nodes'] = 0;
        return self::getAllTypes($p);
    }

    public static function getInfo($type_id)
    {
        $p['type_id'] = $type_id;
        $types = self::getList($p);
        return $types[0];
    }

    function checkTypeNameUnique($type_name, $type_id)
    {
        $p['type_name'] = $type_name;
        $p['fields']   = 'num';
        $p['columns'] = 'COUNT(type_id) AS num';
        if (strlen($type_id) > 0 && is_numeric($type_id))
        {
            $p['where'] = "type_id!={$type_id}";
        }
        $types = self::getList($p);
        if ($types[0] > 0)
            $result = false;
        else 
            $result = true;
        return $result;
    }

    function getTypeByID($type_id)
    {
        $p['type_id'] = $type_id;
        $types = self::getList($p);
        return $types[0];
    }
    /**
     * @param array $p
     * @return array
     */
    function getList($p = array())
    {   
        global $conn;
        $condition = array();
        foreach ($p as $k => $v) {
            if ($k == 'where') {
                continue;
            } else {
                $v = addslashes(htmlspecialchars(trim($v)));
                if (strlen($v) || !empty($v)) {
                    $p[$k] = $v;
                } else {
                    unset($p[$k]);
                }
            }
        }
        extract($p);
        if (isset($type_id) && is_numeric($type_id))
            $condition[] = "type_id={$type_id}";
        if (isset($parent_id) && is_numeric($parent_id))
            $condition[] = "parent_id={$parent_id}";
        if (isset($total_nodes) && is_numeric($total_nodes))
            $condition[] = "total_nodes={$total_nodes}";
        if (isset($type_name))
            $condition[] = "type_name='{$type_name}'";
        if (isset($is_hidden))
            $condition[] = "is_hidden='{$is_hidden}'";
        // extra condition
        if (isset($where))
            $condition[] = $where;
        // type is string
        // return array index

        // type is string, sperated by ";"
        // return fields
        $fields = isset($fields) ? explode(";", $fields) : array();
        // type is string
        // select fields
        if (!isset($columns))
            $columns = " * ";
            
        $sql = "SELECT {$columns} FROM article_type ";
        if (count($condition))
            $sql .= "WHERE " . implode(" AND ", $condition);
        else
            $sql .= " WHERE 1=1 ";
        $sql .= " AND is_inactive=0 ";
        if (!empty($orderby)) {
            $sql .= ' ORDER BY ' . $orderby;
        }
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