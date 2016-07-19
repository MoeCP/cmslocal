<?php
class PaymentSetting {

    function PaymentSetting()
    {
        $this->__construct();
    }

	function __construct()
	{

	}

    function getAll()
    {
        global $conn, $g_pay_per_month, $g_interval_days, $g_delay_days;
        $sql = "SELECT * FROM `payment_settings` WHERE 1 ORDER BY `end_month`"; 
        $result = $conn->GetAll($sql);
        $row = $result[0];
        unset($result[0]);
        $g_interval_days = floor(30/$g_pay_per_month);
        $g_delay_days = $g_interval_days;
        $g_payment_settings = $result;
        return $result;
    }
}
?>
