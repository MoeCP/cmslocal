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
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/article_payment_log.class.php';

if (!empty($_GET)) {
    $opt_action = $_GET['opt_action'];
    if ($opt_action == 'export') {
        require_once "File/CSV.php";
        $current_month = htmlspecialchars(trim($_GET['month']));
        if (!strlen($current_month)) {
            $current_month = changeTimeToPayMonthFormat(getDelayTime());
        }
        $filename = 'accounting_report-' . $current_month. '-' . time() . '.csv';
        $file = $g_article_storage . $filename;
        $result = ArticlePaymentLog::monthlyReport('client', $_GET, false);
        
        $result = array_values($result);
        $fields = array_keys($result[0]);
        $conf = array(
            'fields' => count($fields),
            'sep' => ',',
            'quote' => '"',
            'crlf' => "\n",
        );
        array_unshift($result, $fields);
        foreach ($result as $row) {
            if (isset($row['cost'])) $row['cost'] = '$' . $row['cost'];
            $data = array_values($row);
            File_CSV::write($file, $data, $conf);
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
}
$search = ArticlePaymentLog::monthlyReport('client', $_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('total_amount', $search['total_amount']);
    $smarty->assign('total_rs', $search['total_rs']);

}

// added by snug xu 2006-10-17 14:30
// get all possible month from cp payment history
$monthes = CpPaymentHistory::getPaymentMonthByParam($_GET);
// pr($monthes, true);
$monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
$month = trim($_GET['month']);
if (strlen($month) == 0)
{
	$month = changeTimeToPayMonthFormat(getDelayTime());
}
//##added by leo 12/23/2014
$monthes = $monthes + genPayMonthList(1, 2);
$smarty->assign('monthes', $monthes);
$smarty->assign('month', $month);
// change month to time
$smarty->assign('now', changeTimeFormatToTimestamp($month));
// end
$all_agency = User::getAllUsers($mode='id_name_only', $user_type = 'agency');
asort($all_agency);
$smarty->assign('all_agency', $all_agency);
$campaign_list = Campaign::getAllCampaigns('id_name_only', '');
$smarty->assign('campaign_list', $campaign_list);
$smarty->assign('users_status', array('All') + $g_tag['status']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->assign('actionurl', $_SERVER['REQUEST_URI']);
$smarty->display('client_campaign/client_mreport.html');
?>