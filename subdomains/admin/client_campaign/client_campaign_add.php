<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');

// added by snug xu 2006-11-24 14:41
// let agency user access this page
if (!user_is_loggedin() || (User::getPermission() < 4 && User::getPermission() != 2)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/OrderCampaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
$order_campaign_id = $_GET['order_campaign_id'];
if (trim($_POST['client_id']) != '' && trim($_POST['campaign_name']) != '') {
    $operation = $_POST['operation'];
    unset($_POST['operation']);
    $p = $_POST;
    $monthly_recurrent = $p['monthly_recurrent'];
    $recurrent_time = $p['recurrent_time'];
    if ($monthly_recurrent == 1 && $recurrent_time > 0) {
        unset($p['monthly_recurrent']);
        unset($p['recurrent_time']);
    }
    if ($order_campaign_id > 0) $p['order_id'] = $order_campaign_id;
    if ($campaign_id = Campaign::add($p)) {
        if ($order_campaign_id) {
            
            $order_info = OrderCampaign::getInfo($order_campaign_id);
            $arr = array(
                'order_campaign_id'=> $order_campaign_id, 
                //'status'=> 10, 
                'campaign_date'=> date("Y-m-d"), 
                'campaign_id' => $campaign_id,
                // added by nancy xu 2011-02-18 16:28
                // when campaign order created as campaign,
                // if Additional Style Guide, Sample Content, Content Instructions, Special Instructions changes, the campaign order will also change.
                'campaign_requirement' => $_POST['campaign_requirement'],
                'sample_content' => $_POST['sample_content'],
                'keyword_instructions' => $_POST['keyword_instructions'],
                'special_instructions' => $_POST['special_instructions'],
                // end
             );
            if ($operation == 'copy') {
                $arr['campaign_name'] = $_POST['campaign_name'];
                $arr['date_start'] = $_POST['date_start'];
                $arr['date_end'] = $_POST['date_end'];
            }
            OrderCampaign::store($arr);
            // added by nancy xu 2011-03-31 16:58
            $recurrent_time = $order_info['recurrent_time'];
            if ($monthly_recurrent == 1 && $recurrent_time > 0) {
                $campaign_name = $order_info['campaign_name'];
                
                for($i = 1;$i < $recurrent_time;$i++) {
                    $today = getdate();
                    $month = $today['mon'] + $i;
                    $year = $today['year'];
                    if ($month > 12) {
                        $year++;
                        $month = $month%12;
                    }
                    $start_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
                    $this_month = strtotime($start_date);
                    $month++;
                    if ($month > 12) {
                        $year++;
                        $month = ($month + $i)%12;
                    }
                    $next_month = strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01') - 3600;
                    $end_date = date("Y-m-d", $next_month);
                    $time = strtotime($year . '-' . $month . '-01');
                    $p['campaign_name'] = $campaign_name . ' - ' . date("F Y", $this_month);
                    $p['date_start'] = $start_date;
                    $p['date_end'] = $end_date;
                    $campaign_id = Campaign::add($p);
                }
            }
            // end
            echo "<script>alert('".$feedback."');</script>";
            echo "<script>window.location.href='/client_campaign/order_list.php';</script>";
        }
        //sql_log();
        echo "<script>alert('".$feedback."');</script>";
        echo "<script>window.location.href='/client_campaign/list.php';</script>";
        exit;
    }
}

$all_client = Client::getAllClients('id_name_only');
$arr = Category::getAllCategoryByCategoryId();
if (empty($arr)) {
    $arr = array();
}
$category = array(0 => array('name' => 'Please select a category')) + $arr;
$smarty->assign('all_client', $all_client);
if (empty($_POST))
{
    $_POST['title_param'] = 1;
}
if (!empty($order_campaign_id)) {
    $client_campaign_info = OrderCampaign::getInfo($order_campaign_id);
    $client_campaign_info = array_merge($client_campaign_info, $_POST);
    $smarty->assign('order_campaign_id', $order_campaign_id);
} else {
    $client_campaign_info = $_POST;
}
$smarty->assign('client_name', $all_client[$client_campaign_info['client_id']]);
$smarty->assign('client_campaign_info', $client_campaign_info);
$smarty->assign('operation', $operation);
$smarty->assign('all_levels', $g_tag['content_levels']);
$tones = array(0 => '[choose]') + $g_tag['article_tones'];
$smarty->assign('tones', $tones);
// added by nancy xu 2010-11-03 14:30
require_once CMS_INC_ROOT.'/client_user.class.php';
$oClientUser = new ClientUser();
$domains = array('0' => '[choose domain]');
if ($client_campaign_info['client_id'] > 0)  {
    $domains += $oClientUser->getDomains(array('client_id' => $client_campaign_info['client_id']));
}
$smarty->assign('word_options', $g_word_options);
$smarty->assign('domains', $domains);
//End
$smarty->assign('expertises', $g_user_levels);
$smarty->assign('campaign_type', $g_tag['campaign_type']);
$smarty->assign('category', $category);
$smarty->assign('templates', $g_templates);
$smarty->assign('feedback', $feedback);
$smarty->assign('article_type', ArticleType::getAllLeafNodes(array('is_inactive' => 0)));
$smarty->display('client_campaign/client_campaign_form.html');
?>