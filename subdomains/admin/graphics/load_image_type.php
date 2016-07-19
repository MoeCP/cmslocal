<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
require_once CMS_INC_ROOT . '/image_type.class.php';
if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$type_id = $_GET['tid'];
if ($type_id != '' && $type_id >= 0) {
    $info = ImageType::getTypeByID($type_id);
    if (!empty($info)) {
        $name = $info['type_name'] . ' - ';
    }
    $parent_id = '';
    $children_id = $_GET['cid'];
    if ($children_id != '' && $children_id >=0) {
        $cinfo  = ImageType::getTypeByID($children_id);
        $name = $cinfo['type_name'];
        $parent_id = $cinfo['parent_id'];
    }
?>
<script language="JavaScript">
$('type_name').value='<?php echo $name;?>';
</script>
<?php
}
?>