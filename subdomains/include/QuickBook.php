<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . COMMON_PATH . 'QB');
global $g_pay_user;
require_once 'QuickBooks.php';
$g_pay_user = array(
//    'username' => "!copypress",
//    'password' => "Copypre$$2011",
    'username' => "apinedacp",
    'password' => "19amp82",
    'api_driver_dsn' => null,
    'source_type' => QUICKBOOKS_API_SOURCE_ONLINE_EDITION,
    'source_options' => array(
        'connection_ticket' => 'TGT-97-WjsdAh_UW4fuA$XKCTR1UQ', 
        'application_login' => 'cp-test.cpo.com', 
        'application_id' => '208259033', 
    ),
    'source_dsn' => null, 
    'api_options' => array(),
    'driver_options' => array()
);

class QuickBook {
    private $config = null;
    private $API = null;
    private $fields = null;

    function __construct($config)
    {
        $this->QuickBook($config);
    }

    function QuickBook($config)
    {
        $this->config = $config;
    }

    function getApi()
    {
        return $this->API;
    }

    function login()
    {
        extract($this->config);
        if ($api_driver_dsn and !QuickBooks_Utilities::initialized($api_driver_dsn))
        {
            QuickBooks_Utilities::initialize($api_driver_dsn);
            QuickBooks_Utilities::createUser($api_driver_dsn, $username, $password);
        }
        $this->API = new QuickBooks_API($api_driver_dsn, $username, $source_type, $source_dsn, $api_options, $source_options, $driver_options);
        return $this->API;
    }

    function saveBill($p, $types_payment, $tran_datetime)
    {
        global $feedback;
        if ($p['pay_pref'] == 2) {
            $fields = $this->saveQBCheck($p, $types_payment, $tran_datetime);
        } else {
            $fields = $this->saveQBBill($p, $types_payment, $tran_datetime);
        }
        return empty($fields) ? false : $fields;
    }

    function saveQBCheck($p, $types_payment, $tran_datetime)
    {
        global $feedback;
        $data = $this->getPostData($p, $tran_datetime);
        extract($data);
        $name = $p['first_name'] . ' ' . $p['last_name'];
        $fields = array(
            // 'AccountRef' => array('FullName' => 'Checking'),
           // 'AccountRef' => array('FullName' => 'Chase Bank', 'ListID'=>39), // 这个帐号可能是银行帐号
            'AccountRef' => array('FullName' => 'UsAmeriBank', 'ListID'=>282), // Bank Account
            'PayeeEntityRef' => array('ListID' => $p['qb_vendor_id'], 'FullName' => $name),
            //'PayeeEntityRef' => array('ListID' => 31, 'FullName' => 'Andrew Pineda'),
            'TxnDate' => $dueDate,
            'Memo' => $memo,
            'ExpenseLineAdd' => $this->getExpenseLines($types_payment),
        );
        $this->login();
        $check = $this->setObject('QuickBooks_Object_Check', $fields);
        global $g_fields;
        $g_fields = array();
        $ret = $this->API->cAddCheck($check,  'QuickBook::_quickbooks_check_callback');
        // $ret = $this->API->cAddCheck($check,  array($this, '_quickbooks_check_callback'));
        return $g_fields;
    }

    function saveQBBill($p, $types_payment, $tran_datetime)
    {
        global $feedback;
        $total_cost = 0;
        $name = $p['first_name'] . ' ' . $p['last_name'];
        $data = $this->getPostData($p, $tran_datetime);
        extract($data);
        $fields = array(
            'VendorRef' => array('ListID' => $p['qb_vendor_id'], 'FullName' => $name),
            //'VendorRef' => array('ListID' => 31, 'FullName' => 'Andrew Pineda'),
            'TxnDate' => $postingPeriod,
            'DueDate' => $dueDate,
            'RefNumber' => $refNo, 
            'TermsRef' => array('FullName' => 'Net 15', 'ListID' => 2),
            'Memo' => $memo,
            'ExpenseLineAdd' => $this->getExpenseLines($types_payment),
        );

        $this->login();
        $bill = $this->setObject('QuickBooks_Object_Bill', $fields);
        global $g_fields;
        $g_fields = array();
        $ret = $this->API->cAddBill($bill,  'QuickBook::_quickbooks_bill_callback');
        return $g_fields;
    }

