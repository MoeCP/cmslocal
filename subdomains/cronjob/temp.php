<?php 
require_once 'pre_cron.php';//parameter settings
$sql = "SELECT `ar`.`article_id` , `ar`.`approval_date` , `ar`.`title` , 
`ar`.`current_version_number` AS version ,  ck.copy_writer_id 
FROM `articles` AS `ar` 
LEFT JOIN  campaign_keyword AS ck ON ck.keyword_id AND ar.keyword_id
LEFT JOIN article_action AS aa ON aa.article_id = ar.article_id
AND aa.new_status =5
WHERE (
`ar`.`article_status` =5
OR `ar`.`article_status` =6
)
AND aa.article_id IS NULL 
AND ck.status!='D' 
AND approval_date >0";
echo $sql . "\n";
exit();
$articles = $conn->GetAll($sql);
echo $sql . "<br />";
echo count($articles) . "<br />";
exit();
foreach ($articles  as $article)
{
    $sql = "SELECT id FROM seq_article_action_action_id";
    $action_id = $conn->GetAll($sql);
    echo ($sql . "\n");
    if (!empty($action_id))
    {
        $id = $action_id[0]['id'];
        $id++;
    }
    $sql = "UPDATE seq_article_action_action_id SET id = {$id}";
    $conn->Execute($sql);
    $sql = "INSERT INTO article_action  ";
    $sql .= "SET action_id = {$id}, \n";
    $sql .= "article_id = {$article['article_id']}, \n";
    $sql .= "version  = {$article['version']} , \n";
    $sql .= "new_version = {$article['version']} , \n";
    $sql .= "title = '" . addslashes($article['title']) . "',  \n";
    $sql .= "created_time = '{$article['approval_date']}',  \n";
    $sql .= "copy_writer_id = {$article['copy_writer_id']},  \n";
    $sql .= "new_copy_writer_id = {$article['copy_writer_id']},  \n";
    $sql .= "curr_flag = 1,  \n";
    $sql .= "status = 4,  \n";
    $sql .= "opt_name  = 'cronjob',  \n";
    $sql .= "opt_type  = '2', \n ";
    $sql .= "opt_id   = 0,  \n";
    $sql .= "new_status = 5 ";
    $conn->Execute($sql);
    // echo $sql . "\n";
    echo $conn->Affected_Rows() . "\n";
    if ($article['copy_writer_id'] == 63 || $article['copy_writer_id'] == 145)
    {
         $sql = "update  articles  set target_pay_month = 200710 where article_id = {$article['article_id']}";
         echo $article['copy_writer_id'] . ': ' . $sql . "\n";
         $conn->Execute($sql);
         echo $conn->Affected_Rows() . "\n";
    }
    echo $article['article_id'] . "\n";
}
?>