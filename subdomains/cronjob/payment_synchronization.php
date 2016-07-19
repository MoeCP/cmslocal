<?php
require_once 'pre_cron.php';
$sql ='SELECT ar.article_id, ar.client_approval_date, ar.is_canceled, ck.article_type, ck.copy_writer_id, ck.editor_id, cc.campaign_id, cc.client_id ';
$sql .=' FROM articles AS ar ';
$sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ';
$sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ';
$sql .= "WHERE (ar.article_status = 5 OR ar.article_status=6) AND ck.status!='D' ";
$conn->debug = true;
$result = $conn->GetAll($sql);
$format = 'SELECT COUNT(*) AS num FROM article_payment_log AS apl WHERE `month`=%s AND user_id=%s and article_id=%s ';
foreach ($result as $k => $row) {
    $date = $row['client_approval_date'];
    $month = date("Ym", strtotime($date));
    $article_id = $row['article_id'];
    $data = array(
        'article_id' => $article_id,
        'article_type' => $row['article_type'],
        'client_id' => $row['client_id'],
        //'is_canceled' => $row['is_canceled'],
        'campaign_id' => $row['campaign_id'],
        'month' => $month,
        'pay_month' => $month,
    );
    $copywriter_id = $row['copy_writer_id'];
    $editor_id = $row['editor_id'];
    // check copy writer whether is exist or not
    $q = sprintf($format, $month, $copywriter_id, $article_id);
    $total = $conn->GetOne($q);
    if (empty($total)) {
        $data['user_id'] = $copywriter_id;
        $data['role'] = 'copy writer';
        store($data);
    }
    // check editor whether is exist or not
    $q = sprintf($format, $month, $editor_id, $article_id);
    $total = $conn->GetOne($q);
    if (empty($total)) {
        $data['user_id'] = $editor_id;
        $data['role'] = 'editor';
        store($data);
    }
}

function store($data)
{
    global $conn;
    $fields = array_keys($data);
    $sql = 'INSERT INTO `article_payment_log` (' . implode(", ", $fields). ') VALUES (\'' .implode("','", $data) . '\')';
    return $conn->Execute($sql);
}
?>