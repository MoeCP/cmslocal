<?php

$g_menu = array();

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
$_top_level_menus = array('suggestions', 'candidates', 'account');
//============================ added by leo 8/7/2009 4:56 PM ==================================//
//shows up the top level menu on the tab right.
if (in_array($g_current_path, $_top_level_menus)) {
    $smarty->assign('show_toplevel_right', 'Y');
}
//============================ added by leo 8/7/2009 4:56 PM ==================================//

$smarty->assign('main_menu', $g_menu);
// $smarty->assign('current_menu_index', $current_menu_index);
// $smarty->assign('sub_menu', $g_menu[$current_menu_index]['sub_menu']);

// $smarty->assign('user_permission_int', User::getPermission());
$smarty->assign('search_type', $g_tag['search_type']);
?>
