<?php
require_once COMMON_PATH . 'netsuite' . DS . 'PHPtoolkit.php';
$g_pay_user = array(
    'email' => "blueglass@govirtualoffice.com",
    'password' => "blueglass1",
    'account' => "1238476",
    'role' => "3",
);
class NetSuite {
    private $config = null;
    private $myNSclient = null;

    function __construct($config)
    {
        $this->NetSuite($config);
    }

    function NetSuite($config)
    {
        $this->config = $config;
    }

    function login()
    {
        extract($this->config);
        $this->myNSclient = new nsClient( nsHost::live );
        $this->myNSclient->setPassport($email, $password, $account, $role);
        return $this->myNSclient;
    }

    function saveBill($p, $types_payment, $tran_datetime)
    {
        global $feedback;
        $firstDateTime = $tran_datetime;
        $month = $p['month'];
        $pay_per_month = getPayPerMonth($month);
        $interval = floor(31/$pay_per_month);
        $lastDateTime = $firstDateTime + 86400 * ($interval -1);
        // $last_month = strtotime("-1 month");
        $memo = date("M Y", $firstDateTime) . '-' . substr($month, -1);
        $postingPeriod = date(DATE_ATOM, $firstDateTime);
        $tranDate = date(DATE_ATOM, $lastDateTime);
        $next_pay_month = nextPayMonth($month, $firstDateTime, $pay_per_month);
        $next_pay_time = changeTimeFormatToTimestamp(nextPayMonth($next_pay_month)) - 7200;
        $dueDate = date(DATE_ATOM, $next_pay_time);
        //$p['payment'] = 0.01;
        $fields = array(
            'entity' => array('internalId' => $p['vendor_id']),
            'customForm' => 103,
            'postingPeriod' => $postingPeriod,
            'dueDate' => $dueDate,
            'tranDate' => $tranDate,
            'tranId' => $p['invoice_no'],
            'userTotal' => $p['payment'],
            'memo' => $memo,
        );
        $list = array();
        /*
         *'account'		=> array('internalId' => 430),  this is defined payment account.
         * 223:5022 Cost of Good Sold : Search : Copypress. this is  the previous used
         * 430:1406 Interco - CopyPress. this is now used
         */
        foreach ($types_payment as $item) {
            //$item['cost'] = 0.01;
            $arr = array(
                'account'		=> array('internalId' => 232), // change 430 to 232
                'amount'		=> $item['cost'],
                'memo'		=> $item['name'],
                'class'		=> array('internalId' => 5), // change 10 to 5
                'department' => array('internalId' => 11), 
                //'customer'		=> array('internalId' => 3),
                'location'		=> array('internalId' => 1),
                'isBillable'		=> false);
            $list[] = $arr;
        }
        $fields['expenseList'] = array(
            'expense' => $list
        );
        $this->login();
        $vendorBill = new nsComplexObject('VendorBill');
        $vendorBill->setFields($fields);
        $addResponse = $this->myNSclient->add($vendorBill);
        if (!$addResponse->isSuccess) {
            $feedback = $addResponse->statusDetail[0]->message;
            return false;
        } else {
            //$feedback = $addResponse->statusDetail[0]->message;
            $fields['nbill_id'] = $addResponse->recordRef->getField('internalId');
            if (empty($fields['nbill_id'])) $fields['nbill_id'] = 0;
        }
        unset($fields['entity']);
        $fields['vendor_id']  = $p['vendor_id'];
        /*$fields['nbill_id'] =0;
        pr($fields);
        pr($addResponse);*/
        return $fields;
    }

    function saveVendor($p)
    {
        global $feedback;
        foreach ($p as $k => $v) {
            if (is_string($v)) $p[$k] = trim($v);
        }

        extract($p);

        if (empty($firstName)) {
            $feedback  = 'Please specify first name';
            return false;
        }

        if (empty($lastName)) {
            $feedback  = 'Please specify last name';
            return false;
        }

        /*if (empty($achacct['bankName'])) {
            $feedback  = 'Please specify bank name';
            return false;
        }

        if (empty($achacct['accountNumber'])) {
            $feedback  = 'Please specify account number';
            return false;
        }

        if (empty($achacct['routingNumber'])) {
            $feedback  = 'Please specify routing number';
            return false;
        }*/

        $p['is1099Eligible'] = true;
        $p['isPerson'] = true;
        $p['emailPreference'] = '_default';
        $p['customForm'] = 103;
        $p['globalSubscriptionStatus'] = '_softOptOut';
        extract($address);
        if ($country == 'United States of America') {
            $country = 'United States';
        }
        $country = str_replace(" ", "", ucwords('_' . strtolower($country)));
        $address['country'] = $country;
        unset($p['address']);
        unset($p['achacct']);
        $address['defaultShipping'] = true;
        $address['terms'] = 1; // Net 15
        $address['expenseAccount'] = 223; // 5022 Cost of Good Sold : Content : Copypress
        $address['defaultBilling'] = true;
        if (empty($phone)) {
            unset($p['phone']);
        } else {
            $address['phone'] = $phone;
        }
        $p['addressbookList'] = array(
            'addressbook' => $address
        );

        /*$p['achacctList'] = array(
            'achacct' => $achacct
        );*/
        if ($pay_pref != 3) {
            $p['billPay']  = true;
            $p['printOnCheckAs'] = $addressee;
        }
        $p['entityId'] = $addressee;
        unset($p['user_id']);
        if (isset($p['vendor_id']) && !empty($p['vendor_id'])) {
            $vendor_id = $p['vendor_id'];
            $p['internalId'] =  $vendor_id;
        }
        $vaddresses = $p['vaddresses'];
        if (!empty($vaddresses) && $vendor_id > 0) {
            $vaddresses = explode(";", $vaddresses);
        }

        unset($p['vendor_id']);
        unset($p['pay_pref']);
        unset($p['vaddresses']);
        if (empty($vendor_id) || $vendor_id > 0) {
            $this->login();
            if ($vendor_id > 0 && empty($vaddresses)) {
                $vaddresses = $this->addressinternalIds(new nsRecordRef(array('internalId' => $vendor_id, 'type' => 'vendor')));
            }
            if ($vendor_id > 0 && !empty($vaddresses)) {
                $p['addressbookList']['addressbook']['internalId'] = $vaddresses[0];
            }
            $vendor = new nsComplexObject('Vendor');
            $vendor->setFields($p);
            if ($vendor_id > 0) {
                $addResponse = $this->myNSclient->update($vendor);
            } else {
                $addResponse = $this->myNSclient->add($vendor);
            }
            if (!$addResponse->isSuccess) {
                $feedback = $addResponse->statusDetail[0]->message;
                return false;
            } else {
                $addresses = $this->addressinternalIds($addResponse->recordRef);
                $arr = array(
                    'internalId' => $addResponse->recordRef->getField('internalId'),
                    'addresses' => $addresses,
                );
                return $arr;
            }
        }
        return false;
    }

    function addressinternalIds($obj)
    {
        $addresses = array();
        $getResponse  = $this->myNSclient->get($obj);
        $lists = $getResponse->record->getField('addressbookList')->getField('addressbook');
        foreach ($lists as $item) {
            $addresses[] = $item->getField('internalId');
        }
        return $addresses;
    }
}
?>