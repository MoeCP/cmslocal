<?php
class OrderCampaignPayment {

    function updateByOrderId($arr, $order_id)
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                $arr[$k] = serialize($value);
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        extract($arr);
        $conn->StartTrans();
        $q = "UPDATE `order_campaign_payments` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= "WHERE order_id='{$order_id}'";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }
    function save($arr) 
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                $arr[$k] = serialize($value);
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        extract($arr);
        if (empty($subtotal)) {
            $subtotal = $qty * $article_price;
            $arr['subtotal'] = $subtotal;
        }
        if (strlen($discount) == 0) $discount = 0;
        if (strlen($fees) == 0) $fees = 0;
        if (empty($total)) {
            $total = $subtotal - $discount + $fees;
            $arr['total'] = $total;
        }
        if ($order_id == '') {
            $feedback = "Please Choose a campaign order";
            return false;
        } 
        
        $conn->StartTrans();
        if (empty($payment_id)) {
            $payment_id = self::getIDByOrderId($order_id);
        }
        if (empty($payment_id)) {
            $arr['created'] = date("Y-m-d H:i:s");
            if (user_is_loggedin()) {
                $arr['created_by'] = User::getID();
                $arr['creation_role'] = User::getRole();
            } else if (client_is_loggedin) {
                $arr['created_by'] = Client::getID();
                $arr['creation_role'] = 'client';
            }
            $keys = array_keys($arr);
            $q = "INSERT INTO `order_campaign_payments` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            unset($arr['payment_id']);
            $q = "UPDATE `order_campaign_payments` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE payment_id='{$payment_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function getIDByOrderId($order_id)
    {
        global $conn;
        $q = "SELECT payment_id FROM `order_campaign_payments` WHERE order_id = '{$order_id}'";
        return $conn->GetOne($q);
    }

    function getInfoByToken($token)
    {
        global $conn;
        $q = "SELECT * FROM `order_campaign_payments` WHERE token = '{$token}'";
        return $conn->GetRow($q);
    }

    function getInfoByOrderId($order_id)
    {
        global $conn;
        $q = "SELECT * FROM `order_campaign_payments` WHERE order_id = '{$order_id}'";
        return $conn->GetRow($q);
    }


    function store($arr)
    {
        global $conn;
        $q = "UPDATE `order_campaign_payments` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            if ($k == 'payment_id') {
                $payment_id = $v;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= " WHERE payment_id='{$payment_id}'";
        $conn->Execute($q);
    }

    function getInfo($payment_id)
    {
        global $conn, $feedback;
        $payment_id = addslashes(htmlspecialchars(trim($payment_id)));
        if ($payment_id == '') {
            $feedback = "Please Choose a  order campaign payment";
            return false;
        }
        $q = "SELECT * FROM `order_campaign_payments` WHERE payment_id = '{$payment_id}'";
        return $conn->GetRow($q);
    }

    function getList()
    {
        global $conn;
        global $g_pager_params;
        $qw = ' 1 ';
        $sql  = "SELECT COUNT(*) FROM `order_campaign_payments` as ocp WHERE {$qw}";
        $count = $conn->GetOne($sql);
        if ($count > 0) {
            if (trim($p['perPage']) > 0) {
                $perpage = $p['perPage'];
            } else {
                $perpage= 50;
            }
            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $sql  = "SELECT ocp.* FROM `order_campaign_payments` AS ocp ";
            $sql .=" WHERE {$qw}";
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($sql, $perpage, ($from - 1));
            if ($rs) {
                $orders = array();
                while (!$rs->EOF) {
                    $orders[] = $rs->fields;
                    $rs->MoveNext();               
                }
                $rs->Close();
            } else {
                return false;
            }
            return array('pager'  =>$pager->links,
                         'total'  => $pager->numPages(),
                         'result' => $orders
            );
        } else {
            return false;
        }
    }

}
?>
