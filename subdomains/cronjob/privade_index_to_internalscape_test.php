<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once 'HTTP' . DS . 'Client.php';

$param = array(
    'u'=>'secondstepsearch',
    'k'=>'93op9p0j6nx4tdpg',
    'o'=>'pindexadd',
    'e'=>'UTF-8',
);
$pub_url = 'http://test.copypress.com/index.php';
$url = $pub_url .  '?' . http_build_query($param);
$time = time();
$conn->debug = true;
$articles = Article::getArticleByParams( array('nullphandle' => true), null, 0, array(5, 6), 15);//get all the article_id and body that article status is 5 or 6
$handle = add_private_index($url, $articles[5422], true);
exit();
pr($articles[5422], true);

echo (time()-$time)/86400 . "\n";
?>
