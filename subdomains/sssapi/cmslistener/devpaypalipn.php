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
ini_set("display_errors", "1");
error_reporting(E_ALL ^ E_NOTICE);
define('WEB_PATH', dirname(dirname(__FILE__)));//publish path 

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
require_once ADODB_INC_ROOT.'/adodb.inc.php';

require_once CMS_INC_ROOT . '/config.php';
require_once CMS_INC_ROOT . '/User.class.php';
require_once CMS_INC_ROOT . '/Logger.class.php';
require_once CMS_INC_ROOT . '/Inputfilter.class.php';
require_once CMS_INC_ROOT . '/article_type.class.php';
require_once CMS_INC_ROOT . '/utils.php';
require_once CMS_INC_ROOT . '/g_parameters.php';

$g_copyright = '&copy; 2006-'.date('Y').' Copy Writer Management System';

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
$hostname = 'localhost';
$database = 'cmsdev_db';
$username = 'cmsdev_dbuser';
$password = 'a3JkJLlB';
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
?>
<?php
require_once CMS_INC_ROOT . '/OrderCampaignPayment.class.php';
require_once CMS_INC_ROOT . '/OrderCampaign.class.php';
require_once CMS_INC_ROOT . '/Email.class.php';

$environment = 'sandbox';	// or 'beta-sandbox' or 'live'
$g_pay_pal_email = 'techni_1297287905_biz@blueglass.com';
/*$conn->debug = true;
$_POST = array (
  'mc_gross' => '0.01',
  'protection_eligibility' => 'Ineligible',
  'payer_id' => 'XBDARQEN3URXC',
  'tax' => '0.00',
  'payment_date' => '19:11:23 Feb 23, 2011 PST',
  'payment_status' => 'Completed',
  'charset' => 'windows-1252',
  'first_name' => 'Test',
  'mc_fee' => '0.01',
  'notify_version' => '3.0',
  'custom' => '',
  'payer_status' => 'verified',
  'business' => 'techni_1297287905_biz@blueglass.com',
  'quantity' => '1',
  'verify_sign' => 'AQyQpLEBDM0SUd6tQ6o630Bj2J.CANsuJAbSvw7ANbZBoN2JVyQcENHd',
  'payer_email' => 'techni_1297288135_per@blueglass.com',
  'txn_id' => '83241555EU587870W',
  'payment_type' => 'instant',
  'last_name' => 'User',
  'receiver_email' => 'techni_1297287905_biz@blueglass.com',
  'payment_fee' => '0.01',
  'receiver_id' => '83MAR8NLZHPXL',
  'txn_type' => 'web_accept',
  'item_name' => 'CopyPress Articles',
  'mc_currency' => 'USD',
  'item_number' => '100',
  'residence_country' => 'US',
  'test_ipn' => '1',
  'handling_amount' => '0.00',
  'transaction_subject' => 'CopyPress Articles',
  'payment_gross' => '0.01',
  'shipping' => '0.00',
  'cmd'=> '_notify-validate',
);*/
file_put_contents(getcwd().'/debug_paypal.txt', "\n\n" . date("Y-m-d-H-i-s") . "\n".var_export($_POST, true),FILE_APPEND);
if (!empty($_POST)) {
    // read the post from PayPal system and add 'cmd'
    
    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $order_id = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $payment_date = $_POST['payment_date'];
    $payer_id = $_POST['payer_id'];
    $payment_status = strtolower($_POST['payment_status']);
    if ($payment_status == 'completed') {
        $info = OrderCampaignPayment::getInfoByOrderId($order_id);
        if (!empty($info) && $txn_id != $info['trans_num']) {
            $payment_id = $info['payment_id'];
            if ($payment_amount != $info['total']) {
                $order_info = OrderCampaign::getInfo($order_id);
                $info = array_merge($info, $order_info);
                $str  = 'Client:' . $info['client_name'] ."\n";
                $str .= 'Campaign Name:' . $info['campaign_name'] ."\n";
                $str .= 'Campaign OrderID:' . $info['order_campaign_id'] ."\n";
                $str .= 'Amount:' . $info['total'] ."\n";
                $str .= 'PayPal Amount:' . $payment_amount ."\n";
                $str .= 'Receiver email:' . $receiver_email ."\n";
                Email::sendAnnouceMail(36, $g_to_email, array('datastring' => $str));
            } else {
                $p = $_POST;
                if ($g_pay_pal_email != $receiver_email && !empty($g_pay_pal_email)) {
                    $p['receiver_email'] = $g_pay_pal_email;
                }
                $p['cmd'] = '_notify-validate';
                $prefix_url = '';
                if ($environment == 'sandbox') {
                    $prefix_url = '.' . $environment . '.';
                }
                $url = 'https://www' . $prefix_url . 'paypal.com/cgi-bin/webscr';
                require_once "HTTP/Client.php";
                $client = new HTTP_Client();
                $client->post($url, $p);
                $result = $client->currentResponse();
                $res = trim($result['body']);
                if (strcmp ($res, "VERIFIED") == 0) {
                    $data = array(
                        // 'token' => $token,
                        'payer_id' => $payer_id,
                        'status' => 10,
                        'trans_date' => date("Y-m-d", strtotime($payment_date)),
                        'trans_num' => $txn_id,
                        'account' => 'PayPal',
                        'payment_id' => $payment_id,
                        'detail_data' => serialize($p)
                    );
                    OrderCampaignPayment::store($data);
                    $data = array(
                        'status' => 10,
                        'order_campaign_id' => $order_id,
                    );
                    OrderCampaign::store($data);
                    echo 'SUCCESS';
                    exit();
                } else if (strcmp ($res, "INVALID") == 0) {
                  // log for manual investigation
                }
            }
        }
    }
    echo 'FAILED';
} else {
    echo 'INVALID';
}

?>
