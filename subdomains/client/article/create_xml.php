<?php 
require_once('../pre.php');//¼ÓÔØÅäÖÃÐÅÏ¢
require_once CMS_INC_ROOT . DS .'Article.class.php';
$_GET['url_part'] = 'client';
if (strlen($_GET['u']) == 0 && strlen($_GET['p']) == 0) {
	if ((!user_is_loggedin() || User::getPermission() < 5) && !client_is_loggedin()) { // 4=>5
        header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
        exit;
    }
}

require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

$client_id = Client::getID();
$p['client_id'] = $client_id;
if (isset($_GET['campaign_id']) && !empty($_GET['campaign_id']))
    $p['campaign_id'] = $_GET['campaign_id'];
$p['aa_start'] = "";
$p['aa_end'] = "";
$res = Article::getDownloadInfo($p);
$res['article_status'] = array(5,6);
$result = Article::getArticlesList($res);
//print_r($result);
if (!empty($result) && $result!=array()){
    $p = $_GET;
    $p['result'] = $result;
    createXML($p, $res['aa_start']);
} else {
    echo "<script type='text/javascript'>alert('There is no latest articles for you to download!');window.close();</script>";
}
exit;
?>