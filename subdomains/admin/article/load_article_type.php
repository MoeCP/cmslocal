<?php
$g_current_path = "preference";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$type_id = $_GET['tid'];
if ($type_id != '' && $type_id >= 0) {
    $info = ArticleType::getTypeByID($type_id);
    if (!empty($info)) {
        $name = $info['type_name'] . ' - ';
        $type_cost = $info['type_cost'];
        $editor_cost = $info['editor_cost'];
        $cp_cost = $info['cp_cost'];
    }
    $parent_id = '';
    $children_id = $_GET['cid'];
    if ($children_id != '' && $children_id >=0) {
        $cinfo  = ArticleType::getTypeByID($children_id);
        $name = $cinfo['type_name'];
        $type_cost = $cinfo['type_cost'];
        $editor_cost = $cinfo['editor_cost'];
        $cp_cost = $cinfo['cp_cost'];
        $parent_id = $cinfo['parent_id'];
    }
?>
<script language="JavaScript">
$('type_name').value='<?php echo $name;?>';
$('cp_cost').value='<?php echo $cp_cost;?>';
$('editor_cost').value='<?php echo $editor_cost;?>';
</script>
<?php
}
?>