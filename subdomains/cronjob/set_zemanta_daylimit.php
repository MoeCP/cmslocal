<?php
require_once 'pre_cron.php';
$conn->debug = true;
$now = time();
$sql = "UPDATE `zemanta_apis` SET unused_per_day=15000,last_used={$now}";
$conn->Execute($sql);
?>