    function saveVendor($p)
    {
        global $feedback;
        foreach ($p as $k => $v) {
            if (is_string($v)) $p[$k] = trim($v);
        }

        extract($p);

        if (empty($FirstName)) {
            $feedback  = 'Please specify first name';
            return false;
        }

        if (empty($LastName)) {
            $feedback  = 'Please specify last name';
            return false;
        }
        $data = array();

        $Name = $FirstName . ' ' . (empty($MiddleName) ? '':$MiddleName . ' ') . $LastName;
        if ($ListID > 0) {
            $data['ListID'] = $ListID;
            $data['EditSequence'] = $EditSequence;
        }
        $data['Name'] = $Name;
        $data['FirstName'] = $FirstName;
        $data['MiddleName'] = $MiddleName;
        $data['LastName'] = $LastName;
        
        extract($VendorAddress);
        
        if ($VendorAddress['Country'] == 'United States of America') {
            $VendorAddress['Country'] = 'USA';
        }
        $data['VendorAddress'] = $VendorAddress;
        global $g_tag;
        $payment_preference = $g_tag['payment_preference'];
        $Phone = $payment_preference[$p['Phone']];
        if ($p['Phone'] == 3) $Phone = 'Paypal';
        $data['Phone'] = $Phone;
        $data['Mobile'] = changePhoneFormat($Mobile);
        $data['Pager'] = $Pager;
        $data['AltPhone'] = $AltPhone;
        $data['Fax'] = 22;
        $data['Email'] = $Email;
        $data['TermsRef'] = array('FullName' => 'Net 15');
        $data['VendorTaxIdent'] = changeSSNFormat($VendorTaxIdent);
        $data['IsVendorEligibleFor1099'] = 'true';
        $vendor = $this->setObject('QuickBooks_Object_Vendor', $data);
        if (empty($ListID) || $ListID > 0) {
            global $g_fields;
            $g_fields = array();
            $this->login();
            if ($ListID > 0) {
                $method = 'VendorMod';
                $ret = $this->API->cSaveVendor($vendor, 'QuickBook::_quickbooks_vendor_callback');
            } else {
                $method = 'VendorAdd';
                $ret = $this->API->addVendor($vendor, 'QuickBook::_quickbooks_vendor_callback');
            }
            $user_id = $p['user_id'];
            return empty($g_fields) ? false : $g_fields;
        }
        return false;
    }

    function getExpenseLines($types_payment)
    {
        $expense_lines = array();
        foreach ($types_payment as $item) {
            $qd_listid = $item['qd_listid'];
            if (empty($qd_listid) || $qd_listid == 91) {
                $qd_listid = 166;
                // the old one is 91
            }
            //$item['cost'] = 0.01;
            $expense_lines[] = array(
                'AccountRef' => array('ListID' => $qd_listid),
                'Amount' => $item['cost'],
                'BillableStatus' => 'NotBillable',
             );
        }
        return $expense_lines;
    }

    function getPostData($p, $tran_datetime)
    {
        $firstDateTime = $tran_datetime;
        $month = $p['month'];
        $pay_per_month = getPayPerMonth($month);
        $interval = floor(31/$pay_per_month);
        $lastDateTime = $firstDateTime + 86400 * ($interval -1);
        $memo = date("M Y", $firstDateTime) . '-' . substr($month, -1);
        $postingPeriod = date("Y-m-d", $firstDateTime);
        $next_pay_month = nextPayMonth($month, $firstDateTime, $pay_per_month);
        $next_pay_time = changeTimeFormatToTimestamp(nextPayMonth($next_pay_month)) - 7200;
        // $dueDate = date("Y-m-d", $next_pay_time);
        // added by nancy xu 2012-01-29 17:20
        // get due date by check.
        $dateTime = $p['pay_pref'] == 2 ? $lastDateTime : $next_pay_time;
        $dueDate = date("Y-m-d", $dateTime);
        // end
        $refNo = $p['user_id'] . '-' . date("Ym", $firstDateTime) . '-' . substr($month, -1);
        return compact('postingPeriod', 'dueDate', 'memo', 'refNo');
    }

