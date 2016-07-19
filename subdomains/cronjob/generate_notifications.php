<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS .  'article_type.class.php';//parameter settings
require_once CMS_INC_ROOT . DS .  'g_tag_map.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'Notification.class.php';
require_once CMS_INC_ROOT . DS . 'article_action.class.php';
require_once CMS_INC_ROOT . DS . 'OrderCampaign.class.php';
$interval = $g_tag['notification_interval'];
$date_start = date("Y-m-01 00:00:00", strtotime('-' . $interval));
$date_end  = date("Y-m-d H:i:s");
$fields = $g_note_fields;
$conn->debug = true;
// get all copywriter user
$roles = array("editor", 'copy writer');
foreach ($roles as $role) {
    $where = array('u.role=\'' . $role . '\'');
    $users = User::getAllCopyWritersByParameters(array('where' => $where));
    //pr($users);
    if (!empty($users)) {
        echo "=========================================================\n";
        $user_ids = array_keys($users);
        $qw = ' AND u.user_id IN (' . implode(',', $user_ids).') AND ck.keyword_status!=0 ';
        foreach ($fields as $k => $s) {
            $where = '';
            switch($k) {
                case 'total_rejected':
                    if ($role == 'copy writer') {
                        $where = $qw;
                        $where .= " AND aa.status REGEXP '^(3|4|1gc)$'";
                        $where .= " AND aa.new_status  = 2";
                    }
                    break;
                case 'total_approval':
                    if ($role == 'copy writer') {
                        $where = $qw;
                        $where .= " AND aa.status = '1gc'";
                        $where .= " AND (aa.new_status = 4 OR aa.new_status=5)";
                    }
                    break;
                case 'total_assigned':
                    if ($role == 'copy writer') {
                        $where = $qw;
                        $where .= " AND aa.copy_writer_id  != aa.new_copy_writer_id AND aa.new_copy_writer_id > 0";
                        $where .= " AND ar.article_status = 0 ";
                    }
                    break;
                case 'total_unassigned':
                    if ($role == 'copy writer') {
                        $where = ' AND ck.copy_writer_id = 0 ';
                    } else {
                        $where = ' AND ck.editor = 0 ';
                    }
                    $result = Campaign::getCountGroupByCampaigns($k, $where);
                    if (!empty($result)) {
                        $total_user = count($user_ids);
                        $copy_writer_ids = array();
                        for ($i=0;$i<$total_user;$i++) {
                            $user_id = $user_ids[$i];
                            $copy_writer_ids[$user_id] = array();
                            foreach ($result as $row) {                                
                                $campaign_id = $row['campaign_id'];
                                $copy_writer_ids[$user_id][] = $campaign_id;
                                $total = $row[$k];
                                store_notifications($s, $total, $campaign_id,  $row['campaign_name'], $user_id, $k, $date_start, $date_end, $role);
                            }
                        }
                        if (!empty($copy_writer_ids)) {
                            hidden_notification($copy_writer_ids, $k);
                        }
                    }
                    break;
                case 'total_client_rejected':
                    if ($role == 'editor') {
                        $where = $qw;
                        $where .= " AND aa.status = '4'";
                        $where .= " AND aa.new_status  = 3";
                    }
                    break;
                case 'total_client_approval':
                    if ($role == 'editor') {
                        $where = $qw;
                        $where .= " AND (aa.status = '4' OR aa.status = '1gc')";
                        $where .= " AND aa.new_status  = 5";
                    }
                    break;
                case 'total_google_clean':
                    if ($role == 'editor') {
                        $where = $qw;
                        $where .= " AND aa.status = '1'";
                        $where .= " AND aa.new_status  = '1gc'";
                    }
                    $where .= " AND ar.article_status  = '1gc'";
                    break;
                case 'new_message':
                    break;
                case 'total_paid':
                    break;
                case 'assigned_denied':
                    break;
                case 'noaction_within24':
                    break;
            }
            if ($k != 'total_unassigned' && !empty($where)) {
                $where .= " AND aa.created_time >= '{$date_start}' AND aa.created_time  <= '{$date_end}' ";
                $result = ArticleAction::getCountGroupByCampaigns($k, $where, $role);
                if (!empty($result)) {
                    $copy_writer_ids = array();
                    foreach ($result as $row) {
                        $user_id = $row['user_id'];
                        $campaign_id = $row['campaign_id'];
                        $total = $row[$k];
                        if (!isset($copy_writer_ids[$user_id])) $copy_writer_ids[$user_id] = array();
                        $copy_writer_ids[$user_id][] = $campaign_id;
                        $note = store_notifications($s, $total, $campaign_id,  $row['campaign_name'], $user_id, $k, $date_start, $date_end, $role);
                        if (!empty($note) && $k == 'total_google_clean' && $role == 'editor') {
                            if (!isset($emails[$user_id])) $emails[$user_id] = array();
                            $totalgc = ArticleAction::getSumByArticleStatus($user_id, $campaign_id, '1gc', $role);
                            if ($totalgc > 0) $emails[$user_id][] = $note;
                        }
                    }
                    if (!empty($copy_writer_ids)) {
                        hidden_notification($copy_writer_ids, $k);
                    }
                    // added by nancy xu 2010-01-19 16:33
                    // sent email to editor when there are keywords that need to be approved.)
                    if ($k == 'total_google_clean' && !empty($emails) && $role == 'editor') {
                        $body = "Dear %s\n\n"
                                   ."%s\n"
                                   ."Please login at %s to do the editing. \n\n"
                                   ."Thank you.\n\n"
                                   ."Sincerely,\n\n"
                                   ."CopyPress \n\n";
                        $subject = 'Articles need editing and approval';
                        foreach ($emails as $user_id => $notes) {
                            if (empty($notes)) continue;

                            $note = implode(".\n", $notes) . '.';
                            $user = $users[$user_id];
                            $first_name = $user['first_name'];
                            $content = sprintf($body, $first_name, $note, $domain);
                            $content = nl2br($content);
                            send_smtp_mail($user['email'], $subject, $content, $mailer_param);
                        }
                    }
                    // end
                }
            }
            echo '==============================================' . "\n";
        }
    }
}
// pm noticification
foreach ($fields as $k => $s) {
    unset($result);
    unset($where);
    switch($k) {
        case 'new_campaign_order':
            $result = OrderCampaign::getCampaignOrderNotification(array('oc.status=0'));
            break;
        case 'confirm_campaign_order':
            $result = OrderCampaign::getCampaignOrderNotification(array('oc.status=7'));
            break;
        case 'pcnt_editor_approval':
            $where  = " AND aa.status = '1gc'";
            $where .= " AND (aa.new_status = 4 OR aa.new_status=5)";
            break;
        case 'pcnt_client_approval':
            $where  = " AND (aa.status = '4' OR aa.status = '1gc')";
            $where .= " AND aa.new_status  = 5";
            break;
    }
    if (!empty($result)) {
        foreach ($result as $row) {
            $campaign_id = $row['campaign_id'];
            $campaign_name = $row['campaign_name'];
            $role = $row['role'];
            if ($k == 'new_campaign_order' || $k == 'confirm_campaign_order') {
                $p_name = generateCampaignLink($campaign_name, $campaign_id, $role);
                $p = array($row['company_name'], $p_name);
                $total = 0;
            }
            store_notifications($s, $total, $campaign_id,  $campaign_name, $row['user_id'], $k, null, null, $role, $p);
        }
    } else if (!empty($where)) {
        $where .= ' AND ck.keyword_status!=0';
        $result = ArticleAction::getCountGroupByCampaigns($k, $where);
        $campaign_ids = array();
        foreach ($result as $row) {
            $campaign_ids[] = $row['campaign_id'];
        }
        if (!empty($campaign_ids)) {
            $campaigns = Campaign::getTotalGroupByCampaignID($campaign_ids);
        }
        foreach ($result as $row) {
            $total = $row[$k];
            $role = $row['role'];
            $campaign_id = $row['campaign_id'];
            $campaign_name = $row['campaign_name'];
            if ($total == $campaigns[$campaign_id]) {
                $p_name = generateCampaignLink($campaign_name, $campaign_id, $role);
                $p = array($p_name, '100%');
                store_notifications($s, $total, $campaign_id,  $campaign_name, $row['user_id'], $k, null, null, $role, $p);
            }
        }
    }
}

