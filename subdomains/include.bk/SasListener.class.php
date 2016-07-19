<?php

class SasListener
{
	function __construct()
	{

	}

    function SasListener()
    {
        $this->__construct();
    }

    public function add($bind)
    {    
        global $conn, $feedback;
        foreach($bind as $k => $value) {
            $bind[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        $conn->StartTrans();
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $sql  = "INSERT INTO `sas_listeners` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        return $result;
    }
}
?>
