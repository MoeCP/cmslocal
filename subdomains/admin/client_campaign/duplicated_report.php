<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
$logout_folder = '';//the folder of logout.php in
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}
$p = $_GET;
$search = Campaign::getDuplicatedArticles($p);
$opt = $p['opt'];
if ($opt == 'csv') {
    require_once "File/CSV.php";
    $filename = 'duplicated-report-' . date("Ymd") . '.csv';
    $file = $g_article_storage . $filename;
    if (!empty($search)) {
        $search = array_values($search);
        $fields = array_keys($search[0]);
        $conf = array(
            'fields' => count($fields),
            'sep' => ',',
            'quote' => '"',
            'crlf' => "\n",
        );
        array_unshift($search, $fields);
        foreach ($search as $row) {
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
}
$all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
$all_editor = User::getAllUsers($mode = 'id_name_only', $user_type = 'all_editor', false);
$smarty->assign('all_copy_writer', $all_copy_writer);
$smarty->assign('all_editor', $all_editor);
$all_campaigns = Campaign::getAllCampaigns('campaign_name', '');
$smarty->assign('all_campaigns', $all_campaigns);

$smarty->assign('article_status', $g_tag['article_status']);
$smarty->assign('result', $search['result']);
$smarty->assign('pager', $search['pager']);
$smarty->assign('count', $search['count']);
$smarty->assign('total', $search['total']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/duplicated_report.html');
?>