<?php
class FeedArticle {
 
    function save($arr) 
    {
        global $conn, $feedback;
        foreach ($arr as $k => $value)  {
            if (is_array($value)) {
                if (!empty($value)) $arr[$k] = addslashes(serialize($value));
                else $arr[$k] = '';
            } else {
                $arr[$k] = addslashes(htmlspecialchars(trim($value),ENT_QUOTES));
            }
        }
        extract($arr);
        
        if ($title == '') {
            $feedback = "Please Choose a title";
            return false;
        }

        if ($description == '') {
            $feedback = "Please Choose a description";
            return false;
        }

        if ($link == '') {
            $feedback = "Please specify the url";
            return false;
        } else if (!valid_url($link)) {
            $feedback = "Invalid url";
            return false;
        }

        $conn->StartTrans();
        if (empty($id)) {
            $arr['created'] = date("Y-m-d H:i:s");
            $keys = array_keys($arr);
            $q = "INSERT INTO `feed_articles` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            $q = "UPDATE `feed_articles` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE id='{$id}'";
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

    function store($arr)
    {
        global $conn, $feedback;
        $q = "UPDATE `feed_articles` SET ";
        $sets = array();

        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                if (!empty($v)) $v = addslashes(serialize($v));
                else $v = '';
            } else {
                $v = addslashes(htmlspecialchars(trim($v)));
            }
            if ($k == 'id') {
                $id = $v;
            }
            $sets[] = "{$k}='{$v}'";
        }
        $q .= implode(", ", $sets);
        $q .= " WHERE id='{$id}'";
        if (empty($id)) {
            $feedback = 'Please specify the feed articles';
            return false;
        }
        $conn->Execute($q);
        return true;
    }

    function getInfo($id)
    {
        global $conn, $feedback;
        $id = addslashes(htmlspecialchars(trim($id)));
        if ($id == '') {
            $feedback = "Please Choose a feed";
            return false;
        }
        $q = "SELECT * FROM `feed_articles` WHERE id = '{$id}'";
        return $conn->GetRow($q);
    }

    function uploadFeedToDb($items, $url_id)
    {
        foreach ($items as $index => $obj) {
            $arr = (array) $obj;
            foreach ($arr as $k => $v) {
                $arr[$k] = html_entity_decode($v);
            }
            $arr['index'] = $index;
            $arr['url_id'] = $url_id;
           FeedArticle::save($arr);
        }
    }

    function getItemsByParam($p = array())
    {
        global $conn;
        $condtions[] = " 1 ";
        if (isset($p['url_id']) && $p['url_id'] > 0) {
            $condtions[] = 'fa.url_id=' . $p['url_id'];
        }
        $sql  = "SELECT fa.*, ar.richtext_body, ar.title, ck.optional1 FROM feed_articles AS fa ";
        $sql .= ' LEFT JOIN articles AS ar ON (ar.article_id=fa.article_id) ';
        $sql .= ' LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ';
        $sql .="  {$qw}";
        $sql .= " ORDER BY  fa.index ";
        return $conn->GetAll($sql);
    }

    function getList($p)
    {
        global $conn;
        global $g_pager_params;
        $condtions[] = " 1 ";
        $condtions = array();
        if (isset($p['url_id']) && $p['url_id'] > 0) {
            $condtions[] = 'fa.url_id=' . $p['url_id'];
        }
        $qw = 'WHERE ' . implode(' AND ', $condtions);
        $sql  = "SELECT COUNT(*) FROM feed_articles AS fa {$qw}";
        $count = $conn->GetOne($sql);
        $total_unstored = 0;
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
            $sql  = "SELECT fa.* FROM feed_articles AS fa ";
            $sql .="  {$qw}";
            $sql .= " ORDER BY  fa.id ";
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($sql, $perpage, ($from - 1));
            if ($rs) {
                $result = array();
                while (!$rs->EOF) {
                    $fields = $rs->fields;
                    $result[] = $fields;
                    if ($fields['article_id'] == 0) $total_unstored++;
                    $rs->MoveNext();
                }
                $rs->Close();
            } else {
                return false;
            }
            return array('pager'  =>$pager->links,
                         'total'  => $pager->numPages(),
                         'result' => $result,
                         'count' => $count,
                         'total_unstored' => $total_unstored
            );
        } else {
            return false;
        }
    }

    function addFeedsToArticle($ids, $campaign_id)
    {
        global $conn;
        $campaign = Campaign::getInfo($campaign_id);
        $company_name = strtoupper($campaign['company_name']);
        $numbers = explode(" ", $company_name);//we can use preg_split()
        $article_number = "";
        foreach ($numbers as $k => $v) {
            $article_number .= substr($v, 0, 1);
        }
        $now = time();
        $created = date("Y-m-d H:i:s", $now);
        $date_start = date("Y-m-d", $now);
        $date_end = date("Y-m-d", ($now + 1209600)); // 86400 * 14
        $user_id = User::getID();
        $role = User::getRole();
        foreach ($ids as $id) {
            if ($id > 0) {
                $info = self::getInfo($id);
                if (!empty($info) && $info['article_id'] == 0) {
                    $conn->StartTrans();
                    $title = $info['title'];
                    $keyword = array(
                        'keyword_category' => 403,
                        'keyword_status' => -1,
                        'article_type' => $campaign['article_type'],
                        'campaign_id' => $campaign_id,
                        'copy_writer_id' => 0,
                        'editor_id' => 0,
                        'keyword_description' => '',
                        'mapping_id' => '',
                        'optional1' => $info['link'],
                        'optional2' => '',
                        'optional3' => '',
                        'optional4' => '',
                        'creation_user_id' => $user_id,
                        'creation_role' => $role,
                        'date_start' => $date_start,
                        'date_end' => $date_end,
                        'date_created' => $created,
                        'date_assigned' => '0000-00-00 00:00:00',
                        'keyword' => $title,
                    );
                    $article = array(
                        'body' => $info['description'],
                        'article_number' => $article_number,
                        'creation_user_id' => $user_id,
                        'creation_role' => $role,
                        'creation_date' => $created,
                        'language' => 'en',
                        'title' => $title,
                        'html_title' => $title,
                        'current_version_number' => '1.0',
                        'article_status' => '0',
                    );
                    $article_id = Campaign::saveKeyword($keyword, $article, false);
                    if ($article_id > 0) {
                        $data = array(
                            'id' => $id,
                            'article_id' => $article_id,
                        );
                        FeedArticle::store($data);
                    }
                    $conn->CompleteTrans();
                }
            }
        }
    }

}
?>
