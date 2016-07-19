<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';
// added by nancy xu 2010-09-20 16:42
if (!empty($_POST)) {
    $p = $_POST;
    $order_id = 0;
    $order_campaign_id = $p['order_campaign_id'];
    if ($p['form_refresh'] != 'N' && is_numeric($order_campaign_id)) {
        $info = OrderCampaign::getInfo($order_campaign_id);
        if (empty($info)) {
            $feedback = 'Invalid Campaign Order';
        } else {
            foreach ($info as $k => $v) {
                if (empty($v)) unset($info[$k]);
            }
            
            $campaign_id = $info['campaign_id'];
            $campaign_info = Campaign::getInfoFields($campaign_id);
            foreach ($info as $k => $v) {
                if (isset($campaign_info[$k])) $info[$k] = $campaign_info[$k];
            }
            unset($info['order_campaign_id']);
            unset($info['campaign_id']);
            unset($info['campaign_date']);
            $info['parent_id'] = $order_campaign_id;
            $info['parent_campaign_id'] = $campaign_id;
            $info['campaign_name'] = $info['campaign_name'] . '-' . date("FY");
            $info['date_start'] = date("Y-m-d");
            $info['date_end'] = getMonthLastDate();
            $order_id = OrderCampaign::save($info, 'copy');
        }
    } else {
        $feedback = 'Invalid Campaign Order';
    }
     $arr = array(
         'order_id' => $order_id,
         'feedback' => $feedback,
     );
     echo json_encode($arr);
     exit();
}
// end

$search = OrderCampaign::getList($_GET);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}
$clients = array('' => '[all]') + Client::getAllClients('id_company_only');

$smarty->assign('clients', $clients);
$smarty->assign('login_role', User::getRole());
$smarty->assign('login_permission', User::getPermission());
$smarty->display('client_campaign/order_list.html');
?>