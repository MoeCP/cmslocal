<?php
class ArticleTag {

    function save($arr) 
    {
        global $conn;
        
        if (isset($arr['_'])) unset($arr['_']);
        $keys = array_keys($arr);
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes($value);
        }
        $q = "REPLACE INTO `article_tags` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        return $conn->Execute($q);
    }

    function del($arr)
    {
        global $conn;
        if (isset($arr['_'])) unset($arr['_']);
        foreach ($arr as $k => $v) {
            $arr[$k] = addslashes(trim($v));
        }
        extract($arr);

        $condtions = array();
       if (isset($tag_id) && !empty($tag_id)) {
            if (is_array($tag_id)) {
                $condtions[] = "tag_id IN ('" . implode("', '", $tag_id) . "')";
            } else {
                $condtions[] = "tag_id='{$tag_id}'";
            }
        }
       if (isset($article_id) && !empty($article_id)) {
            if (is_array($tag_id)) {
                $condtions[] = "article_id IN ('" . implode("', '", $article_id) . "')";
            } else {
                $condtions[] = "article_id='{$article_id}'";
            }
        }
        if (isset($source) && !empty($source)) {
            $condtions[] = "source='{$source}'";
        }
        if (!empty($condtions)) {
            $sql = "DELETE FROM `article_tags` WHERE " . implode(" AND ", $condtions);
            $conn->Execute($sql);
            return true;
        } else {
            return false;
        }
    }

    function storeTags($article_id, $tag_ids, $source, $opt)
    {
        global $conn;
        $rows = array();
        if ($article_id > 0) {
            if (!empty($tag_ids)) {
                $conn->StartTrans();
                if ($opt == 'add') {
                    foreach ($tag_ids as $id) {
                        $rows[] = '(' . $id . ', ' . $article_id . ', ' . $source  .')';
                    }
                    $sql = "REPLACE INTO `article_tags` (`tag_id`, `article_id`,`source`) VALUES " . implode(",", $rows);
                } else if ($opt == 'del'){
                    $conditions = array('source=' . $source, 'tag_id IN (' . implode(',', $tag_ids). ')', 'article_id='.$article_id);
                    $sql = "DELETE FROM `article_tags` WHERE " . implode(" AND ", $conditions);
                }
                $conn->Execute($sql);
                $ok =$conn->CompleteTrans();
                if ($ok) return true;
            }
        }
        return false;
    }

    function getInfoByParam($param)
    {
        global $conn;
        foreach ($param as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $value) {
                    $v[$key] = addslashes(trim($value));
                }
            } else {
                $v = addslashes(trim($v));
            }
            $param[$k] = $v;
        }
        extract($param);
        $condtions = array();
       if (isset($tag_id) && !empty($tag_id)) {
            if (is_array($tag_id)) {
                $condtions[] = "tag_id IN ('" . implode("', '", $tag_id) . "')";
            } else {
                $condtions[] = "tag_id='{$tag_id}'";
            }
        }
       if (isset($article_id) && !empty($article_id)) {
            if (is_array($article_id)) {
                $condtions[] = "article_id IN ('" . implode("', '", $article_id) . "')";
            } else {
                $condtions[] = "article_id='{$article_id}'";
            }
        }
        if (isset($source) && !empty($source)) {
            $condtions[] = "source='{$source}'";
        }
        $sql = "SELECT * FROM `article_tags` ";
        if (!empty($condtions)) {
            $sql .= 'WHERE  ' . implode(" AND ", $condtions);
        }
        return $conn->GetAll($sql);
    }

    function getSourceByArticleId($article_id)
    {
        $rs = self::getCampaignSourceByArticleIds($article_id, 0, array('cc.source'));
         return isset($rs[0]) ? $rs[0] : null;
    }


    function getCampaignSourceByArticleIds($article_ids, $source = 0, $fields = null)
    {
        global $conn;
        $source = addslashes(trim($source));
        $conditons = array();
        if ($source > 0) {
            $conditons[] = 'cc.source=' . $source;
        }
        if (is_array($article_ids)) {
            foreach ($article_ids as $k => $v) {
                $article_ids[$k] = addslashes(trim($v));
            }
            $conditons[] = "ar.article_id IN ('" . implode("','", $article_ids) . "')";
        } else {
            $article_ids = addslashes(trim($article_ids));
            $conditons[] = "ar.article_id = '{$article_ids}'";
        }
        if (empty($fields)) {
            $fields[] = 'ar.article_id';
        }
        $sql ="SELECT " . implode(" AND ", $fields). " FROM articles AS ar ";
        $sql .= "LEFT JOIN campaign_keyword as ck on (ck.keyword_id= ar.keyword_Id) ";
        $sql .= "LEFT JOIN client_campaigns as cc on (cc.campaign_id= ck.campaign_id) ";
        $sql .= "WHERE " . implode(" AND ", $conditons);
        return $conn->GetCol($sql);
    }

    function getTagIdsByParams($param = array())
    {
        $result = self::getInfoByParam($param);
        $tag_ids = array();
        foreach ($result as $row) {
            $tag_ids[] = $row['tag_id'];
        }
        return $tag_ids;
    }

    function showTags4Article($article_id)
    {
        $source = self::getSourceByArticleId($article_id);
        $list = null;
        if ($source > 0) {
            $selected_tags = self::getTagIdsByParams(compact('source', 'article_id'));
            $list = DomainTag::getAllTagsBySource($source, $selected_tags);
        }
        return $list;
    }

    function getSelectedTagsByArticleId($article_id)
    {
        $result = self::getInfoByParam(array('article_id' => $article_id));
        //pr($result,true);
        return self::__getSelectedTags($result);
    }

    function getTagsByArticleId($article_id)
    {
        global $conn;
        $conditions = array();
        if (empty($article_id)) {
            return false;
        } else if (is_array($article_id)) {
            $article_id = array_unique($article_id);
            asort($article_id);
            $tmp = $article_id;
            if (count($article_id)  > 1) {
                $first_article_id = array_shift($tmp);
                $last_article_id = array_pop($tmp);
                $conditions[] = "(at.article_id >= '" . $first_article_id. "' AND at.article_id <= '" . $last_article_id . "') ";
            }
            $conditions[] = "at.article_id IN ('" . implode("', '", $article_id) . "')";
        } else {
            $article_id = addslashes(htmlspecialchars(trim($article_id)));
            $conditions[] = "at.article_id = '" . $article_id . "' ";
        }
        $sql = "SELECT DISTINCT at.* FROM `article_tags` AS at ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions) ;
        }
        $result = $conn->GetAll($sql);
        return self::__getSelectedTags($result);
    }

    function getTagsByCampaignId($campaign_id = null, $q)
    {
        global $conn;
        $sql  = "SELECT DISTINCT at.* FROM `article_tags` AS at ";
        $sql .= "\nLEFT JOIN articles as ar on ar.article_id=at.article_id ";
        $sql .= "\nLEFT JOIN campaign_keyword as ck on ck.keyword_id=ck.keyword_id ";
        $sql .= "\nLEFT JOIN users AS u ON (u.user_id = ar.creation_user_id) ";
        $sql .= "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ";
        $sql .= "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ";
        $sql .= "\nLEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ";
        $sql .= "\nLEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ";
        $sql .= $q;
        $sql .= "\n AND at.tag_id > 0 AND at.article_id > 0 ";
        $result  = $conn->GetAll($sql);
        return self::__getSelectedTags($result);
    }

    function getSelectedTagsForArticles($tags, $article_tags)
    {
        $result = array();
        foreach ($article_tags as $aid => $selected_tag) {
            foreach($tags as $tag_id => $tag) {
                if (in_array($tag_id, $selected_tag)) {
                    if (!isset($result[$aid])) $result[$aid] = array();
                    $result[$aid][$tag_id] = $tag['output_name'];
                }
            }
        }
        return $result;
    }

    function __getSelectedTags($tag_infos)
    {
        $result = $tags = array();
        if (!empty($tag_infos)) {
            foreach ($tag_infos as $row) {
                extract($row);
                if (!isset($arr[$source])) $arr[$source] = array();
                if (!isset($arr[$source][$article_id])) $arr[$source][$article_id] = array();
                $arr[$source][$article_id][] = $tag_id;
            }
            foreach ($arr as $source => $row) {
                if (!isset($tags[$source])) $tags[$source] = DomainTag::getAllTagsBySource($source);
                $result += self::getSelectedTagsForArticles($tags[$source], $row);
            }
        }
        return $result;
    }

    function showSelectedTags4Article($article_id)
    {
        $list = self::showTags4Article($article_id);
        $selected_tags = array();
        if (!empty($list)) {
            foreach ($list as $tag_id => $row) {
                if ($row['selected']) {
                    $selected_tags[$tag_id] = $row['output_name'];
                }
            }
        }
        return $selected_tags;
    }
}
?>
