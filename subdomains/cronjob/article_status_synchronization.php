<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'Campaign.class.php';
require_once 'HTTP' . DS . 'Client.php';
$conn->debug = true;
//$url_path = 'http://222.212.70.246:8083/nextstep/content/';
$url_path = '/content/';
$host = '222.212.74.102';
$port = '8083';
$client = new HTTP_Client;
// Synchronize started articles
$article_ids = Campaign::getSynchronizationArticleId('started');
if (!empty($article_ids)) {
    $data = generateXML($article_ids);
    $started_api = $url_path . 'articleStarted.do';
    //$started_api = $url_path . 'articleStarted';
    // $started_api = 'http://api.i9cms/index.php';
    // $result = postApi($client, $started_api, $data);
    $result = xmlPost($host, $port, $started_api, $data);
   if ($result) Campaign::updateArticleStatus($article_ids, 'started', 2);
}
// end
// Synchronize completed articles
$article_ids = Campaign::getSynchronizationArticleId('completed');
if (!empty($article_ids)) {
    $data = generateXML($article_ids);
    $completed_api = $url_path . 'articleCompleted.do';
    //$completed_api = $url_path . 'articleCompleted';
    // $result = postApi($client, $completed_api, $data);
    $result = xmlPost($host, $port, $completed_api, $data);
    if ($result) Campaign::updateArticleStatus($article_ids, 'completed', 2);
}
// end

// function for this cronjob
function postApi($client, $api, $data)
{
    $client->post($api, $data);
    $result = $client->currentResponse();
    return $result['body'];
}
function xmlPost($host, $port, $uri, $xml){
	$fp = fsockopen($host, $port, $errno, $errstr);
	$result = '';
    if (!$fp) {
        echo "ERROR: $errno - $errstr\n";
        return false;
    } else {
        fputs($fp, "POST ".$uri." HTTP/1.0\r\n");
        fputs($fp, "Host: ".$host."\r\n");
        fputs($fp, "Content-Type: text/xml\r\n");
        fputs($fp, "Content-Length: ".strlen($xml)."\r\n");
        fputs($fp, "Connection: close\r\n");
        fputs($fp, "\r\n");
        fputs($fp, $xml);
        while (!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        fclose($fp);
    }
	return $result;
}
function generateXML($article_ids)
{
    array_walk($article_ids, 'generateNode');
    $xml = '<root><apikey>Efeer$45k9833</apikey>' . implode("", $article_ids)  .'</root>';
    return $xml;
}

function generateNode(&$item,$key, $prefix = 'articleId')
{
    $item = '<' . $prefix. '>' . $item.'</' . $prefix . '>';
}
?>
