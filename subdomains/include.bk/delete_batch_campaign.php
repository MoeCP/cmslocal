<?php
require_once 'pre_cron.php';
$start_time = time();
$conn->debug = true;
$sql = "SELECT DISTINCT cc.campaign_id, cc.client_id, cf.client_id, cc.campaign_name, cl.campaign_name 
FROM  campaign_logs  as cl, `client_campaigns`  as cc
LEFT JOIN  campaign_keyword AS ck ON (ck.campaign_id= cc.campaign_id)
LEFT JOIN  campaign_files AS cf ON (cf.client_id= cc.client_id)
WHERE (cc.campaign_name=cl.campaign_name) and cf.client_id= cc.client_id and ck.copy_writer_id = 0 AND ck.editor_id=0";
$result = $conn->GetAll($sql);
foreach ($result as $k => $row) {
    $campaign_id = $row['campaign_id'];
    $conn->StartTrans();
    $sql = "DELETE ar  FROM articles AS ar, campaign_keyword AS ck  WHERE ck.keyword_id=ar.keyword_id AND ck.campaign_id={$campaign_id} AND ar.article_number LIKE '%-{$campaign_id}-%'";
    $conn->Execute($sql);
    $sql = "DELETE ck FROM  campaign_keyword AS ck WHERE ck.campaign_id={$campaign_id}";
    $conn->Execute($sql);
    $sql = "DELETE cc FROM  client_campaigns AS cc WHERE cc.campaign_id={$campaign_id}";
    $conn->Execute($sql);
    $conn->CompleteTrans();
}
$diff = time() - $start_time;
echo $diff . "\n";
?>