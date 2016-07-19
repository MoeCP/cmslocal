<?php

/**
 * Example showing how to connect PHP to QuickBooks Online Edition
 * 
 * * IMPORTANT * 
 * Before using this file, you must go through the Intuit application 
 * registration process. This is documented here: 
 * 	http://wiki.consolibyte.com/wiki/doku.php/quickbooks_online_edition
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */

// 
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/Users/kpalmer/Projects/QuickBooks/');
error_reporting(E_ALL | E_STRICT);

/**
 * Require the QuickBooks base classes
 */
require_once 'QuickBooks.php';

// Tell the framework what username to use to keep track of your requests
//	(You can just make these up at the moment, they don't do anything...)
$username = 'CopyPress';
$password = 'password';

// Tell the QuickBooks_API class you'll be connecting to QuickBooks Online Edition
$source_type = QUICKBOOKS_API_SOURCE_ONLINE_EDITION;

// If you want to log requests/responses to a database, you can provide a DSN-
//	style connection string to the database here. 
$api_driver_dsn = null;
// $api_driver_dsn = 'mysql://root:@localhost/quickbooks_api';
// $api_driver_dsn = 'pgsql://pgsql@localhost/quickbooks_onlineedition';

// This is not applicable to QBOE
$source_dsn = null;

// Various API options
$api_options = array();

// Options for QBOE
$source_options = array(
	// There are two models of communication for QuickBooks Online Edition. One 
	//	is the 'Hosted' model, the other is the 'Desktop' model. You can use 
	//	either if you're developing a web application. 
	// 
	// If you're using the 'Desktop' model of communication with QuickBooks OE, 
	//	then you can safely ignore the 'certificate' parameter. The 'Desktop' 
	//	model of communication with QBOE is easier to set up, at the expense of 
	//	being a little less secure. 
	// 
	// If you're using the 'Hosted' model of communication with QuickBooks OE, 
	//	then you'll be generating a private key and a CSR, and Intuit will sign 
	//	sign your CSR and send it back to you. You must provide a full path to 
	//	the concatenation of the private key file and the signed CSR. So, the 
	//	file should look something like:
	// 
	//	-----BEGIN RSA PRIVATE KEY-----
	//	... bla bla bla lots of stuff here ...
	//	-----END RSA PRIVATE KEY-----
	//	-----BEGIN CERTIFICATE-----
	//	... bla bla bla lots of stuff here ...
	//	-----END CERTIFICATE-----
	//	
	//	You'll then save that someplace safe with a .pem extension, and point 
	//	this file path to that file. 
	//'certificate' => '/Users/kpalmer/Projects/QuickBooks/QuickBooks/dev/test_qboe.pem', 
	
	// These next 3 configuration options are *required* 
	//	You should have been supplied with all 3 of these values when you went 
	//	through the Application Registration process on the Intuit Developer 
	//	website. 
	//	
	//	connection_ticket - QuickBooks Online Edition does an HTTP POST to your callback URL to send you this
	//	application_login - Provided by the application registration page
	//	application_id - Provided by the application registration page 	
	'connection_ticket' => 'TGT-97-WjsdAh_UW4fuA$XKCTR1UQ', 
	'application_login' => 'cp-test.cpo.com', 
	'application_id' => '208259033', 
	
	// This is just for debugging/testing, and you should comment this out... 
	//'override_session_ticket' => 'V1-184-KUvW2h21VA7N3MNOgLXotw:134864687', 	// Comment this line out unless you know what you're doing!
	);

// Driver options
$driver_options = array();

// If you want to log requests/responses to a database, initialize the database
if ($api_driver_dsn and !QuickBooks_Utilities::initialized($api_driver_dsn))
{
	QuickBooks_Utilities::initialize($api_driver_dsn);
	QuickBooks_Utilities::createUser($api_driver_dsn, $username, $password);
}

// Create the API instance
$API = new QuickBooks_API($api_driver_dsn, $username, $source_type, $source_dsn, $api_options, $source_options, $driver_options);

// Turn on debugging mode
//$API->useDebugMode(true);

// With QuickBooks Online Edition, the API can return values to you rather than 
//	using callback functions to return values. Remember that is you use this, 
//	your code will be less portable to systems using non-real-time connections
//	(i.e. the QuickBooks Web Connector). 

//$API->enableRealtime(true);
//$return = $API->qbxml('<CustomerQueryRq></CustomerQueryRq>', '_get_customer_callback');
// if ($API->usingRealtime())
// {
	// print('Our real-time response from QuickBooks Online Edition was: ');
	// var_dump($return);
	// print_r($return);
// }

// Let's get some general information about this connection to QBOE: 
print('Our connection ticket is: ' . $API->connectionTicket() . "\n");
print('Our session ticket is: ' . $API->sessionTicket() . "\n");
print('Our application id is: ' . $API->applicationID() . "\n");
print('Our application login is: ' . $API->applicationLogin() . "\n");
print("\n");

