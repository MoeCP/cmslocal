<?php
	require_once 'pre_cron.php';//parameter settings
    $conn->debug = true;
    $q = "SELECT COUNT(ar.article_id) AS completed_in_month, ck.copy_writer_id, ck.campaign_id, DATE_FORMAT(ar.client_approval_date, '%Y%m') AS month ";
    $q .= 'FROM articles  AS ar ';
    $q .= 'LEFT JOIN campaign_keyword  AS ck ON ck.keyword_id = ar. keyword_id ';
    $q .= "WHERE (article_status=5 OR article_status=6) AND ck.status!='D'  ";
    $q .= "GROUP BY ck.copy_writer_id, ck.campaign_id, month";
    $result = $conn->GetAll($q);
    foreach ($result as $k => $row) {
        $q = "SELECT history_id  FROM cp_campaign_article_summary ".
         "WHERE campaign_id = '".$row['campaign_id']."' ".
         "AND `month` = '".$row['month']."' " . 
         "AND `copy_writer_id` = '".$row['copy_writer_id']."' ";
        $rs = $conn->Execute($q);
        if ($rs) {
            $history_id = $rs->fields['history_id'];
            $rs->Close();
        }
        if ($history_id > 0) {
            $q = "UPDATE cp_campaign_article_summary ".
                " SET completed_in_month = " . $row['completed_in_month'] . 
                ' WHERE history_id=' . $history_id;
        } else {
            $history_id = $conn->GenID('seq_cp_campaign_article_summary_history_id');
            $row['history_id'] = $history_id;
            $row['is_paid'] = 0;
            $q = "INSERT INTO cp_campaign_article_summary (`" . implode("`, `", array_keys($row)) . "`) ";
            $q .= "VALUES ('" . implode("', '", $row) . "') ";
        }
        $conn->Execute($q);
    }
?>