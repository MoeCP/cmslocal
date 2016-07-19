<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CRONJOB_INC_ROOT . 'lib' . DS . 'GoogleSearch.php';//load google search API
require_once 'HTTP' . DS . 'Client.php';
$conn->debug = true;
$match_pct =  0.10;
$limit_dup_url = 3;
$url_params = array();
$param = array(
    'u'=>'secondstepsearch',
    'k'=>'leo123',
    'o'=>'psearch',
    'c'=>1,
);
$internalscapeurl = 'http://index.copypress.com/index.php';
$internalscapeurl .= '?' . http_build_query($param);

$full_articles = Article::getArticleByParams(NULL, NULL);
foreach ($full_articles as $article_key => $item) {
    $status = '1gc';
    if (!check_article_by_internalscape($item, $internalscapeurl, $match_pct)) {
        Article::setArticleStatus( $article_key, $status, 1 );
    }
}
?>