print('Last error number: ' . $API->errorNumber() . "\n");
print('Last error message: ' . $API->errorMessage() . "\n");
print("\n");

// The "raw" approach to accessing QuickBooks Online Edition is to build and 
//	parse the qbXML requests/responses send to/from QuickBooks yourself. Here 
//	is an example of querying for a customer by building a raw qbXML request. 
//	The qbXML response is passed back to you in the _raw_qbxml_callback() 
//	function as the $qbxml parameter. 

// $return = $API->qbxml('<VendorQueryRq></VendorQueryRq>', '_raw_qbxml_callback');
// // This function gets called when QuickBooks Online Edition sends a response back
// function _raw_qbxml_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
// {
	// print('We got back this qbXML from QuickBooks Online Edition: ' . $qbxml);
// }


///////////////////////////////////////API CALLS STARTS HERE////////////////////////////////

// // Used to get the entire Customer List
// $API->qbxml('<CustomerQueryRq></CustomerQueryRq>', '_get_customer_callback');
// function _get_customer_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
// {
	// print('This is a list of all the customer' . "\n");
	// print_r($qbxml);
// }


// // Used to get the entire Vendor List
// $API->qbxml('<VendorQueryRq></VendorQueryRq>', '_get_vendor_callback');
// function _get_vendor_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
// {
	// print('This is a list of all the vendors' . "\n");
	// print_r($qbxml);
// }

// This API call can be used to get 1 specific Vendor based on its full name
// $name = 'Andrew Pineda';
// $API->getVendorByName($name,'_get_vendorByName_callback');
// function _get_vendorByName_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres) 
// {
	// print('This is the Vendor return' . "\n");
	// var_dump($Iterator);
	
	// // // you can extract the data using the $Iterator object provided
	// print_r($Iterator->getName());
	// print_r($Iterator->getListID());
	
	// // or you can manually parse the $qbxml and get the data that way
	// // The only reason to manually parse this way is because some data (like balance)
	// // cannot be retrieved by $Iterator object
	// $xml = simplexml_load_string($qbxml);
	// print_r ($xml->QBXMLMsgsRs->VendorQueryRs->VendorRet->Name);
	// print_r ($xml->QBXMLMsgsRs->VendorQueryRs->VendorRet->ListID);	
// }


// Used to get the entire Account List
$API->qbxml('<AccountQueryRq></AccountQueryRq>', '_get_account_callback');
function _get_account_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
{
	print('This is a list of all the account' . "\n");
	print_r($qbxml);
}


// // Used to get the entire Bill List
// $API->qbxml('<BillQueryRq></BillQueryRq>', '_get_bill_callback');
// function _get_bill_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
// {
	// print('This is a list of all the bill' . "\n");
	// print_r($qbxml);
// }

// This addBill is hardcoded with values right now
// function payrollBill($API)
// {	
	// $qbxml = '<BillAddRq><BillAdd>
	// <VendorRef> 
		// <ListID >31</ListID> 
		// <FullName >Andrew Pineda</FullName> 
	// </VendorRef>
	// <TxnDate>2011-06-01</TxnDate>
	// <DueDate>2011-06-15</DueDate>
	// <RefNumber >6-201105-2</RefNumber>
	// <TermsRef> 
		// <ListID >2</ListID> 
		// <FullName >Net 15</FullName> 
	// </TermsRef>
	// <Memo >May 2011 - 2</Memo>
	// <ExpenseLineAdd>
		// <AccountRef> 
			// <ListID >69</ListID> 
			// <FullName >Contractor Payroll</FullName> 
		// </AccountRef>
		// <Amount >2.00</Amount> 
	// </ExpenseLineAdd>
	// </BillAdd></BillAddRq>';

	// $API->qbxml($qbxml, '_get_bill_callback');
// }

// function _get_bill_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
// {
	// print('This is the bill details that was just added' . "\n");
	// print_r($qbxml);
// }

// This addBill is hardcoded with values right now
function payrollCheck($API)
{	
	$qbxml = '<CheckAddRq><CheckAdd>

<AccountRef> 
	<FullName>Checking</FullName>
</AccountRef>

<PayeeEntityRef>
	<ListID>31</ListID> //vendorID - please use this ID to test
</PayeeEntityRef>


<TxnDate >2010-07-01</TxnDate> 
<Memo>June 2011 - 1</Memo>

<ExpenseLineAdd>
	<AccountRef>
		<ListID>69</ListID>
		<FullName>Contractor Payroll</FullName>
	</AccountRef>
	<Amount>1.00</Amount> //invoice amount
</ExpenseLineAdd>

</CheckAdd></CheckAddRq>';

	$API->qbxml($qbxml, '_get_check_callback');
}

function _get_check_callback($method, $action, $ID, &$err, $qbxml, $Iterator, $qbres)
{
	print('This is the check details that was just added' . "\n");
	print_r($qbxml);
}


payrollCheck($API);

?>