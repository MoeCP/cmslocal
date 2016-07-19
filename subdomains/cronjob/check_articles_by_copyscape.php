<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once 'HTTP' . DS . 'Client.php';
$match_pct = 0.25;
$limit_dup_url = 3;
$param = array(
    'u'=>'secondstepsearch',
    'k'=>'93op9p0j6nx4tdpg',
    'o'=>'cpsearch',
    'e'=>'UTF-8',
    'c'=>1,
);

$time = time();

$articles = Article::getArticleByParams( NULL, null, 0, 1, 1);//get all the article_id and body that article status is 1
echo " the search result in: " . sizeof($articles) . "\n";
$copyscapeurl = "http://www.copyscape.com/api/";
$url_params = array();
foreach ($param as $k => $v) {
    $url_params[] = $k .'='. urlencode($v);
}
$copyscapeurl .= '?' . implode('&', $url_params);
foreach( $articles as $article_key => $row )
{
    $article = trim($row['body']);
    check_article_by_copyscape($article, $article_key, $row['keyword'], $copyscapeurl, $match_pct);
}
echo (time()-$time)/86400;
?>
