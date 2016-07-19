<?php
/**
 *add by liu shu fen 12:45 2008-1-15
 */
require_once CMS_INC_ROOT . DS. 'Pref.class.php';
class CpCampaignRanking {
    /**
     *function get whole fields data 
     *@param $p array
     *       $p['ranking_id']
     *       $p['copywriter_id']
     *       $p['campaign_id']
     *       $p['limit']
     *@return $r(array)/null
     */
    function getAllCpCampaignRankingInfo($p = array()) {//getAllRankingInfo
        global $conn;
        $qw[] = " WHERE 1 ";
        
        if ( isset($p['ranking_id']) && !empty($p['ranking_id']) ) {
            $qw[] = " cpcr.ranking_id=" . trim($p['ranking_id']);
        }
        
        if ( isset($p['copywriter_id']) && !empty($p['copywriter_id']) ) {
            $qw[] = " cpcr.copywriter_id=" . trim($p['copywriter_id']);
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " cpcr.campaign_id=". trim($p['campaign_id']);
        }

        if ( isset($p['date_start_l']) && !empty($p['date_start_l'])) {
            $qw[] = " cc.date_start>='". trim($p['date_start_l'])."'";
        }
        if ( isset($p['date_start_r']) && !empty($p['date_start_r'])) {
            $qw[] = " cc.date_start<='". trim($p['date_start_r'])."'";
        }
        if ( isset($p['date_end_l']) && !empty($p['date_end_l'])) {
            $qw[] = " cc.date_end>='". trim($p['date_end_l'])."'";
        }
        if ( isset($p['date_end_r']) && !empty($p['date_end_r'])) {
            $qw[] = " cc.date_start<='". trim($p['date_end_r'])."'";
        }
        if ( isset($p['client_id']) && !empty($p['client_id'])) {
            $qw[] = " cc.client_id='". trim($p['client_id'])."'";
        }
        if ( isset($p['keyword']) && !empty($p['keyword'])) {
            $qw[] = " cc.campaign_name LIKE '%". trim($p['keyword'])."%'";
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
        $sql = " SELECT DISTINCT cpcr.*, cc.campaign_name, u.first_name, u.last_name, u.user_name FROM cp_campaign_ranking AS cpcr ";
        $sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=cpcr.campaign_id) ';
        $sql .= 'LEFT JOIN users AS u ON (u.user_id=cpcr.copywriter_id) ';
        if (user_is_loggedin() ) {
            if (User::getPermission() == 3) {
                $sql .= "LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id=cpcr.campaign_id AND ck.copy_writer_id=cpcr.copywriter_id) ";
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
    
    /**
     *function insert a new record of cp_campaign_ranking
     *@param $p array
     *@return boolean true/false
     */
    function createCpCampaignRanking($p) {
        global $conn, $feedback;
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id']) ) {
            $set[] = " campaign_id=" . trim($p['campaign_id']);
        } else {
            $feedback = "Please provide a campaign frist";
            return false;
        }

        if ( isset($p['copywriter_id']) && !empty($p['copywriter_id']) ) {
            $set[] = " copywriter_id=" . trim($p['copywriter_id']);
        } else {
            $feedback = "Please provide a copywriter first";
            return false;
        }

        if ( isset($p['readability']) && !empty($p['readability']) ) {
            $set[] = " readability=" . trim($p['readability']);
        } else {
            $feedback = "Please provide readability value first";
            return false;
        }

        if ( isset($p['informational_quality']) && !empty($p['informational_quality']) ) {
            $set[] = " informational_quality=" . trim($p['informational_quality']);
        } else {
            $feedback = "Please provide informational quality value first";
            return false;
        }
       
        if ( isset($p['timeliness']) && !empty($p['timeliness']) ) {
            $set[] = " timeliness=" . trim($p['timeliness']);
        } else {
            $feedback = "Please provide timeliness value first";
            return false;
        }
        
        if (isset($p['comments']) && !empty($p['comments'])) {
            $set[] = " comments='" . trim($p['comments']) . "'";
        }

        $ranking_value = self::countRankingValue($p);
        if ($ranking_value) {
            $set[] = " ranking=" . trim($ranking_value);
        } else {
            $feedback = "Please set the ranking quotieey first!";
            return false;
        }

        //check whether the copywriter has been rank with that campaign
        //if it does, update the old record
        $check_old_record = self::getAllCpCampaignRankingInfo($p);
        if (!empty($check_old_record)) {
            $p['ranking_id'] = $check_old_record[0]['ranking_id'];
            return self::updateCpCampaignRanking($p);
        }

        $conn->StartTrans();
        $sql = "INSERT INTO cp_campaign_ranking SET ";
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
    /**
     *@funciton update record in table: cp_campaign_ranking
     *@param $p array
     @return boolean true/false
     */
    function updateCpCampaignRanking($p) {
        global $conn, $feedback;
        if ( isset($p['readability']) && !empty($p['readability']) ) {
            $set[] = " readability=" . trim($p['readability']);
        } else {
            $feedback = "Please provide the readability value";
            return false;
        }

        if ( isset($p['informational_quality']) && !empty($p['informational_quality']) ) {
            $set[] = " informational_quality=" . trim($p['informational_quality']);
        } else {
            $feedback = "Please provide the informational quality value";
            return false;
        }

        if ( isset($p['timeliness']) && !empty($p['timeliness']) ) {
            $set[] = " timeliness=" . trim($p['timeliness']);
        } else {
            $feedback = "Please provide the timeliness value";
            return false;
        }
        
        if (isset($p['comments']) && !empty($p['comments'])) {
            $set[] = " comments='" . addslashes(trim($p['comments'])) . "' ";
        }

        $ranking_value = self::countRankingValue($p);
        if ( $ranking_value > 0 ) {
            $set[] = " ranking=" . trim($ranking_value);
        }
        
        $qw[] = " WHERE 1 ";
        if (isset($p['ranking_id']) && !empty($p['ranking_id'])) {
            $qw[] = " ranking_id=" . trim($p['ranking_id']);
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id']) ) {
            $qw[] = " campaign_id=" . trim($p['campaign_id']);
        }
        
        if ( isset($p['copywriter_id']) && !empty($p['copywriter_id']) ) {
            $qw[] = " copywriter_id=" . trim($p['copywriter_id']);
        }

        $conn->StartTrans();
        $sql = "UPDATE cp_campaign_ranking SET ";
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
    function storeCpCampaignRanking($p) {
        if (isset($p['ranking_id']) && !empty($p['ranking_id'])) {
            //update the old record
            return self::updateCpCampaignRanking($p);
        } else {
            //insert a new record
            return self::createCpCampaignRanking($p);
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
        if ( isset($p['copy_writer_id']) && !empty($p['copy_writer_id']) ) {
            $copy_writer_id = $p['copy_writer_id'];
            if (is_array($copy_writer_id)) {
                $qw[] = " copywriter_id IN('" . implode("', '", $copy_writer_id) . "')";
            } else {
                $copy_writer_id = trim($copy_writer_id);
                $qw[] = " copywriter_id=" . $copy_writer_id;
            }
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }

        $sql = " SELECT * FROM cp_campaign_ranking ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $r = $conn->GetAll($sql);
        if ($r) {
            foreach($r as $re) {
                $ranking_value = self::countRankingValue($re);
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
        if ( isset($p['copy_writer_id']) && !empty($p['copy_writer_id']) ) {
            $copy_writer_id = $p['copy_writer_id'];
            if (is_array($copy_writer_id)) {
                $qw[] = " copywriter_id IN('" . implode("', '", $copy_writer_id) . "')";
            } else {
                $copy_writer_id = trim($copy_writer_id);
                $qw[] = " copywriter_id=" . $copy_writer_id;
            }
        }

        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }

        $sql = " SELECT * FROM cp_campaign_ranking ";
       
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        return  $conn->GetAll($sql);
    }//Function getAllByParam END
    
    /**
     *@function compute of ranking value
     *@param $p array
     *@return number
     */
    function countRankingValue($p) {
        if ( isset($p['readability']) && !empty($p['readability']) ) {
            $readability = trim($p['readability']);
        } else {
            $feedback = "Please provide readability value first";
            return -1;
        }

        if ( isset($p['informational_quality']) && !empty($p['informational_quality']) ) {
            $informational_quality= trim($p['informational_quality']);
        } else {
            $feedback = "Please provide informational quality value first";
            return -1;
        }
       
        if ( isset($p['timeliness']) && !empty($p['timeliness']) ) {
            $timeliness= trim($p['timeliness']);
        } else {
            $feedback = "Please provide timeliness value first";
            return -1;
        }

        $readability_quotiety  = self::getSingleRankingQuotietyByField('readability');
        $info_quality_quotiety = self::getSingleRankingQuotietyByField('informational_quality');
        $timeliness_quotiety   = self::getSingleRankingQuotietyByField('timeliness');

        $ranking_value = $readability * $readability_quotiety + $informational_quality * $info_quality_quotiety +  
                         $timeliness  * $timeliness_quotiety;

        return $ranking_value;
    } //Function countRankingValue END
    
    /**
     *@function get three ranking quotieties from table:preference
     *@param null
     *@return $result(array)
     */
    function getAllQuotieties() {
        $readability_quotiety  = self::getSingleRankingQuotietyByField('readability');
        $info_quality_quotiety = self::getSingleRankingQuotietyByField('informational_quality');
        $timeliness_quotiety   = self::getSingleRankingQuotietyByField('timeliness');

        $result = array();
        if (!empty($readability_quotiety )) {
            $result['readability'] = $readability_quotiety;
        }
        if (!empty($informational_quality )) {
            $result['informational_quality'] = $info_quality_quotiety;
        }
        if (!empty($timeliness )) {
            $result['timeliness'] = $timeliness_quotiety;
        }
        return $result;
    }

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

        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=" . trim($p['campaign_id']);
        }
        if ( isset($p['copy_writer_id']) && !empty($p['copy_writer_id']) ) {
            $qw[] = " copywriter_id=" . trim($p['copy_writer_id']);
        }

        if (isset($p['ranking']) && !empty($p['ranking'])) {
            $set[] = " ranking=" . trim($p['ranking']);
        } else {
            $feedback = "Please provide ranking value first";
            return false;
        }
    
        $conn->StartTrans();
        $sql = "UPDATE cp_campaign_ranking SET ";
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
        $param['pref_table'] = 'cp_campaign_ranking';
        
        $conn->StartTrans();

        //store readability quotiety
        $param['pref_field'] = 'readability';
        if (isset($p['readability_id']) && !empty($p['readability_id'])) {
            $param['pref_id'] = trim($p['readability_id']);
        }
         
        if (isset($p['readability']) && !empty($p['readability'])) {
            $param['pref_values'] = array(trim($p['readability']));
        }
        self::storeSingleRankingQuotiey($param);
        
        //store informational quality quotiety
        $param['pref_field'] = 'informational_quality';
        if (isset($p['quality_id']) && !empty($p['quality_id'])) {
            $param['pref_id'] = trim($p['quality_id']);
        } 
        if (isset($p['informational_quality']) && !empty($p['informational_quality']))
        {
            $param['pref_values']= array(trim($p['informational_quality']));
        }
        self::storeSingleRankingQuotiey($param);

        //store timeliness quotiety
        $param['pref_field'] = 'timeliness';
        if (isset($p['timeliness_id']) && !empty($p['timeliness_id'])) {
            $param['pref_id'] = trim($p['timeliness_id']);
        } 
        if (isset($p['timeliness']) && !empty($p['timeliness'])) {
            $param['pref_values'] = array(trim($p['timeliness']));
        }
        self::storeSingleRankingQuotiey($param);
        $ok = $conn->CompleteTrans();

        //update all the ranking value in cp_campaign_ranking table after the ranking quotieties
        //have changed
        $result = self::updateAllRankingValue();
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
        $r = Preference::getPref('cp_campaign_ranking', $pref_field);
        return empty($r) ? null : $r[$pref_field][0];      
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
    function getQuotietyAllInfo( $pref_field, $pref_value='') {
        $pref_table = 'cp_campaign_ranking';
        $row = Preference::getPrefAllInfo($pref_table,$pref_field, $pref_value);
        empty($row) ? null : $row[0];
    }//END
    
}//add END  
?>