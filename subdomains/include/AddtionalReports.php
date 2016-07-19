<?php
/*
SELECT COUNT(DISTINCT article_id) FROM article_payment_log WHERE is_canceled = 0 AND pay_month = 2014121;
SELECT COUNT(DISTINCT article_id) FROM article_payment_log WHERE is_canceled = 0 AND pay_month = 2014122;
SELECT COUNT(DISTINCT article_id) FROM article_payment_log WHERE is_canceled = 0 AND pay_month = 2015011;
SELECT COUNT(DISTINCT article_id) FROM article_payment_log WHERE is_canceled = 0 AND pay_month = 2015012;
SELECT COUNT(DISTINCT article_id) FROM article_payment_log WHERE is_canceled = 0 AND pay_month = 2015021;
*/

class AddtionalReports {
    //It will return the recently month estimate cost
    function EstimateCost($p = array()) {
        global $conn;

        $estcost = array();
        $estcost['esteac'] = 0; //Estimate Editor Approved Cost
        $estcost['estaosc'] = 0; //Est. All Other Status Cost
        $estcost['nofoea'] = 0; //Number of articles of Editor Approved 
        $estcost['nofoaos'] = 0; //Number of articles of All Other Status 
        $estcost['cactotal'] = 0; //Total of Client Approved Cost
        $estcost['nofcatotal'] = 0; //Numer of Total articles of Client Approved
        $estcost['nofoca'] = 0; //Number of articles of Client Approved 

        if (!empty($p)) {
            extract($p);
        }
        if (isset($nofpayroll) && $nofpayroll > 0) {
        } else {
            $nofpayroll = 5;
        }
        $estcost['nofpayroll'] = $nofpayroll;

        if (isset($starttime) && strlen($starttime) == 7) {//strlen(2014111)
            $startpaycycle = $starttime;
            $_ipr = substr($starttime, -1, 1);
            $_year = substr($starttime, 0, 4);
            $_month = substr($starttime, 4, 2);
            if ($_ipr == 1) {
                $starttime = $_year . "-" . $_month . "-01 00:00:01";
            } else {
                $starttime = $_year . "-" . $_month . "-16 00:00:01";
            }
            $starttime = strtotime($starttime);
        } else {
            $starttime = time();
            $startpaycycle = date("Ym1", $starttime);
        }
        $nof_months = ceil($nofpayroll/2);
        $monthes = genPayMonthList($starttime, $nof_months+1);
        //for($i=1; $i<=$nofpayroll; $i++) {
        $i = 1;
        foreach($monthes as $kpm => $kpmv) {
            if ($kpm<$startpaycycle) continue;
            $q = "SELECT COUNT(DISTINCT article_id) AS nofaonapl FROM article_payment_log 
                 WHERE is_canceled = 0 AND pay_month = '{$kpm}'";
            $rs = $conn->Execute($q);
            $estcost['nofa'][$kpm] = 0;
            if ($rs) {
                if ($rs->fields['nofaonapl'] > 0) $estcost['nofa'][$kpm] = $rs->fields['nofaonapl'];
                $rs->Close();
            }

            $estcost['nofcatotal'] += $estcost['nofa'][$kpm];
            $estcost['nofprk'][$kpm] = $kpmv;

            $estcost['nofacost'][$kpm] = self::MonthlyCost($kpm);
            $estcost['nofcatotalcost'] += $estcost['nofacost'][$kpm];
            $i++;
            if ($i > $nofpayroll) break;
        }

        //Getting Number of articles of All Other Status 
        $q = "SELECT COUNT(*) AS nofoaos FROM articles AS ar 
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            WHERE ck.status!='D' AND ar.article_status < 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['nofoaos'])) $estcost['nofoaos'] += $rs->fields['nofoaos'];
            $rs->Close();
        }

        //Getting Number of articles of Editor Approved
        $q = "SELECT COUNT(*) AS nofoea FROM articles AS ar 
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            WHERE ck.status!='D' AND ar.article_status = 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['nofoea'])) $estcost['nofoea'] += $rs->fields['nofoea'];
            $rs->Close();
        }

        //Start Getting the Est. All Other Status Cost -------------------------------
        $q = "SELECT SUM(ac.editor_article_cost+ac.cp_article_cost) AS estaosc
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ac.pay_by_article = 1 AND ck.status!='D' AND ar.article_status < 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['estaosc'])) $estcost['estaosc'] += $rs->fields['estaosc'];
            $rs->Close();
        }

        $q = "SELECT SUM((ac.editor_cost+ac.cp_cost)*ar.total_words) AS estaosc
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ac.pay_by_article = 0 AND ck.status!='D' AND ar.article_status < 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['estaosc'])) $estcost['estaosc'] += $rs->fields['estaosc'];
            $rs->Close();
        }

        $q = "SELECT SUM(at.editor_article_cost+at.cp_article_cost) AS estaosc
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ck.status!='D' AND ar.article_status < 4 AND at.pay_by_article=1";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['estaosc'])) $estcost['estaosc'] += $rs->fields['estaosc'];
            $rs->Close();
        }

        $q = "SELECT SUM((at.editor_cost+at.cp_cost)*ar.total_words) AS estaosc
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ck.status!='D' AND ar.article_status < 4 AND at.pay_by_article=0";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['estaosc'])) $estcost['estaosc'] += $rs->fields['estaosc'];
            $rs->Close();
        }
        //End Getting the Est. All Other Status Cost -------------------------------


        //Start Getting the estimate Editor Approved Cost -------------------------------
        $q = "SELECT SUM(ac.editor_article_cost+ac.cp_article_cost) AS esteac
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ac.pay_by_article = 1 AND ck.status!='D' AND ar.article_status = 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['esteac'])) $estcost['esteac'] += $rs->fields['esteac'];
            $rs->Close();
        }

        $q = "SELECT SUM((ac.editor_cost+ac.cp_cost)*ar.total_words) AS esteac
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ac.pay_by_article = 0 AND ck.status!='D' AND ar.article_status = 4";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['esteac'])) $estcost['esteac'] += $rs->fields['esteac'];
            $rs->Close();
        }

        $q = "SELECT SUM(at.editor_article_cost+at.cp_article_cost) AS esteac
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ck.status!='D' AND ar.article_status = 4 AND at.pay_by_article=1";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['esteac'])) $estcost['esteac'] += $rs->fields['esteac'];
            $rs->Close();
        }

        $q = "SELECT SUM((at.editor_cost+at.cp_cost)*ar.total_words) AS esteac
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            WHERE ck.status!='D' AND ar.article_status = 4 AND at.pay_by_article=0";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['esteac'])) $estcost['esteac'] += $rs->fields['esteac'];
            $rs->Close();
        }
        //End Getting the estimate Editor Approved Cost -------------------------------

        return $estcost;
    }

    function MonthlyCost($month){
        global $conn;
        $cacost = 0;

        $q = "SELECT SUM(ac.editor_article_cost+ac.cp_article_cost) AS cacost
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.role='copy writer')  
            WHERE ac.pay_by_article = 1 AND ck.status!='D' AND (ar.article_status=5 OR ar.article_status=6) AND apl.pay_month = '$month' AND apl.is_canceled = 0";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['cacost'])) $cacost += $rs->fields['cacost'];
            $rs->Close();
        }

        $q = "SELECT SUM((ac.editor_cost+ac.cp_cost)*ar.total_words) AS cacost
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.role='copy writer')  
            WHERE ac.pay_by_article = 0 AND ck.status!='D' AND (ar.article_status=5 OR ar.article_status=6) AND apl.pay_month = '$month' AND apl.is_canceled = 0";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['cacost'])) $cacost += $rs->fields['cacost'];
            $rs->Close();
        }

        $q = "SELECT SUM(at.editor_article_cost+at.cp_article_cost) AS cacost
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.role='copy writer')  
            WHERE ck.status!='D' AND (ar.article_status=5 OR ar.article_status=6) AND at.pay_by_article=1 AND apl.pay_month = '$month' AND apl.is_canceled = 0";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['cacost'])) $cacost += $rs->fields['cacost'];
            $rs->Close();
        }

        $q = "SELECT SUM((at.editor_cost+at.cp_cost)*ar.total_words) AS cacost
            FROM articles AS ar
            LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id 
            LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
            LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id AND ac.article_type=ck.article_type)   
            LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.role='copy writer')  
            WHERE ck.status!='D' AND (ar.article_status=5 OR ar.article_status=6) AND at.pay_by_article=0 AND apl.pay_month = '$month' AND apl.is_canceled = 0 ";
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!empty($rs->fields['cacost'])) $cacost += $rs->fields['cacost'];
            $rs->Close();
        }

        return $cacost;
    }
}
?>