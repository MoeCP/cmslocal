<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once "File/CSV.php";
$grole = isset($_GET['role']) && !empty($_GET['role']) ? $_GET['role'] : 'copy writer';

$users = User::getUserProductReport($grole, -1, $_GET);

$filename = str_replace(' ', '-', strtolower( $grole)) . '-' . 'production_report-'. time() . '.csv';
$file = $g_article_storage . $filename;
if (!empty($users)) {
    $result = array();
    if ($grole == 'all editor') {
        $fields = array('User', 'Role', 'First Name', 'Last Name', 'Email', 'Total Campaigns in All Reports', 'Total Assigned', '% Pending Approaval', '% Editor Approved', '% Client Approved', 'Last Login');
    } else {
        $fields = array('User',  'First Name', 'Last Name', 'Email', 'Total Campaigns in All Reports', 'Total Assigned', '% Submitted', '% Editor Approved', '% Client Approved', 'Last Login');
    }
    foreach ($users as $k => $user){
        extract($user);
        if ($grole == 'all editor') {
            $arr = array($user_name, $role, $first_name, $last_name, $email, $total_camp, $total, $pct_total_pending_approval. '(' . $total_pending_approval. ')', $pct_total_editor_approval . '(' .  $total_editor_approval. ')', $pct_total_client_approval . '(' . $total_client_approval . ')');
        } else {
            $arr = array($user_name, $first_name, $last_name, $email, $total_camp, $total, $pct_total_submit. '(' . $total_submit. ')', $pct_total_editor_approval . '(' .  $total_editor_approval. ')', $pct_total_client_approval . '(' . $total_client_approval . ')');
        }
        $arr[] = date("m/d/y H:i:s", $time);
        $result[] = $arr;
    }
    $users = $result;
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