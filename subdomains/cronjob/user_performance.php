<?php
require_once 'pre_cron.php';
require_once  CMS_INC_ROOT . DS . 'User.class.php';
require_once  CMS_INC_ROOT . DS . 'UserPerformance.class.php';
require_once  CMS_INC_ROOT . DS . 'UserMonthScore.class.php';
require_once  CMS_INC_ROOT . DS . 'article_score.class.php';
$conn->debug = true;
$users = User::getAllUserInfo();
if (!empty($users)) {
    $user_id_arr = array_keys($users);
    $user_field_name = 'ck.copy_writer_id';
    $conditions = array($user_field_name. " IN (".implode(",", $user_id_arr).") ");
    //$rankings = ArticleScore::getAllByParam(array('user_id' => $user_id_arr));

    $max_monthes = UserMonthScore::getMaxMonthUserList($user_id_arr);
    if (!empty($max_monthes)) {
        rsort($max_monthes);
        $monthes = array_values($max_monthes);
        $max_month = $monthes[0];
        $thismonth = date("Ym");
        if ($max_month == $thismonth) {
            $start_time = "-1 month";
        } else {
            $start_time = changeTimeFormatToTimestamp($max_month);
        }
    } else {
        $start_time = User::getMCpUpdated($conditions, 'MIN(ar.cp_updated)');
        $start_time = strtotime($start_time);
    }
    //$start_time = strtotime($start_time);
    $end_time = strtotime("+1 month");
    $start_date = date("Y-m-01 00:00:00", $start_time);
    $end_date = date("Y-m-01 00:00:00", $end_time);
    $end_time = strtotime($end_date);
    //echo $end_date . ' ' . $start_date .'===================' . "\n\n";
    while ($start_time < $end_time) {
        $next_month_time = strtotime("+1 month", $start_time);
        $next_month = date("Y-m-01 00:00:00", $next_month_time);
        $conditions[1] = "(ar.client_approval_date >='" . $start_date . "' AND ar.client_approval_date < '" . $next_month . "')";
        $month = date("Ym", $start_time);
        $start_time = $next_month_time;
        $start_date = date("Y-m-01 00:00:00", $start_time);
        $result = ArticleScore::generateMonthReport($conditions);
        foreach ($result as $user_id => $user) {
            $user['report_month'] = $month;
            UserMonthScore::store($user);
        }
    }
}
?>