<?php
require_once 'pre_cron.php';
require_once  CMS_INC_ROOT . DS . 'User.class.php';
require_once  CMS_INC_ROOT . DS . 'UserPerformance.class.php';
require_once  CMS_INC_ROOT . DS . 'UserMonthPerformance.class.php';
require_once  CMS_INC_ROOT . DS . 'cp_campaign_ranking.class.php';
$conn->debug = true;
$users = User::getAllUserInfo();
if (!empty($users)) {
    $user_id_arr = array_keys($users);
    $user_field_name = 'ck.copy_writer_id';
    $conditions = array($user_field_name. " IN (".implode(",", $user_id_arr).") ");
    $rankings = CpCampaignRanking::getAllByParam(array('copy_writer_id' => $user_id_arr));
    $result = User::getAllCpPerformanceByConditions($users, $rankings, $conditions);

    // $users = User::getAllCpPerformance();
    foreach ($result as $user_id => $user) {
        UserPerformance::store($user);
    }

    $max_monthes = UserMonthPerformance::getMaxMonthUserList($user_id_arr);
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
    }
    $start_time = '2010-07-01';
    $start_time = strtotime($start_time);
    $end_time = strtotime("+1 month");
    $start_date = date("Y-m-01 00:00:00", $start_time);
    $end_date = date("Y-m-01 00:00:00", $end_time);
    $end_time = strtotime($end_date);
    //echo $end_date . ' ' . $start_date .'===================' . "\n\n";

    while ($start_time < $end_time) {
        $next_month_time = strtotime("+1 month", $start_time);
        $next_month = date("Y-m-01 00:00:00", $next_month_time);
        $conditions[1] = "(ck.date_assigned >='" . $start_date . "' AND ck.date_assigned < '" . $next_month . "')";
        $month = date("Ym", $start_time);
        $start_time = $next_month_time;
        $start_date = date("Y-m-01 00:00:00", $start_time);
        $result = User::getAllCpPerformanceByConditions($users, $rankings, $conditions);
        foreach ($result as $user_id => $user) {
            $user['report_month'] = $month;
            $data = $user;
            if ((!isset($data['performance_id'])||empty($data['performance_id'])) && isset($data['user_id']) && !empty($data['user_id'])) {
                $month = $data['report_month'];
                $role = $data['role'];
                $performance_id = UserMonthPerformance::getIdByUserIDAndMonth($data['user_id'], $month, $role);
                $data['performance_id'] = $performance_id;
            }
            if (isset($data['performance_id']) && !empty($data['performance_id'])) {
            } else {
                UserMonthPerformance::add($data);
            }
        }
    }
}
?>