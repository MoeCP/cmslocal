<?php
ini_set('upload_max_filesize', '20M');
$g_current_path = "client_campaign";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Campaign.class.php';
require_once CMS_INC_ROOT.'/Client.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
require_once CMS_INC_ROOT.'/feed_url.class.php';
require_once CMS_INC_ROOT.'/feed_article.class.php';
require_once COMMON_PATH. 'xml_customize_parser.php';

if (!user_is_loggedin() || User::getPermission() < 5) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

if (!empty($_POST) && !empty($_POST['feed_url']) || !empty($_GET) && isset($_GET['url_id']) && $_GET['url_id'] > 0) {
    if (!empty($_POST)) {
        $p = $_POST;
        $url = $p['feed_url'];
        $campaign_id = $p['campaign_id'];
        $client_id = $p['client_id'];
        $destination_path = WEB_PATH . DS . 'openfiles' . DS . 'gfeed' . DS . $client_id ;
        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0777);
        }
        $destination_path .= DS . $campaign_id;
         if (!file_exists($destination_path)) {
            mkdir($destination_path, 0777);
        }
        $prefix = time() . rand(1,1000000);
        $file = $destination_path . DS  . $prefix . '.xml';
        $parser = new XMLCustomizeParser();
        $parser->copyXML($url, $file);
        $url = $g_base_url . '/openfiles/gfeed/' . $client_id . '/' . $campaign_id . '/' . $prefix . '.xml';
        $info = array('feed_url' => $url);
        //$url = 'http://i9cms/openfiles/gfeed/jr-products-ftp.xml';
        $feedback = 'Succuess!';
    } else {
        $url_id = $_GET['url_id'];
        $info = FeedUrl::getInfo($url_id);
        $url = $info['feed_url'];
        $campaign_id = $info['campaign_id'];
        $_GET['campaign_id'] = $campaign_id;
        $parser = new XMLCustomizeParser();
    }
    if (!empty($url)) {
        $parser->readerOpen($url);
        $data = $parser->getHeaderInfo();
        $info += $data;
        $url_id = FeedUrl::save($info);
        $i=0;
        do {
            if ($parser->currentNodeName() == 'channel') {
                break;
            } else if ($parser->currentNodeType() == XMLReader::ELEMENT) {
                $arr = $parser->currentNodeData();
                foreach ($arr as $k => $v) {
                    if (preg_match("/g:/", $k)) {
                        unset($arr[$k]);
                    }
                }
                $arr['index'] = $i;
                $arr['url_id'] = $url_id;
                FeedArticle::save($arr);
                $i++;
            }
        } while($parser->readNext());
    }
     echo "<script>alert('".$feedback."');</script>";
     echo "<script>location.href='/client_campaign/feeds.php?url_id={$url_id}&campaign_id={$campaign_id}'</script>";
     exit();
}

$search = FeedUrl::getList($_GET);

if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$smarty->assign('maxsize', ini_get('upload_max_filesize'));
$campaign_id = $_GET['campaign_id'];
$campaign_info = Campaign::getInfo($campaign_id);
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('client_id', $campaign_info['client_id']);
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/uploadfeed.html');
?>