<?php
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';

if (isset($_POST) && count($_POST))
{
    $p = $_POST;
}
else if (isset($_GET))
{
    $p = $_GET;
}
if (isset($p) && !empty($p))
{
    $end   = $p['end_date'];
    $start = $p['start_date'];
}
else
{
    $start = date("Y-m-") . '01 00:00:00';
    $end   = date("Y-m-d H:i:s");
}
$search = Article::editorWorkingReport($p);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$smarty->assign('start', $start);
$smarty->assign('end', $end);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/editor_work_report.html');
?>