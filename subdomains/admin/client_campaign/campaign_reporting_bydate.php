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
//###require_once CMS_INC_ROOT.'/article_payment_log.class.php';
require_once CMS_INC_ROOT.'/campaign_reporting.class.php';

if (!empty($_GET)) {
    $opt_action = $_GET['opt_action'];
    if ($opt_action == 'export') {
        require_once "File/CSV.php";

        $filename = 'campaign_reporting_bydate-' . time() . '.csv';
        $file = $g_article_storage . $filename;
        //##echo $_GET['opt_action'] = 'export';
        //##print_r($_GET);
        $result = CampaignReporting::baseon($_GET);
        
        $result = array_values($result);
        $fields = array_keys($result[0]);
        $fieldstr = implode(", ", $fields);
        $fieldstr = str_replace("_", " ", $fieldstr);
        $fieldstr = ucwords($fieldstr);
        $fields = explode(", ", $fieldstr);
        //##print_r($fields);
        //##exit;
        $conf = array(
            'fields' => count($fields),
            'sep' => ',',
            'quote' => '"',
            'crlf' => "\n",
        );
        array_unshift($result, $fields);
        foreach ($result as $row) {
            if (isset($row['estimate_money'])) $row['estimate_money'] = '$' . $row['estimate_money'];
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


$baseones = array('1'=>'Client Approved','2'=>'Editor Approved','3'=>'Assigned Writer');
$smarty->assign('baseones', $baseones);

$search = CampaignReporting::baseon($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('total_amount', $search['total_amount']);
    $smarty->assign('total_rs', $search['total_rs']);
}


$smarty->assign('now', changeTimeFormatToTimestamp($month));
$all_clients = Client::getAllClients('id_name_only', false);
$smarty->assign('all_clients', $all_clients);
$smarty->assign('users_status', array('All') + $g_tag['status']);
//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$query_string = $_SERVER['QUERY_STRING'];
if( strlen( $query_string ) )
{
	$query_string = '&'.$query_string;
}
$smarty->assign('actionurl', $_SERVER['REQUEST_URI']);
$smarty->assign('startNo', getStartPageNo());

$smarty->display('client_campaign/campaign_reporting_bydate.html');
?>