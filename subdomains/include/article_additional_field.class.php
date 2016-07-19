<?php
class ArticleAdditionalField{
    private $id;
    private $article_id;
    private $small_image;
    private $large_image;
    private $image_credit;
    private $image_caption;
    private $meta_description;
    private $blurb;
    private $optional_field;

	function __construct()
	{
        $this->id = 0;
        $this->article_id = 0;
        $this->small_image = null;
        $this->large_image = null;
        $this->image_credit = null;
        $this->image_caption = null;
        $this->meta_description = null;
        $this->blurb = null;
        $this->optional_field = null;
	}

    function ArticleAdditionalField()
    {
        $this->__construct();
    }

    public function getInfo($id)
    {
       $p = array('id'=>$id);
       $list = self::__getResult($p);
       return $list[0];
    }

    public function getInfoByArticleId($article_id)
    {
       $p = array('article_id'=>$article_id);
       $list = self::__getResult($p);
       return $list[0];
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        $id = addslashes(htmlspecialchars(trim($p['id'])));
        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));

        if(is_numeric($id) && $id > 0)
            $condition[] = "id={$id}";
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
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        $sql = " SELECT {$query} FROM article_additional_fields";
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

    function storeFromArticle($data = array())
    {
        global $conn;
        $article_id = $data['article_id'];
        $id = self::getIdByArticleId($article_id);
        $fields = array('article_id', 'small_image', 'large_image', 'image_credit', 'image_caption', 'meta_description', 'blurb', 'category_id','optional_field');
        $hash = array();
        foreach ($data as $k => $v) {
            if (in_array($k, $fields)) {
                if ($k== 'category_id' && empty($v)) continue;
                $hash[$k] = addslashes(htmlspecialchars(trim($v)));
                unset($data[$k]);
            }
        }
        if ($id > 0) {
            $hash['modified_by'] = User::getID();
            $hash['modified'] = time();
        } else {
            $hash['created_by'] = User::getID();
            $hash['created'] = time();
        }
        self::store($hash, $id);
        return $data;
    }

     function getIdByArticleId($article_id)
    {
         global $conn, $feedback;
         if (empty($article_id) || !is_numeric($article_id)) {
             $feedback = 'Invalid article id, please to check';
             return false;
         }
         $sql = "SELECT id FROM article_additional_fields WHERE article_id = '" . addslashes(htmlspecialchars(trim($article_id))). "'";
         return $conn->GetOne($sql);
    }

    function store($data, $id = null)
    {
        if ($id) {
            $ret = self::update($data, $id);
        } else {
            $ret = self::insert($data);
        }
        return $ret;
    }

    function insert($data)
    {
        global $conn;
        $sql = 'INSERT INTO article_additional_fields (%s) VALUES (%s)';
        $fields = array_keys($data);
        $field = implode(',', $fields);
        $value = "'" . implode("','", $data) . "'";
        $sql = sprintf($sql, $field, $value);
        return $conn->Execute($sql);
    }

    function update($data, $id)
    {
        global $conn;
        $sql = 'UPDATE article_additional_fields SET %s  WHERE id=%s';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . $v .  '\'';
        }
        $set = implode(",", $sets);
        $sql = sprintf($sql, $set, $id);
        return $conn->Execute($sql);
    }

}
?>