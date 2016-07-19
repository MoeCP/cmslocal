<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'article_type.class.php';
require_once CMS_INC_ROOT . DS . 'g_tag_map.php';

// set campaign id(s) by user 
// $campaign_ids = array(94); // 2007-10-15 22:28:09
$campaign_ids = array(111); // 2007-10-22 16:06
$len = count($campaign_ids);

$xml_dir = $g_tag['xml_dir']['zipsearch'];
// $param['aa_start']          = "2008-01-07 02:52:01";
 $param['aa_start']          = "2008-01-14 06:51:09";
$param['article_status']   = 5; 
$param['ck.campaign_id'] = $campaign_ids; 
$articles = Article::getArticlesList($param);
$xml = Article::generateXML($articles, 'articles');
$handle = fopen($xml_dir . date("Y-m-d-H-i-s", time()) . ".xml", "w+");
fwrite($handle, $xml);
fclose($handle);
?>
