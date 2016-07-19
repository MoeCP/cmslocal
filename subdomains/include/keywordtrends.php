<?php
function getSearchResult($method, $term, $num = 5)
{ 
    $method = ucwords(strtolower($method));
    $method = 'scrape' . $method;
    return ${method}($term, $num);
}

function scrapeFacebook($term, $num = 5){
    $results = array();
    $matches = array();
    $fb = json_decode(@file_get_contents('https://graph.facebook.com/search?type=post&q='.urlencode($term)));
    if (is_array($fb->data)) {
        foreach($fb->data as $post){
            $pubDate = dateFormat($post->updated_time);
          if($post->type == "status")
            $results[] = array(
              'title' => $post->name,
              'content' => $post->message,
              'pubDate' => $pubDate,
            );
          else
            $results[] = array(
              'title' => $post->name,
              'content' => $post->message,
              'link' => $post->link,
              'pubDate' => $pubDate,
            );
        }
    }
    return array_slice($results, 0, $num);
} 

function dateFormat($str)
{
    return date("Y-m-d H:i:s", strtotime($str));
}


function scrapeBuzz($term, $num = 5){
    $results = array();
    $matches = array();
    $buzz = @file_get_contents('http://buzz.yahoo.com/search;_ylt=?p='.urlencode($term));
    preg_match_all( '/<dt class="articleHeadlineContainer">.*href="(.*)".*>([^<]*)<\/a>.*<\/dt>/sU', $buzz, $matches);

    foreach($matches[1] as $key => $match){
      $results[$key]['link'] = $match;
      $results[$key]['title'] = $matches[2][$key];
    }

    preg_match_all( '/<dd class="articleAttributionContainer.*>.*href="(.*)".*>([^<]*)<\/a>[^<]*<p>(Submitted:([^<]*))<\/p>.*<\/dd>/sU', $buzz, $matches);
    if (is_array($matches[1])) {
        foreach ($matches[1] as $key => $match) {
            $results[$key]['pubDate'] = dateFormat($matches[4][$key]);
        }
    }
    preg_match_all( '/<dd class="articleSummaryContainer.*>(.*)<\/dd>/sU', $buzz, $matches);
    if (is_array($matches[1])) {
        foreach($matches[1] as $key => $match){
          $results[$key]['content'] = trim($match);
        }
    }
    return array_slice($results, 0, $num);
} 


function scrapeTwitter($term, $num = 5){
    $results = array();
    $xml = @simplexml_load_file('http://search.twitter.com/search.atom?q='.$term);
    // echo file_get_contents('http://search.twitter.com/search.atom?q='.$term);
    // pr($xml);
    if (is_array($xml->entry)) {
        foreach ($xml->entry as $entry) {
          $results[] = array(
            'link' => (string) $entry->link->attributes()->href,
            'title' => (string) $entry->title,
            'content' => (string) $entry->content,
            'pubDate' => dateFormat((string) $entry->published),
          );
        }
    }
    return array_slice($results, 0, $num);
} 



function scrapeGoogleNews($term, $num = 5){
    $results = array();
    $xml = @simplexml_load_file('http://news.google.com/news?output=rss&q='.$term.'&num='.$num);
    if (is_array($xml->channel->item)) {
        foreach ($xml->channel->item as $item) {
          $results[] = array(
            'title' => (string) $item->title,
            'link' => (string) $item->link,
            'guid' => (string) $item->guid,
            'pubDate' => dateFormat((string) $item->pubDate),
            'content' => (string) $item->description,
          );
        }
    }
    return array_slice($results, 0, $num);
} 
   
//  $results['facebook'] = scrapeFacebook($term);
//  $results['buzz'] = scrapeBuzz($term);
//  $results['twitter'] = scrapeTwitter($term);
//  $results['googlenews'] = scrapeGoogleNews($term);
//  
//  var_dump($results);

?>