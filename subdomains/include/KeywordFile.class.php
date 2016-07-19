<?php
class KeywordFile {
 
    function save($arr) 
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                if (!empty($value)) $arr[$k] = addslashes(serialize($value));
                else $arr[$k] = '';
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        extract($arr);
        
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }

        if ($filename == '') {
            $feedback = "Please specify the file name";
            return false;
        }

        $conn->StartTrans();
        if (empty($file_id)) {
            $file_id = $conn->GenID('seq_keyword_files_file_id');
            $arr['created'] = date("Y-m-d H:i:s");
            $arr['file_id'] = $file_id;
            $keys = array_keys($arr);
            $q = "INSERT INTO `keyword_files` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            $q = "UPDATE `keyword_files` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE file_id='{$file_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return $file_id;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function store($arr)
    {
        global $conn, $feedback;
        $q = "UPDATE `keyword_files` SET ";
        $sets = array();

        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                if (!empty($v)) $v = addslashes(serialize($v));
                else $v = '';
            } else {
                $v = addslashes(htmlspecialchars(trim($v)));
            }
            if ($k == 'file_id') {
                $file_id = $v;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= "WHERE file_id='{$file_id}'";
        if (empty($file_id)) {
            $feedback = 'Please specify the keyword file';
            return false;
        }
        $conn->Execute($q);
        return true;
    }

    function getInfo($file_id)
    {
        global $conn, $feedback;
        $file_id = addslashes(htmlspecialchars(trim($file_id)));
        if ($file_id == '') {
            $feedback = "Please Choose a  file";
            return false;
        }
        $q = "SELECT * FROM `keyword_files` WHERE file_id = '{$file_id}'";
        $info = $conn->GetRow($q);
        if (!empty($info['data'])) $info['data'] = unserialize($info['data']);
        if (!empty($info['fields'])) $info['fields'] = unserialize($info['fields']);
        return $info;
    }

    function getList()
    {
        global $conn;
        global $g_pager_params;
        $qw = ' 1 ';
         if (client_is_loggedin()) {
             $qw .= ' AND oc.client_id=\'' . Client::getID() . '\'';
         }
        $sql  = "SELECT COUNT(*) FROM order_campaigns as oc WHERE {$qw}";
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
            $sql  = "SELECT oc.*, c.user_name AS client_name, cat.category, cc.date_created, u.user_name AS creator,cc.is_import_kw FROM order_campaigns AS oc ";
            $sql .= "LEFT JOIN client AS c ON c.client_id = oc.client_id ";
            $sql .= "LEFT JOIN category AS cat ON cat.category_id  = oc.category_id ";
            $sql .= "LEFT JOIN client_campaigns AS cc ON cc.campaign_id  = oc.campaign_id ";
            $sql .= "LEFT JOIN users AS u ON u.user_id  = cc.creation_user_id ";
            $sql .=" WHERE {$qw}";
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
                         'result' => $orders
            );
        } else {
            return false;
        }
    }
}
?>
