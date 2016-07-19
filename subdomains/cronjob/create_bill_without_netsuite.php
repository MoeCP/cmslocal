<?php
require_once 'pre_cron.php';
require_once CMS_INC_ROOT . DS . 'User.class.php';
require_once CMS_INC_ROOT . DS . 'article_payment_log.class.php';
require_once CMS_INC_ROOT . DS . 'article_cost_history.class.php';
require_once CMS_INC_ROOT . DS . 'cp_payment_history.class.php';
$conn->debug = true;

$p = array(
    'cph.payment_flow_status' => 'cpc',
    'where' => 'cph.month < 201010 AND u.vendor_id > 0 '
);
$result = CpPaymentHistory::getAllByParam($p);
//pr($result);
foreach ($result as $row) {
    $p = array(
        'user_id' => $row['user_id'],
        'month' => $row['month'],
        'payment_flow_status' => 'cbill',
        'vendor_id' => $row['vendor_id'],
        'role' => $row['role'],
     );
    setPaymentFlowStatus($p);
}
function setPaymentFlowStatus($p = array())
{
    global $conn, $feedback;
    global $g_tag;
    //$conn->debug = true;

    $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
    if ($user_id == '') {
        $feedback = "Please choose an user";
        return false;
    } else {
        $qw = ' AND u.user_id=' . $user_id;
    }

    $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
    if ($payment_flow_status == '') {
        $feedback = "Please set the payment status";
        return false;
    }

    $role = addslashes(htmlspecialchars(trim($p['role'])));
    if ($role == '') {
        $role = 'copy writer';
    }
    $qw .= ' AND u.role=\'' . $role . '\'';

    $month = addslashes(htmlspecialchars(trim($p['month'])));
    $month = str_replace('-', "", $month);
    if ($month == '')  {
        $month = date( "Ym" );
    }
    $data = $p;
    unset($data['vendor_id']);
    unset($data['article_ids']);
    unset($data['payment_flow_status']);
    $now = generateDateTimeByMonth($month);

    $data['approved_user'] = 0;
    $data['check_no']        = '';
    $data['invoice_no']       = '';
    $data['reference_no']   = '';
    $data['payment']         = 0;
    $q = "SELECT * FROM cp_payment_history ".
         "WHERE user_id = '".$user_id."' AND month = '".$month."' AND role='" . $role . "'";
    $rs = $conn->Execute($q);
    $pf_status = '';
    if ($rs) 
    {
        if (!$rs->EOF)
        {
            $is_update = true;
            $fields = $rs->fields;
            foreach ($fields as $k => $v) {
                if (empty($v) && strlen($v) == 0) unset($fields[$k]);
             }
            $data = array_merge($data, $fields);
            $pf_status = $rs->fields['payment_flow_status'];
        }
        $rs->Close();
    }
    else
    {
        $is_update = false;
    }
    if ($pf_status == 'paid') {
        $feedback = 'This user had paid.';
        return false;
    }


    if ($payment_flow_status == 'cpc' || $payment_flow_status == 'dwe') {
        if ($pf_status == 'ap' || $pf_status == 'cpc' || $pf_status == 'dwe' || ($pf_status == '' && $payment_flow_status == 'cpc')) {
            //
        } else {
            $feedback = 'Please wait CopyPress confirm';
            return false;
        }

        if ($pf_status == 'cpc' && $payment_flow_status == 'dwe') {
            return false;
        }
    }
    
    if ($payment_flow_status != $pf_status) 
    {
       $conn->StartTrans();
        $result = true;
        // add new workflow name cbill(create bill) for payment
        // create bill will  interact with netsuite
        if( $payment_flow_status ==  'paid' || $payment_flow_status == 'cbill') {
            $date_pay = date('Y-m-d H:i:s');
            $invoice_date = date('Y-m-d H:i:s', $now);
            if ($payment_flow_status == 'cbill') {
                $date_bill = date("Y-m-d H:i:s");
                $date_pay = '0000-00-00 00:00:00';
                $data['date_bill'] = $date_bill;
            } else {
                $data['date_pay'] = $date_pay;
            }
            $invoice_status = 1;
            // added by nancy xu 2009-12-29 15:16
            $p['role'] = $role;
            $data['invoice_date'] = $invoice_date;
            $data['invoice_status'] = $invoice_status;
            $result = ArticleCostHistory::storeArticleCostHistoryByParam($p, $invoice_status, $cost_arr, $date_bill);
            $types = User::sumTypePaymentHistory($user_id, $month, $total, $role);
            $payment = 0;
            foreach ($types as $tk=> $row) {
                $cost = round($row['cost'], 2);
                $types[$tk]['cost'] = $cost;
                $payment += $cost;
            }

            // create bill to netsuite ignore it
            $types = serialize($types);
            $data['types'] = $types;
            $data['total'] = $total;
            $data['payment'] = $payment;
            
            // end
        }
        if (!isset($data['set_flow_status_time']))
            $data['set_flow_status_time'] = date("Y-m-d H:i:s");
        $data['payment_flow_status'] = $payment_flow_status;
        if ($result) {
            $q = CpPaymentHistory::getSqlByData($data, $is_update);
            $conn->Execute( $q );
            if( $payment_flow_status ==  'paid' || $payment_flow_status ==  'cbill')
            {
                $log_param['user_id']  = $user_id;
                $log_param['role']  = $role;
                $log_param['pay_month']  = $month;
                ArticlePaymentLog::updatePaymentInfo($log_param, $payment_flow_status);
            }
        }
        $ok = $conn->CompleteTrans();
    }
    if ($ok || $payment_flow_status == $pf_status) 
    {
        $feedback = 'Success';
        return true;
    }
    else
    {
        $feedback = 'Failure, Please try again';
        return false;
    }
}//end setPaymentFlowStatus()
?>