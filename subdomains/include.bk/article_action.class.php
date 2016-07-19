<?php
class ArticleAction{

    private $action_id;
    private $article_id;
    private $title;
    private $version;
    private $new_version;
    private $status;
    private $new_status;
    private $created_time;
    private $opt_id;
    private $opt_name;
    private $opt_type;
    private $editor_id;

	private function __construct()
	{
        $this->action_id = 0;
        $this->article_id = 0;
        $this->title = '';
        $this->version = 0;
        $this->new_version = 0;
        $this->status = 0;
        $this->new_status = 0;
        $this->created_time = 0;
        $this->opt_id = 0;
        $this->opt_name = '';
        $this->opt_type = 0;
        $this->editor_id = 0;
	}

    function ArticleAction()
    {
        $this->__construct();
    }

    /**
     * filter the field not existing this class
     * @author snug xu <snugxxn@gmail.com>
     * @param array $hash
     * @return array 
     */
    private function filterUselessFields($hash)
    {
        foreach ($hash as $key => $value) 
        {
            switch ($key)
            {
            case 'action_id':
                // do nothing
                break;
            case 'article_id':
                // do nothing
                break;
            case 'title':
                // do nothing
                break;
            case 'version':
                // do nothing
                break;
            case 'copy_writer_id':
                // do nothing
                break;
            case 'new_copy_writer_id':
                // do nothing
                break;
            case 'curr_flag':
                // do nothing
                break;
            case 'new_version':
                // do nothing
                break;
            case 'status':
                // do nothing
                break;
            case 'new_status':
                // do nothing
                break;
            case 'created_time':
                // do nothing
                break;
            case 'opt_id':
                // do nothing
                break;
            case 'opt_name':
                // do nothing
                break;
            case 'editor_id':
            case 'opt_type':
                // do nothing
                break;
            default:
                unset($hash[$key]);
                break;
            }
        }
        return $hash;
    }

    /**
     * store article action
     * @global $conn database connenction
     * @author snug xu <snugxxn@gmail.com>
     * @param array $hash: array from page
     * @return bool
     */
    public static function store($hash)
    {
        global $conn;
        if ($hash['status'] == 'A') {
            $hash['status'] = $hash['article_status'];
        }
        $posted_by = array();
        if (isset($hash['posted_by']) && !empty($hash['posted_by'])) {
            $posted_by = unserialize($hash['posted_by']);
        } 
        $field = '';
        $status = $hash['status'];
        $new_status = $hash['new_status'];
        if (($status == '0' || $status == '2') && $new_status == '1' ) {
            $field = 'submitted';
        } elseif ($status == '4' && $new_status == '5' ) {
            $field = 'client_approved';
        } elseif ($status == '5' && $new_status == '6' ) {
            $field = 'published';
        } else if ($status == '4' && $new_status == '3' ){
            $field = 'client_rejected';
        } else if (($status == '1gd' || $status == '3' || $status == '1gc' || $status == '4') && $new_status == '2' ){
            $field = 'rejected';
        } else if (($status == '1gc' || $status == '3') && $new_status == '4'){
            $field = 'approved';
        } else if ($hash['status'] == '1' && $hash['new_status'] == '1gd'){
            $field = 'duplicated';
        } else if ($hash['status'] == '1' && $hash['new_status'] == '1gc'){
            $field = 'google_clean';
        // added by nancy xu 2012-05-24 15:06
        // disabled the article
        } else if ($hash['new_status'] == '99') {
            $field = 'disabled';
        }
        // end
        if (!empty($field)) {
            $posted_by[$field] = array(
                    'opt_id' => $hash['opt_id'],
                    'date_time' => $hash['created_time'],
                    'opt_name' => $hash['opt_name'],
                    'opt_type' => $hash['opt_type'],
             );
        } else {
            $posted_by = null;
        }

        $hash = self::filterUselessFields($hash);
        if (empty($hash['new_copy_writer_id']))
            $hash['new_copy_writer_id'] = $hash['copy_writer_id'];
        $conditions = array();
        if (strcasecmp($hash['status'], $hash['new_status']) != 0) {
            $conditions[] = "status='{$hash['status']}'";
            $conditions[] = "new_status='{$hash['new_status']}'";
            $conditions[] = "article_id = '{$hash['article_id']}'";
            $conditions[] = "new_copy_writer_id = {$hash['new_copy_writer_id']}";
            $conditions[] = "copy_writer_id = {$hash['copy_writer_id']}";
        } else if ($hash['new_version'] != $hash['version']){
            $conditions[] = "new_version='{$hash['new_version']}'";
            $conditions[] = "version='{$hash['version']}'";
            $conditions[] = "article_id = '{$hash['article_id']}'";
            $conditions[] = "new_copy_writer_id = {$hash['new_copy_writer_id']}";
            $conditions[] = "copy_writer_id = {$hash['copy_writer_id']}";
        } else if ($hash['copy_writer_id'] != $hash['new_copy_writer_id']){
            $conditions[] = "article_id = '{$hash['article_id']}'";
            $conditions[] = "new_version = version";
            $conditions[] = "new_status = status";
            $conditions[] = "new_copy_writer_id = {$hash['new_copy_writer_id']}";
            $conditions[] = "copy_writer_id = {$hash['copy_writer_id']}";
        }
        if (!empty( $conditions)) {
            $sql  = "UPDATE article_action ";
            $sql .= "SET curr_flag = 0  ";
            $sql .= "WHERE " . implode(" AND ", $conditions) ;
            $conn->Execute($sql);
        }
        $hash['curr_flag'] = 1;
        $action_id = $hash['action_id'];

        $hash['action_id'] = $conn->GenID('seq_article_action_action_id');

        foreach ($hash as $key => $value) 
        {
            $hash[$key] = "'" . mysql_escape_string(htmlspecialchars(trim($value))) . "'";
        }
        $hash_keys = array_keys($hash);

        $sql = "REPLACE INTO `article_action` (`" . implode("`, `", $hash_keys). "`) VALUES (" . implode(", ", $hash) . ")";
        $conn->Execute($sql);
        if (!empty($posted_by)) {
            $sql = "update articles set posted_by = '" . addslashes(serialize($posted_by)). "' where article_id = " . $hash['article_id'];
            $conn->Execute($sql);
        }
        return true;
    }

