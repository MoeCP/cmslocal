<?php
require_once 'pre_cron.php';//parameter settings
require_once 'HTTP' . DS . 'Client.php';
/////////////////////////////////////////////////////////////////////////////////////
$param = array(
    'u'=>'secondstepsearch',
    'k'=>'93op9p0j6nx4tdpg',
    'o'=>'csearch',
    'e'=>'UTF-8',
    'c'=>1,
);
$internalscapeurl = 'http://index.copypress.com/index.php?' . http_build_query($param);
$string = 'Chipping and remapping both include some risks. When performed incorrectly, either technique can destroy your car\'s computer, rendering the vehicle unfit for driving. Bear in mind as well that altering the computer system voids most manufacturer warranties. Some retuning companies offer alternate warranties with their remapping services, but the value of these warranties varies. To be safe, avoid remapping or chipping a car until after the manufacturer\'s warranty expires.';
$copy_writer_id = 943;
$post = array('t' => string_encode($string), 'uid' => $copy_writer_id);
$obj = copyscape_post($internalscapeurl, $post, $article_id);
$limit_dup_url = 3;
echo get_dup_links($obj);
pr($obj);
exit();
/////////////////////////////////////////////////////////////////////////////////////
require_once CRONJOB_INC_ROOT . 'lib' . DS . 'GoogleSearch.php';//load google search API
$ajaxUrl = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=";
//$article= 'test test test test  It is generally advised that you use a toothbrush with softer bristles';
$article= "They don't. Well at least they shouldn't. Meaning they shouldn't even be given the opportunity. I can't fathom any possible scenario where somebody would be so stupid as to take a JB iPod or iPhone to Apple and expect to get serviced";
$string = string_encode($article);
$string = urlencode('"' . $string . '"');
$results = curl_post($ajaxUrl, $string);
pr($results);
if (!empty($results) && isset($results[0]) && $results[0]->GsearchResultClass == 'GwebSearch') {
    echo 'here';
}
function curl_post($ajaxUrl, $string)
{
        $url = $ajaxUrl . $string;
        echo $url . "\n";
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
