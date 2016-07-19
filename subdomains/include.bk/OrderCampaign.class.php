<?php
class OrderCampaign {

    function save($arr, $operation = 'init') 
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        extract($arr);
        if ($operation == 'init') {
            if (!isset($agreeterm) || empty($agreeterm)) {
                $feedback = "Please agree the terms and conditions";
                return false;
            }
        }
        unset($arr['agreeterm']);
        
        //$client_id = $arr['client_id'];
        if (client_is_loggedin()) {
            $client_id = Client::getID();
            $arr['client_id'] = $client_id;
        }
        if ($client_id == '') {
            $feedback = "Please Choose a client";
            return false;
        }
        //$category_id = $arr['category_id'];
        if ($category_id == 0) {
            $feedback = "Please Choose a category";
            return false;
        }
        //$campaign_name = $arr['campaign_name'];
        if ($campaign_name == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }
        /*if ($campaign_requirement == '') {
            $feedback = "Please enter Additional Style Guide";
            return false;
        }
        if ($sample_content == '') {
            $feedback = "Please enter Sample Content";
            return false;
        }*/
        if ($keyword_instructions == '') {
            $feedback = "Please enter Content Instructions";
            return false;
        }
        if ($ordered_by == '') {
            $feedback = "Please enter Ordered By";
            return false;
        }
        //$date_start = $arr['date_start'];
        if ($date_start == '') {
            $feedback = "Please provide the start date of the campaign";
            return false;
        }
        //$date_end = $arr['date_end'];
        if ($date_end == '') {
            $feedback = "Please provide the Due Date of the campaign";
            return false;
        }
        $int_date_end = strtotime($date_end);
        $int_date_start = strtotime($date_start);
        if ($int_date_end < $int_date_start) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        } else if ((($int_date_end - $int_date_start)/86400) < 14) {
            $feedback = 'Interval time is less than 14 days,Please try again';
            return false;
        }

        if ($min_word > $max_word) {
            $feedback = 'Min number of words is more than max number, please try again';
            return false;
        }
        if ($is_mentioned == 1 && empty($biz_name)) {
            $feedback = 'Please sepecify the business name';
            return false;
        }
        //$cost_per_article = $arr['cost_per_article'];
        if ($cost_per_article == '') $cost_per_article = 0;
        $arr['cost_per_article'] = $cost_per_article;
        $editor_cost = $arr['editor_cost'];
        if ($editor_cost == '') $editor_cost = 0;
        $arr['editor_cost'] = $editor_cost;
        if (!is_numeric($cost_per_article)) {
            $feedback = "Copywriter cost per word per article must be a integer";
            return false;
        }
        if (!is_numeric($editor_cost)) {
            $feedback = "Editor cost per word per article must be a integer";
            return false;
        }
        //$order_campaign_id = $arr['order_campaign_id'];
        $conn->StartTrans();
        if (empty($order_campaign_id)) {
            $order_campaign_id = $conn->GenID('seq_order_campaigns_order_campaign_id');
            $arr['order_campaign_id'] = $order_campaign_id;
            $arr['date_created'] = date("Y-m-d H:i:s");
            $keys = array_keys($arr);
            $q = "INSERT INTO `order_campaigns` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            unset($arr['order_campaign_id']);
            $q = "UPDATE `order_campaigns` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE order_campaign_id='{$order_campaign_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return $order_campaign_id;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function updatePaymentByOrderID($arr, $order_id)
    {
        global $conn, $feedback;
        $q = "UPDATE `order_campaign_payments` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            $sets[] = "{$k}='{$v}'";
            if ($k == 'status') {
                $status = $arr['status'];
            }
        }
        $q .= implode(", ", $sets);
        $q .= " WHERE order_id='{$order_id}'";
        if (isset($status) && $status > 0 ) {
            $q .= " AND status < '{$status}' ";
        }
        $conn->Execute($q);
        $ok = $conn->Affected_Rows();
        if ($ok == 1) {
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function update($arr)
    {
        global $conn, $feedback;
        $q = "UPDATE `order_campaigns` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            if ($k == 'order_campaign_id') {
                $order_campaign_id = $v;
                continue;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= " WHERE order_campaign_id='{$order_campaign_id}'";
        $conn->Execute($q);
        $ok = $conn->Affected_Rows();
        if ($ok == 1) {
            return $order_campaign_id;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }


    function store($arr)
    {
        global $conn, $feedback;
        $conn->StartTrans();
        $q = "UPDATE `order_campaigns` SET ";
        $sets = array();
        foreach ($arr as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            if ($k == 'order_campaign_id') {
                $order_campaign_id = $v;
                continue;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= " WHERE order_campaign_id='{$order_campaign_id}'";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return $order_campaign_id;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function getInfo($order_campaign_id)
    {
        global $conn, $feedback;
        $order_campaign_id = addslashes(htmlspecialchars(trim($order_campaign_id)));
        if ($order_campaign_id == '') {
            $feedback = "Please Choose a  order campaign";
            return false;
        }
        $q  = "SELECT oc.*, cl.user_name as client_name ";
        $q .= "FROM order_campaigns AS oc ";
        $q .= "LEFT JOIN client AS cl ON (oc.client_id=cl.client_id)";
        $q .= "WHERE oc.order_campaign_id = '{$order_campaign_id}'";
        return $conn->GetRow($q);
    }

    function getOrderCampaignIDByCampaignId($campaign_id)
    {
        global $conn, $feedback;
        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign id";
            return false;
        }
        $q = "SELECT order_campaign_id FROM order_campaigns WHERE campaign_id = '{$campaign_id}'";
        return $conn->GetOne($q);
    }


    function getPaidOrderCampaigns()
    {
        global $conn;
        $sql = 'SELECT oc.order_campaign_id AS tracking, ocp.subtotal AS amount,c.referrer_name as userID  ';
        $sql .= 'FROM order_campaigns AS oc ';
        $sql .='LEFT JOIN client AS c ON c.client_id=oc.client_id ';
        $sql .='LEFT JOIN order_campaign_payments AS ocp ON ocp.order_id=oc.order_campaign_id ';
        $sql .= 'WHERE c.referrer_type=2 AND oc.status=10 AND ocp.subtotal > 0 ';
        $sql .= ' AND oc.shareasale IS NULL ';
        return $conn->GetAll($sql);
    }

    function getList($p = array())
    {
        global $conn;
        global $g_pager_params;
        $qw = ' 1 ';
         if (client_is_loggedin()) {
             $qw .= ' AND oc.client_id=\'' . Client::getID() . '\'';
         } else if (user_is_loggedin() && User::getPermission() == 4) {
             $qw .= ' AND c.project_manager_id=' . User::getID() . ' ';
         }
         
         if (isset($p['client_id']) && $p['client_id'] > 0) {
             $qw .= ' AND oc.client_id=\'' . $p['client_id'] . '\'';
         }
        $sql  = "SELECT COUNT(*) FROM order_campaigns AS oc ";
        $sql .=" LEFT JOIN client AS c ON c.client_id = oc.client_id WHERE {$qw}";
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
            $sql  = "SELECT oc.*, ock.keyword_id, ock.fields, oc.date_created AS order_date, c.user_name AS client_name, cat.category, cc.date_created, u.user_name AS creator,at.type_name as content_type, ocp.status as pay_status, cc.is_import_kw FROM order_campaigns AS oc ";
            $sql .= "LEFT JOIN client AS c ON c.client_id = oc.client_id ";
            $sql .= "LEFT JOIN category AS cat ON cat.category_id  = oc.category_id ";
            $sql .= "LEFT JOIN client_campaigns AS cc ON cc.campaign_id  = oc.campaign_id ";
            $sql .= "LEFT JOIN article_type AS at ON at.type_id  = oc.article_type ";
            $sql .= "LEFT JOIN users AS u ON u.user_id  = cc.creation_user_id ";
            $sql .= "LEFT JOIN order_campaign_payments AS ocp ON ocp.order_id  = oc.order_campaign_id ";
            $sql .= "LEFT JOIN order_campaign_keywords AS ock ON ock.order_id  = oc.order_campaign_id ";
            $sql .=" WHERE {$qw} AND oc.status >= 0 ";
            //$sql .= " ORDER BY  oc.campaign_id, oc.timestamp, oc.order_campaign_id DESC ";
            $sql .= " ORDER BY  oc.timestamp DESC, oc.order_campaign_id DESC ";
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
                         'result' => $orders,
                         'count' => $count
            );
        } else {
            return false;
        }
    }

    function sendAnnouceMail($info, $tos, $event_id = 23, $cc=null)
    {
        global $g_placeholders, $mailer_param, $conn;
        if (!empty($cc)) {
            $mailer_param['cc'] = $cc;
        }
        $arr = Email::getInfoByEventId($event_id);
        $subject = $arr['subject'];
        $body = $arr['body'];
        if ($event_id == 23) {
            $datastring = "Campaign Name: {$info['campaign_name']}\n";
            if (empty($info['campaign_site_url'])) $info['campaign_site_url'] = 'n/a';
            $datastring .= "Campaign Site Url: {$info['campaign_site_url']}\n";
            $datastring .= "Desired Start Date: {$info['date_start']}\n";
            $datastring .= "Desired Due Date: {$info['date_end']}\n";
            if (empty($info['keyword_instructions'])) $info['keyword_instructions'] = 'n/a';
            $datastring .= "Content Instructions: {$info['keyword_instructions']}\n";
            $info['datastring'] = $datastring;
        } else if ($event_id == 31) {
            $datastring = "Transaction Details \n";
            if (empty($info['content_type'])) $info['content_type'] = 'n/a';
            $datastring .= "Content Type: {$info['content_type']}\n";
            $datastring .= "Content Qty: {$info['qty']}\n";
            $datastring .= "Max Number of Words: {$info['max_word']}\n";
            $datastring .= "merchant: n/a\n";
            $datastring .= "amount: \${$info['total']}\n";
            $datastring .= "Transaction id: n/a\n";
            $info['datastring'] = $datastring;
        } else if ($event_id == 32) {
            $datastring = "Campaign Name: {$info['campaign_name']}\n";
            if (empty($info['content_type'])) $info['content_type'] = 'n/a';
            $datastring .= "Content Type: {$info['content_type']}\n";
            $datastring .= "Quantity of Articles: {$info['qty']}\n";
            $datastring .= "Max Number of Words: {$info['max_word']}\n";
            $datastring .= "Start Date: {$info['date_start']}\n";
            $datastring .= "Due Date: {$info['date_end']}\n";
            if (empty($info['keyword_instructions'])) $info['keyword_instructions'] = 'n/a';
            $datastring .= "Content Instructions: {$info['keyword_instructions']}\n";
            $datastring .= "Ordered By: {$info['ordered_by']}\n";
            $datastring .= "Subtotal:  \${$info['subtotal']}\n";
            $datastring .= "Discount:  \${$info['discount']}\n";
            $datastring .= "Fees:  \${$info['fees']}\n";
            $datastring .= "Total:  \${$info['total']}\n";
            $info['datastring'] = $datastring;
        }
        // added by nancy xu 2011-02-02 10:54
        // cc on notification emails to order campaign
        $client_pm = Client::getPMInfo(array('order_campaign_id' => $info['order_campaign_id']));       
        if (!empty($client_pm) && $client_pm['email'] != $tos) {
            $mailer_param['cc'] = $client_pm['email'];
        }
        // end
        $body = email_replace_placeholders($body, $info);
        $subject = email_replace_placeholders($subject, $info);
        $body = nl2br($body);
        return send_smtp_mail($tos, $subject, $body, $mailer_param);
    }

    function getCampaignOrderNotification($conditions = array())
    {
        global $conn;
        $sql  = "SELECT oc.campaign_name, oc.order_campaign_id AS campaign_id, cl.company_name, u.user_id, u.role FROM order_campaigns AS oc ";
        $sql .= "INNER JOIN client AS cl ON (cl.client_id=oc.client_id) ";
        $sql .= "INNER JOIN users AS u ON (cl.project_manager_id=u.user_id) ";
        if (!empty($conditions)) {
            $sql .= 'WHERE ' . implode(" AND ", $conditions);
        }
        return $conn->GetAll($sql);
    }
}
?>
