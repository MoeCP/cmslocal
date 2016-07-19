<?php
require_once '../pre.php';
require_once CMS_INC_ROOT . '/SasListener.class.php';
//$conn->debug =true;
$p = $_REQUEST;
//$transdate = date("Y-m-d H:i:s", strtotime($p['transdate']));
foreach ($p as $k => $v) {
    $p[$k] = trim($v);
}
$data = array(
    'created' => date("Y-m-d H:i:s"),
    'tracking' => $p['tracking'],
    'amount' => $p['amount'],
    'trans_date' => $p['transdate'],
    'trans_id' => $p['transID'],
    'commission' => $p['commission'],
    'user_id' => $p['userID'],
);
if (empty($p['userID'])) unset($data['user_id']);
SasListener::add($data);
?>