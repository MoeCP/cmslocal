<?php
require_once 'pre_cron.php';
$sql = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `cc`.`campaign_name`, `ck`.`copy_writer_id`, `ck`.`editor_id`, `ck`.`keyword`, ar.article_id, ar.article_number, ar.title, ar.approval_date,ar.client_approval_date,  ar.is_canceled, apl.log_id, apl.month as apl_month, apl.paid_time, 
ar.article_status, cc.campaign_name, ar.total_words AS word_count , 
at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked, ach.pay_by_article AS ach_checked, 
ac.cp_article_cost AS ac_article_cost, at.cp_article_cost AS at_article_cost, ac.cp_cost AS ac_word_cost, at.cp_cost AS at_word_cost, ach.cost_per_article AS ach_type_cost, 
ac.editor_article_cost AS ac_e_article_cost, at.editor_article_cost AS at_e_article_cost, ac.editor_cost AS ac_e_word_cost, at.editor_cost AS at_e_word_cost,ach_e.pay_by_article AS ach_e_checked, ach_e.cost_per_article AS ach_e_type_cost, 
CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name, uc.pay_level,uc.email as uc_email
FROM campaign_keyword AS ck 
LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) 
LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND ck.copy_writer_id = apl.user_id) 
LEFT JOIN article_type AS at ON at.type_id = ck.article_type 
LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  
LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)  
LEFT JOIN article_payment_log AS apl_e ON (apl_e.article_id = ar.article_id AND ck.editor_id = apl_e.user_id) 
LEFT JOIN `article_cost_history` AS ach_e ON (ach_e.campaign_id = ck.campaign_id AND ach_e.article_type=ck.article_type AND ach_e.user_id=apl_e.user_id AND ach_e.month=apl_e.pay_month)   
LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) 
LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) 
WHERE 1  and cc.client_id='429' AND ck.status!='D' 
GROUP BY ar.article_id
ORDER BY ck.keyword_id DESC";
$data = array(array("article title", 'cost'));
$rows = $conn->GetAll($sql);
$fp = fopen('HipmunkCost.csv', 'w');

$i = 1;
foreach ($rows as $k => $fields) {
    $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
    extract($tmp);
    $word_count = $fields['word_count'] ;
    $fields['cost_per_article'] = $cost_per_unit;
    $fields['cost_for_article'] = ($checked == 0) ? $word_count* $cost_per_unit : $cost_per_unit;
    $tmp = getCostAndPayType($fields, 'e_');
    extract($tmp);
    $editor_cost = ($checked == 0) ? $word_count* $cost_per_unit : $cost_per_unit;
    $fields['cost_per_article'] += $cost_per_unit;
    $fields['cost_for_article'] += $editor_cost;

    if ($i == 1) {
        fputcsv($fp, array_keys($fields));
        $i++;
    }

    fputcsv($fp, $fields);

    $data[] = array($fields['title'], $fields['cost_for_article']);
}
print_r($data);

fclose($fp);

?>