    function generateXml($data, $method = '')
    {
        $xml =  '';
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $xml .= '<' . $k . '>'  . $this->generateXml($v) . '</' . $k . '>';
            } else {
                if (strlen($v)) {
                    $xml .= '<' . $k . '>' . $v. '</' .$k . '>';
                }
            }
        }
        if (!empty($method)) {
            $xml = '<' . $method . 'Rq><' . $method . '>' . $xml. '</' .$method . '></' .$method . 'Rq>';
        }
        return $xml;
    }

    public static function _quickbooks_vendor_callback($method, $action, $ID, $err, $qbxml, $qbobject, $qbres)
    {
        global $g_fields, $feedback;
        if (empty($err)) {
            $obj = QuickBooks_Object_Vendor::fromQBXML($qbxml, $action);
            $key  = 'QBXMLMsgsRs ' . $action . 'Rs VendorRet';
            $fields = array();
            $fields['ListID'] = $obj->get($key .' ListID');
            $fields['EditSequence'] = $obj->get($key .' EditSequence');
            $g_fields = $fields;
        } else {
            $feedback = $err;
        }
        return $g_fields;
    }

    public static function _quickbooks_check_callback($method, $action, $ID, $err, $qbxml, $qbobject, $qbres)
    {
        global $g_fields, $g_pay_plugin, $feedback;
        if (empty($err)) {
            $obj = QuickBooks_Object_Check::fromQBXML($qbxml, $action);
            $key  = 'QBXMLMsgsRs ' . $action . 'Rs CheckRet';
            $g_fields['nbill_id'] = $obj->get($key . ' TxnID');
            $g_fields['tranDate'] = $obj->get($key . ' TimeCreated');
            $g_fields['customForm'] = $obj->get($key . ' EditSequence');
            $g_fields['tranId'] = $obj->get($key . ' TxnNumber');
            $g_fields['vendor_id'] = $obj->get($key . ' PayeeEntityRef ListID');
            $g_fields['postingPeriod'] = $obj->get($key . ' TxnDate');
            $list = $obj->getArray($key . ' ExpenseLineRet *');
            $g_fields['memo'] = $obj->get($key . ' Memo');
            $g_fields['userTotal'] = $obj->get($key . ' Amount');
            $g_fields['dueDate'] = '';
            $g_fields['pay_plugin'] = $g_pay_plugin;
            $tmp = array();
            foreach ($list as $k => $v) {
                $tkey = str_replace($key . ' ExpenseLineRet ', '', $k);
                $tkey = trim($tkey);
                if (is_array($v)) {
                    foreach ($v as $subk => $subv) {
                        $tmp[$subk][$tkey] = $subv;
                    }
                } else {
                    $tmp[$tkey] = $v;
                }
            }
            $g_fields['expenseList'] = $tmp;
        } else {
            $feedback = $err;
        }
        return $g_fields;
    }

    public static function _quickbooks_bill_callback($method, $action, $ID, $err, $qbxml, $qbobject, $qbres)
    {
        global $g_fields, $g_pay_plugin, $feedback;
        if (empty($err)) {
            $obj = QuickBooks_Object_Bill::fromQBXML($qbxml, $action);
            $key  = 'QBXMLMsgsRs ' . $action . 'Rs BillRet';
            $g_fields['nbill_id'] = $obj->get($key . ' TxnID');
            $g_fields['tranDate'] = $obj->get($key . ' TimeCreated');
            $g_fields['customForm'] = $obj->get($key . ' EditSequence');
            $g_fields['tranId'] = $obj->get($key . ' TxnNumber');
            $g_fields['vendor_id'] = $obj->get($key . ' VendorRef ListID');
            $g_fields['postingPeriod'] = $obj->get($key . ' TxnDate');
            $g_fields['dueDate'] = $obj->get($key . ' DueDate');
            $list = $obj->getArray($key . ' ExpenseLineRet *');
            $g_fields['memo'] = $obj->get($key . ' Memo');
            $g_fields['userTotal'] = $obj->get($key . ' AmountDue');
            $g_fields['pay_plugin'] = $g_pay_plugin;
            $tmp = array();
            foreach ($list as $k => $v) {
                $tkey = str_replace($key . ' ExpenseLineRet ', '', $k);
                $tkey = trim($tkey);
                if (is_array($v)) {
                    foreach ($v as $subk => $subv) {
                        $tmp[$subk][$tkey] = $subv;
                    }
                } else {
                    $tmp[$tkey] = $v;
                }
            }
            $g_fields['expenseList'] = $tmp;
        } else {
            $feedback = $err;
        }
        return $g_fields;
    }
     

    function setObject($className, $data, $key = '', $obj = null)
    {
        if (empty($obj)) $obj = new $className();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                //if (!empty($key)) $tmp = ($key == 'VendorAddress') ? $key : $key. ' ';
                if ($k == 'ExpenseLineAdd') {
                    $class = $className. '_ExpenseLine';
                    $lines = array();
                    foreach ($v as $subk => $subv) {
                        $line = $this->setObject($class, $subv);
                        $obj->addExpenseLine($line);
                    }
                } else {
                    if (!empty($key)) $tmp = $key. ' ';
                    $obj = $this->setObject($className, $v, $tmp.$k, $obj);
                }
            } else {
                // if (!empty($key)) $tmp = ($key == 'VendorAddress') ? $key : $key. ' ';
                if (!empty($key)) $tmp = $key. ' ';
                $obj->set($tmp. $k, $v, $obj);
            }
        }
        return $obj;
    }
}
?>