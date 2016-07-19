<?php
class LabelFieldDescription{
    private $desc_id ;
    private $title;
    private $unique_key;
    private $description;
    private $created;

	private function __construct()
	{
        $this->desc_id = 0;
        $this->title = null;
        $this->unique_key = null;
        $this->description = null;
        $this->created = date("Y-m-d H:i:s");
	}

    function LabelFieldDescription()
    {
        $this->__construct();
    }

    public function getInfo($desc_id)
    {
       $p = array('desc_id'=>$desc_id);
       $list = self::__getResult($p);
       return $list[0];
    }

    public function getInfoByUniqueKey($unique_key)
    {  
       $p = array('unique_key'=>$unique_key);
       $result = self::__getResult($p);
       return $result[0];
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);

        if(is_numeric($desc_id) && $desc_id > 0)
            $condition[] = "desc_id={$desc_id}";
        if(!empty($unique_key))
        {
            $condition[] = "unique_key='{$unique_key}'";
        }

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
        $sql = " SELECT {$query} FROM label_field_description";
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

    function getID($p)
    {
        $p['columns'] = 'desc_id';
        $p['single_column'] = 'desc_id';
        $result = self::__getResult($p);
        if (!empty($result)) return $result[0];
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
        $sql = 'INSERT INTO label_field_description (%s) VALUES (%s)';
        $fields = array_keys($data);
        $field = implode(',', $fields);
        $value = "'" . implode("','", $data) . "'";
        $sql = sprintf($sql, $field, $value);
        return $conn->Execute($sql);
    }

    function update($data, $desc_id)
    {
        global $conn;
        $sql = 'UPDATE label_field_description SET %s  WHERE desc_id=%s';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . $v .  '\'';
        }
        $set = implode(",", $sets);
        $sql = sprintf($sql, $set, $desc_id);
        // echo $sql;
        return $conn->Execute($sql);
    }
}
?>