function hidden_notification($copy_writer_ids, $field) {
    if (!empty($copy_writer_ids)) {
        foreach ($copy_writer_ids as $user_id => $campaign_ids) {
            if (!empty($campaign_ids)) {
                $param = array(
                    'field_name' => $field,
                    'user_id' => $user_id,
                    'not in' => array('campaign_id' => $campaign_ids),
                 );
                Notification::hiddenNotification($param);
            }
        }
    }
}

function generateCampaignLink($name, $cid,  $role)
{
    if ($role == 'editor') {
        $p_name = '<a href="/article/article_list.php?campaign_id=' . $cid. '" target="_blank" >' . $name . '</a>';
    } else if ($role == 'copy writer') {
        $p_name = '<a href="/article/article_keyword_list.php?campaign_id=' . $cid. '" target="_blank" >' . $name . '</a>';
    } else {
        $p_name = '<a href="/client_campaign/keyword_list.php?campaign_id=' . $cid. '" target="_blank" >' . $name . '</a>';
    }
    return $p_name;
}

function store_notifications($note, $total, $campaign_id,  $campaign_name, $user_id, $field, $date_start, $date_end, $role, $s_param = array())
{
    if ($campaign_id > 0) {
        $copy_writer_ids[$user_id][] = $campaign_id;
        $param = array('user_id' => $user_id, 'field_name' => $field, 'campaign_id' => $campaign_id);
        if ($date_start > 0) $param['>='] = array('generate_date' => $date_start);
        if ($date_end > 0) $param['<='] = array('generate_date' => $date_end);
        $ret = Notification::getListByParam($param, array('notification_id', 'total', 'is_hidden'), 'notification_id DESC', true);
        if (empty($s_param)) {
            $name = generateCampaignLink($campaign_name, $campaign_id, $role);
            $s_param = array($total, $name);
        }

        $hash = array(
            'user_id' => $user_id,
            'role' => $role,
            'generate_date' => date("Y-m-d H:i:s"),
            'field_name' => $field,
            'campaign_name' => $campaign_name,
            'campaign_id' => $campaign_id,
            'total' => $total,
            'notes' => sprintf($note, $s_param[0], $s_param[1]),
        );
        $note = '';
        if (!empty($ret)) {
            $old_total = 0;
            foreach ($ret as $item) {
                if ($item['is_hidden'] == 1) {
                    $old_total += $item['total'];
                } else { 
                    $is_insert = false;
                    $notification_id = $item['notification_id'];
                }
            }
            if (!$is_insert && $old_total == 0) {
                $old_total = $ret[0]['total'];
            }
            if ($old_total != $total) {
                if ($total > $old_total) {
                    $total = $total - $old_total;
                    $hash['notes'] = sprintf($note, $total, $campaign_name);
                    $hash['total'] = $total;
                }
                if ($is_insert) {
                    Notification::save($hash);
                    $note = $hash['notes'];
                }  else {
                    $param['notification_id'] = $notification_id;
                    Notification::updateByParam($hash, $param);
                }
            }
        } else {
            Notification::save($hash);
            $note = $hash['notes'];
        }
    }
    return $note;
}
/*
(mysqli): SELECT u.*  FROM `users`  AS u   WHERE u.status!='D' AND u.role='editor'
-----
=========================================================
==============================================
==============================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as total_client_rejected, u.user_id, u.role, ck.campaign_id, cc.campa
n_name
FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN users AS u ON (u.user_id = ck.editor_id )
 WHERE  aa.curr_flag = 1  AND u.user_id IN (5,36,47,55,73,76,87,88,230,247,274,284,309,377,443,444,779,851,859,875,878
79,883,888,902,914,916,927) AND ck.keyword_status!=0  AND aa.status = '4' AND aa.new_status  = 3 AND aa.created_time >
'2012-04-01 00:00:00' AND aa.created_time  <= '2012-05-23 09:39:45'  AND ck.status!='D'
GROUP BY  ck.editor_id, ck.campaign_id
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_client_rejected' AND user_id='5' AND campaign
d NOT IN ('439', '503')
-----
==============================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as total_client_approval, u.user_id, u.role, ck.campaign_id, cc.campa
n_name
FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN users AS u ON (u.user_id = ck.editor_id )
 WHERE  aa.curr_flag = 1  AND u.user_id IN (5,36,47,55,73,76,87,88,230,247,274,284,309,377,443,444,779,851,859,875,878
79,883,888,902,914,916,927) AND ck.keyword_status!=0  AND (aa.status = '4' OR aa.status = '1gc') AND aa.new_status  =
AND aa.created_time >= '2012-04-01 00:00:00' AND aa.created_time  <= '2012-05-23 09:39:45'  AND ck.status!='D'
GROUP BY  ck.editor_id, ck.campaign_id
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_client_approval' AND user_id='5' AND campaign
d NOT IN ('418', '439', '486', '534')
-----
==============================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as total_google_clean, u.user_id, u.role, ck.campaign_id, cc.campaign
ame
FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN users AS u ON (u.user_id = ck.editor_id )
 WHERE  aa.curr_flag = 1  AND u.user_id IN (5,36,47,55,73,76,87,88,230,247,274,284,309,377,443,444,779,851,859,875,878
79,883,888,902,914,916,927) AND ck.keyword_status!=0  AND aa.status = '1' AND aa.new_status  = '1gc' AND aa.created_ti
 >= '2012-04-01 00:00:00' AND aa.created_time  <= '2012-05-23 09:39:45'  AND ck.status!='D'
GROUP BY  ck.editor_id, ck.campaign_id
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_google_clean' AND user_id='5' AND campaign_id
OT IN ('418', '439', '486', '503', '530', '534')
-----
==============================================
==============================================
==============================================
==============================================
==============================================
-----
(mysqli): SELECT u.*  FROM `users`  AS u   WHERE u.status!='D' AND u.role='copy writer'
-----
=========================================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as total_rejected, u.user_id, u.role, ck.campaign_id, cc.campaign_nam

FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN users AS u ON (u.user_id = ck.copy_writer_id )
 WHERE  aa.curr_flag = 1  AND u.user_id IN (2,6,16,17,28,30,34,38,39,46,53,54,56,58,59,61,66,67,69,70,75,79,84,100,101
05,106,110,111,121,125,127,129,130,139,146,149,155,159,161,163,175,176,178,180,181,187,196,201,204,205,210,213,217,223
33,237,239,246,253,254,260,266,272,279,280,281,293,296,301,306,307,315,316,321,325,326,333,334,335,336,339,340,349,352
56,367,369,370,378,381,390,394,398,405,407,408,410,416,419,429,435,439,440,441,461,476,479,482,489,495,497,506,507,523
27,531,539,541,544,549,551,557,564,565,566,568,584,590,592,593,597,602,605,607,609,611,612,623,625,632,633,634,637,638
43,645,657,658,664,666,672,677,680,686,696,699,711,715,725,732,743,744,748,760,785,795,800,809,810,814,818,826,827,828
31,834,836,838,839,842,844,848,853,856,862,866,876,877,881,890,893,894,895,896,897,898,899,900,901,903,904,907,908,909
11,912,917,918,919,920,921,922,923,924,925,926,928,929,930,931,932,933,934,935,936,937,939,940,941,942,943,944,945,946
47,948,949,950,951,952,953,954,955,956,957,958,959,960,961,962,963,964,965,966,967,969,970) AND ck.keyword_status!=0
D aa.status REGEXP '^(3|4|1gc)$' AND aa.new_status  = 2 AND aa.created_time >= '2012-04-01 00:00:00' AND aa.created_ti
  <= '2012-05-23 09:39:45'  AND ck.status!='D'
GROUP BY  ck.copy_writer_id, ck.campaign_id
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_rejected' AND user_id='6' AND campaign_id NOT
N ('407', '418', '439', '503', '530')
-----
==============================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as total_assigned, u.user_id, u.role, ck.campaign_id, cc.campaign_nam

FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN users AS u ON (u.user_id = ck.copy_writer_id )
 WHERE  aa.curr_flag = 1  AND u.user_id IN (2,6,16,17,28,30,34,38,39,46,53,54,56,58,59,61,66,67,69,70,75,79,84,100,101
05,106,110,111,121,125,127,129,130,139,146,149,155,159,161,163,175,176,178,180,181,187,196,201,204,205,210,213,217,223
33,237,239,246,253,254,260,266,272,279,280,281,293,296,301,306,307,315,316,321,325,326,333,334,335,336,339,340,349,352
56,367,369,370,378,381,390,394,398,405,407,408,410,416,419,429,435,439,440,441,461,476,479,482,489,495,497,506,507,523
27,531,539,541,544,549,551,557,564,565,566,568,584,590,592,593,597,602,605,607,609,611,612,623,625,632,633,634,637,638
43,645,657,658,664,666,672,677,680,686,696,699,711,715,725,732,743,744,748,760,785,795,800,809,810,814,818,826,827,828
31,834,836,838,839,842,844,848,853,856,862,866,876,877,881,890,893,894,895,896,897,898,899,900,901,903,904,907,908,909
11,912,917,918,919,920,921,922,923,924,925,926,928,929,930,931,932,933,934,935,936,937,939,940,941,942,943,944,945,946
47,948,949,950,951,952,953,954,955,956,957,958,959,960,961,962,963,964,965,966,967,969,970) AND ck.keyword_status!=0
D aa.copy_writer_id  != aa.new_copy_writer_id AND aa.created_time >= '2012-04-01 00:00:00' AND aa.created_time  <= '20
-05-23 09:39:45'  AND ck.status!='D'
GROUP BY  ck.copy_writer_id, ck.campaign_id
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_assigned' AND user_id='6' AND campaign_id NOT
N ('418', '486', '503', '534')
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_assigned' AND user_id='129' AND campaign_id N
 IN ('407')
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_assigned' AND user_id='239' AND campaign_id N
 IN ('486')
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_assigned' AND user_id='279' AND campaign_id N
 IN ('530')
-----
-----
(mysqli): UPDATE notifications SET is_hidden = 1 WHERE field_name='total_assigned' AND user_id='970' AND campaign_id N
 IN ('530')
-----
==============================================
==============================================
==============================================
==============================================
==============================================
==============================================
==============================================
==============================================
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as pcnt_editor_approval, u.user_id, u.role, ck.campaign_id, cc.campai
_name
FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN client AS cl ON (cl.client_id=cc.client_id)
LEFT JOIN users AS u ON (u.user_id=cl.project_manager_id)
 WHERE  aa.curr_flag = 1  AND aa.status = '1gc' AND (aa.new_status = 4 OR aa.new_status=5) AND ck.keyword_status!=0 AN
ck.status!='D'
GROUP BY  ck.campaign_id
-----
-----
(mysqli): SELECT COUNT(ck.keyword_id) AS total, ck.campaign_id FROM campaign_keyword AS ck  WHERE ck.campaign_id IN (1
,126,407,413,416,418,421,437,439,440,486,488,503,529,530,534) GROUP BY ck.campaign_id
-----
-----
(mysqli): SELECT COUNT(DISTINCT aa.article_id )  as pcnt_client_approval, u.user_id, u.role, ck.campaign_id, cc.campai
_name
FROM client_campaigns AS cc
LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)
LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id)
LEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id)
LEFT JOIN client AS cl ON (cl.client_id=cc.client_id)
LEFT JOIN users AS u ON (u.user_id=cl.project_manager_id)
 WHERE  aa.curr_flag = 1  AND (aa.status = '4' OR aa.status = '1gc') AND aa.new_status  = 5 AND ck.keyword_status!=0 A
 ck.status!='D'
GROUP BY  ck.campaign_id
-----
-----
(mysqli): SELECT COUNT(ck.keyword_id) AS total, ck.campaign_id FROM campaign_keyword AS ck  WHERE ck.campaign_id IN (1
,126,407,413,416,418,421,437,439,440,486,488,529,530,534) GROUP BY ck.campaign_id
-----
-----
(mysqli): SELECT oc.campaign_name, oc.order_campaign_id AS campaign_id, cl.company_name, u.user_id, u.role FROM order_
mpaigns AS oc INNER JOIN client AS cl ON (cl.client_id=oc.client_id) INNER JOIN users AS u ON (cl.project_manager_id=u
ser_id) WHERE oc.status=0
-----
-----
(mysqli): SELECT oc.campaign_name, oc.order_campaign_id AS campaign_id, cl.company_name, u.user_id, u.role FROM order_
mpaigns AS oc INNER JOIN client AS cl ON (cl.client_id=oc.client_id) INNER JOIN users AS u ON (cl.project_manager_id=u
ser_id) WHERE oc.status=7
-----
*/
?>