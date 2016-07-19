<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'Inputfilter.class.php';
require_once CMS_INC_ROOT . DS . 'Article.class.php';
$articles = Article::getArticleListByParam(array(), array('ar.article_id', 'ar.richtext_body', 'ar.body', 'total_words'));
foreach ($articles as $article) {
    $richtext_body = $article['richtext_body'];
    $body = change_richtxt_to_paintxt($richtext_body, ENT_QUOTES);
    $body = stripslashes($body);
    $total_words = calculateArticleWords($body);
    $p = array('article_id' => $article['article_id'], 'body' => $body, 'total_words' => $total_words);
    Article::updateArticleInfoByArticleID($p);
}
?>