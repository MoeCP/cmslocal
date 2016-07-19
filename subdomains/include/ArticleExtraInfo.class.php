<?php
require_once CMS_INC_ROOT. DS . 'GeographicName.class.php';
require_once CMS_INC_ROOT . DS . 'Article.class.php';
class ArticleExtraInfo {

     /**
     * store article extra info
     *
     * @param array $p
     *
     * @return boolean/int  if success will return true，else return false
     */
    function store($p = array())
    {
        global $conn, $feedback;
        // dispose related articles info
        $related_numbers  = $p['related_article_id'];
        $related_keywords = $p['related_keyword'];
        $related_urls         = $p['related_article_url'];
        unset($p['related_article_id']);
        unset($p['related_keyword']);
        unset($p['related_article_urls']);
        $total_related_articles = count($related_numbers);
        $related_articles = array();
        for ($i = 0; $i < $total_related_articles; $i++)
        {
            $article_no = trim($related_numbers[$i]);
            $keyword   = trim($related_keywords[$i]);
            $article_url = trim($related_urls[$i]);
            if (strlen($article_no) && strlen($keyword))
            {
                $related_articles[$article_no] = array(
                    'keyword' => $keyword,
                    'url' => $article_url,
                );
            }
            else if (strlen($article_no) == 0 && strlen($keyword) == 0)
            {
                continue;
            } 
            else
            {
                $feedback = "Please specify related article id and related keyword";
                return false;
            }
        }
        foreach ($p as $k => $value)
        {
            $p[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        $hash['related_articles'] = addslashes(serialize($related_articles));
        $extra_info_id = $p['extra_info_id'];
        if (empty($extra_info_id))
        {
            $hash['created_time'] = date("Y-m-d H:i:s");
            $extra_info_id = null;
        }
        $hash['article_number'] = $p['article_number'];
        $hash['article_type']     = $p['article_type'];

        if (empty($p['campaign_id']))
        {
            $feedback = 'Please Specify Campaign';
            return false;
        }
        $hash['campaign_id'] = $p['campaign_id'];
        /*if (empty($p['article_url']))
        {
            $feedback = 'Please Specify Article URL';
            return false;
        }*/
        $hash['article_url'] = $p['article_url'];
        if (empty($p['article_id']))
        {
            $feedback = 'Please Specify article';
            return false;
        }
        $hash['article_id'] = $p['article_id'];
        if (empty($p['is_force_load'])) $p['is_force_load'] = 0;
        $hash['is_force_load'] = $p['is_force_load'];
       
        $hash['country']  = GeographicName::getNameByID($p['country']);
        $hash['state']     = GeographicName::getNameByID($p['state']);
        $hash['city']       = GeographicName::getNameByID($p['city']);
        if ($p['city'] > 0)
        {
            $hash['geolevel'] = 'city';
        }
        else if ($p['state'] > 0)
        {
            $hash['geolevel'] = 'state';
        }
        else if ($p['country'] > 0)
        {
            $hash['geolevel'] = 'country';
        }

        // check it whether or not exist.
        /*$q = "SELECT COUNT(article_url) AS count FROM article_extra_info ".
             "WHERE article_number = '" . $p['article_number'] . "'";
        if ($extra_info_id > 0)
            $q .= " AND extra_info_id={$extra_info_id}";
        $rs =& $conn->Execute($q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "This Article exist in database";
            return false;
        }*/

        $conn->StartTrans();
        // added by nancy check whether extra info exists or not by article id - STARTED
        if (empty($extra_info_id)) {            
            $sql = "SELECT  `extra_info_id` FROM `article_extra_info` WHERE `article_id`=" . $p['article_id'];
            $rs = $conn->Execute($sql);
            if ($rs) {
                if (!$rs->EOF) {
                    $extra_info_id = $rs->fields['extra_info_id'];
                }
                $rs->Close();
            }
        }
        // end
        if ($extra_info_id > 0)
        {
            $q = 'UPDATE article_extra_info SET ';
            foreach ($hash as $k => $value)
            {
                $sets[] = "{$k} = '{$value}'";
            }
            $q .= implode(", ", $sets);
            $q .= " WHERE extra_info_id='{$extra_info_id}'";
            
        }
        else
        {
            $id = $conn->GenID('seq_article_extra_info_extra_info_id');
            $hash['extra_info_id'] = $id;
            $extra_info_id = $id;
            $fields = array_keys($hash);
            $q = "INSERT INTO article_extra_info (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $hash)."')";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $extra_info_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getInfoByArticleId($article_id)
    {
        global $conn, $feedback;
        $sql  = ' SELECT aei.*, ar.article_number, ar.article_id ';
        $sql .= ' FROM articles as ar ';
        $sql .= ' LEFT JOIN article_extra_info as aei on ar.article_id = aei.article_id';
        $sql .= ' WHERE ar.article_id = ' . $article_id;
        $result = array();
        $rs = &$conn->Execute($sql);
        if ($rs) {
            if (!$rs->EOF) {
                $result = $rs->fields;
            }
            $rs->Close();
        }
        return $result;
    }

    

    function generateXMLString($p = array(), $campaign_id = array(), $add_p = array())
    {
        global $g_tag;
        //global $debugh;
        $condition = array();
        $extra_ids = array();
        $ext_type = $g_tag['article_extra_info_type'];
        $aol_url    = $g_tag['url']['aol'];
        $total = count($p);
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<InfinitenineArticles>";
        $xml .= "<DocumentRoot>{$aol_url}</DocumentRoot>";
        $xml .= "<Date>" . date("mdY") . "</Date>";
        $xml .= "<ArticleCount>" . $total . "</ArticleCount>";
        if (!empty($campaign_id))
            $condition = array('campaign_id' => $campaign_id);
        foreach ($p as $k=> $item) {
            // added by snug xu 2007-12-18 10:23 - STARTED
            if (strtolower($item['country']) == 'singapore')
            {
                $item['city'] = $item['country'];
                $item['country'] = '';
                $item['geolevel'] = 'city';
            }
            // added by snug xu 2007-12-18 10:23 - FINISHED
            $extra_ids[] = $item['extra_info_id'];
            $xml .= "<Article>";
            $xml .= "<ArticleName>{$item['title']}</ArticleName>";
            $xml .= "<ArticleID>{$item['article_number']}</ArticleID>";
            $article_url = self::getArticleURL($item['article_url'], $aol_url, $item['keyword']);
            $xml .= "<ArticleURL>{$article_url}</ArticleURL>";
            $xml .= "<ArticleType>{$ext_type[$item['article_type']]}</ArticleType>";
            $xml .= "<City>{$item['city']}</City>";
            $xml .= "<State>{$item['state']}</State>";
            $xml .= "<Country>{$item['country']}</Country>";
            $xml .= "<MetaKeyword>{$item['keyword_meta']}</MetaKeyword>";
            $xml .= "<MetaDescription>{$item['description_meta']}</MetaDescription>";
            $keyword_category = $item['keyword_category'];
            $condition['keyword_category'] = $keyword_category;
            //$condition['start'] = 0;
            //$condition['total'] = 10;
            $aol_keywords = Article::getArticleListByParam($condition, array('keyword'));
            $xml .= "<AOLSearchKeyword><![CDATA[" . implode(";", $aol_keywords) . "]]></AOLSearchKeyword>";
            if (isset($add_p['title_tag_add']))  $item['html_title'] .= $add_p['title_tag_add'];
            $xml .= "<TitleTag>{$item['html_title']}</TitleTag>";
            $xml .= "<GeoLevel>{$item['geolevel']}</GeoLevel>";
            if (!empty($item['richtext_body'])) 
            {
                // modified by snug xu 2007-12-18 10:21 - STARTED
                $content = html_entity_decode($item['richtext_body'], ENT_QUOTES);
                $nl = "\r\n";
                $content = str_ireplace(array("<br>", "<br/>", "<br />", "</p>", "&nbsp;", "</div>"), array($nl . "<br>", $nl . "<br/>", $nl . "<br />", "</p>" . $nl . $nl, " ", "</div>" . $nl), $content); 
                $content = strip_tags($content, "<a><h1><h2><h3><h4><h5><h6><h7><strong>");
                $content = nl2br($content);
                $content = str_replace($nl, "", $content);
                // modified by snug xu 2007-12-18 10:21 - FINISHED
                $body_format = 'HTML';
                // added by snug xu 2007-09-17 10:33 - STARTED
                // strip .html
                if (preg_match_all('/\.html[ ]*/ims', $content, $matches))
                {
                    $content = preg_replace("/\.html[ ]*/ims", "", $content);
                }
                // strip target="_blank"
                if (preg_match_all("/target=\"_blank[ ]*\"/ims", $content, $matches))
                {
                    $content = preg_replace('/target="_blank[ ]*"/ims', "", $content);
                }
                // added by snug xu 2007-09-17 10:33 - END
            }
            else 
            {
                $content = $item['body'];
                $body_format = 'text';
            }
            $content = str_replace("cp.infinitenine.com/article/", "", $content);
            $xml .= "<BodyTextFormat>{$body_format}</BodyTextFormat>";
            $xml .= "<BodyText><![CDATA[{$content}]]></BodyText>";
            $condition = array(
                'keyword_category' => $keyword_category,
                'aei.city'                => $item['city'],
                'aei.country'           => $item['country'],
                'aei.state'              => $item['state'],
                'ar.article_status'    => 6,
                /*'not'                    => array(
                    'aei.extra_info_id' => $item['extra_info_id'],
                )*/
            );
            $condition['not'] = array(
                'ar.article_id' => $item['article_id']
            );
            $relatedaArticles = Article::getArticleListByParam($condition, array('ar.article_number', 'ck.keyword', 'aei.article_url'), array('article_number'));
            $xml .= "<RelatedArticle>";
            foreach ($relatedaArticles as $k => $items) {
                $keyword = $items['keyword'];
                $xml .= "<Link>";
                $xml .= "<RelatedArticleID>{$k}</RelatedArticleID>";
                $url = self::getArticleURL($items['article_url'], $aol_url, $keyword);
                $xml .= "<ArticleURL>{$url}</ArticleURL>";
                $xml .= "<LinkDescription>{$keyword}</LinkDescription>";
                $xml .= "</Link>";
            }
            $xml .= "</RelatedArticle>";
            /*$relatedaArticles = unserialize($item['related_articles']);
            if (!empty($relatedaArticles)) {
                $xml .= "<RelatedArticle>";
                foreach ($relatedaArticles as $k => $items) {
                    $keyword = Article::getArticleListByParam(array('article_number' => $k), array('keyword'), array(), true);
                    $xml .= "<Link>";
                    $xml .= "<RelatedArticleID>{$k}</RelatedArticleID>";
                    $url = self::getArticleURL($items['article_url'], $aol_url, $keyword);
                    $xml .= "<ArticleURL>{$url}</ArticleURL>";
                    $xml .= "<LinkDescription>{$keyword}</LinkDescription>";
                    $xml .= "</Link>";
                }
                $xml .= "</RelatedArticle>";
            }*/
            $xml .= "</Article>";
        }
        if (!empty($extra_ids)) {
            self::update(array('extra_info_id' => $extra_ids), 'is_load=1');
        }
        $xml .= "</InfinitenineArticles>";
        return $xml;
    }
    
    /**
     * @param array $p: condition array
     * @param array $sets
     * @return boolean: false or true
     */
    function update($p = array(), $sets)
    {
        global $conn, $feedback;
        //global $debugh;
        $conditions[] = "1=1";
        if (isset($p['extra_info_id']) && !empty($p['extra_info_id'])) {
            $extra_info_id = $p['extra_info_id'];
            if (is_array($extra_info_id))
                $conditions[] = "extra_info_id IN (" . implode(',', $extra_info_id) . ")";
            else if ($extra_info_id > 0)
                $conditions[] = "extra_info_id = {$extra_info_id}";
        }
        if (!empty($sets) && !empty($conditions)) {
            $sql = "UPDATE article_extra_info ";
            if (is_array($sets))  $sql .= "SET " . implode(", ", $sets) . " ";
            else if (is_string($sets)) $sql .= "SET " . $sets . " ";
            $sql .= "WHERE " . implode(" AND ", $conditions);
            //fwrite($debugh, $sql);
            $conn->Execute($sql);
            return true;
        } else {
            return false;
        }
    }

    function updateQA($p)
    {
        global $conn;
        $article_id = $p['article_id'];
        $info = $this->getInfoByArticleId($article_id);
        foreach ($p as $k => $v) {
            $info[$k] = $v;
        }
        if (!empty($info['extra_info_id'])) {
            $extra_info_id = $info['extra_info_id'];
            $q = 'UPDATE article_extra_info SET ';
            foreach ($info as $k => $value) {
                $sets[] = "{$k} = '{$value}'";
            }
            $q .= implode(", ", $sets);
            $q .= " WHERE extra_info_id='{$extra_info_id}'";
        } else {
            $extra_info_id = $conn->GenID('seq_article_extra_info_extra_info_id');
            $info['extra_info_id'] = $extra_info_id;
            $info['created_time'] = date("Y-m-d H:i:s");
            $fields = array_keys($info);
            $q = "INSERT INTO article_extra_info (" . implode(", ", $fields). ") ".
            "VALUES ('" . implode("', '", $info)."')";
        }
        $conn->Execute($q);
        return $extra_info_id;
    }

    function getArticleURL($input_url, $aol_url, $keyword)
    {
        /*if (empty($input_url))
        {
            $input_url = 'http://' . $aol_url;
            $keyword = preg_replace("/[ ]+/", "-", $keyword);
            $input_url .= $keyword;
        }*/
        $input_url = 'http://' . $aol_url;
        $keyword = preg_replace("/[\:]+/", " ", strtolower(trim($keyword)));
        $keyword = preg_replace("/[ ]+/", "-", strtolower(trim($keyword)));
        $input_url .= $keyword;
        return $input_url;
    }

    /**
     * @param array $p
     * @return array $result
     */
    function getArticlesByParam($p = array())
    {
        global $conn, $feedback;
        //global $debugh;
        $conditions =  array();
        if (isset($p['publish_end']) && !empty($p['publish_end'])) {
            $publish_end = $p['publish_end'];
        } else {
            $publish_end = date("Y-m-d H:i:s");
        }
        if (isset($p['publish_start']) && !empty($p['publish_start'])) {
            $publish_start = $p['publish_start'];
            if (isset($p['publish_end']) && !empty($p['publish_end'])) {
                $publish_end = $p['publish_end'];
            } else {
                $publish_end = date("Y-m-d H:i:s");
            }
            $conditions[] = "( aa.created_time >= '{$publish_start}' AND aa.created_time <= '{$publish_end}' )";
        }
        else
        {
            $conditions[] = "(aa.created_time <= '{$publish_end}' )";
        }

        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $campaign_id = $p['campaign_id'];
            if (is_array($campaign_id))
                $conditions[] = "aei.campaign_id IN (" . implode(',', $campaign_id) . ")";
            else if ($campaign_id > 0)
                $conditions[] = "aei.campaign_id = {$campaign_id}";
        }

        if (isset($p['article_status']) && !empty($p['article_status'])) {
            $article_status = $p['article_status'];
            if (is_array($article_status))
                $conditions[] = "ar.article_status IN ('" . implode("','", $article_status) . "')";
            else if (is_string($article_status) || is_numeric($article_status))
                $conditions[] = "ar.article_status = '{$article_status}'";
        }

        if (isset($p['or']) && !empty($p['or'])) {
            $h = $p['or'];
            if (isset($h['is_load']) && strlen($h['is_load']))
                $or_conditions[] = "aei.is_load={$h['is_load']}";
            if (isset($h['is_force_load']) && strlen($h['is_force_load']))
                $or_conditions[] = "aei.is_force_load={$h['is_force_load']}";
            if (!empty($or_conditions)) $conditions[] = "(". implode(" OR ", $or_conditions) . ")";
        }

        if (isset($p['!=']) && !empty($p['!='])) {
            $h = $p['!='];
            $temp_conditions = array();
            foreach ($h as $k => $v)
            {
                $temp_conditions[] = "{$k} != '{$v}'";
            }
            if (!empty($not_conditions)) $conditions[] = "(". implode(" AND ", $temp_conditions) . ")";
        }

        $sql  = "SELECT DISTINCT aei.* , ar.title, ar.html_title, ar.body, ar.richtext_body, ar.article_status, ck.keyword_category, ck.keyword_meta, ck.description_meta, ck.keyword ";
        $sql .= "FROM article_extra_info AS aei ";
        $sql .= "LEFT JOIN articles AS ar ON ar.article_id = aei.article_id ";
        $sql .= "LEFT JOIN article_action AS aa ON (ar.article_id = aa.article_id AND aa.status = 5 AND aa.new_status = 6 )";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.keyword_id ";
        $sql .= "WHERE 1=1 ";
        if (!empty($conditions)) $sql .= "AND " . implode(" AND ", $conditions);
        //fwrite($debugh, $sql);
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $id    = $rs->fields['extra_info_id'];
                $city = $rs->fields['city'];
                if (empty($city)) $city = 0;
                $state = $rs->fields['state'];
                if (empty($state)) $state  = 0;
                $country = $rs->fields['country'];
                if (empty($country)) $country  = 0;
                $result[$country][$state][$city][$id] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }
}//end class Preference
?>