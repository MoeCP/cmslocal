<?php
class ArticleSearchresult {
    
    
	function __construct()
	{

	}

    function ArticleSearchresult()
    {
        $this->__construct();
    }

    public static function store( $hash ) 
    {
        global $conn, $feedback;
        $data = array();
        foreach ($hash as $k => $v) {
            $data[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }
        if (!isset($data['created'])) $data['created'] = date("Y-m-d H:i:s");
        $sql = 'INSERT INTO article_searchresults (%s) VALUES (%s)';
        $fields = array_keys($data);
        $field = implode(',', $fields);
        $value = "'" . implode("','", $data) . "'";
        $sql = sprintf($sql, $field, $value);
        return $conn->Execute($sql);
    }

    public static function getResult($param = array())
    {
        global $conn;
        $sql = "SELECT * FROM article_searchresults AS asr ";
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                if (!is_array($v)) {
                    $v = trim($v);
                }
                if (empty($v)) unset($param[$k]);
                else $param[$k] = $v;
            }
            extract($param);
            if (isset($conditions)) {
                $sql .= 'WHERE ' . implode(" AND ", $conditions) . ' ';
            }
            if (isset($orderby)) {
                $sql .= 'ORDER BY ' . $orderby . ' ' ;
            }

            if (isset($limit)) {
                $start =  isset($start) ? $start : 0;
                $sql .= 'LIMIT ' . $start .  ',  ' . $limit;
            }
        }
        return $conn->GetAll($sql);
    }
}
?>
