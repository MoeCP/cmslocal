<?php
/**
 *add by liu shu fen 12:45 2008-1-15
 */
require_once CMS_INC_ROOT . DS. 'Pref.class.php';
class ArticleRanking {
    /**
     *function get whole fields data 
     *@param $p array
     *       $p['ranking_id']
     *       $p['copywriter_id']
     *       $p['campaign_id']
     *       $p['limit']
     *@return $r(array)/null
     */
    function getAllArticleRankingInfo($p = array()) {//getAllRankingInfo
        global $conn;
        $qw[] = " WHERE 1 ";
        
        if ( isset($p['ranking_id']) && !empty($p['ranking_id']) ) {
            $qw[] = " cpcr.ranking_id=" . trim($p['ranking_id']);
        }
        
        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $qw[] = " cpcr.user_id=" . trim($p['user_id']);
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " cpcr.campaign_id=". trim($p['campaign_id']);
        }

        if ( isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            $qw[] = " cpcr.keyword_id=". trim($p['keyword_id']);
        }
        
        if (isset($p['limit']) && !empty($p['limit'])) {
            if (isset($p['limit']['start'])) {
                $start = isset($p['limit']['start']);
            } else {
                $start = 0;
            }

            if (isset($p['limit']['number']) && !empty($p['limit']['number']) && $p['limit']['number'] > 0) {
                $limit = " ORDER BY cpcr.ranking DESC LIMIT " . $start . ", " . $p['limit']['number'] . " ";
            } else {
                $limit = " ORDER BY cpcr.ranking DESC ";
            }
        }
        $sql = " SELECT DISTINCT cpcr.*, ck.keyword, cc.campaign_name, u.first_name, u.last_name, u.user_name FROM article_rankings AS cpcr ";
        $sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=cpcr.campaign_id) ';
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=cpcr.keyword_id AND ck.copy_writer_id=cpcr.user_id) ';
        $sql .= 'LEFT JOIN users AS u ON (u.user_id=cpcr.user_id) ';
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
        $left_join = "FROM article_rankings AS arank " 
        ."LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=arank.keyword_id) "
        ."LEFT JOIN articles AS ar ON (ar.keyword_id=ck.keyword_id) "
        ."LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=arank.campaign_id) ";
        $sql = "SELECT COUNT(arank.ranking_id ) " . $left_join . $where;
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
    function createArticleRanking($p) {
        global $conn, $feedback;

        $set = self::dataCheck($p);
        if (empty($set)) return false;
        //check whether the copywriter has been rank with that campaign
        //if it does, update the old record
        $check_old_record = self::getAllArticleRankingInfo($p);
        if (!empty($check_old_record)) {
            $p['ranking_id'] = $check_old_record[0]['ranking_id'];
            return self::updateArticleRanking($p);
        }

        $conn->StartTrans();
        $sql = "INSERT INTO article_rankings SET ";
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

        if ( isset($p['punctuation']) && !empty($p['punctuation']) ) {
            $set[] = " punctuation=" . trim($p['punctuation']);
        } else {
            $feedback = "Please provide punctuation value first";
            return false;
        }

        if ( isset($p['grammar']) && !empty($p['grammar']) ) {
            $set[] = " grammar=" . trim($p['grammar']);
        } else {
            $feedback = "Please provide grammar value first";
            return false;
        }

        if ( isset($p['structure']) && !empty($p['structure']) ) {
            $set[] = " structure=" . trim($p['structure']);
        } else {
            $feedback = "Please provide structure value first";
            return false;
        }

        if ( isset($p['ap_style']) && !empty($p['ap_style']) ) {
            $set[] = " ap_style=" . trim($p['ap_style']);
        } else {
            $feedback = "Please provide AP Style value first";
            return false;
        }

        if ( isset($p['style_guide']) && !empty($p['style_guide']) ) {
            $set[] = " style_guide=" . trim($p['style_guide']);
        } else {
            $feedback = "Please provide style guide value first";
            return false;
        }

        if ( isset($p['quality']) && !empty($p['quality']) ) {
            $set[] = " quality=" . trim($p['quality']);
        } else {
            $feedback = "Please provide content quality value first";
            return false;
        }

        if ( isset($p['communication']) && !empty($p['communication']) ) {
            $set[] = " communication=" . trim($p['communication']);
        } else {
            $feedback = "Please provide communication value first";
            return false;
        }

        if ( isset($p['cooperativeness']) && !empty($p['cooperativeness']) ) {
            $set[] = " cooperativeness=" . trim($p['cooperativeness']);
        } else {
            $feedback = "Please provide cooperativeness value first";
            return false;
        }
       
        if ( isset($p['timeliness']) && !empty($p['timeliness']) ) {
            $set[] = " timeliness=" . trim($p['timeliness']);
        } else {
            $feedback = "Please provide timeliness value first";
            return false;
        }

        $ranking_value = empty($p['ranking'])? self::countRankingValue($p) : $p['ranking'];
        if ($ranking_value) {
            $set[] = " ranking=" . trim($ranking_value);
        } else {
            $feedback = "Please set the ranking quotieey first!";
            return false;
        }
        return $set;
    }
    /**
     *@funciton update record in table: cp_campaign_ranking
     *@param $p array
     @return boolean true/false
     */
    function updateArticleRanking($p) {
        global $conn, $feedback;

        $set = self::dataCheck($p);
        if (empty($set)) return false;
        
        $qw[] = " WHERE 1 ";
        if (isset($p['ranking_id']) && !empty($p['ranking_id'])) {
            $qw[] = " ranking_id=" . trim($p['ranking_id']);
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
        $sql = "UPDATE article_rankings SET ";
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
    function storeArticleRanking($p) {
        if (isset($p['ranking_id']) && !empty($p['ranking_id'])) {
            //update the old record
            return self::updateArticleRanking($p);
        } else {
            //insert a new record
            return self::createArticleRanking($p);
        }       
    }//END

    /**
     *@function get ranking value automaticly.from table: cp_campaign_ranking (three fields)      
     *          multiply with table:preference(three ranking quotiety)
     *@param $p array
     *@return $result(array)/null
     */
    function getRankingValue($p = array()) {//getCpCampaignRanking
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

        $sql = " SELECT * FROM article_rankings ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $r = $conn->GetAll($sql);
        if ($r) {
            foreach($r as $re) {
                $ranking_value = $re['ranking'];
                if (empty($ranking_value)) {
                    $ranking_value = self::countRankingValue($re);
                }
               if ($ranking_value > 0) {
                    $result[$re['ranking_id']]['ranking'] = $ranking_value;
               }
            }
            return $result;
        } else
            return null;
    }//Function getRankingValue END

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

        $sql = " SELECT * FROM article_rankings ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        return  $conn->GetAll($sql);
    }//Function getAllByParam END

    function generateMonthReport($p = array())
    {
        global $conn, $feedback;
        $p[] = "1";
        $fields = array('punctuation', 'grammar', 'structure', 'ap_style', 'style_guide', 'quality', 'communication', 'cooperativeness', 'timeliness', 'ranking');
        $columns = array();
        foreach ($fields as $v) {
            $columns[] = 'SUM(r.' . $v. ') AS ' . $v;
        }
        $sql = "SELECT " . implode(', ', $columns). ", COUNT(r.ranking_id) AS total, u.user_id, u.user_name, u.first_name, u.last_name, u.email, u.permission ";
        $sql .= "FROM article_rankings AS r ";
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
     *@function compute of ranking value
     *@param $p array
     *@return number
     */
    function countRankingValue($p) {
        foreach ($p as $k => $v) {
            $p[$k] = trim($v);
        }
        extract($p);

        if ( !isset($punctuation)|| empty($punctuation)) {
            $feedback = "Please provide punctuation value first";
            return -1;
        }

        if ( !isset($grammar)|| empty($grammar)) {
            $feedback = "Please provide grammar value first";
            return -1;
        }

        if ( !isset($structure)|| empty($structure)) {
            $feedback = "Please provide structure value first";
            return -1;
        }

        if ( !isset($ap_style)|| empty($ap_style)) {
            $feedback = "Please provide ap style value first";
            return -1;
        }

        if ( !isset($style_guide)|| empty($style_guide)) {
            $feedback = "Please provide style guide value first";
            return -1;
        }

        if ( !isset($quality)|| empty($quality)) {
            $feedback = "Please provide content quality value first";
            return -1;
        }

        if ( !isset($communication)|| empty($communication)) {
            $feedback = "Please provide communication value first";
            return -1;
        }

        if ( !isset($cooperativeness)|| empty($cooperativeness)) {
            $feedback = "Please provide cooperativeness value first";
            return -1;
        }
       
        if ( !isset($timeliness)|| empty($timeliness)) {
            $feedback = "Please provide timeliness value first";
            return -1;
        }

        if (empty($p['ranking'])) {
            $quotieties = self::getRankingQuotietyByField();
            $ranking_value = 0;
            foreach ($quotieties as $k => $v) {
                $ranking_value += ($p[$k]/5) * $v;
            }
        } else {
            $ranking_value = $p['ranking'];
        }

        return $ranking_value;
    } //Function countRankingValue END
    

    /**
     *@function update the ranking value after ranking quotieties have changed
     *@param null
     *@return boolean true/false
     */
    function updateAllRankingValue() {
        global $feedback;
        $feedback = '';
        $all_ranking_value = self::getRankingValue();

        if (!empty($all_ranking_value)) {
            foreach ($all_ranking_value as $id => $value) {
                $param['ranking_id'] = $id;
                $param['ranking'] = $value['ranking'];
                self::updateSingleRankingValue($param);
            }
            if (empty($feedback)) {
                return true;
            } else {
                return false;
            }
        }
    }//END

    /**
     *@function update single field of ranking quotiety in table:preference
     *@param $p array
     *@erturn boolean true/false
     */
    function updateSingleRankingValue($p) {
        global $conn, $feedback;
        $feedback = '';
        $qw[] = " WHERE 1 ";
        if (isset($p['ranking_id']) && !empty($p['ranking_id'])) {
            $qw[] = " ranking_id=" . trim($p['ranking_id']);
        }

        if (isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            $qw[] = " keyword_id=" . trim($p['keyword_id']);
        }

        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=" . trim($p['campaign_id']);
        }

        if ( isset($p['user_id']) && !empty($p['user_id']) ) {
            $qw[] = " user_id=" . trim($p['user_id']);
        }

        if (isset($p['ranking']) && !empty($p['ranking'])) {
            $set[] = " ranking=" . trim($p['ranking']);
        } else {
            $feedback = "Please provide ranking value first";
            return false;
        }
    
        $conn->StartTrans();
        $sql = "UPDATE article_rankings SET ";
        if (!empty($set)) {
            $sql .= implode(',', $set);
        }
        if (!empty($qw)) {
            $sql .= implode(' AND ', $qw);
        }

        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            return true;
        } else {
            $feedback = "Update Failed, please try agian!";
            return false;
        }
    }//END
    
    /**
     *@function store the modified ranking quotiety value
     *@param $p array
     *@return boolean true/false
     */
    function storeAllRankingQuotiety($p) {
        global $conn;
        $param = array();
        
        $conn->StartTrans();
        $fields = array('punctuation', 'grammar', 'structure', 'ap_style', 'style_guide', 'quality', 'communication', 'cooperativeness', 'timeliness');
        foreach ($fields as $field) {
            $param = array();
            $param['pref_table'] = 'article_rankings';
            $param['pref_field'] = $field;
            if (isset($p[$field . '_id']) && !empty($p[$field . '_id'])) {
                $param['pref_id'] = trim($p[$field . '_id']);
            }
            $param['pref_values'] =  array(trim($p[$field]));
             self::storeSingleRankingQuotiey($param);
        }
        $ok = $conn->CompleteTrans();

        if ($ok ) {//$result$r1 && $r2 && $r3
            $feedback = "Succeed!";
            return true;
        } else {
            $feedback = "Failed!";
            return false;
        }

    }//Funciton storeAllRankingQuotiety END

    /**
     *@function get single ranking quotiety value query by 
     *@param $pref_field string
     *@return array/null
     */
    function getSingleRankingQuotietyByField($pref_field) {
        $r = self::getRankingQuotietyByField($pref_field);
        return empty($r) ? null : $r[$pref_field];      
    } //END


    /**
     *@function get ranking quotiety values
     *@param $pref_field string
     *@return array/null
     */
    function getRankingQuotietyByField($pref_field = '') {
        $r = Preference::getPref('article_rankings', $pref_field);
        if (!empty($r)) {
            foreach ($r as $k => $v) {
                $r[$k] = $v[0];
            }
            return $r;
        } else {
            return null;
        }
    } //END

    function getSingleRankingQuotietyById($pref_id) {
        $r = Preference::getPrefById($pref_id);
        return empty($r) ? null : $r['pref_value'];
    }

    /**
     *store the ranking quotiety value in table preference
     *@param $p['pref_id']     int
             $p['pref_table']  string
             $p['pref_field']  string
             $p['pref_values'] array()
     *@return boolean true/false
     */        
    function storeSingleRankingQuotiey($p) {
        global $feedback;
        if (isset($p['pref_id']) && !empty($p['pref_id'])) {
            //update the ranking quotiety
            if (isset($p['pref_values']) && !empty($p['pref_values'])) {
                $p['pref_value'] = $p['pref_values'][0];
            } else {
                $feedback = "Please insert value first!";
                return false;
            }
            return Preference::updatePref($p);
        } else {
            //insert a new record
            return Preference::storeBatch($p);
        }
    }//Fucntion END
    
    /**
     *@function get three ranking quotieties value
     *@param $pref_field string
     *       $pref_value string
     *@return $row(array)/null
     */
    function getQuotietyAllInfo( $pref_field = '', $pref_value='') {
        $pref_table = 'article_rankings';
        $row = Preference::getPrefAllInfo($pref_table,$pref_field, $pref_value);
        if (!empty($pref_field)) {
            return empty($row) ? null : $row[0];
        } else {
            return $row;
        }
    }//END
    
}//add END  
?>