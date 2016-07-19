<?php
require_once 'pre_cron.php';
require_once COMMON_PATH.'/mycsvparser.php';
$import_file = WEB_PATH . DS . 'octobernetwork-updated.csv' ;
$oMyCSV = new MyCSVParser(array('file' => $import_file, 'quote' => '"', 'import_separator' => ','));
ini_set("memory_limit", "512M");
$headers['fields'] = $oMyCSV->getFirstLine();
$import_data = $oMyCSV->getAllData();
// pr($import_data);
//pr(array_keys($import_data));
//pr($import_data['Keyword'][0]);
//pr($import_data['Mapping-Id'][0]);
//pr($import_data['optional field 1'][0]);
//pr($import_data['optional field 2'][0]);
//pr($import_data['optional field 3'][0]);
//$conn->debug = true;
$p = array(
    'keyword' => $import_data['Keyword'],
    'mapping_id' => $import_data['Mapping-Id'],
    'optional1' => $import_data['optional field 1'],
    'optional2' => $import_data['optional field 2'],
    'optional3' => $import_data['optional field 3'],
    'optional4' => $import_data['optional field 4'],
    'optional5' => $import_data['optional field 5'],
    'optional6' => $import_data['optional field 6'],
    'optional7' => $import_data['optional field 7'],
    'campaign_id' => 587,
    'article_type' => 0,
    'keyword_description' => 'same as the campaign content instructions',
    'date_start' => '2010-10-04',
    'date_end' => '2010-10-11',
    'editor_id' => '113',
    'creation_user_id' => '1',
    'creation_role' => 'admin',
);
Campaign::addKeywordByCronjob($p);
//pr($feedback);
exit();
?>