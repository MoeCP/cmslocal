<?php
require_once 'pre.php';
//ini_set("display_errors", "1");
function isValidURL1($url)
{
    //$rs = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-_]+)*(:[0-9]+)?(/.*)?$|ims', $url, $arr);
    //return $rs;
    //echo filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false ? false : true;
}
echo isValidURL('http://www.ukbathrooms.com/shop/shower_enclosures_doors/hinged_pivot_shower_doors/products/merlyn_vivid_8_hinged_door___inline_shower_panel.html') . ' ' . isValidURL1('http://www.ukbathrooms.com/shop/bathrooms/baths/freestanding_traditional_baths/products/royce_morgan_hampton_double_ended_bath_.html') . ' 2' . isValidURL1('www.ukbathrooms.com');
?>