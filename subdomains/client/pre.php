<?php

/* $Id: pre.php,v 1.0 2006-04-26 15:35:24 Leo Exp $ */

/*  GLOBAL VARS

    $benchmark_start -- micro time when script starts

    $conn     -- ADODB connection
    $feedback -- various functions' feedback

    $smarty   -- Smarty object
*/

//common folder and storage folder should lay other place where remote host cann't access
ini_set("display_errors", "0");
error_reporting(E_ALL ^ E_NOTICE);
define('WEB_PATH', dirname(__FILE__));//publish path 

define('DS', DIRECTORY_SEPARATOR);
$wp_arr = explode(DS, WEB_PATH);
array_pop($wp_arr);
define('BASE_PATH', implode(DS, $wp_arr) . DS);
define('COMMON_PATH', BASE_PATH . 'common' . DS);

ini_set("include_path", "." . PATH_SEPARATOR . COMMON_PATH . "PEAR" . PATH_SEPARATOR . COMMON_PATH . "aws" .PATH_SEPARATOR . COMMON_PATH . "PHPExcel"); 
error_reporting(E_ALL ^ E_NOTICE);

define('ADODB_INC_ROOT',  COMMON_PATH . 'adodb');
define('SMARTY_INC_ROOT', COMMON_PATH . 'Smarty');
define('JPGRAPH_INC_ROOT', COMMON_PATH . 'jpgraph');
define('MAILER_INC_ROOT', COMMON_PATH . 'phpmailer'); 

//define('TTF_DIR', '****/font/truetype/');


// Modify these line below to suite your system config, no trailing slash
define('CMS_INC_ROOT', BASE_PATH . 'include');
define('CMS_SMARTY_ROOT', BASE_PATH .'smarty'. DS); 
$g_article_storage = BASE_PATH . 'storage/';
define('CAMPAIGN_KEYWORD_FILE_PATH', $g_article_storage . 'client_campaign_keywords' . DS);

// DO NOT MODIFY ANY LINES BELOW !!!!

//xdebug_start_profiling(); // xdebug, NOT AVAILABLE IN PRODUCTION STAGE !!!!!! Use $benchmark_start instead
                          // related scripts/templates including: footer.html

mb_internal_encoding('UTF-8');

require_once CMS_INC_ROOT.'/benchmark.php';

require_once CMS_INC_ROOT.'/Search.class.php';


benchmark_start();

require_once ADODB_INC_ROOT.'/adodb.inc.php';

require_once CMS_INC_ROOT . '/config.php';
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Logger.class.php';
require_once CMS_INC_ROOT . '/Inputfilter.class.php';
require_once CMS_INC_ROOT . '/article_type.class.php';
require_once CMS_INC_ROOT . '/utils.php';
require_once CMS_INC_ROOT.'/custom_field.class.php';

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

$g_pager_perPage['50'] = '50';
$g_pager_perPage['25'] = '25';
$g_pager_perPage['100'] = '100';
$g_pager_perPage['200'] = '200';

session_set_cookie_params(0);
session_start();

// misc
// ...
//some global var
$g_article_storage = BASE_PATH .'storage/';       //this path should change for some security reason
$personal_dict_path = WEB_PATH .'/article/spell_checker/spell_checker/personal_dictionary/personal_dictionary.pws';


$ADODB_COUNTRECS = false; // set to false for better performance
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$conn = &ADONewConnection('mysqli');  # create a connection
$conn->debug = false;
if ($_GET['leo_debug'] == 'leo') {
    $conn->debug = true;
}

// Smarty
require_once SMARTY_INC_ROOT.'/Smarty.class.php';

$smarty = new Smarty;

$smarty->template_dir = CMS_SMARTY_ROOT.'/template/';
$smarty->compile_dir  = CMS_SMARTY_ROOT.'/template_client_c/';
$smarty->config_dir   = CMS_SMARTY_ROOT.'/config/';
$smarty->cache_dir    = CMS_SMARTY_ROOT.'/cache/';
$smarty->caching      = false; // We don't use cache by default
$smarty->debugging    = false;

if ($conn->debug) {
    function adodb_smarty_outp(&$msg)
    {
        global $smarty;
        $smarty->assign("adodb_log", $smarty->get_template_vars('adodb_log').$msg);
    }
    define("ADODB_OUTP", "adodb_smarty_outp");
}


$conn->PConnect($hostname, $username, $password, $database);# connect to MySQL, agora db

$q = "SET NAMES 'utf8'";
$conn->Execute($q);

//session_start();

//$smarty->compile_check = false;

$smarty->assign('js_check', true); // ALWAYS set to 'true' except that you need to debug PHP's validation functions

require_once CMS_INC_ROOT.'/g_tag_map.php';
require_once CMS_INC_ROOT.'/g_parameters.php';
require_once CMS_INC_ROOT . '/g_param_www.php';
// User related
if (user_is_loggedin()) {
    // Use this smarty var instead of $smarty.session.user_id for this is capable for chkSessionIP
    //$smarty->assign('loggedin_user_id', User::getID());
	// User related
	//require_once 'cms_menu.php';
	//Display the url if he have the permission
}

if (user_is_loggedin()) {
    $smarty->assign("loggedin_user_name", User::getName());
    $smarty->assign("user_role", User::getRole());
    $smarty->assign("last_login_time", User::getLastLoginTime());
} else {
    require_once CMS_INC_ROOT.'/Client.class.php';
    if (client_is_loggedin()) {
        $smarty->assign("loggedin_user_name", Client::getName());
        $smarty->assign("user_role", 'client');
        $smarty->assign("last_login_time", Client::getLastLoginTime());
    }
//do nothing
}

$smarty->assign("g_copyright", $g_copyright);


$g_theme = $_SESSION['g_theme'];
if ($g_theme == "") $g_theme = 'Default';

$smarty->assign('g_pager_perPage', $g_pager_perPage);//how many records will be show per page

$smarty->assign('sys_charset', 'utf-8');
$smarty->assign('is_include_left', false);//Control whether or not include left region.
$smarty->assign('is_include_right', false);//Control whether or not include right region
$smarty->assign('page_title', 'Copy Writer Management Version 1.01');
$smarty->assign('theme', $g_theme);
$smarty->assign('theme_image_path', '/themes/'.$g_theme.'/images/');
$smarty->assign('atd_key', $g_atd_key);

?>
