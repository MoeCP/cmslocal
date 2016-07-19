<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . DS . 'UserEsign.class.php';
require_once CMS_INC_ROOT . DS . 'UserEsignConfig.class.php';
require_once CMS_INC_ROOT . DS . 'esignlib.php';
require_once CMS_INC_ROOT . DS . 'UserEsignLog.class.php';

$config = UserEsignConfig::getDefaultConfig();
$oEsignD = new ESignLib($config);
$all_config = UserEsignConfig::getAll();

$esigns = UserEsign::search(array('el' => array('estatus' => 6)), false);
foreach ($esigns as $row) {
    $doc_key = $row['doc_key'];
    $config_id = $row['config_id'];
    if ($config_id == $config['config_id'] || $config_id == 0) {
        $oEsign = $oEsignD;
    } else {
        $oEsign = new ESignLib($all_config[$config_id]);
    }
    $row['max_created'] = UserESignLog::maxCreatedByParam($row);
    $result = $oEsign->getInfo($doc_key, $row);
    if (isset($result['logs'])) {
        UserESignLog::storeBatch($result['logs']);
    }
    if (isset($result['esign'])) {
        $esign = $result['esign'];
        if ($esign['estatus'] == 7) {
            $title = $row['title'];
            $filename = preg_replace( '#\s+#', '-', trim($title)) . '-' . date("Y-m-d"). '-' . $row['esign_id'] . '.pdf';
            $title = strtolower($title);
            $w9_status = $row['w9_status'];
            $vendor_id = $row['vendor_id'];
            if ($vendor_id == 0 && $w9_status == 0 && substr($title,0,3) == 'w-9') {
                $tmp = array('w9_status' => 1, 'user_id' => $row['user_id']);
                User::setByID($tmp);
            }
            $oEsign->getLatestDoc($doc_key, $row['user_id'], $filename);
            $data = $oEsign->getFormData($doc_key);
            if (!empty($data)) $esign['fields'] = $data;
        }
        $esign['filename'] = $filename;
        UserESign::store($esign);
    }
    // pr($result);
}
?>