    //add by liu shu fen 17:27 2007-12-29
    function getArticleNum($p) {
        global $conn;
        $qw[] = " WHERE 1 ";
        if (isset($p['copywriter_id']) && !empty($p['copywriter_id'])) {
            $qw[] = " copy_writer_id=" . trim($p['copywriter_id']) . " ";
        }
        if (isset($p['status']) && !empty($p['status'])) {
            $qw[] = " status='" . trim($p['status']) . "' ";
        }
        if (isset($p['new_status']) && !empty($p['new_status'])) {
            $qw[] = " new_status='" . trim($p['new_status']) . "' ";
        }
        if (isset($p['article_id']) && !empty($p['article_id'])) {
            if (is_array($p['article_id'])) {
                $article_ids = implode(",", $p['article_id']);
            } else {
                $article_ids = trim($p['article_id']);
            }
            $qw[] = " article_id IN (" . $article_ids . ") ";
        }
        $sql = "SELECT COUNT(*) AS count FROM article_action ";
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $res = $conn->getAll($sql);
        if ($res) {
            return $res[0]['count'];
        } else {
            return null;
        }
    }//END

    
    /**
     * @param $result  array
     * @param $field string
     * @param $user_field_name string
     * @param $qw string
     * @param $group_by string
     */
    function getCountGroupByCampaigns($field = 'total_submit', $qw = '', $role = '' , $group_by='ck.campaign_id')
    {
        global $conn;
        if (!empty($role)) {
            $role_field = ($role == 'copy writer' ) ? 'ck.copy_writer_id' : 'ck.editor_id';
        }

        $query  = "SELECT COUNT(DISTINCT aa.article_id )  as " . $field . ", u.user_id, u.role, " . $group_by . ", cc.campaign_name \n";
        
        $query .= "FROM client_campaigns AS cc \n";
        $query .= "LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id) \n";
        $query .= "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id) \n";
        if (!empty($role)) {
            $query .= "LEFT JOIN users AS u ON (u.user_id = " . $role_field . " ) \n";
        } else {
            $query .= "LEFT JOIN client AS cl ON (cl.client_id=cc.client_id) \n";
            $query .= "LEFT JOIN users AS u ON (u.user_id=cl.project_manager_id) \n";
        }
        $query .= ' WHERE  aa.curr_flag = 1 ';
        $query .= $qw . " AND ck.status!='D' \n";
        if (!empty($role)) {
            $query .= "GROUP BY  " . $role_field . ", ".  $group_by ;
        } else {
            $query .= "GROUP BY  " . $group_by ;
        }
        return $conn->GetAll($query);
    } // end getCountGroupByCampaigns

    function getLastestKeywordGroupByCampaigns($field = 'total_submit', $qw = '', $group_by='ck.campaign_id', $order_by = 'aa.created_time desc')
    {
        global $conn;
        $query  = "SELECT COUNT(DISTINCT aa.article_id )  as " .$field.", ar.title, u.user_id, " . $group_by . ", cc.campaign_name \n";
        $query .= "FROM users AS u \n";
        $query .= "LEFT JOIN campaign_keyword AS ck on (ck.copy_writer_id = u.user_id) \n";
        $query .= "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) \n";
        $query .= ' WHERE  aa.curr_flag = 1 ';
        $query .= $qw . " AND ck.status!='D' \n";
        $query .= "GROUP BY  ck.copy_writer_id, ".  $group_by ;
        if (!empty($order_by)) $query .= ' ORDER BY ' . $order_by;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        return $result;
    } // end getCountGroupByCampaigns
}
?>