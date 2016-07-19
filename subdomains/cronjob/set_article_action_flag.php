<?php
/*
*Creator: Snug Xu
*Created Time: 14:19 2006-8-25
*Function Description: check whether campaign is finished or not, if the campaign is finished set status as completed
*Statsu Value Description:0 means uncompleted; 1 means completed
*/
require_once 'pre_cron.php';//parameter settings

function setCurrentFlag($old_status = '1gc', $new_status = '4')
{
    global $conn;
    $sql = "SELECT MAX( `created_time` ) , MAX(`action_id`) as action_id , `article_id` , `opt_id` , `opt_type` 
    FROM `article_action` 
    WHERE `status` = '{$old_status}'
    AND `new_status` ='{$new_status}'
    GROUP BY `article_id`
    ";
    $all_actions = $conn->GetAll($sql);
    echo ($sql . "\n");
    if (!empty($all_actions))
    {
        $action_ids = array();
        foreach ($all_actions as $k => $item)
        {
            $action_ids[] = $item['action_id'];
        }
    }
    $sql = "UPDATE `article_action`
    SET `curr_flag` = 1
    WHERE `action_id` IN (" . 
    implode(", ", $action_ids) . ")";
    $conn->Execute($sql);
    echo $conn->Affected_Rows() . "\n";
    return $conn->Affected_Rows();
}
$ret = setCurrentFlag('1gc', '4');
echo ($ret . "\n");
$ret = setCurrentFlag('4', '5');
echo ($ret . "\n");
echo "Running finished" . "\n";
?>