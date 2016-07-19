<?php
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() == 2) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/keywordtrends.php';
if (!empty($_GET)) {
    $keyword = $_GET['keyword'];
    if (!empty($keyword)) {
        $types = array('googlenews', 'facebook', 'buzz', 'twitter');
        foreach ($types as $k => $type) {
            $results[$k] = array();
            $results[$k]['rs'] = getSearchResult($type, $keyword);
            
            // $keyword = urlencode($keyword);
            switch($type) {
            case 'googlenews':
                $results[$k]['label'] = 'Google News';
                $results[$k]['more'] = 'http://news.google.com/news/search?pz=1&cf=all&ned=cn&hl=en&q=' . $keyword;
                break;
            case 'facebook':
                $results[$k]['label'] = 'Facebook';
                $results[$k]['more'] = 'http://openfacebooksearch.com/?q=' . $keyword;
                break;
            case 'buzz':
                $results[$k]['label'] = 'Yahoo Buzz';
                if (!empty($results[$k]['rs'])) {
                    $results[$k]['more'] = 'http://buzz.yahoo.com/search;_ylt=?p=' . $keyword . '&fr=orion';
                }
                break;
            case 'twitter':
                $results[$k]['label'] = 'Twitter';
                $results[$k]['more'] = 'http://search.twitter.com/search?q=' . $keyword;
                break;
            }
        }
//          $results['googlenews'] = scrapeGoogleNews($keyword);
//          $results['facebook'] = scrapeFacebook($keyword);
//          $results['buzz'] = scrapeBuzz($keyword);
//          $results['twitter'] = scrapeTwitter($keyword);
    }
}
//pr($results);
$smarty->assign('results', $results);
$smarty->assign('feedback', $feedback);
$smarty->display('trends/results.html');
?>