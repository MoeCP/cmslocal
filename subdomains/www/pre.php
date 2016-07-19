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
define('RESUME_UPLOAD_PATH', WEB_PATH . DS . 'resumes' . DS);//publish path 

ini_set("include_path", "." . PATH_SEPARATOR . COMMON_PATH . "PEAR" . PATH_SEPARATOR . COMMON_PATH . "aws"); 
error_reporting(E_ALL ^ E_NOTICE);

define('ADODB_INC_ROOT',  COMMON_PATH . 'adodb');
define('SMARTY_INC_ROOT', COMMON_PATH . 'Smarty');
define('JPGRAPH_INC_ROOT', COMMON_PATH . 'jpgraph');
define('MAILER_INC_ROOT', COMMON_PATH . 'phpmailer'); 

//define('TTF_DIR', '****/font/truetype/');


// Modify these line below to suite your system config, no trailing slash
define('CMS_INC_ROOT', BASE_PATH . 'include');
define('CMS_SMARTY_ROOT', BASE_PATH .'smarty'. DS); 

// DO NOT MODIFY ANY LINES BELOW !!!!

//xdebug_start_profiling(); // xdebug, NOT AVAILABLE IN PRODUCTION STAGE !!!!!! Use $benchmark_start instead
                          // related scripts/templates including: footer.html

mb_internal_encoding('UTF-8');

require_once CMS_INC_ROOT.'/benchmark.php';

require_once CMS_INC_ROOT.'/Search.class.php';


benchmark_start();

require_once ADODB_INC_ROOT.'/adodb.inc.php';

require_once CMS_INC_ROOT . '/config.php';
require_once CMS_INC_ROOT . '/Candidate.class.php';
require_once CMS_INC_ROOT . '/Inputfilter.class.php';
require_once CMS_INC_ROOT . '/article_type.class.php';
require_once CMS_INC_ROOT . '/g_param_www.php';

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

session_set_cookie_params(0);
session_start();
//session_start();

// misc
// ...
//some global var
$g_article_storage = BASE_PATH . 'storage/';       //this path should change for some security reason
$personal_dict_path = WEB_PATH . '/article/spell_checker/spell_checker/personal_dictionary/personal_dictionary.pws';


$ADODB_COUNTRECS = false; // set to false for better performance
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$conn = &ADONewConnection('mysqli');  # create a connection
$conn->debug = false;
if ($_GET['leo_debug'] == 'leo') {
    $conn->debug = true;
}

$conn->PConnect($hostname, $username, $password, $database);# connect to MySQL, agora db
require_once CMS_INC_ROOT . '/g_parameters.php';
// Smarty
require_once SMARTY_INC_ROOT.'/Smarty.class.php';

$smarty = new Smarty;

$smarty->template_dir = CMS_SMARTY_ROOT.'www/';
$smarty->compile_dir  = CMS_SMARTY_ROOT.'template_www_c/';
$smarty->config_dir   = CMS_SMARTY_ROOT.'config/';
$smarty->cache_dir    = CMS_SMARTY_ROOT.'cache/';
$smarty->caching      = false; // We don't use cache by default
$smarty->debugging    = false;
//$smarty->compile_check = false;

if ($conn->debug) {
    function adodb_smarty_outp(&$msg)
    {
        global $smarty;
        $smarty->assign("adodb_log", $smarty->get_template_vars('adodb_log').$msg);
    }
    define("ADODB_OUTP", "adodb_smarty_outp");
}

$q = "SET NAMES 'utf8'";
$conn->Execute($q);


$smarty->assign('js_check', true); // ALWAYS set to 'true' except that you need to debug PHP's validation functions


$smarty->assign("g_copyright", $g_copyright);


if ($g_theme == "") $g_theme = 'Default';

$smarty->assign('g_pager_perPage', $g_pager_perPage);//how many records will be show per page
$smarty->assign('sys_charset', 'utf-8');
$smarty->assign('is_include_left', false);//Control whether or not include left region.
$smarty->assign('is_include_right', false);//Control whether or not include right region
$smarty->assign('theme', $g_theme);
$smarty->assign('theme_image_path', '/themes/'.$g_theme.'/images/');

//$handle = fopen(WEB_PATH . "/test_log.txt", 'a+');
?>
