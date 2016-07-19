<?php
ini_set("display_errors", "1");
error_reporting(E_ALL ^ E_NOTICE);
define('WEB_PATH', dirname(__FILE__));//publish path 

define('DS', DIRECTORY_SEPARATOR);
$wp_arr = explode(DS, WEB_PATH);
array_pop($wp_arr);
define('BASE_PATH', implode(DS, $wp_arr) . DS);
define('COMMON_PATH', BASE_PATH . 'common' . DS);

ini_set("include_path", ini_get('include_path') . PATH_SEPARATOR . COMMON_PATH . "PEAR". PATH_SEPARATOR . COMMON_PATH . "aws"); 
error_reporting(E_ALL ^ E_NOTICE);

define('ADODB_INC_ROOT',  COMMON_PATH . 'adodb');
define('SMARTY_INC_ROOT', COMMON_PATH . 'Smarty');
define('JPGRAPH_INC_ROOT', COMMON_PATH . 'jpgraph');
define('MAILER_INC_ROOT', COMMON_PATH . 'phpmailer'); 

// Modify these line below to suite your system config, no trailing slash
define('CMS_INC_ROOT', BASE_PATH . 'include');
define('CMS_SMARTY_ROOT', BASE_PATH .'smarty'. DS); 
define('CRONJOB_INC_ROOT', WEB_PATH . DS); 

$baseUrl = 'http://content.copypress.com';

require_once ADODB_INC_ROOT . DS . 'adodb.inc.php';
require_once CMS_INC_ROOT . DS . 'config.php';
require_once CMS_INC_ROOT . DS . 'Article.class.php';
require_once CMS_INC_ROOT . DS . 'Campaign.class.php';
require_once CMS_INC_ROOT . DS . 'User.class.php';
require_once CMS_INC_ROOT . DS . 'utils.php';
require_once CMS_INC_ROOT.'/custom_field.class.php';

$ADODB_COUNTRECS = false; // set to false for better performance
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$conn = &ADONewConnection('mysqli');  # create a connection
$conn->PConnect($hostname, $username, $password, $database);# connect to MySQL, agora db
$conn->debug = false;
$sql = "SET NAMES 'utf8'";
$conn->Execute($sql);
$domain = "http://content.copypress.com";

require_once CMS_INC_ROOT . DS . 'article_type.class.php';
require_once CMS_INC_ROOT . DS . 'g_parameters.php';
require_once CMS_INC_ROOT . DS . 'g_tag_map.php';
require_once CMS_INC_ROOT . DS . 'cronjob_utils.php';
?>
