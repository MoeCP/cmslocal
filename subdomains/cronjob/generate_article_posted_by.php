<?php
require_once 'pre_cron.php';
$start_time = time();
require_once 'HTTP/Client.php';
require_once CMS_INC_ROOT.'/article_action.class.php';
$conn->debug =true;
$limit = 10000;
$sql = " SELECT article_id FROM articles WHERE posted_by IS NULL AND article_status != '0' LIMIT " . $limit;
$result = $conn->GetAll($sql);
foreach ($result as $row) {
    $article_id  = $row['article_id'];
    if ($article_id > 0) {
        $sql = "SELECT * FROM `article_action` WHERE curr_flag=1 AND  `status` != `new_status` AND article_id = " . $article_id;
        $logs = $conn->GetAll($sql);
        $posted_by = array();
        foreach ($logs as $log) {
            $status = $log['status'];
            $new_status = $log['new_status'];
            $field = '';
            if (($status == '0' || $status == '2') && $new_status == '1' ) {
                $field = 'submitted';
            } elseif ($status == '4' && $new_status == '5' ) {
                $field = 'client_approved';
            } elseif ($status == '5' && $new_status == '6' ) {
                $field = 'published';
            } else if ($status == '4' && $new_status == '3' ){
                $field = 'client_rejected';
            } else if (($status == '1gd' || $status == '3' || $status == '1gc' || $status == '4') && $new_status == '2' ){
                $field = 'rejected';
            } else if (($status == '1gc' || $status == '3') && $new_status == '4'){
                $field = 'approved';
            } else if ($hash['status'] == '1' && $hash['new_status'] == '1gd'){
                $field = 'duplicated';
            } else if ($hash['status'] == '1' && $hash['new_status'] == '1gc'){
                $field = 'google_clean';
            }
            if (!empty($field)) {
                $posted_by[$field] = array(
                        'opt_id' => $log['opt_id'],
                        'date_time' => $log['created_time'],
                        'opt_name' => $log['opt_name'],
                        'opt_type' => $log['opt_type'],
                 );
            }
        }
        $sql = "UPDATE articles SET posted_by = '" . addslashes(serialize($posted_by)). "' WHERE article_id = " . $article_id;
        $conn->Execute($sql);
    }
}
echo "\n" . (time() - $start_time)/3600 . "\n";
?>