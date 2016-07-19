<?php
error_reporting(E_ALL);
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
require_once CMS_INC_ROOT.'/otherutils.php';

$p = array();
$p = $_GET;
$p['title'] = 1;
$p['ht'] = 1;
$p['mk'] = 1;
$p['md'] = 1;
$p['body'] = 1;
$p['author'] = 1;
$p['is_rich'] = 1;
$p['text_body'] = 1;
$p['rich_body'] = 1;
$p['result'] = 1;
$p['mid'] = 1;
$p['optional1'] = 1;
$p['optional2'] = 1;
$p['optional3'] = 1;
$p['optional4'] = 1;
$p['optional5'] = 1;
$p['optional6'] = 1;
$p['optional7'] = 1;
$p['optional8'] = 1;
$p['optional9'] = 1;
$p['optional10'] = 1;
getAtom($p);
exit;
?>