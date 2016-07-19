<?php
require_once CMS_INC_ROOT.'/Client.class.php';
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}

$g_menu = array();

$i = 0;
$g_menu[$i]['path'] = 'home';
$g_menu[$i]['liclass'] = 'home';
$g_menu[$i]['module_name'] = 'Home Page';//Ê×Ò³
$g_menu[$i]['image'] = 'topbar-1-home';//Ê×Ò³
$g_menu[$i]['url'] = '/index.php';
$g_menu[$i]['sub_menu'] = array();
$j = 0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Home Page';
$g_menu[$i]['sub_menu'][$j]['url'] = '/index.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/hp.gif" border="0">';

$i ++;
$g_menu[$i]['path'] = 'client_campaign';
$g_menu[$i]['liclass'] = 'three';
$g_menu[$i]['module_name'] = 'My Campaigns';
$g_menu[$i]['image'] = 'topbar-3-campaigns';
$g_menu[$i]['url'] = '/client_campaign/list.php';

$g_menu[$i]['sub_menu'] = array();
$j = 0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'My Campaign List';
$g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/list.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
/*$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'My Pending Keywords';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/pending_keyword_list.php';*/
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Add New Campaign';
$g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/campaign_type.php';
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'My Articles';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_keyword_list.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Articles Awaiting Approval';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/pending_article_list.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ks.gif" border="0">';
/*
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'My Past Keywords';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/past_article_list.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Article List';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_list.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/as.gif" border="0">';
*/
$j ++;
/*if (Client::getAgencyId() <= 0 || Client::getAgencyId() == '') {
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Order New Campaign';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/order_campaign_set.php';
//   $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Campaign Orders';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/order_list.php';
 //   $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';

}*/

/*
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Article Version History';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_history_list.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
$j ++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Article Download';
$g_menu[$i]['sub_menu'][$j]['url'] = '/article/download_article_list.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
*/
$i ++;
$g_menu[$i]['path'] = 'preference';
$g_menu[$i]['liclass'] = 'five';
$g_menu[$i]['module_name'] = 'My Account';
$g_menu[$i]['image'] = 'topbar-7-my-account';//Ê×Ò³
$g_menu[$i]['url'] = '/client/detail.php';
$g_menu[$i]['sub_menu'] = array();
$j = 0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Update Password';
$g_menu[$i]['sub_menu'][$j]['url'] = '/passwd.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';
/*$j++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Generate API Key';
$g_menu[$i]['sub_menu'][$j]['url'] = '/client/generatekey.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';
$j++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'API Keys';
$g_menu[$i]['sub_menu'][$j]['url'] = '/client/keylist.php';*/
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';
$j++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Contact Us';
$g_menu[$i]['sub_menu'][$j]['url'] = '/forms/campaigns.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';

$i++;
$g_menu[$i]['path'] = 'Help';
$g_menu[$i]['liclass'] = 'two';
$g_menu[$i]['image'] = 'topbar-help';
$g_menu[$i]['pos'] = 'right';
$g_menu[$i]['module_name'] = 'Help';
$g_menu[$i]['url'] = '/suggestions/report_bugs.php';
$g_menu[$i]['sub_menu'] = array();
$j=0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Tech Support Form';
$g_menu[$i]['sub_menu'][$j]['url'] = '/suggestions/report_bugs.php';
//$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';
/*$j++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Content Questions';
$g_menu[$i]['sub_menu'][$j]['url'] = '/suggestions/suggestions.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';*/
/*$j++;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Download Approval Tutorial';
$g_menu[$i]['sub_menu'][$j]['url'] = '/manual_content/Download-Approval-Tutorial.pdf';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';
$g_menu[$i]['sub_menu'][$j]['target'] = 'blank';*/
unset($i, $j);

function get_cmi_by_path($path)
{
    global $g_menu;

    $count = count($g_menu);
    for ($i = 0; $i < $count; $i ++) {
        if ($g_menu[$i]['path'] == $path) {
            return $i;
        }
    }
}

$current_menu_index = get_cmi_by_path($g_current_path);

$smarty->assign('main_menu', $g_menu);
$smarty->assign('current_menu_index', $current_menu_index);
$smarty->assign('login_role', 'client');
$smarty->assign('g_current_path', $g_current_path);
$smarty->assign('sub_menu', $g_menu[$current_menu_index]['sub_menu']);

$smarty->assign('search_type', $g_tag['search_type']);
//print_r($g_tag['search_type']);
?>