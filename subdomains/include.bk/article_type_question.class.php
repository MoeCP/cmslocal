<?php
class ArticleTypeQuestion
{
	private $qid;
	private $type_id;
	private $question;

	function __construct()
	{
		$this->type_id = 0;
		$this->question = '';
		$this->qid = 0;
	}

    function ArticleTypeQuestion()
    {
        $this->__construct();
    }

    public static function getTableName()
    {
        return 'article_type_questions';
    }

    function storeBatchData($p)
    {
        global $conn, $feedback;
        if (strlen(trim($p['type_id'])) == 0) {
            $feedback = 'Please specify the article type';
            return false;
        }
        $type_id = $p['type_id'];
        if (trim($p['question']) == '') {
            $feedback = 'Please specify the question';
            return false;
        }
        $questions = explode("\n", $p['question']);
        // $sql = "SELECT count(*) FROM " . self::getTableName() . ' WHERE type_id = ' . $type_id;
        $inserts = array();
        foreach ($questions as $v) {
            $v = addslashes(trim($v));
            $inserts[] = "({$type_id}, '{$v}')";
        }
        $sql = "Insert INTO " . self::getTableName() . " (`type_id` , `question`) values " . implode(",", $inserts);
        $conn->Execute($sql);
        $feedback = 'Success';
        return true;
    }

    function getQuestionsByParam($p = array())
    {
        global $conn, $feedback;
        if (empty($p)) {
            $feedback = 'Please specify some paramter';
            return false;
        }
        $qw = ' WHERE  1 ';
        if (isset($p['type_id']) && strlen($p['type_id']) > 0) {
            $qw .= ' AND type_id = ' . $p['type_id'];
        } else if (isset($p['type_id'])) {
            $feedback = 'Please specify the type id';
            return false;
        }
        $sql = "select * from " . self::getTableName() .  $qw;
        return $conn->GetAll($sql);
    }

    function getQuestions($p = array())
    {
        global $conn, $feedback;
        $qw = ' WHERE 1';
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $qw.= " AND ".$search->getLikeCondition("CONCAT(atq.question, at.type_name)")." ";
            }
        }
        if (trim($p['article_type']) != '') {
            $qw .= " AND atq.type_id = " . $p['article_type'];
        }
        $sql = "SELECT count(*) FROM " . self::getTableName() . ' AS atq  LEFT JOIN article_type AS at ON (at.type_id=atq.type_id) '.  $qw;
        $count = $conn->GetOne($sql);
        if ($count == 0 || !isset($count)) {
            return false;
        }
        $perpage = 50;
        if (trim($p['perPage']) > 0) {
            $perpage = $p['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems'   => $count
        );

        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $sql = "SELECT atq.*, at.type_name FROM " . self::getTableName() . " AS atq " 
                 ."LEFT JOIN article_type AS at ON (at.type_id=atq.type_id) ".  $qw;
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
    }
}
?>