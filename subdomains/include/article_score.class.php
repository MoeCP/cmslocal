<?php
/**
 *add by liu shu fen 12:45 2008-1-15
 */
require_once CMS_INC_ROOT . DS. 'Pref.class.php';
class ArticleScore {
    /**
     *function get whole fields data 
     *@param $p array
     *       $p['score_id']
     *       $p['copywriter_id']
     *       $p['campaign_id']
     *       $p['limit']
     *@return $r(array)/null
     */
    function getAllArticleScoreInfo($p = array()) {//getAllRankingInfo
        global $conn;
        $qw[] = " WHERE 1 ";
        
        if ( isset($p['score_id']) && !empty($p['score_id']) ) {
            $qw[] = " ascore.score_id=" . trim($p['score_id']);
        }
        
        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $qw[] = " ascore.user_id=" . trim($p['user_id']);
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " ascore.campaign_id=". trim($p['campaign_id']);
        }

        if ( isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            $qw[] = " ascore.keyword_id=". trim($p['keyword_id']);
        }
        
        if (isset($p['limit']) && !empty($p['limit'])) {
            if (isset($p['limit']['start'])) {
                $start = isset($p['limit']['start']);
            } else {
                $start = 0;
            }

            if (isset($p['limit']['number']) && !empty($p['limit']['number']) && $p['limit']['number'] > 0) {
                $limit = " ORDER BY ascore.score DESC LIMIT " . $start . ", " . $p['limit']['number'] . " ";
            } else {
                $limit = " ORDER BY ascore.score DESC ";
            }
        }
        $sql = " SELECT DISTINCT ascore.*, ck.keyword, cc.campaign_name, u.first_name, u.last_name, u.user_name FROM article_score AS ascore ";
        $sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ascore.campaign_id) ';
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ascore.keyword_id AND ck.copy_writer_id=ascore.user_id) ';
        $sql .= 'LEFT JOIN users AS u ON (u.user_id=ascore.user_id) ';
        if (user_is_loggedin() ) {
            if (User::getPermission() == 3) {
                $qw[] = "ck.editor_id=" . User::getID();
            } elseif (User::getPermission() == 2) {
                $sql .= "LEFT JOIN client AS cl ON cl.client_id=cc.client_id ";
                $qw[] = "cl.agency_id=" . User::getID();
            }
        }
        
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        if (!empty($limit)) {
            $sql .= $limit;
        }
        $r = $conn->getAll($sql);
        if ($r) {
            return $r;
        } else
            return null;
    }//function getAllRankingInfo END

    function search($p = array()) {
        global $conn;
		if (strlen($p['perPage']) > 0) {
            $perpage = $p['perPage'];
			unset($p['perPage']);
        } else {
            $perpage= 50;
        }
        $month = $p['rmonth'];
        $user_id = $p['user_id'];
        $conditions = array('1');
        if (!empty($month)) {
            $start_date = substr($month, 0, 4) . '-' . substr($month, 4,2) . '-01 00:00:00';
            $time = strtotime($start_date);
            $end_date = date("Y-m-01 00:00:00", strtotime("+1 month", $time));
            $conditions[] = "(ar.client_approval_date >= '" . $start_date. "' AND ar.client_approval_date < '" . $end_date . "') ";
        }
        if ($user_id > 0) {
            $conditions[] = "arank.user_id= " . $user_id;
        }
        $where = " WHERE " . implode(" AND ", $conditions);
        $left_join = "FROM article_score AS arank " 
        ."LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=arank.keyword_id) "
        ."LEFT JOIN articles AS ar ON (ar.keyword_id=ck.keyword_id) "
        ."LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=arank.campaign_id) ";
        $sql = "SELECT COUNT(arank.score_id ) " . $left_join . $where;
        $count = $conn->GetOne($sql);
        if ($count == 0 || !isset($count)) {
            return false;
        }
        $sql = "SELECT arank.*, ck.keyword, ar.article_number, ar.article_id, cc.campaign_name " . $left_join
        .$where;
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
		list($from, $to) = $pager->getOffsetByPageId();
		$rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        $result = array();
		if ($rs) {
			while (!$rs->EOF)  {
				$result[]= $rs->fields;
				$rs->MoveNext();
			}
			$rs->Close();
		}
        
		return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);        
    }
    
    /**
     *function insert a new record of cp_campaign_ranking
     *@param $p array
     *@return boolean true/false
     */
    function createArticleScore($p) {
        global $conn, $feedback;
        $set = self::dataCheck($p);
        if (empty($set)) return false;
        //check whether the copywriter has been rank with that campaign
        //if it does, update the old record
        if (!isset($p['score_id']) || empty($p['score_id'])) $check_old_record = self::getAllArticleScoreInfo($p);
        if (!empty($check_old_record)) {
            $p['score_id'] = $check_old_record[0]['score_id'];
            return self::updateArticleScore($p);
        }

        $conn->StartTrans();
        $sql = "INSERT INTO article_score SET ";
        if (!empty($set)) {
            $sql .= implode(",", $set);
        }
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        
        if ($ok) {
            $feedback = "Insert Success!";
            return true;
        } else {
            $feedback = "Insert Failure, please try again!";
            return false;
        }

    }

    function dataCheck($p) {
        global $feedback;
        $set = array();
        if ( isset($p['keyword_id']) && !empty($p['keyword_id']) ) {
            $set[] = " keyword_id=" . trim($p['keyword_id']);
        } else {
            $feedback = "Please provide a article frist";
            return false;
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id']) ) {
            $set[] = " campaign_id=" . trim($p['campaign_id']);
        } else {
            $feedback = "Please provide a campaign frist";
            return false;
        }

        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $set[] = " user_id=" . trim($p['user_id']);
        } else {
            $feedback = "Please provide a copywriter first";
            return false;
        }

        if ( isset($p['score']) && !empty($p['score']) ) {
            $set[] = " score=" . trim($p['score']);
        } else {
            $feedback = "Please provide score value first";
            return false;
        }
        return $set;
    }
    /**
     *@funciton update record in table: cp_campaign_ranking
     *@param $p array
     @return boolean true/false
     */
    function updateArticleScore($p) {
        global $conn, $feedback;

        $set = self::dataCheck($p);
        if (empty($set)) return false;
        
        $qw[] = " WHERE 1 ";
        if (isset($p['score_id']) && !empty($p['score_id'])) {
            $qw[] = " score_id=" . trim($p['score_id']);
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id']) ) {
            $qw[] = " campaign_id=" . trim($p['campaign_id']);
        }
        
        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $qw[] = " user_id=" . trim($p['user_id']);
        }

        if ( isset($p['keyword_id']) && !empty($p['keyword_id']) ) {
            $qw[] = " keyword_id=" . trim($p['keyword_id']);
        }

        $conn->StartTrans();
        $sql = "UPDATE article_score SET ";
        if (!empty($set)) {
            $sql .= implode(", ", $set);
        }
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = "Update Succeed!";
            return true;
        } else {
            $feedback = "Update Failure, please try again!";
            return false;
        }

    }//funciton updateCpCampaignRanking END
    
    /**
     *@function store the modification of cp_campaign_ranking record
     *@param $p array
     *return boolean true/false
     */
    function storeArticleScore($p) {
        if (isset($p['score_id']) && !empty($p['score_id'])) {
            //update the old record
            return self::updateArticleScore($p);
        } else {
            //insert a new record
            return self::createArticleScore($p);
        }       
    }//END


    /**
     *@function get ranking value automaticly.from table: cp_campaign_ranking (three fields)      
     *          multiply with table:preference(three ranking quotiety)
     *@param $p array
     *@return $result(array)/null
     */
    function getAllByParam($p = array()) {//getCpCampaignRanking
        global $conn;
        $qw[] = " WHERE 1 ";
        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $user_id = $p['user_id'];
            if (is_array($user_id)) {
                $qw[] = " user_id IN('" . implode("', '", $user_id) . "')";
            } else {
                $user_id = trim($user_id);
                $qw[] = " user_id=" . $user_id;
            }
        }

        if ( isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            $qw[] = " keyword_id=". trim($p['keyword_id']);
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }

        $sql = " SELECT * FROM article_score ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        return  $conn->GetAll($sql);
    }//Function getAllByParam END

    function generateMonthReport($p = array())
    {
        global $conn, $feedback;
        $p[] = "1";
        $fields = array('score');
        $columns = array();
        foreach ($fields as $v) {
            $columns[] = 'SUM(r.' . $v. ') AS ' . $v;
        }
        $sql = "SELECT " . implode(', ', $columns). ", COUNT(r.score_id) AS total, u.user_id, u.user_name, u.first_name, u.last_name, u.email, u.permission ";
        $sql .= "FROM article_score AS r ";
        $sql .= "LEFT JOIN users AS u ON u.user_id=r.user_id ";
        $sql .= "LEFT JOIN articles AS ar ON ar.keyword_id=r.keyword_id ";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id=r.keyword_id AND ck.copy_writer_id=r.user_id ";
        $sql .= " WHERE " . implode(" AND ", $p);
        $sql .=" GROUP BY r.user_id ";
        $result = $conn->GetAll($sql);
        
        foreach ($result as $k => $row) {
            $total = $row['total'];
            foreach ($row as $subk => $subv) {
                if (in_array($subk, $fields)) {
                    $v = round($subv/$total, 2);
                    $result[$k][$subk] = $v;
                }
            }
        }
        return $result;
    }

    /**
     *@function get score value automaticly.from table: cp_campaign_ranking (three fields)      
     *          multiply with table:preference(three ranking quotiety)
     *@param $p array
     *@return $result(array)/null
     */
    function getScoreValue($p = array()) {//getCpCampaignRanking
        global $conn;
        $qw[] = " WHERE 1 ";
        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $user_id = $p['user_id'];
            if (is_array($user_id)) {
                $qw[] = " user_id IN('" . implode("', '", $user_id) . "')";
            } else {
                $user_id = trim($user_id);
                $qw[] = " user_id=" . $user_id;
            }
        }

        if ( isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            $qw[] = " keyword_id=". trim($p['keyword_id']);
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }

        $sql = " SELECT * FROM article_score ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $r = $conn->GetAll($sql);
        if ($r) {
            foreach($r as $re) {
                $ranking_value = $re['score'];
                if (empty($ranking_value)) {
                    $ranking_value = 0;
                }
               if ($ranking_value > 0) {
                    $result[$re['score_id']]['score'] = $ranking_value;
               }
            }
            return $result;
        } else
            return null;
    }//Function getRankingValue END
   
    
}//add END  
?>