<?php
class CampaignReporting{

    function CampaignReporting()
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
    function baseon($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "";
        $client_id = $p['client_id'];
        if (strlen($client_id)) {
            $client_id = addslashes(htmlspecialchars(trim($client_id)));
            $q .= "\nAND c.client_id = '".$client_id."' ";
        }

        $date_start = $p['date_start'];
        $date_end = $p['date_end'];

        //###client_id,baseon,date_start,date_end,perPage
        //$baseones = array('1'=>'Client Approved','2'=>'Editor Approved','3'=>'Assigned Writer');
        $baseon = $p['baseon'];
        if ($baseon == 2) {
            if (!empty($date_start)) {
                $q .= "\nAND ar.approval_date >= '".$date_start."' ";
            }
            if (!empty($date_end)) {
                $q .= "\nAND ar.approval_date <= '".$date_end."' ";
            }
        } elseif ($baseon == 3) {
            if (!empty($date_start)) {
                $q .= "\nAND ck.date_assigned >= '".$date_start."' ";
            }
            if (!empty($date_end)) {
                $q .= "\nAND ck.date_assigned <= '".$date_end."' ";
            }
        } else {
            if (!empty($date_start)) {
                $q .= "\nAND ar.client_approval_date >= '".$date_start."' ";
            }
            if (!empty($date_end)) {
                $q .= "\nAND ar.client_approval_date <= '".$date_end."' ";
            }
        }

        //ck.date_assigned,cc.client_id,cc.article_type,ar.approval_date,ar.client_approval_date,
        //at.editor_cost,at.cp_cost,at.pay_by_article,at.cp_article_cost,at.editor_article_cost,
        /*
        SELECT cc.campaign_name,cc.campaign_id,cc.article_type,at.editor_cost,cc.client_id,cc.max_word,c.company_name,
            at.cp_cost,at.pay_by_article,at.cp_article_cost,at.editor_article_cost,count(ar.article_id) AS nofa
            FROM articles AS ar
            LEFT JOIN campaign_keyword ck ON (ck.keyword_id = ar.keyword_id)
            LEFT JOIN client_campaigns cc ON (cc.campaign_id = ck.campaign_id)
            LEFT JOIN client c ON (c.client_id = cc.client_id)
            LEFT JOIN article_type at ON (cc.article_type = at.type_id)
            WHERE c.client_id = 350 AND ar.approval_date >= '2014-06-01' GROUP BY cc.campaign_id;
        */

        $qs = "SELECT cc.campaign_name,cc.campaign_id,cc.article_type,at.editor_cost,cc.client_id,cc.max_word,c.company_name,
        c.user_name,at.cp_cost,at.pay_by_article,at.cp_article_cost,at.editor_article_cost,count(ar.article_id) AS nofa";

        $where = "\nWHERE 1 {$q} ";
        $count = $total_item;
        $sql_left  = "\nFROM articles AS ar ";
        $sql_left .= "\nLEFT JOIN campaign_keyword ck ON (ck.keyword_id = ar.keyword_id) ";
        $sql_left .= "\nLEFT JOIN client_campaigns cc ON (cc.campaign_id = ck.campaign_id)  ";
        $sql_left .= "\nLEFT JOIN client c ON (c.client_id = cc.client_id) ";
        $sql_left .= "\nLEFT JOIN article_type at ON (cc.article_type = at.type_id) ";

        $query = "\nSELECT COUNT(DISTINCT ck.campaign_id) AS count " . $sql_left .  $where;
        $count = $conn->GetOne($query);
        if ($count <= 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }


        //###$cp_users = User::getCpPaymentHistory(array(), false);

     
        $q = $qs . $sql_left . $where;
        $q .= "\nGROUP BY cc.campaign_id";
        //##$q .= "\nORDER BY ck.keyword_id DESC, google_approved_time  ";
        if ($p['opt_action'] == "export") {
            $rs = &$conn->Execute($q);
        } else {
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
            list($from, $to) = $pager->getOffsetByPageId();

            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        }
        $total_amount = 0;
        if ($rs) {
            $result = array();
            $kb = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;

                // get type cost
                // cost per article in article_cost table
                $estimate_money = 0;
                $estimate_total = 0;
                if ($fields['pay_by_article'] == 0) {
                    $estimate_money = $fields['cp_cost'] * $fields['nofa'] * $fields['max_word'];
                    $estimate_total = ($fields['cp_cost']+$fields['editor_cost']) * $fields['nofa'] * $fields['max_word'];
                } else {
                    $estimate_money = $fields['cp_article_cost'] * $fields['nofa'];
                    $estimate_total = ($fields['cp_article_cost']+$fields['editor_article_cost']) * $fields['nofa'];
                }
                $total_amount += $estimate_money;

                $fields['estimate_money'] = $estimate_money;
                $fields['estimate_total'] = $estimate_total;
                $fields['assigned_words'] = $fields['nofa'] * $fields['max_word'];
                $result[$i] = $fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        if ($p['opt_action'] == "export") {
            return $result;
        } else {
            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'result' => $result,
                         'count' => $count,
                         'total_amount' => $total_amount,
                         'total_rs' => count($result),
                         );
        }

    }//end search()
}
?>