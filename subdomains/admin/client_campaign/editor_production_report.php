<?php
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
if( count( $_POST ) ) {
	$operation = $_POST['operation'];
	switch( $operation ) {
    case 'auto_reminder':
        User::sendAutoReminder($_POST['user_id']);
        echo "<script>window.location.href='/client_campaign/editor_production_report.php';</script>";
        exit;
        break;
    }
}
$search = User::getUserProductReport('all editor', $_GET['perPage'], $_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$ajaxurl = '/client_campaign/campaign_production_report.php';
$smarty->assign('ajaxurl', $ajaxurl);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
    $query_string = '&'.$query_string;
}
$smarty->assign('query_string', $query_string);

$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);

$smarty->assign('users_status', $g_tag['users_status']);
$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$smarty->assign('actionurl', '/client_campaign/editor_production_report.php');
$smarty->assign('exporturl', '/client_campaign/production_export.php');
$smarty->assign('startNo', getStartPageNo());
$smarty->display('client_campaign/editor_production_report.html');
?>