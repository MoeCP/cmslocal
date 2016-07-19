<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
$conn->debug = true;
$sql  = "SELECT COUNT(DISTINCT ar.article_id) AS total , u.user_id  ";
$sql .= "FROM articles AS ar, article_action AS aa, users AS u, campaign_keyword AS ck ";
$sql .= "WHERE ck.keyword_id =ar.keyword_id AND u.user_id=ck.copy_writer_id AND u.permission=1 ";
$sql .= "AND aa.article_id=ar.article_id AND aa.status=4 AND aa.new_status=3 ";
$sql .= "GROUP BY ck.copy_writer_id ";
$result = $conn->GetAll($sql);
foreach ($result as $row) {
    $sql = "UPDATE users SET total_rejected = " . $row['total'] . ' WHERE user_id = ' . $row['user_id'] . ' AND total_rejected > ' . $row['total'] ;
    $conn->Execute($sql);
}
$sql  = "SELECT COUNT(DISTINCT ar.article_id) AS total , u.user_id ";
$sql .= "FROM articles AS ar, article_action AS aa, users AS u, campaign_keyword AS ck ";
$sql .= "WHERE ck.keyword_id =ar.keyword_id AND u.user_id=ck.editor_id AND u.permission=3 ";
$sql .= "AND aa.article_id=ar.article_id AND aa.status=4 AND aa.new_status=3 ";
$sql .= "GROUP BY ck.editor_id ";
$result = $conn->GetAll($sql);
foreach ($result as $row) {
    $sql = "UPDATE users SET total_rejected = " . $row['total'] . ' WHERE user_id = ' . $row['user_id'] . ' AND total_rejected > ' . $row['total'] ;
    $conn->Execute($sql);
}
?>
