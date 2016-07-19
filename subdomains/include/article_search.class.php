<?php
class ArticleSearch{

    function ArticleSearch()
    {
        $this->__construct();
    }

	function __construct()
	{

	}

    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function searchKeyword($p = array(), $total_item =-1 )
    {
        global $conn, $feedback;

        global $g_pager_params;
         
        $q = "";
        $article_id = $p['article_id'];
        /*
        if (!empty($article_id)) {
            if (is_array($article_id)) {
                $q .= "\nAND ar.article_id in ('". implode("','", $article_id)."') ";
            } else {
                $article_id = addslashes(htmlspecialchars(trim($article_id)));
                $q .= "\nAND ar.article_id = '".$article_id."' ";
            }
        }
        */

        if (!empty($p["date_start"]) && !empty($p["fst"])) {
            if ($p["kso"] == 1) {
                $q .= "\nAND ar.title LIKE '%".addslashes(htmlspecialchars(trim($p["fst"])))."%' ";
            } elseif($p["kso"] == 2) {
                $q .= "\nAND ar.body LIKE '%".addslashes(htmlspecialchars(trim($p["fst"])))."%' ";
            } elseif($p["kso"] == 3) {
                $q .= "\nAND (ar.body LIKE '%".addslashes(htmlspecialchars(trim($p["fst"])))."%' OR ar.title LIKE '%".addslashes(htmlspecialchars(trim($p["fst"])))."%' ) ";
            }
        } elseif(!empty($article_id)) {
            if (is_array($article_id)) {
                $q .= "\nAND ar.article_id in ('". implode("','", $article_id)."') ";
            } else {
                $article_id = addslashes(htmlspecialchars(trim($article_id)));
                $q .= "\nAND ar.article_id = '".$article_id."' ";
            }
        }

        $type_id = $p['tid'];
        if (strlen($type_id)) {
            $type_id = addslashes(htmlspecialchars(trim($type_id)));
            $q .= "\nAND ck.article_type = '".$type_id."' ";
        }

        $copy_writer_id = $p['uid'];
        if (!empty($copy_writer_id)) {
            $copy_writer_id = addslashes(htmlspecialchars(trim($copy_writer_id)));
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }

        $editor_id = $p['eid'];
        if (!empty($editor_id)) {
            $editor_id = addslashes(htmlspecialchars(trim($editor_id)));
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }

        $client_id = $p['cid'];
        if (!empty($client_id)) {
            $client_id = addslashes(htmlspecialchars(trim($client_id)));
            $q .= "\nAND cc.client_id = '".$client_id."' ";
        }

        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        } else {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }
        
        $where = "\nWHERE 1 {$q} ";
        $count = $total_item;
        $sql_left  = "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ";
        $sql_left  .= "\nLEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id  AND aa.status=1 AND aa.new_status='1gc' AND aa.curr_flag=1)";
        $sql_left .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND ck.copy_writer_id = apl.user_id) ";
        $sql_left .= "\nLEFT JOIN article_type AS at ON at.type_id = ck.article_type ";
        $sql_left .= "\nLEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  ";
        $sql_left .= "\nLEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)  ";
        $sql_left .= "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ";
        if ($total_item == -1 || !empty($p["date_start"])) {
            $q .= ' AND ar.phandle IS NOT NULL ';
            $where = "\nWHERE ck.status!='D' " . $q;
            if (!empty($p["date_start"])) {
                if (empty($p["date_start_end"])) $p["date_start_end"] = date("y-m-d");
                $where .= " AND (cc.date_start >= '".trim($p["date_start"])."' AND cc.date_start <= '".trim($p["date_start_end"])."') ";
            }
		    $query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ".
                "\nFROM campaign_keyword AS ck ". $sql_left .  $where;
            $count = $conn->GetOne($query);
        }

        if ($count <= 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $cp_users = User::getCpPaymentHistory(array(), false);

     
        $q = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `cc`.`campaign_name`, `ck`.`copy_writer_id`, ". 
            "`ck`.`editor_id`, `ck`.`keyword`, `ck`.`article_type`, `ck`.`keyword_description`, " . 
            "`ck`.`date_start`, `ck`.`date_end`, `ck`.`creation_user_id`, `ck`.`creation_role`, " .
            "`ck`.`keyword_category`, `ck`.`status`, `ck`.`cost_per_article`, at.parent_id ,`ck`.`is_sent`,`ar`.`cp_updated`, " . 
            "ar.article_id, ar.article_number, ar.approval_date, MAX(aa.created_time) as google_approved_time , " . 
            "ar.target_pay_month, ar.is_canceled, apl.log_id, apl.month as apl_month, apl.paid_time, ar.curr_dl_time, " .
            "ar.article_status, ar.checking_url,  cc.campaign_name, ar.total_words as word_count , " . 
            "ac.cp_cost AS cost_per_article, at.cp_cost AS type_cost, ach.cost_per_article as ach_type_cost, " . 
            "cc.client_id, ck.editor_id , ck.copy_writer_id, ar.title, " . 
            "ar.body, ar.richtext_body  \n" . 
             "FROM campaign_keyword AS ck \n".
             $sql_left .
             $where  ;
        $q .= "\nGROUP BY ar.article_id";
        $q .= "\nORDER BY ck.keyword_id DESC, google_approved_time  ";
        list($from, $to) = $pager->getOffsetByPageId();
        if ($total_item > 0 ) {
            $rs = &$conn->Execute($q);
        } else {
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        }
        if ($rs) {
            $result = array();
            $kb = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                // get type cost
                // cost per article in article_cost table
                $cost_per_article = $fields['cost_per_article'];
                // cost per article in article_cost_history table
                $ach_per_article = $fields['ach_per_article'];
                // cost per article in article_type table
                $type_cost     = $fields['type_cost'];
                $word_count     = $fields['word_count'];
                $cost_per_article = $ach_per_article > 0 ? $ach_per_article : $cost_per_article;
                $cost_per_article = $cost_per_article > 0 ? $cost_per_article : $type_cost;
                $cost_for_article = $cost_per_article * $word_count;
                $fields['cost_per_article'] = $cost_per_article;
                $fields['cost_for_article'] = $cost_for_article;
                $result[$i] = $fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     );

    }//end search()

    function getArticleIdsByTagName($tagname)
    {
        global $conn;
        $sql = "SELECT DISTINCT at.article_id FROM `article_tags` AS at ";
        $sql .= "LEFT JOIN domain_tags AS dt ON (dt.tag_id=at.tag_id) ";
        $sql .= "LEFT JOIN items AS i ON (i.item_id=dt.item_id) ";
        $sql .= "WHERE i.name LIKE '%" . $tagname . "%' ";
        $result = $conn->GetAll($sql);
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['article_id'];
        }
        return $ids;
    }

}
?>