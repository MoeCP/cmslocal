<?php 
require_once('../pre.php');//加载配置信息
$_GET['url_part'] = 'user';
if (strlen($_GET['u']) == 0 && strlen($_GET['p']) == 0) {
    if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
        header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
        exit;
    }
}
if (trim($_GET['article_id']) == '' && trim($_GET['cid']) == '' && trim($_GET['article_ids']) == '') {
    echo "<script>alert('Please choose a campaign or an article');window.close();</script>";
    exit;
}
require_once CMS_INC_ROOT.'/DomainTag.class.php';
getXML($_GET);
//echo "<script>window.close();</script>";
exit;
?>