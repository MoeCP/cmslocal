<?php

$g_menu = array();

$i = 0;
$g_menu[$i]['path'] = 'home';
$g_menu[$i]['module_name'] = 'Home Page';//Ê×Ò³
$g_menu[$i]['url'] = '/index.php';
$g_menu[$i]['sub_menu'] = array();
$j = 0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Home Page';
$g_menu[$i]['sub_menu'][$j]['url'] = '/index.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/hp.gif" border="0">';

if (User::getPermission() >= 4) { // 3=>4
    $i ++;
    $g_menu[$i]['path'] = 'user';
    $g_menu[$i]['module_name'] = 'User Management';
    $g_menu[$i]['url'] = '/user/list.php';

    $g_menu[$i]['sub_menu'] = array();
    $j = 0;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'User List';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/user/list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/us.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Add New User';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/user/user_add.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ua.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Copy Writer Payment Information';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/user/payment.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cp.gif" border="0">';
}

if (User::getPermission() >= 5) { // 4=>5
    $i ++;
    $g_menu[$i]['path'] = 'client';
    $g_menu[$i]['module_name'] = 'Client Management';
    $g_menu[$i]['url'] = '/client/list.php';

    $g_menu[$i]['sub_menu'] = array();
    $j = 0;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Client List';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client/list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cs.gif" border="0">';
    $j ++;
	$g_menu[$i]['sub_menu'][$j]['label'] = 'Add Client';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client/client_add.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ca.gif" border="0">';
}

if (User::getPermission() >= 4) { // 3=>4
    $i ++;
    $g_menu[$i]['path'] = 'client_campaign';
    $g_menu[$i]['module_name'] = 'Client Campaign Management';
    $g_menu[$i]['url'] = '/client_campaign/client_list.php';

    $g_menu[$i]['sub_menu'] = array();
    /*
    $j = 0;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'campaign List';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
    */
    $j = 0;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'client campaign';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/client_list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Add New Campaign';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/client_campaign_add.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
    /*
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Keyword List';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/keyword_list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ks.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Add Campaign Keyword';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';   
	$j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Assign Keyword';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/batch_assign_keyword.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ak.gif" border="0">';
    */
}

if (User::getPermission() >= 1 && User::getPermission() < 4) { // 3=>4
    //$g_current_path = 'article';
    $i ++;
    $g_menu[$i]['path'] = 'article';
    $g_menu[$i]['module_name'] = 'Campaign & Article Management';
    if (User::getPermission() == 1) {
        $g_menu[$i]['url'] = '/client_campaign/ed_cp_campaign_list.php';
    } else {
        $g_menu[$i]['url'] = '/client_campaign/client_list.php';
    }

    $g_menu[$i]['sub_menu'] = array();
    $j = 0;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Campaign & Article Management';
    if (User::getPermission() == 1) {
        $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/ed_cp_campaign_list.php';
    } else {
        $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/client_list.php';
    }
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/as.gif" border="0">';

    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'My Articles';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
    /*
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'My Keywords';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_keyword_list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/cd.gif" border="0">';
    */
    if (User::getPermission() > 1) {
        $j ++;
        $g_menu[$i]['sub_menu'][$j]['label'] = 'Article Version History';
        $g_menu[$i]['sub_menu'][$j]['url'] = '/article/article_history_list.php';
        $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
        $j ++;
        $g_menu[$i]['sub_menu'][$j]['label'] = 'Reassign Keyword';
        $g_menu[$i]['sub_menu'][$j]['url'] = '/client_campaign/list_cpw.php';
        $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ak.gif" border="0">';
    }
}

$i ++;
$g_menu[$i]['path'] = 'preference';
$g_menu[$i]['module_name'] = 'System Setting';
$g_menu[$i]['url'] = '/user/passwd.php';
$g_menu[$i]['sub_menu'] = array();
$j = 0;
$g_menu[$i]['sub_menu'][$j]['label'] = 'Update Password';
$g_menu[$i]['sub_menu'][$j]['url'] = '/user/passwd.php';
$g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/up.gif" border="0">';

if (User::getPermission() >= 5) { // 4=>5
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Mail Templates';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/mail/list.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/ps.gif" border="0">';
    $j ++;
    $g_menu[$i]['sub_menu'][$j]['label'] = 'Add Mail Templates';
    $g_menu[$i]['sub_menu'][$j]['url'] = '/mail/add.php';
    $g_menu[$i]['sub_menu'][$j]['image'] = '<img src="/image/icons/as.gif" border="0">';
}

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
$smarty->assign('sub_menu', $g_menu[$current_menu_index]['sub_menu']);

$smarty->assign('search_type', $g_tag['search_type']);
//print_r($g_tag['search_type']);
?>