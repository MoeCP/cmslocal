<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT . '/image_type.class.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$page_url = "/graphics/image_type.php";
if (count($_POST))
{
    $opt = $_POST['operation'];
    if ($opt == 'save')
    {
        $selected_type = ImageType::store($_POST);
        if (is_numeric($selected_type) && strlen($_POST['type_id']) == 0)
        {
            echo '<script language="JavaScript">window.location.href=\'' . $page_url . '?type_id=' . $selected_type . '\'</script>';
        }
    }
}
/***get all article type from database***/
$types       =  ImageType::getAllTypes();
$roots       =  ImageType::getAllTypes(array('parent_id' => -1));
$type_num = count($types);
$types       = array('' => "[Choose Image Type]") + $types;
$roots = array('-1' => "[No Parent]") + $roots;
$smarty->assign('image_types', $types);
$smarty->assign('type_num', $type_num);
// get selected article type
$selected_type = $_GET['type_id'];
$smarty->assign('selected_type', $selected_type);
// get article type info by type id
$info = strlen($selected_type) ? ImageType::getTypeByID($selected_type) : array();
if (!empty($info) && $info['parent_id'] >= 0) {
    $smarty->assign('parent_image_type', $roots[$info['parent_id']]);
}
$smarty->assign('info', $info);
$smarty->assign('feedback', $feedback);
$smarty->assign('page_url', $page_url);
$smarty->assign('roots', $roots);
$smarty->display('graphics/image_type.html');
?>