<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT  . DS . 'ClientSetting.class.php';
require_once CMS_INC_ROOT . DS . 'Article.class.php';
$conn->debug = true;
$result = ClientSetting::getAllByParam(array('is_active' => 1));
if (!empty($result)) {
    foreach ($result as $row) {
        $campaign_id = $row['campaign_id'];
        $client_id = $row['client_id'];
        $days = $row['days'];
        if ($client_id > 0 && $days >= 0) {
            $conditions = array('cc.client_id = ' . $client_id);
            if (!empty($campaign_id)) {
                $campaign_ids = explode(",", $campaign_id);
                $conditions[] = "ck.campaign_id IN ('" . implode("','", $campaign_ids) . "')";
            }
            $article_status = $row['article_status'];
            $to_article_status = $row['to_article_status'];
            $conditions[] = "ar.article_status='" .  $article_status. "'";
            if ($days >= 0) {
                $time = time() - $days * 86400;
                if ($article_status == '1gc') {
                    $conditions[] = 'ar.google_approved_time<=\'' . date("Y-m-d H:i:s", $time). '\'';
                } else if ($article_status == '4') {
                    $conditions[] = 'ar.approval_date<=\'' . date("Y-m-d H:i:s", $time). '\'';
                } else if ($article_status == '5') {
                    $conditions[] = 'ar.client_approval_date<=\'' . date("Y-m-d H:i:s", $time). '\'';
                }
                $lists = Article::getArticleListByParam(array('conditions' => $conditions, 'total'=>50));
                foreach ($lists as $data) {
                    $data['article_status'] = $to_article_status;
                    if ($article_status == '1gc' && $to_article_status == 5) {
                        $data['approve_action'] = 'forcec';
                        Article::autoForceApprove($data);
                        echo $feedback . "================================\n";
                    }
                }
            }
            
        }
        
    }
}
?>