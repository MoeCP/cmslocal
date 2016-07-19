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
require_once "File/CSV.php";
$users = User::getAllAccountingReportNoPagination($_GET);
$current_month = htmlspecialchars(trim($_GET['month']));
if (!strlen($current_month)) {
    $current_month = changeTimeToPayMonthFormat(getDelayTime());
}
foreach ($users as $k => $row) {
    if ($row['pay_gct_count'] < 0) {
        $row['pay_words_total']  = $row['pay_words_total'] + $row['pay_gct_count'];
        unset($row['pay_gct_count']);
    }
    $users[$k] = $row;
}
$filename = 'accounting_report-' . $current_month. '-' . time() . '.csv';
$file = $g_article_storage . $filename;
if (!empty($users)) {
    $users = array_values($users);
    $fields = array_keys($users[0]);
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