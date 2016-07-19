<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "reporting";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

require_once CMS_INC_ROOT.'/feed_url.class.php';
require_once CMS_INC_ROOT.'/feed_article.class.php';
$info = FeedUrl::getInfo($_GET['url_id']);
$str = html_entity_decode($info['xml_str']);
$filename = 'google-feed' . time() . '.xml';
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary ");
header("Content-Disposition: attachment;filename=$filename");
if (!empty($info) && !empty($str)) {

    if (!empty($str)) {
        $data = FeedArticle::getItemsByParam($_GET);
        $xml = simplexml_load_string($str);
        $gtitle = $xml->xpath("//title");
        $gdesc = $xml->xpath("//description");
        $glink = $xml->xpath("//link");
        foreach ($data as $item) {
            $index = $item['index'];
            if ($item['article_id'] > 0) {
                $title = htmlentities($item['title']);
                $gtitle[$index][] = $title;
                $option1 = htmlentities($item['option1']);
                $glink[$index][] = $option1;
                $body = htmlentities($item['richtext_body']);
                $gdesc[$index][] = $body;
            }
        }
    }
    echo $xml->asXML();
} else {
    echo "Invalid Feed";
}
exit();
?>