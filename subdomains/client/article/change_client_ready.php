<?php
$g_current_path = "article";
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once('../cms_menu.php');
if (!client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Article.class.php';
$data = $_REQUEST;
Article::setIsClientReady($data['aid'], $data['status']);
echo '<script>alert("' . $feedback . '")</script>';
exit();
?>