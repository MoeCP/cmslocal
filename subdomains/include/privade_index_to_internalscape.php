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
$pub_url = 'http://index.copypress.com/index.php';
$url = $pub_url .  '?' . http_build_query($param);
$time = time();
$conn->debug = true;
$articles = Article::getArticleByParams( array('nullphandle' => true), null, 0, array(5, 6), 15);//get all the article_id and body that article status is 1
echo " the search result in: " . sizeof($articles) . "\n";
//pr($articles);
foreach( $articles as $article_key => $row )
{
    $body = trim($row['body']);
    if (strlen($body) > 100) {
        pr($row, true);
        $handle = add_private_index($url, $row, true);
        if (!empty($handle)) {
            Article::updateArticleInfoByArticleID(array('phandle' => $handle, 'article_id' => $article_key));
            break;
        } else if ($handle === false) {
            break;
        }
    } else {
        Article::updateArticleInfoByArticleID(array('phandle' => $article_key, 'article_id' => $article_key));
    }
}
echo (time()-$time)/86400 . "\n";
?>
