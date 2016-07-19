<?php
error_reporting(E_WARNING|E_ERROR);
class SProxyCurlPlugin
{
var $error = null;
var $oSrvVars = null;
var $rCURL = null;
function SProxyCurlPlugin($oSrvVars){
$this->oSrvVars = $oSrvVars;
}
function isCURLExists(){
return extension_loaded('curl');
}
function makeRequest($sParamsString){
$sMethod = (empty($sParamsString))?('GET'):('POST');
$rCurl = curl_init();
curl_setopt($rCurl, CURLOPT_URL, $this->oSrvVars->getURI('full'));
curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($rCurl, CURLOPT_HEADER, 1);
curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($rCurl, CURLOPT_TIMEOUT, 30);
curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($rCurl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
$proxy_server = $this->oSrvVars->get('proxy_server');
$proxy_port = $this->oSrvVars->get('proxy_port');
$proxy_user_pass = $this->oSrvVars->get('proxy_user_pass');
if(!empty($proxy_server))
{
curl_setopt($rCurl, CURLOPT_PROXY, $proxy_server);
}
if(!empty($proxy_port))
{
curl_setopt($rCurl, CURLOPT_PROXYPORT, $proxy_port);
}
if(!empty($proxy_user_pass))
{
curl_setopt($rCurl, CURLOPT_PROXYUSERPWD, $proxy_user_pass);
}
$aHeadersList = $this->_getHTTPHeaders($sMethod);
curl_setopt($rCurl, CURLOPT_HTTPHEADER, $aHeadersList);
if($sMethod === 'POST'){
curl_setopt($rCurl, CURLOPT_POST, 1);
curl_setopt($rCurl, CURLOPT_POSTFIELDS, $sParamsString);
}
$sResponseText = curl_exec($rCurl);
if($sResponseText === false)
{
$this->error = curl_error($rCurl);
}
curl_close($rCurl);
if(!empty($this->error)){
return false;
}
return $sResponseText;
}
function getError(){
return $this->error;
}
function _getHTTPHeaders($sMethod){
$aRequestHeader = array();
$aRequestHeader[] = $sMethod . " " . $this->oSrvVars->getURI('full') . " HTTP/1.1";
$aRequestHeader[] = "Host: " . $this->oSrvVars->getURI('host');
$aRequestHeader[] = "Content-Type: application/x-www-form-urlencoded";
$aRequestHeader[] = "Accept: */*";
$aRequestHeader[] = "User-Agent: " .$_SERVER['HTTP_USER_AGENT'];
$aRequestHeader[] = "Pragma: no-cache";
$sCookies = '';
foreach ($_COOKIE as $sKey => $sValue){
$sCookies .= $sKey . '=' . $sValue . '; ';
}
$aRequestHeader[] = "Cookie: " . $sCookies;
$aRequestHeader[] = "Connection: close";
return $aRequestHeader;
}
}
?>
