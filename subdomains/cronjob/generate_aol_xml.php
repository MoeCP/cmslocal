<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'ArticleExtraInfo.class.php';
require_once CMS_INC_ROOT . DS . 'GeographicName.class.php';
require_once CMS_INC_ROOT . DS . 'article_type.class.php';
require_once CMS_INC_ROOT . DS . 'g_tag_map.php';

$campaign_ids = Campaign::getCampaignsByParam(array('like' => array('campaign_name' => 'AOL Travel%')), 'cc.campaign_id');
$campaign_ids = array(98);
$len = count($campaign_ids);
$xml_dir = $g_tag['xml_dir']['aol'];
// $debugh = fopen($xml_dir. 'test.txt', "w+");
//for ($i = 0 ; $i < $len; $i++)
//{
    $campaign_id = $campaign_ids;
    // 2007-10-10 00:00:00 
    // 2008-01-30 00:00:00 
    // all campaign: 2008-03-05 17:45:44 last time
    // 98: 2008-06-02 18:05:14 last time
    $now = date("Y-m-d H:i:s");
    // $now = '2008-01-30 00:00:00';
    $conditions = array(
        'campaign_id'   => $campaign_id, 
        'article_status' => 6,
        // 'publish_start'  => '2008-01-30 00:00:00',
        'publish_end'  => $now,
//        'or' => array(
//            'is_load' => 0,
//            'is_load' => 1,
//            'is_force_load' => 1,
//        )
     );
    file_put_contents(CRONJOB_INC_ROOT . "aol_time.txt", $now);
    $filetime = strtotime($conditions['publish_end']);
    // get articles from db
    $articles = ArticleExtraInfo::getArticlesByParam($conditions);
    $p['title_tag_add'] = ' - AOL Travel';
    if (is_array($articles) && !empty($articles)) {
        foreach ($articles as $country => $items) {
            foreach ($items as $state => $item) {
                foreach ($item as $city => $value) {
                    if (!empty($value)) {
                        $filename = empty($city) ? '' : $city .'_';
                        $filename .= empty($state) ? '' : $state . '_';
                        $filename .= empty($country) ? '' : $country;
                        $filename = str_replace(",", " ", $filename);
                        $filename = preg_replace("/[ ]+/", "-", strtolower($filename)) . '_' . date("mdY", $filetime) . ".xml";
                        $filename = $xml_dir. $filename;
                        // if (file_exists($filename)) continue;
                        // generate xml for each city
                        $xml = ArticleExtraInfo::generateXMLString($value, $campaign_id, $p);
                        $handle   = fopen($filename, "w+");
                        echo count($value) . "\t" . $filename . "\n";
                        if (fwrite($handle, $xml) === false) {
                            echo "can't write {$filename}";
                        }
                        fclose($handle);
                    }
                }
            }
        }
    }
//}
// fclose($debugh);
?>
