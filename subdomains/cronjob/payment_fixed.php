<?php
require_once 'pre_cron.php';
$sql ='SELECT ar.article_id, ar.client_approval_date, ar.is_canceled, ck.article_type, ck.copy_writer_id, ck.editor_id, cc.campaign_id, cc.client_id ';
$sql .=' FROM articles AS ar ';
$sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ';
$sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ';
$sql .= "WHERE (ar.article_status = 5 OR ar.article_status=6) AND ck.status!='D' and ck.campaign_id = 1653 ";
// $conn->debug = true;
$result = $conn->GetAll($sql);
// $format = 'SELECT COUNT(*) AS num FROM article_payment_log AS apl WHERE `month`=%s AND user_id=%s and article_id=%s ';
$format = 'SELECT COUNT(*) AS num FROM article_payment_log AS apl WHERE user_id=%s and article_id=%s ';
//$format = 'UPDATE article_payment_log AS apl SET month= %s, pay_month=%s WHERE  user_id=%s AND article_id=%s AND  paid_time= \'0000-00-00 00:00:00\' AND pay_month=201003 AND pay_month <=month  ';
//$format = 
$insert_format = "INSERT INTO `article_payment_log` (%s) values (%s) ";
foreach ($result as $k => $row) {
    $date = $row['client_approval_date'];
    if ($date >= '2010-09-01 00:00:00') {
        $month = date("Ym", strtotime($date));
        $article_id = $row['article_id'];
        $copywriter_id = $row['copy_writer_id'];
        $editor_id = $row['editor_id'];
        // check copy writer whether is exist or not
        // $q = sprintf($format, $month, $copywriter_id, $article_id);
        $q = sprintf($format, $copywriter_id, $article_id);
        $num = $conn->GetOne($q);
        $data = array(
            'user_id' => $copywriter_id,
            'month' => $month,
            'article_id' => $article_id,
            'role' => 'copy writer',
            'client_id' => $row['client_id'],
            'campaign_id' => $row['campaign_id'],
            'article_type' => $row['article_type'],
            'is_canceled' => $row['is_canceled'],
            'pay_month' => $month,
        );
        $keys = array_keys($data);
        $keystr = '`' . implode("`,`", $keys) . '`';
        if ($num == 0) {
            $valuestr =  "'" . implode("','" , $data) . "'";
            $sql = sprintf($insert_format, $keystr, $valuestr);
            $conn->Execute($sql);
            echo $num . ' ' . $sql . "\n";
        }
        // check editor whether is exist or not
        // $q = sprintf($format, $month, $editor_id, $article_id);
        $q = sprintf($format, $editor_id, $article_id);
        $num = $conn->GetOne($q);
        if ($num == 0) {
            $data['role'] = 'editor';
            $data['user_id'] =$editor_id;
            $valuestr =  "'" . implode("','" , $data) . "'";
            $sql = sprintf($insert_format, $keystr, $valuestr);
            $conn->Execute($sql);
            echo $num . ' ' . $sql . "\n";
        }
    }    
}
?>