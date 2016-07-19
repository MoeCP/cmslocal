<?php
	require_once 'pre_cron.php';//parameter settings
    $conn->debug = true;
    $q = 'SELECT user_id, month FROM cp_payment_history AS cph ';
    $q .= 'WHERE payment_flow_status=\'paid\' AND invoice_status=1';
    $result = $conn->GetAll($q);
    foreach ($result as $item) {
        $types = User::sumTypePaymentHistory($item['user_id'], $item['month'], $total);
        $hash = array(
            'types' => serialize($types),
            'total' => $total
        );
        $q = 'UPDATE cp_payment_history SET ';
        $sets = array();
        foreach ($hash as $k => $v) {
            $sets[] = $k . '=\'' . $v. '\'';
        }
        $q .= implode(", ", $sets);
        $q .= 'WHERE user_id=' . $item['user_id'] . ' AND month=' . $item['month'];
        $conn->Execute($q);
    }
?>