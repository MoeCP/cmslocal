<?php
ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}

require_once "File/CSV.php";
$p = $_GET;
$p['article_status'] = 0;
$result = Campaign::getOverdueArticles($p, false);
$filename = 'overdue_articles_report-' . time() . '.csv';
$file = $g_article_storage . $filename;
if (!empty($result)) {
    $fields = array('Keyword', 'Start Date', 'Due Date',  'Status', 'Company Name', 'Campiagn Name', 'Copywriter', 'Editor', 'Days Overdue');
    $article_statuses = $g_tag['article_status'];
    $conf = array(
        'fields' => count($fields),
        'sep' => ',',
        'quote' => '"',
        'crlf' => "\n",
    );
    array_unshift($result, $fields);
    foreach ($result as $row) {
        if (isset($row['keyword_id'])) unset($row['keyword_id']);
        if (isset($row['campaign_id'])) unset($row['campaign_id']);
        if (isset($row['copy_writer_id'])) unset($row['copy_writer_id']);
        if (isset($row['editor_id'])) unset($row['editor_id']);
        if (isset($row['article_type'])) unset($row['article_type']);
        if (isset($row['article_id'])) unset($row['article_id']);
        if (isset($row['article_status'])) $row['article_status'] = $article_statuses[$row['article_status']];
        //if (isset($row['overdue'])) $row['overdue'] = ceil($row['overdue']);
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