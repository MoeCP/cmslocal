<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'article_type.class.php';
require_once CMS_INC_ROOT . DS . 'g_tag_map.php';

// set campaign id(s) by user 
// $campaign_ids = array(94); // 2007-10-15 22:28:09
$campaign_ids = array(58, 77, 85, 94, 100, 109); // 2007-10-22 16:06
$len = count($campaign_ids);

$xml_dir = $g_tag['xml_dir']['zipsearch'];
echo $xml_dir;
// $param['aa_start']          = "2007-10-15 22:28:10";
// $param['aa_start']          = "2007-10-22 01:15:55"; // 2007-10-28-05-22-31.xml
// $param['aa_start']          = "2007-10-28 05:22:31"; 
//$param['aa_start']          = "2007-11-05 22:54:32"; 
//$param['aa_start']          = "2007-11-12 23:49:42";
//$param['aa_start']          = "2007-11-14 20:56:00";
//$param['aa_start']          = "2007-11-18 21:11:59";
//$param['aa_start']          = "2007-11-25 19:36:25";
//$param['aa_start']          = "2007-12-02 22:21:14";
//$param['aa_start']          = "2007-12-26 21:45:25";
//$param['aa_start']          = "2008-01-07 02:52:01";
//$param['aa_start']          = "2008-01-13 23:13:20";
//$param['aa_start']          = "2008-01-21 00:43:19";
$param['aa_start']          = "2008-02-03 21:05:50";
$param['article_status']   = 6; 
$param['ck.campaign_id'] = $campaign_ids; 
$articles = Article::getArticlesList($param);
if ($articles) {
    
    $xml = Article::generateXML($articles, 'articles');
    $handle = fopen($xml_dir . date("Y-m-d-H-i-s", time()) . ".xml", "w+");
    fwrite($handle, $xml);
    fclose($handle);
} else {
    echo "no articles";
}
?>
