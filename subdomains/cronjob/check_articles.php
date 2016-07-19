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
$param = array(
    'u'=>'secondstepsearch',
    'k'=>'93op9p0j6nx4tdpg',
    'o'=>'csearch',
    'e'=>'UTF-8',
    'c'=>1,
);
$url_params = array();
foreach ($param as $k => $v) {
    $url_params[] = $k .'='. urlencode($v);
}
$copyscapeurl = 'http://www.copyscape.com/api/?' . implode('&', $url_params);

$param['o'] = 'psearch';
$internalscapeurl = 'http://index.copypress.com/index.php?' . http_build_query($param);

$articles = Article::getArticleByParams( NULL, 15, 0, 1 );//get all the article_id and body that article status is 1
$full_articles = Article::getArticleByParams(array('article_id' => array_keys($articles)), NULL);
echo " the search result in: " . sizeof($articles) . "\n";
$ajaxUrl = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=";
foreach( $articles as $article_key => $article_query ) {
    // total query string for one article
    $strlen = count($article_query);
    // record the url that google api searched
    $url_array = array(); 
    // record the keyword chack number
    $j = $i = 0;
    $dup_links = '';
    $aid = $article_key;
    $item = $full_articles[$aid];
    $keyword = $item['keyword'];
    $user = get_article_user($article_key);
    $editor = get_article_user($article_key, 'editor');
    $status = '1gc';
    $body = $item['body'];
    if (!check_article_by_internalscape($item, $internalscapeurl, $match_pct, $dup_links)) {
        if (!check_article_by_copyscape($body, $aid, $keyword, $copyscapeurl, $match_pct)) { 
            Article::setArticleStatus( $article_key, $status, 1 );
        }
    }
    /*foreach ($article_query as $key => $article) {
        // strip the space
        $article = trim($article);
        $j++;
        if( strlen( $article ) > 0 ) {	
            $string = string_encode($article);
            $string = urlencode('"' . $string . '"');
            $results = curl_post($ajaxUrl, $string);*/
           /*
            * check whether the article is copied from other sites or not
            * if the article is copied from other sites, set status as '1gc'--Google Clean
            * if the article is written by author, set status as '1gd'--Possible Duplication
            */
            /*$status = '1gc';
            if (!empty($results) && isset($results[0]) && $results[0]->GsearchResultClass == 'GwebSearch') {
                $status = '1gd';
                $url = "http://www.google.com/search?hl=en&newwindow=1&q=". $string . "";
                // get copywriter info
                if (!empty($user)) {
                    $mailer_param['cc'] = $editor['email'];
                    $dup_links = "<br /><a href=\"{$url}\" >Google Search Link</a><br />";             
                }
                break;
            }
        }
    }*/
    // compare checked query string and total query string
    /*if (!check_article_by_internalscape($item, $internalscapeurl, $match_pct, $dup_links)) {
        if ($status == '1gd' && !empty($user)) {
            // set duplicated checking url for one article
            Article::setCheckingURL( $article_key, $status, $url ,1 );
            sent_duplicated_article_email($user, $dup_links, $keyword, $article_key, $editor);
        } elseif ($j == $strlen && $status == '1gc') {
            $body = $item['body'];
            if (!check_article_by_copyscape($body, $aid, $keyword, $copyscapeurl, $match_pct)) { 
                Article::setArticleStatus( $article_key, $status, 1 );
            }
        }
    }*/
}

function curl_post($ajaxUrl, $string)
{
        $url = $ajaxUrl . $string;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 'http://content.copypress.com');
        $body = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($body);
        $results = $json->responseData->results;
        return $results;
}
?>
