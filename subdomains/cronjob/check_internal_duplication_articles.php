<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CRONJOB_INC_ROOT . 'lib' . DS . 'GoogleSearch.php';//load google search API
require_once 'HTTP' . DS . 'Client.php';
$conn->debug = false;
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

$param['o'] = 'psearch';
$internalscapeurl = 'http://index.copypress.com/index.php?' . http_build_query($param);

$sql = "SELECT ar.article_id, ar.body,ck.keyword_id,  ck.keyword, ar.title, ar.creation_user_id ,ar.creation_role, ck.article_type, ck.copy_writer_id, ck.editor_id, cc.source, cc.client_id FROM articles AS ar " 
        . "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.keyword_id " 
        . "LEFT JOIN client_campaigns AS cc ON cc.campaign_id = ck.campaign_id   " 
        . " WHERE (cc.client_id = 229 OR cc.campaign_id = 6585) and ar.article_id in (182396,182437,182556,182663,182716,182717,182721,182763) "
        . " ORDER BY  ar.article_id LIMIT 100 ";

$articles = $conn->GetAll($sql);

foreach ($articles as $k => $item) {
    check_duplicated_article_by_internalscape($item, $internalscapeurl, $match_pct);
}
echo $item['article_id'] . "\n";

function check_duplicated_article_by_internalscape($item, $url, $match_pct)
{
    global $admin_host;
    extract($item);
    if (empty($body)) return false;
    $article_id = $item['article_id'];
    $string = string_encode($body);
    $post = array('t' => $string, 'uid' => $copy_writer_id);
    $xml = copyscape_api_post($url, $post);
    echo "===============================================================\n";
    echo "https://content.copypress.com/article/article_comment_list.php?article_id=" . $article_id . "\n";
    echo "*************************************************\n";
    echo $xml . "\n";
    echo "*************************************************\n";
    echo "===============================================================\n";
    return false;
    $obj = @get_object_vars(@simplexml_load_string($xml));
    if (isset($obj['error'])) {
        return false;
    }
    //print_r($obj);
    $status = '1gc';
    $total = $obj['count'];
    if ($total > 0) {
        if ($total == 1) {
            $first_result = get_object_vars($obj['result']);
        } else {
            foreach ($obj['result'] as $item) {
                $first_result = get_object_vars($item);
                if ($first_result['id'] <> $article_id) {
                    break;
                }
            }
        }
        //pr($first_result);
        if ($first_result['id'] == $article_id) {
            return false;
        }
        
        $exactlysimilarrate = $first_result['exactlysimilarrate'];
        if ($exactlysimilarrate > $match_pct) {
            $data = "===============================================================\n";
            
            $data .= "https://content.copypress.com/article/article_comment_list.php?article_id=" . $article_id . "\n";
            $data .= "*************************************************\n" . $xml . "\n*************************************************\n";
            $data .= "===============================================================\n";
            $status = '1gd';
            file_put_contents(WEB_PATH . DS . "duplication_articles.txt", $data, FILE_APPEND);
        }
    }
    return false;
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
