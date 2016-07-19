<?php
class PaymentBill{

	private $bill_id;
	private $nbill_id;
	private $vendor_id;
	private $customForm;
	private $postingPeriod;
	private $dueDate;
	private $tranDate;
	private $tranId;
	private $userTotal;
	private $memo;
	private $expenseList;
    private $_table;

	function __construct()
	{
        $this->_table = '`payment_bills`';
	}

    function PaymentBill()
    {
        $this->__construct();
    }


	function store( $hash )
	{
		global $conn, $feedback;
        foreach ($hash as $k => $v) {
            if (is_string($v)) {
                $hash[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
            } else if (is_array($v)) {
                $hash[$k] = mysql_escape_string(serialize($v));
            }
        }
        extract($hash);
        
		// assembled sql - START
		$sql = '';
        if ($client_user_id > 0) {
            $sql = "UPDATE  " . $this->_table . " SET  ";
            $sets = array();
            foreach ($bind as $key => $value) {
                $sets[] = $key . '=\'' . $value .'\'';
            }
            $sql .= implode(', ', $sets);
            $sql .= 'WHERE bill_id=' . $bill_id;
        } else {
            $fields = array_keys($hash);
            $values = "'". implode("', '", $hash) . "'";
            $fields = "`" . implode("`, `", $fields) . "`";
            $sql = "INSERT INTO  " . $this->_table . " ({$fields}) VALUES ({$values}) ";
        }
		if (strlen($sql)) {
			$conn->Execute($sql);
			$feedback = 'Success';
			return true;
		} else {
			 $feedback = 'Failure, Please try again';
			return false;
		}
	}
}
?>