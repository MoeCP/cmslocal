<?php

// no cache
/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

require_once 'pre.php';
if (!empty($_GET['sid'])) {
    $sid = base64_decode($_GET['sid']);
    $arr = explode("-", $sid);
    if (count($arr) ==  3) {
        $cid = $arr[0];
        $kid = $arr[1];
        $aid = $arr[2];
        if ($cid > 0 && $kid > 0 && $aid > 0) {
            $sql  = "SELECT ar.article_id, ar.richtext_body FROM articles AS ar LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ";
            $sql .= "WHERE ar.article_id={$aid} and ck.keyword_id={$kid} and ck.campaign_id={$cid}";
            $arr = $conn->GetAll($sql);
            if (!empty($arr)) {
                if (!empty($arr[0]['richtext_body'])) {
                    $content = $arr[0]['richtext_body'];
                    echo htmlentities(html_entity_decode($content));
                }
                exit();
            }
        }
    }
}
echo 'Invalid parameter, please to check'; 
?>