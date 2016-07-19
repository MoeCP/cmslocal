<?php
ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
//$conn->debug = true;
if ($conn->debug) {
    ini_set("display_errors", "1");
}
require_once "File/CSV.php";
$users = User::forecastPayrollNoPagination($_GET);
$current_month = htmlspecialchars(trim($_GET['month']));
if (!strlen($current_month)) {
    $current_month = changeTimeToPayMonthFormat(getDelayTime());
}
$article_types = $g_tag['article_type'];
foreach ($users as $k => $row) {
    if (isset($row['pay_gct_count'])) unset($row['pay_gct_count']);
    if (isset($row['vendor_id'])) unset($row['vendor_id']);
    if (isset($row['qb_vendor_id'])) unset($row['qb_vendor_id']);
    if (isset($row['form_submitted'])) unset($row['form_submitted']);
    unset($row['notes']);
    if (isset($row['notes'])) unset($row['notes']);
    if (isset($row['user_type'])) unset($row['user_type']);
    if (isset($row['address'])) unset($row['address']);
    $users[$k] = $row;
}
$filename = 'forecast_report-' . $current_month. '-' . time() . '.csv';
$file = $g_article_storage . $filename;
if (!empty($users)) {
    $users = array_values($users);
    $fields = array_keys($users[0]);
    foreach($fields as $k => $v) {
        if (isset($article_types[$v])) $fields[$k] = 'type_' . $k;
    }
    $conf = array(
        'fields' => count($fields),
        'sep' => ',',
        'quote' => '"',
        'crlf' => "\n",
    );
    array_unshift($users, $fields);

    foreach ($users as $row) {
        if (isset($row['pay_amount'])) $row['pay_amount'] = '$' . $row['pay_amount'];
        $data = array_values($row);
        File_CSV::write($file, $data, $conf);
    }
}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=$filename");
header("Content-Transfer-Encoding: binary ");
if (file_exists($file)) {
    echo file_get_contents($file);
}
exit();
?>