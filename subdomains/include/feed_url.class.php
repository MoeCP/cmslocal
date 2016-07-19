<?php
class FeedUrl {
 
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

        if ($feed_url == '') {
            $feedback = "Please specify the url";
            return false;
        } else if (!valid_url($feed_url)) {
            $feedback = "Invalid url";
            return false;
        }

        $conn->StartTrans();
        if (empty($url_id)) {
            $url_id = $conn->GenID('seq_feed_urls_url_id');
            $arr['created'] = date("Y-m-d H:i:s");
            $arr['url_id'] = $url_id;
            $keys = array_keys($arr);
            $q = "INSERT INTO `feed_urls` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            $q = "UPDATE `feed_urls` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE url_id='{$url_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful!';
            return $url_id;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function store($arr)
    {
        global $conn, $feedback;
        $q = "UPDATE `feed_urls` SET ";
        $sets = array();

        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                if (!empty($v)) $v = addslashes(serialize($v));
                else $v = '';
            } else {
                $v = addslashes(htmlspecialchars(trim($v)));
            }
            if ($k == 'url_id') {
                $url_id = $v;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= "WHERE url_id='{$url_id}'";
        if (empty($url_id)) {
            $feedback = 'Please specify the keyword file';
            return false;
        }
        $conn->Execute($q);
        return true;
    }

    function getInfo($url_id)
    {
        global $conn, $feedback;
        $url_id = addslashes(htmlspecialchars(trim($url_id)));
        if ($url_id == '') {
            $feedback = "Please specify feed";
            return false;
        }
        $q = "SELECT * FROM `feed_urls` WHERE url_id = '{$url_id}'";
        return $conn->GetRow($q);
    }

    function getList($p)
    {
        global $conn;
        global $g_pager_params;
        $condtions[] = " 1 ";
        $condtions = array();
        if (isset($p['url_id']) && $p['url_id'] > 0) {
            $condtions[] = 'fu.url_id=' . $p['url_id'];
        }
        if (isset($p['campaign_id']) && $p['campaign_id'] > 0) {
            $condtions[] = 'fu.campaign_id=' . $p['campaign_id'];
        }
        $qw = 'WHERE ' . implode(' AND ', $condtions);
        $sql  = "SELECT COUNT(*) FROM `feed_urls` AS fu {$qw}";
        $count = $conn->GetOne($sql);
        if ($count > 0) {
            if (trim($p['perPage']) > 0) {
                $perpage = $p['perPage'];
            } else {
                $perpage= 20;
            }
            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $sql  = "SELECT fu.*,cc.campaign_name FROM `feed_urls` AS fu \n";
            $sql .= "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=fu.campaign_id) \n";
            $sql .="  {$qw}";
            $sql .= " ORDER BY  fu.url_id ";
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($sql, $perpage, ($from - 1));
            if ($rs) {
                $result = array();
                while (!$rs->EOF) {
                    $fields = $rs->fields;
                    $result[] = $fields;
                    $rs->MoveNext();
                }
                $rs->Close();
            } else {
                return false;
            }
            return array('pager'  =>$pager->links,
                         'total'  => $pager->numPages(),
                         'count'  => $count,
                         'result' => $result,
            );
        } else {
            return false;
        }
    }
}
?>
