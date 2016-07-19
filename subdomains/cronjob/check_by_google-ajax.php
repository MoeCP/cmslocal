<?php
require_once 'pre_cron.php';//parameter settings
$ajaxUrl = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=";
$string = string_encode("Transformers: Dark of the Moon");
$string = urlencode('"' . $string . '"');
$results = curl_post($ajaxUrl, $string);
print_r($results);
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
