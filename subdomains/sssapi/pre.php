<?php
/* $Id: pre.php,v 1.0 2006-04-26 15:35:24 Leo Exp $ */

/*  GLOBAL VARS

    $benchmark_start -- micro time when script starts

    $conn     -- ADODB connection
    $feedback -- various functions' feedback

    $smarty   -- Smarty object
*/
//common folder and storage folder should lay other place where remote host cann't access
ini_set("session.gc_maxlifetime", 86400);
ini_set("display_errors", "0");
error_reporting(E_ALL ^ E_NOTICE);
define('WEB_PATH', dirname(__FILE__));//publish path 

define('DS', DIRECTORY_SEPARATOR);
$wp_arr = explode(DS, WEB_PATH);
array_pop($wp_arr);
define('BASE_PATH', implode(DS, $wp_arr) . DS);
define('COMMON_PATH', BASE_PATH . 'common' . DS);

ini_set("include_path", "." . PATH_SEPARATOR . COMMON_PATH . "PEAR"); 
error_reporting(E_ALL ^ E_NOTICE);

define('ADODB_INC_ROOT',  COMMON_PATH . 'adodb');
define('JPGRAPH_INC_ROOT', COMMON_PATH . 'jpgraph');
define('MAILER_INC_ROOT', COMMON_PATH . 'phpmailer'); 

//define('TTF_DIR', '****/font/truetype/');


// Modify these line below to suite your system config, no trailing slash
define('CMS_INC_ROOT', BASE_PATH . 'include');

// DO NOT MODIFY ANY LINES BELOW !!!!

//xdebug_start_profiling(); // xdebug, NOT AVAILABLE IN PRODUCTION STAGE !!!!!! Use $benchmark_start instead
                          // related scripts/templates including: footer.html

mb_internal_encoding('UTF-8');
require_once CMS_INC_ROOT.'/Search.class.php';

require_once ADODB_INC_ROOT.'/adodb.inc.php';

require_once CMS_INC_ROOT . '/config.php';
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Logger.class.php';
require_once CMS_INC_ROOT . '/Inputfilter.class.php';
require_once CMS_INC_ROOT . '/article_type.class.php';
require_once CMS_INC_ROOT . '/utils.php';

$g_copyright = '&copy; 2006-'.date('Y').' Copy Writer Management System';

//require_once CMS_CONFIG_ROOT.'/pro.php';

$g_pager_params = array(
    'urlVar' => 'page',
    'mode' => 'Sliding',
    'separator' => '',
    'spacesBeforeSeparator' => 0,
    'spacesAfterSeparator' => 1,
    'prevImg' => 'Pre Page',
    'nextImg' => 'Next Page',
    'altPrev' => 'Pre Page',
    'altNext' => 'Next Page',
    'delta' => 5);

$g_pager_perPage['25'] = '25';
$g_pager_perPage['50'] = '50';
$g_pager_perPage['75'] = '75';
$g_pager_perPage['100'] = '100';
$g_pager_perPage['200'] = '200';

// misc
// ...
//some global var
$g_article_storage = BASE_PATH . 'storage/';       //this path should change for some security reason


$ADODB_COUNTRECS = false; // set to false for better performance
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$conn = &ADONewConnection('mysqli');  # create a connection

$conn->debug = false;
if ($_GET['leo_debug'] == 'leo') {
    $conn->debug = true;
}
$conn->PConnect($hostname, $username, $password, $database);# connect to MySQL, agora db

$q = "SET NAMES 'utf8'";
$conn->Execute($q);
if ($conn->debug) {
    function adodb_smarty_outp(&$msg)
    {
        echo $msg . "<br />";
    }
    define("ADODB_OUTP", "adodb_smarty_outp");
}

require_once CMS_INC_ROOT . '/g_parameters.php';
?>