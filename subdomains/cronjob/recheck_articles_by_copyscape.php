<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'SssCheckLog.class.php';
require_once 'HTTP' . DS . 'Client.php';
$match_pct = 0.25;
$limit_dup_url = 3;
$param = array(
    'u'=>'secondstepsearch',
    'k'=>'93op9p0j6nx4tdpg',
    'o'=>'csearch',
    'e'=>'UTF-8',
    'c'=>1,
);

$conn->debug = true;
$time = time();
$limit_articles = 10;
$max_check_cp = 10;
$max_article_per_cp = 100;
$max_article_id = SssCheckLog::mArticleId();
if (empty($max_article_id)) $max_article_id = 0;
$p = array(
    'total' => $limit_articles,
    'morethan' => array('ar.article_id' => $max_article_id),
    'not' => array('ar.article_status' => array('0', '1', '1gd', '2')),
);
$cp_report = SssCheckLog::getTotalGroupByCopyWriterId();
$total_cp = count($cp_report);
if ($total_cp > $max_check_cp) {
    $copy_writer_ids = array_keys($cp_report);
    $p[] = array('copy_writer_id' => $copy_writer_ids);
}
$fields = array('ar.article_id', 'ck.keyword_id', 'ck.keyword', 'ck.copy_writer_id',  'ck.campaign_id', 'ar.article_status', 'ar.body');
$articles = Article::getArticleListByParam($p, $fields);
echo " the search result in: " . sizeof($articles) . "\n";
$copyscapeurl = "http://www.copyscape.com/api/";
$url_params = array();
foreach ($param as $k => $v) {
    $url_params[] = $k .'='. urlencode($v);
}
$copyscapeurl .= '?' . implode('&', $url_params);
$data = array();
$duplicated_data = array();
pr($cp_report);
foreach( $articles as $row )
{
    $copy_writer_id = $row['copy_writer_id'];
    if ($total_cp > $max_check_cp) {
        if (!in_array($copy_writer_id, $copy_writer_ids)) continue;
    } else {
        if (!isset($cp_report[$copy_writer_id])) $cp_report[$copy_writer_id] = 0;
    }
    $total = $cp_report[$copy_writer_id];
    if ($total > $max_article_per_cp) continue;
    $cp_report[$copy_writer_id]++;

    $string = trim($row['body']);
    unset($row['body']);
    $phrase_array = explode(' ',$string);
    if (count($phrase_array) < 100) continue;
    $string = string_encode($string);
    $post = array('t' => $string);
    $xml = copyscape_api_post($copyscapeurl, $post);
    $obj = get_object_vars(@simplexml_load_string($xml));
    if (isset($obj['error'])) {
        echo $obj['error'] . "\n";
        continue;
    }
    $total = $obj['count'];
    $percent = 0;
    if ($total > 0) {
        $first_result = get_object_vars($obj['result'][0]);
        $wordsmatched = $first_result['wordsmatched'];
        $querywords = $obj['querywords'];
        $percent = $wordsmatched/$querywords;
        // duplicated articles
        $arr = $row;
        if ($percent > $match_pct) {
            for ($i=0; $i<$limit_dup_url;$i++) {
            }
            $duplicated_data[] = $arr;
        }
    }
    $row['percent'] = $percent;
    $row['response_xml'] = $xml;
    $row['created'] = date("Y-m-d H:i:s");
    $data[] = $row;
}
if (!empty($data)) {
    SssCheckLog::batchSave($data);
}
echo (time()-$time)/86400;
?>
