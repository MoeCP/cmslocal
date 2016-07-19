<?php
extract($_REQUEST, EXTR_OVERWRITE);
//error_reporting(E_ALL);
//echo file_get_contents('http://www.google.com');

/*
 ######notice, when you build a real subdomain linkme.infinitenine.com, then please remove the line:
 208.74.170.98    linkme.infinitenine.com where in the file /etc/hosts
*/

$prtcl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$refer = $prtcl . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
$opts = array(
  'http'=>array(
    'header'=>"Referer: $refer\r\n"
  )
);

$context = stream_context_create($opts);

/*
echo $q;
echo $a;
echo $linkid;
echo $date;
echo $systemid;
*/

$api = "http://linkme.infinitenine.com/externalapis/";
//echo "file_get_contents: ";
if ($a == 1) {
    $linkmeapi = "{$api}qualitycheck/{$q}/{$linkid}/{$date}/{$systemid}";
    echo file_get_contents($linkmeapi, false, $context);
} elseif ($a == 2) {
    $url = rawurlencode($url);
    $targerurl = rawurlencode($targerurl);
    $linkmeapi = "{$api}addlinktoinventory/{$q}/{$linkid}/{$date}/{$systemid}/{$url}/{$targerurl}";
    $rs = file_get_contents($linkmeapi, false, $context);
    echo $rs;
} else {
    //do nothing
}
?>
