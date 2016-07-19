<?php
/**
* Article Class（用户操作类）
*
* 本类是实现用户(admin,project manager, copy writer, editor)的添加，修改，删除和查找功能。
* This class file contain add,update,delete and search user's function 
*
* @global  string $conn
* @global  string $feadback
* @author  Leo.Liu  <leo.liuxl@gmail.com>
* @copyright Copyright &copy; 2006
* @access  public
*/

require_once CMS_INC_ROOT . "/article_action.class.php";
require_once CMS_INC_ROOT . "/Client.class.php";

class Article {

    /**
     * Add an article information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function add($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;

        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id == '') {
            $feedback = "Please Choose a keyword";
            return false;
        }
        $language = addslashes(htmlspecialchars(trim($p['language'])));
        if ($language == '') {
            $feedback = "Please enter the language of the article";
            return false;
        }
        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title == '') {
            $feedback = "Please provide the title of the article";
            return false;
        }

        // added by snug xu 2006-11-27 14:53 - START
        // changed paintext to richtext
        $richtext_body = addslashes(htmlspecialchars(trim($p['richtext_body'])));
        if ($richtext_body == '') {
            $feedback = "Please provides article";
            return false;
        }
        $richtext_update = " `richtext_body` = '{$richtext_body}', ";
        $body = change_richtxt_to_paintxt($richtext_body, ENT_QUOTES);
        // added by snug xu 2006-11-27 14:53 - END

        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if ($article_status == '') {
            $article_status = 0;
        }

        // added by snug xu 2007-07-10 18:56 - STARTED
        // add html title sql
        if (isset($p['html_title']))
        {
            $html_title      = addslashes(htmlspecialchars(trim($p['html_title'])));
            $html_title_qw =  "html_title = '" . $html_title."', ";

        } else {
            $html_title_qw = '';
        }
        // added by snug xu 2007-07-10 18:56 - FINISHED

        $conn->StartTrans();
        
        // added by snug xu 2007-06-22 15:25 - STARTED
        // when cp add/save article, record it's lastest updated time
        if (User::getRole() == 'copy writer')
        {
            $cp_qw = "cp_updated='" . date("Y-m-d H:i:s", time()) . "', ";
        } else {
            $cp_qw = '';
        }
        // added by snug xu 2007-06-22 15:25 - FINISHED
        $conn->Execute("UPDATE articles ".
                       "SET `creation_user_id` = '".User::getID()."', ".
                           "`creation_role` = '".User::getRole()."', ".
                           "`creation_date` = '".date('Y-m-d H:i:s', time())."', ".
                           "language = '".$language."', ".
                           "title = '".$title."', ".
                            // added by snug xu 2007-07-10 18:53 - STARTED
                            $html_title_qw . 
                            // added by snug xu 2007-07-10 18:53 - FINISHED
                            $richtext_update . 
                            $cp_qw . 
                           "body = '".$body."', ".
                           "article_status = '".$article_status."' ".
                       "WHERE keyword_id = '".$keyword_id."' ");
        // added by snug xu 2006-11-21 16:53 - START
        // initialize article action info
        self::getArticleActionInfo($action_info, $article_id);
        $action_info['title'] = $title;
        $action_info['new_status'] = $article_status;
        if (strcasecmp($action_info['status'], $article_status) != 0) 
        {
            ArticleAction::store($action_info);
        }
        // added by snug xu 2006-11-21 16:53 - END
        $ok = $conn->CompleteTrans();
        // added by snug xu 2007-06-28 12:43 - STARTED
        self::updateMetaInfo($p);
        // added by snug xu 2007-06-28 12:43 - FINISHED

        if ($ok) {
            $feedback = 'Success';
            if ($article_status == 0) $feedback = '';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            if ($article_status == 0) $feedback = '';
            return false;
        }

    }//end add()

    /**
     * Get article info by $article_id
     *
     * @param int $article_id
     *
     * @return boolean or an array containing all fields in tbl.articles
     */
    function getInfo($article_id, $is_contain_comment = false)
    {
        global $conn, $feedback;

        $article_id = addslashes(htmlspecialchars(trim($article_id)));
        if ($article_id == '') {
            $feedback = "Please Choose an article";
            return false;
        }

        $q = "SELECT ar.*, cc.campaign_name, cc.campaign_id, ck.copy_writer_id, ck.editor_id, ck.keyword, ck.article_type, ck.date_start, ck.date_end, ck.is_sent, ck.keyword_meta, ck.description_meta ".
             "FROM articles AS ar ".
             "LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ".
             "LEFT JOIN client_campaigns  AS cc ON (cc.campaign_id = ck.campaign_id) ".
             "WHERE ar.article_id = '".$article_id."'";
        $rs = &$conn->Execute($q);
        $ret = array();
        if ($rs) {
            if ($rs->fields['article_id'] != 0) {
                $ret = $rs->fields; // return an array
            }
            $rs->Close();

        }
        $ret['richtext_body'] = html_entity_decode($ret['richtext_body'], ENT_QUOTES);
        if (empty($ret) || $is_contain_comment == false) { // return false if article does not exist
            return $ret;
        }

        $q = "SELECT coa.*, u.user_name AS creator ".
             "FROM comments_on_articles AS coa ".
             "LEFT JOIN users AS u ON (u.user_id = coa.creation_user_id) ".
             "LEFT JOIN articles AS ar ON (ar.article_id = coa.article_id) ".
             "LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ".
             "WHERE coa.article_id = '".$article_id."' ORDER BY coa.version_number, coa.creation_date DESC";
        $rs = &$conn->Execute($q);
        if ($rs) {
            $result = array();
            while (!$rs->EOF) {
                $result[] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
            $ret['comment'] = $result;
        }
        return $ret;
    }//end getInfo()

    // added by snug xu 2007-03-06 14:14 - STARTED
    function getInfoByKeywordID($keyword_id) 
    {
        global $conn;
    	$sql = "select ck.*, ar.* from campaign_keyword as ck left join articles as ar on ar.keyword_id=ck.keyword_id where ck.keyword_id = {$keyword_id}";
        $rs = &$conn->Execute($sql);
        $info = array();
        if ($rs) {
            if ($rs->fields['keyword_id'] != 0) {
                $info = $rs->fields; // return an array
            }
            $rs->Close();
        }
        return $info;
    }
    // added by snug xu 2007-03-06 14:14 - FINISHED

    /**
     * Get article info by article_id & word counts parameters
     *
     * If offset is non-negative, the sequence will start at that offset in the array. 
     * If offset is negative, the sequence will start that far from the end of the array. 
     * If length is given and is positive, then the sequence will have that many elements in it. 
     * If length is given and is negative then the sequence will stop that many elements from the end of the array. 
     * If it is omitted, then the sequence will have everything from offset up until the end of the array. 
     *
     * 如果 offset 非负，则序列将从 array 中的此偏移量开始。如果 offset 为负，则序列将从 array 中距离末端这么远的地方开始。 
     * 如果给出了 length 并且为正，则序列中将具有这么多的单元。如果给出了 length 并且为负，则序列将终止在距离数组末端这么远的地方。
     * 如果省略，则序列将从 offset 开始一直到 array 的末端。
     *
     * @param array $p a list of article ID
     * @param int $max_words the word count which function will return
     * @param int $offset
     * @param char $where_status
     *
     * @return boolean or an array containing all fields in tbl.articles
     */
    function getArticleByParams($p = array(), $max_words = 20, $offset = 0, $where_status = 1)
    {
        global $conn, $feedback;

        $where_status = addslashes(htmlspecialchars(trim($where_status)));
        if ($where_status == '') {
            $where_status = 1;
        }
        $qw = "WHERE article_status = '".$where_status."' ";

        if (!empty($p['article_id'])) {
            $article_id_str = addslashes(htmlspecialchars(trim(implode(',', $p['article_id']))));
            $qw .= "AND article_id IN (".$article_id_str.") ";
        }


        $q = "SELECT article_id, body FROM articles ".$qw;
// echo "debut ".$q; 
        $rs = &$conn->Execute($q) or die(mysql_error());
// echo " total number of result ".mysql_num_rows($rs);
        $ret = array();
        if ($rs) {
            while (!$rs->EOF) {
                // modified by snug xu 2006-11-18 23:47  -- START
                $body = $rs->fields['body'];
                // modified by snug xu 2007-08-19 18:50 - START
                // get three sentences to google clean check
                // get first sentence to google clean check
                $sentences = preg_split("/[\.|\?|!|;]/", $body, -1, PREG_SPLIT_NO_EMPTY);
                $len = count($sentences);
                $article_id = $rs->fields['article_id'];
                $ret[$article_id][] = self::_trunc($body, $max_words, $offset);//20 is the $max_words which function will return
                // get last sentence to google clean check
                $k = 1;
                for ($i=($len-1); $i > $k; $i--) {
                    $value = $sentences[$i];
                    if (str_word_count($value) > 5 && $i > 0) {
                        //$ret[$article_id][] = $value;
                        $ret[$article_id][] = self::_trunc($value, $max_words, $offset);
                        break;
                    }
                }
                // get rand sentence to google clean check
                if ($k < $i) {
                    $j = rand($k, $i);
                    $value = $sentences[$j];
                    while (str_word_count($value) <= 5) {
                        $j       = rand($k, $i);
                        $value = $sentences[$j];
                    }
                    //$ret[$article_id][] = $value;
                    $ret[$article_id][] = self::_trunc($value, $max_words, $offset);
                }
                // modified by snug xu 2007-08-19 18:50 - FINISHED
                // modified by snug xu 2006-11-18 23:47  -- END
                // Truncate String by Words. This will take a phrase and truncate it at the word level
                $rs->MoveNext();
            }

            $rs->Close();
        }

        return $ret;
    }//end getArticleByParams()

    /** 
     * get article list by param
     * @param array $p, include article conditons
     * @param array $fields, field name for artices/campaign_keyword table, (format: table.field)
     * @param array $keys: return keys, $return[$keys[0]][$keys[2]][$keys[3]]...
     * @param array $is_sigle_row: return single row this article list
     * @return array, article list
     */
    function getArticleListByParam($p = array(), $fields = array(), $keys = array(), $is_single_row = false)
    {
        global $conn, $feedback;
        // global $debugh;
        $conditions = array();
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $campaign_id = $p['campaign_id'];
            if (is_array($campaign_id))
                $conditions[] = "ck.campaign_id IN (" . implode(', ', $campaign_id) . ")";
            else if ($campaign_id > 0)
                $conditions[] = "ck.campaign_id = {$campaign_id}";
            unset($p['campaign_id']);
        }

        if (isset($p['keyword_category']) && !empty($p['keyword_category'])) {
            $keyword_category = $p['keyword_category'];
            if (is_array($keyword_category))
                $conditions[] = "ck.keyword_category IN (" . implode(', ', $keyword_category) . ")";
            else if ($keyword_category > 0)
                $conditions[] = "ck.keyword_category = {$keyword_category}";
            unset($p['keyword_category']);
        }

        if (isset($p['article_id']) && !empty($p['article_id'])) {
            $article_id = $p['article_id'];
            if (is_array($article_id))
                $conditions[] = "ar.article_id IN (" . implode(', ', $article_id) . ")";
            else if ($article_id > 0)
                $conditions[] = "ar.article_id = {$article_id}";
            unset($p['article_id']);
        }

        if (isset($p['article_status']) && !empty($p['article_status'])) {
            $article_id = $p['article_status'];
            if (is_array($article_status))
                $conditions[] = "ar.article_status IN ('" . implode("', '", $article_id) . "')";
            else if (is_string($article_status))
                $conditions[] = "ar.article_status = '{$article_status}'";
            unset($p['article_status']);
        }

        if (isset($p['article_number']) && !empty($p['article_number'])) {
            $article_number = $p['article_number'];
            if (is_array($article_number))
                $conditions[] = "ar.article_number IN ('" . implode("','", $article_number) . "')";
            else if (is_string($article_number))
                $conditions[] = "ar.article_number = '{$article_number}'";
            unset($p['article_number']);
        }

        if (isset($p['not']) && !empty($p['not'])) {
            $not = $p['not'];
            foreach ($not as $k => $value) {
                if (isset($value) && !empty($value)) {
                    if (is_array($value))
                        $conditions[] = "{$k} NOT IN ('" . implode("','", $value) . "')";
                    else if (is_numeric($value))
                        $conditions[] = "{$k} != {$value}";
                    else if (is_string($value))
                        $conditions[] = "{$k} != '{$value}'";
                }
            }
            unset($p['not']);
        }

        // get limit info
        $limit = '';
        if (isset($p['total']) && !empty($p['total'])) {
            $total = $p['total'];
            if ($total > 0) {
                if (isset($p['start'])) {
                    $start = empty($p['start']) ? 0 : $p['start'];
                    $limit = " LIMIT {$start}, {$total}";
                    unset($p['start']);
                    unset($p['total']);
                }
            }
        }
        
        if (!empty($p)) {
            foreach ($p as $k=> $value) {
                if (isset($value) && !empty($value)) {
                    if (is_array($value))
                        $conditions[] = "{$k} IN ('" . implode("','", $value) . "')";
                    else if (is_numeric($value))
                        $conditions[] = "{$k} = {$value}";
                    else if (is_string($value))
                        $conditions[] = "{$k} = '{$value}'";
                }
            }
        }
        
        $query = empty($fields) ? "ar.*, ck.*" : implode(", ", $fields);

        $sql = "SELECT {$query} ";
        $sql .= "FROM articles AS ar ";
        $sql .= "LEFT JOIN campaign_keyword as ck on ar.keyword_id=ck.keyword_id ";
        $sql .= "LEFT JOIN article_extra_info as aei on aei.article_id=ar.article_id ";
        $sql .= "where 1=1 ";
        if (!empty($conditions)) $sql .= "AND " . implode(" AND ", $conditions);
        $sql .= $limit;
        //fwrite($debugh, $sql . "\n");
        $rs = &$conn->Execute($sql);
        $ret = array();
        if ($rs) {
            while (!$rs->EOF) {
                $hash = count($fields) == 1 ? $rs->fields[$fields[0]] : $rs->fields;
                if (!empty($keys)) {
                    $total_keys = count($keys);
                    $ret[$hash[$keys[$total_keys - 1]]] = $hash;
                    for ($i = $total_keys - 2; $i >= 0; $i++) {
                        $v = $keys[$i];
                        $ret[$hash[$v]] = $ret;
                    }
                } else {
                    $ret[] = $hash;
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        //fwrite($debugh, var_export($ret, true) . "\n");
        return $is_single_row? $ret[0] : $ret;
    }

    /**
     * Truncate String by Words. This will take a phrase and truncate it at the word
     *
     * If offset is non-negative, the sequence will start at that offset in the array. 
     * If offset is negative, the sequence will start that far from the end of the array. 
     * If length is given and is positive, then the sequence will have that many elements in it. 
     * If length is given and is negative then the sequence will stop that many elements from the end of the array. 
     * If it is omitted, then the sequence will have everything from offset up until the end of the array. 
     *
     * @param array $phrase the article which will be deal with by trunc function
     * @param int $max_words the word count which function will return
     * @param int $offset
     *
     * @return a string
     */
    function _trunc($phrase, $max_words, $offset = 0)
    {
        if (trim($phrase) == '') return "";

        $phrase_array = explode(' ',$phrase);
        if(count($phrase_array) > $max_words && $max_words > 0)
            $phrase = implode(' ',array_slice($phrase_array, $offset, $max_words));  
        return $phrase;
    }

    /**
     * this you can set article status that will sign a sign for an article
     *
     * @param int $article_id the article id
     * @param char $status
     * @param char $old_status
     * @param char $copy_writer_id if $copy_writer_id == 0 ignore it 
     *
     * @return boolean
     */
    function setArticleStatus($article_id, $status, $old_status = 1, $copy_writer_id = 0)
    {
        global $conn, $feedback;

        $action_info = array();

        $article_id = addslashes(htmlspecialchars(trim($article_id)));
        if ($article_id == '') {
            $feedback = "Please choose an article";
            return false;
        }

        $status = addslashes(htmlspecialchars(trim($status)));
        if ($status == '') {
            $feedback = "Please set a article status";
            return false;
        }

        $old_status = addslashes(htmlspecialchars(trim($old_status)));
        if ($old_status == '') 
		{
            $old_status = 1;
        }
		//START:Added By Snug 15:13 2006-8-8
		if ($status=='1gc')
		{
			$set_google_approved_time = ", `google_approved_time`=NOW() ";
		} else {
			$set_google_approved_time = '';
		}
		//END ADDED

        // added by snug xu 2007-11-04 16:18 - STARTED
        // if $old_status is client approval or publish, then check whether allowed change status or not
        if ($old_status == 5 || $old_status == 6) {
           if ($status == 6 && $old_status == 5 || $old_status == $status) {
                // do nothing
           } else {
               $feedback = "The article was finished, you can't change them to other article status.";
               return false;
           }
        }// end

        // added by snug xu 2006-11-21 19:17 - START
        // initialize article action info
        self::getArticleActionInfo($action_info, $article_id);
        $action_info['new_status'] = $status;
        // if artile status is changes, store the article action log
        
        if (strcasecmp($action_info['status'], $status) != 0 || $copy_writer_id > 0 && $action_info['copy_writer_id'] != $copy_writer_id) 
        {
            if ($action_info['copy_writer_id'] != $copy_writer_id && $copy_writer_id > 0) 
                $action_info['new_copy_writer_id'] = $copy_writer_id;
            ArticleAction::store($action_info);
        }
        // added by snug xu 2006-11-21 19:17 - END

        $qw = "AND article_status = '".$old_status."'";
		$sql = "UPDATE articles ".
                  "SET `article_status` = '".$status."' ".
				  //START:Added By Snug 15:13 2006-8-8
				  $set_google_approved_time.
				  //END ADDED
                  "WHERE article_id = '".$article_id."' ".$qw;
        $conn->Execute($sql);

        if ($conn->Affected_Rows()) 
		{
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//setArticleStatus()


	function setGoogleApprovedTime($article_id, $google_approved_time)
    {
        global $conn, $feedback;

        $article_id = addslashes(htmlspecialchars(trim($article_id)));
        if ($article_id == '') 
		{
            $feedback = "Please choose an article";
            return false;
        }

        $google_approved_time = addslashes(htmlspecialchars(trim($google_approved_time)));
        if (strlen($google_approved_time) == 0) 
		{
            $feedback = "Please set google approved time first";
            return false;
        }

        $conn->Execute("UPDATE articles ".
                       "SET `google_approved_time` = '".$google_approved_time."' ".
                       "WHERE article_id = '".$article_id."' ");
        if ($conn->Affected_Rows() == 1) 
		{
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//setGoogleApprovedTime()

	/**
     * this you can set Checking URL that let url know the duplicated article
     *
     * @param int $article_id the article id
     * @param char $status
     * @param char $url
     * @param char $where_status
     *
     * @return a string
     */
    function setCheckingURL($article_id, $status, $url, $where_status = 1)
    {
        global $conn, $feedback;

        $article_id = addslashes(htmlspecialchars(trim($article_id)));
        if ($article_id == '') {
            $feedback = "Please choose an article";
            return false;
        }
        $status = addslashes(htmlspecialchars(trim($status)));
        if ($status == '') {
            $feedback = "Please set a article status";
            return false;
        }

		$url = addslashes(htmlspecialchars(trim($url)));
        if ($url == '') {
            $feedback = "Please set a article status";
            return false;
        }

        $where_status = addslashes(htmlspecialchars(trim($where_status)));
        if ($where_status == '') {
            $where_status = 1;
        }

        // added by snug xu 2006-11-21 19:17 - START
        // initialize article action info
        self::getArticleActionInfo($action_info, $article_id);
        $action_info['new_status'] = $status;
        // if artile status is changes, store the article action log
        if (strcasecmp($action_info['status'], $status) != 0) 
        {
            ArticleAction::store($action_info);
        }
        // added by snug xu 2006-11-21 19:17 - END

        $qw = "AND article_status = '".$where_status."'";

		$sql = "UPDATE articles ".
                       "SET `article_status` = '".$status."' ".
                       ", `checking_url` = '".$url."' ".
                       "WHERE article_id = '".$article_id."' ".$qw;
        $conn->Execute( $sql );

        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//setCheckingURL()

    function replaceUserArticleTemp($body, $article_id)
    {
        global $conn;
        $sql = "REPLACE INTO `user_article_temp` (`user_id`, `article_body`, `article_id`) VALUES ('" . User::getID() . "', '{$body}',  '{$article_id}')";
        $conn->Execute($sql);
        $sql = "select uat.article_body from  `user_article_temp` as uat where uat.user_id='" . User::getID() . "'";
        $rs = &$conn->Execute($sql);
        if ($rs)
        {         
            $article_body = $rs->fields['article_body'];
            $rs->Close();
            return $article_body;
        } else {
            return false;
        }
    }

     /**
     * Set article's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setInfo($p = array())
    {
        global $conn, $feedback, $handle;
        
        $action_info = array();
        //global $g_tag;

        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id == '') {
            $feedback = "Please Choose a article";
            return false;
        }
         
        // added by snug xu 2006-11-21 19:17 - START
        // initialize article action info
        self::getArticleActionInfo($action_info, $article_id);
        // added by snug xu 2006-11-21 19:17 - END

        /*
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id == '') {
            $feedback = "Please Choose a keyword";
            return false;
        }
        */
        $language = addslashes(htmlspecialchars(trim($p['language'])));
        if ($language == '') {
            $feedback = "Please enter the language of the article";
            return false;
        }
        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title == '') {
            $feedback = "Please provide the title of the article";
            return false;
        }
        
        // added by snug xu 2007-07-10 18:56 - STARTED
        // add html title sql
        if (isset($p['html_title']))
        {
            $html_title      = addslashes(htmlspecialchars(trim($p['html_title'])));
            $html_title_qw =  "html_title = '" . $html_title."', ";

        } else {
            $html_title_qw = '';
        }
        // added by snug xu 2007-07-10 18:56 - FINISHED

        // added by snug xu 2007-03-05 9:33 - STARTED
        // if there is no rich text body, and only have pain text content
        // get pain text content fields from $p['body']
        $text_body = addslashes(htmlspecialchars(trim($p['body'])));
        if (strlen($text_body) && strlen($body) == 0) {
        	$body = $text_body;
        }
        // added by snug xu 2007-03-05 9:33 - FINISHED

        $article_info = self::getInfo($article_id, false);

        // modified by snug xu 2007-09-18 16:52 - START
        // changed paintext to richtext
        $richtext_body = addslashes(htmlspecialchars(trim($p['richtext_body'])));
        //        $richtext_body = change_special_quot($richtext_body); 
         if (User::getRole() == 'copy writer' && $article_info['article_status'] == 0)
        {
             $richtext_body = nl2br($body);
        } else {
            if(strlen($richtext_body)) {
                $body = change_richtxt_to_paintxt($richtext_body, ENT_QUOTES);
            }
        }
         $richtext_update = " `richtext_body` = '{$richtext_body}', ";
        // modified by snug xu 2007-09-18 16:52 - END

        if (strlen($richtext_body) == 0 && strlen($text_body) == 0) {
        	$feedback = "Please provide the body of the article";
            return false;
        }

       

        // added by snug xu 2006-11-27 15:06 - START
        if (strlen($body))
        {
            $body = addslashes(htmlspecialchars(trim($article_info['body'])));
        }
        // added by snug xu 2006-11-27 15:06 - END

        $new_body = self::replaceUserArticleTemp($body, $article_id);

        //if (User::getPermission() != 1) {
        if ((User::getPermission() == 1 && $article_info['copy_writer_id'] == User::getID()) || User::getPermission() > 2) {
            //do nothing;
        } else {
            $feedback = "You cann't execute this operation";
            return false;
        }

        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if ($article_status == '') {
            $article_status = 0;
        }

        $qu = "";
        require_once CMS_INC_ROOT.'/Campaign.class.php';
        $keyword_info = Campaign::getKeywordInfo($article_info['keyword_id']);
        //print_r($keyword_info);
        if (user_is_loggedin() && (User::getID() == $keyword_info['copy_writer_id'] || User::getID() == $keyword_info['editor_id'] || User::getName() == 'admin')) {
            $qu .= "article_status = '".$article_status."', ";
        }

        // added by snug xu 14:19 2006-11-21 - START
        // initialize the article action info
        $action_info['title'] = $title;
        $action_info['new_status'] = $p['article_status'];
        // added by snug xu 14:19 2006-11-21 - END

        $conn->StartTrans();
//        $old_count = count_chars($article_info['body']);
//        $new_count = count_chars($new_body);
//        $old_len = array_sum($old_count);
//        $new_len = array_sum($new_count);

        $new_len = strlen($new_body);
        $old_len   = strlen($article_info['body']);

        if ($p['action'] != 'temp' && $old_len != $new_len)
        {
            if (($article_info['article_status'] == 2 || $article_info['article_status'] == '1gd' || $article_info['article_status'] == 0)) {
                $version_history_id = $conn->GenID('seq_articles_version_history_version_history_id');
                $q = "INSERT INTO articles_version_history (`version_history_id`, `article_id`, `keyword_id`, ".
                                           "`creation_user_id`, `creation_role`, ".
                                           "`creation_date`, `language`, `title`, `body`, `richtext_body`, ".
                                           "`article_status`, `approval_date`, version_number) ".
                     "VALUES ('" . $version_history_id . "', " .
                             "'" . $article_info['article_id'] . "', " .
                             "'" . $article_info['keyword_id']."', ".
                             "'" . $article_info['creation_user_id'] . "', " .
                             "'" . $article_info['creation_role'] . "', " .
                             "'" . $article_info['creation_date'] . "', " .
                             "'" . $article_info['language'] . "', " .
                             "'" . addslashes($article_info['title']) . "', "  .
                             "'" . addslashes($article_info['body']) . "', " .
                             "'" . addslashes($article_info['richtext_body']) . "', ".
                             "'" . $article_info['article_status']."', ".
                             "'" . $article_info['approval_date']."', ".
                             "'" . $article_info['current_version_number'] . "')";
                $conn->Execute($q);
                $vertion = $article_info['current_version_number'] + 0.1;
                $qu .= "`current_version_number` = '" . $vertion . "', ";
                $qu .=  "`creation_date` = '" . date('Y-m-d H:i:s', time()) . "', ";

                // added by snug xu 14:19 2006-11-21 - START
                // set the article action new version
                $action_info['new_version'] = $vertion;
                // added by snug xu 14:19 2006-11-21 - END
            } else {
                //do nothing;
            }
        }

        $body = preg_replace("/[\r\n]{3,}/i", "\r\n\r\n", $body);
        // added by snug xu 2007-06-22 15:25 - STARTED
        // when cp add/save article, record it's lastest updated time
        if (User::getRole() == 'copy writer')
        {
            $cp_qw = "cp_updated='" . date("Y-m-d H:i:s", time()) . "', ";
        } else {
            $cp_qw = '';
        }
        // added by snug xu 2007-06-22 15:25 - FINISHED

        $sql = "UPDATE articles ".
                       "SET `creation_user_id` = '" . User::getID() . "', " .
                           "`creation_role` = '" . User::getRole() . "', " .
                           "language = '" . $language."', ".
                          // added by snug xu 2007-07-10 18:53 - STARTED
                            $html_title_qw . 
                          // added by snug xu 2007-07-10 18:53 - FINISHED
                           "body = '" . $body . "', " . $qu.
                           $richtext_update . 
                           $cp_qw . 
                           "title = '" . $title . "' ".
                       "WHERE article_id = '" . $article_id . "' ";
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            // added by snug xu 2007-06-28 12:43 - STARTED
            self::updateMetaInfo($p);
            // added by snug xu 2007-06-28 12:43 - FINISED
            // added by snug xu 2006-11-21 19:25 - START
            // if version of article or status of article is changes, store the article action log
            if (strcasecmp($action_info['status'], $action_info['new_status']) != 0 || $action_info['new_version'] != $action_info['version']) 
            {
                ArticleAction::store($action_info);
            }
            // added by snug xu 2006-11-21 19:25 - END
            $feedback = 'Success';
            if ($article_status == 0) $feedback = '';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            if ($article_status == 0) $feedback = '';
            return false;
        }
        
    }//end setInfo()

    /**
     * Editor's monthly working report,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function editorWorkingReport($p = array())
    {
        global $g_pager_params, $conn, $feedback;
        if (isset($p['start_date']) && !empty($p['start_date']))
        {
            $start = $p['start_date'] . ' 00:00:00';
        } else {
            $start = date("Y-m-") . '01' . ' 00:00:00';
        }
        if (isset($p['end_date']) && !empty($p['end_date']))
        {
            $end = $p['end_date'] . " 00:00:00";
        } else {
            $end = date("Y-m-d H:i:s");
        }
        $conditions[] = '1=1';
        $conditions[] = "(aa.created_time >= '{$start}' AND aa.created_time <= '{$end}')";
        $conditions[] = "aa.status != aa.new_status";
        $conditions[] = "aa.new_status = '4'";
        $conditions[] = "(ar.article_status = '4' or ar.article_status = '5' or ar.article_status = '6' or ar.article_status ='3')";
        $tables[] = "FROM users as u ";
        $tables[] = "LEFT JOIN article_action AS aa ON aa.opt_id = u.user_id ";
        $tables[] = "LEFT JOIN articles AS ar ON ar.article_id = aa.article_id ";
        $group = "GROUP BY u.user_id ";
        $sql  = "SELECT COUNT(DISTINCT u.user_id) as count ";
        $sql .= $tables[0];
        $qw  = " AND u.status = 'A'";
        $qw .= " AND (u.role = 'admin' or u.role='editor' or u.role='project manager') ";
        $sql .= 'WHERE 1=1 ';
        $sql .= $qw;
        $rs   = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }
        $perpage = 50;
        if (trim($p['perPage']) > 0) {
            $perpage = $p['perPage'];
        }
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $sql  = "SELECT u.* ";
        $sql .= $tables[0];
        $sql .= 'WHERE 1=1 ';
        $sql .= $qw;
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
             $users = $result = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $result[$user_id] = $rs->fields;
                $users[] = $user_id;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        if (!empty($users))
        {
            $qw = " AND u.user_id in ('" . implode("', '", $users). "') ";
        } else {
            $qw = '';
        }
        $query = "u.user_id, COUNT(DISTINCT ar.article_id) AS total_actually";
        $sql  = "SELECT {$query} ";
        $sql .= implode(" ", $tables);
        $sql .= 'WHERE ' . implode(" AND ", $conditions) . ' ';
        $sql .=  $qw ;
        $sql .= $group;
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $k = $rs->fields['user_id'];
                if ($k > 0)
                {
                    $result[$k]['total_actually'] = $rs->fields['total_actually'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        if (!empty($users)) {
            $qw = " AND ck.editor_id in ('" . implode("', '", $users). "') ";
        }
        else {
            $qw = '';
        }
        $sql  = "SELECT ck.editor_id, count(ar.article_id) as total_assigned ";
        $sql .= "FROM articles as ar ";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON  ck.keyword_id=ar.keyword_id ";
        $sql .= "WHERE ck.date_assigned>='{$start}' AND ck.date_assigned<='{$end}' ";
        $sql .= $qw;
        $sql .= "GROUP BY ck.editor_id ";
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $k = $rs->fields['editor_id'];
                if ($k > 0)
                {
                    $result[$k]['total_assigned'] = $rs->fields['total_assigned'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);
    }

    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function search($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "WHERE 1 ";

        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id != '') {
            $q .= "AND ar.article_id = '".$article_id."' ";
        }

        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND ar.keyword_id = '".$keyword_id."' ";
        }

        $creation_role = addslashes(htmlspecialchars(trim($p['creation_role'])));
        if ($creation_role != '') {
            $q .= "AND ar.creation_role LIKE '%".$creation_role."%' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND ar.creation_user_id = '".$creation_user_id."' ";
        }

        $language = addslashes(htmlspecialchars(trim($p['language'])));
        if ($language != '') {
            $q .= "AND ar.language = '".$language."' ";
        }
        
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status)) {
            $q .= "AND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                $q .= "AND ar.article_status = '".$article_status."' ";
            }
        }

        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title != '') {
            $q .= "AND ar.title LIKE '%".$title."%' ";
        }
        $body = addslashes(htmlspecialchars(trim($p['body'])));
        if ($body != '') {
            $q .= "AND ar.body LIKE '%".$body."%' ";
        }

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "AND ck.article_type = '".$article_type."' ";
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(ar.title, ar.body, ar.current_version_number, ck.keyword, ck.article_type, ck.keyword_description)")." ";
            }
        }

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= "AND ck.copy_writer_id = '".User::getID()."'";
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ck.editor_id = u.user_id) ";
                $q .= "AND ck.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }

        $rs = &$conn->Execute("SELECT COUNT(ar.article_id) AS count FROM articles AS ar ".
                              "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
                              "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
                              "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ".$q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($p['perPage']) > 0) {
            $perpage = $p['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT ar.*, ck.keyword, ck.article_type, ck.keyword_description, ck.date_start, ck.date_end, cc.campaign_name, u.user_name AS creator, uc.user_name as copywriter ".
             "FROM articles AS ar ".
             "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
             "LEFT JOIN users AS u ON (u.user_id = ar.creation_user_id) ".
             "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ".$q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);

    }//end search()

	// added by snug xu 2007-05-28 10:28 - STARED
    /**
     * get list result by parameters 
     */
    function getList($p)
	{
		global $conn, $feedback;
		// initialized values - START
        // sql filter conditon
        $condition = array();
        // get article status
        $article_status = $p['article_status'];
        if ($article_status > 0)
            $condition[] = "ar.article_status='{$article_status}'";
        
        // get editor id
        $editor_id = $p['editor_id'];
        if ($editor_id > 0)
            $condition[] = "ck.editor_id={$editor_id}";

        // extra condition
        $where = trim($p['where']);
        if (isset($p['where']) && strlen($where))
            $condition[] = $where;
        // query fields
        if (isset($p['columns']) && strlen(trim($p['columns'])))
            $columns = $p['columns'];
        else 
            $columns = " * ";
        // query group by
        if (isset($p['group_by']) && strlen(trim($p['group_by'])))
            $group_by = "GROUP BY " . $p['group_by'];
        else 
            $group_by = "";
        // type is string
        // return array index
        if (isset($p['index']) && strlen(trim($p['index'])))
            $index = trim($p['index']);
        // type is string, sperated by ";"
        // return fields
        if (isset($p['fields']) && strlen(trim($p['fields'])))
            $fields = explode(";", trim($p['fields']));
        else 
            $fields = array();
		$sql = '';
		// initialized values - FINISHED
		$sql = "SELECT {$columns} " . 
              "FROM articles AS ar " . 
              "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id=ar.keyword_id " . 
              "LEFT JOIN client_campaigns AS cc ON cc.campaign_id=ck.campaign_id " . 
              "LEFT JOIN users AS u ON u.user_id=ck.copy_writer_id " . 
              "WHERE 1=1 ";
        $where = count($condition) ? " AND " . implode(" AND ", $condition) : '';
        $sql .= $where;
        $sql .= $group_by;
		$rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            $i = 0;
            while (!$rs->EOF) 
			{
                if (strlen($index))
				    $key = $rs->fields[$index];
                else
                    $key = $i;
                switch (count($fields))
                {
                 case 0:
                     $result[$key] = $rs->fields;
                     break;
                 case 1:
                     $result[$key] = $rs->fields[$fields[0]];
                     break;
                 default:
                     foreach($fields as $key => $val)
                     {
                        $result[$key][$val] = $rs->fields[$val];
                     }
                     break;
                }
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
			return $result;
        }
		return false;
	}
    // added by snug xu 2007-05-28 10:28 - FINISHED

    function getAllGoogleApprovedArticle($user_id, $now = '', $is_all=true, $is_pagination=false)
    {
        global $conn, $g_pager_params;
		
		$current_month = mysql_escape_string(htmlspecialchars(trim( $now ) ) );
        if (strlen(trim($current_month)) == 0) {
            $now = time();
			$current_month = date("Ym");
        } else {
            $now = changeTimeFormatToTimestamp($current_month); 
		}
		$user_id = mysql_escape_string(htmlspecialchars(trim( $user_id )));
        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\nAND u.status != 'D' ";
        $qw .= "\nAND u.role = 'copy writer' ";
        $qw .= "\nAND u.user_id = {$user_id} ";
        $param['now']         = $now;
        $param['qw_where'] = $qw;
        $param['user_id']     = $user_id;
        $param['is_all']        = $is_all;
        $param['type']         = 'invoice';
        $sqls = User::getCPAccountingConditionOrSql($param);

        // sql where part
        $where   = $sqls['where'];
        $orderby = $sqls['orderby'];
        $from     = $sqls['from'];
        $select  = $sqls['select'];
        $q = $select . $from . $where . $orderby;

		// added by snug xu 2006-10-27  19:36 - START
		if ($is_pagination)
		{
			// pagination - START
			$sql  = "SELECT COUNT(DISTINCT ar.article_id) AS count ".
                    $from . $where;
            //*********
           // debug($q);
            //*********

			$rs = &$conn->Execute($sql);

			if ($rs) {
				$count = $rs->fields['count'];
				$rs->Close();
			}

			if ($count == 0 || !isset($count)) {
				//$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
				return false;
			}

			$perpage = 50;
			if (trim($p['perPage']) > 0) {
				$perpage = $p['perPage'];
			}
			require_once 'Pager/Pager.php';
			$params = array(
				'perPage'    => $perpage,
				'totalItems' => $count
			);
			$pager = &Pager::factory(array_merge($g_pager_params, $params));
			list($from, $to) = $pager->getOffsetByPageId();
			$rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
			// pagination - FINISHED
		} else {
			$rs = &$conn->Execute($q);
		}
        // modified by snug xu 2007-05-08 12:17 - FINISHED
		// added by snug xu 2006-10-27 19:36 - FINISHED
        if ($rs) {
            $articles = array();
            while (!$rs->EOF) {
                // added by snug xu 2007-05-17 14:07 - STARTED
                // get type cost
                // cost per article in article_cost table
                $cost_per_article = $rs->fields['cost_per_article'];
                // cost per article in article_cost_history table
                $ach_per_article = $rs->fields['ach_per_article'];
                // cost per article in article_type table
                $type_cost     = $rs->fields['type_cost'];
                $cost_per_article = $ach_per_article > 0 ? $ach_per_article : $cost_per_article;
                $cost_per_article = $cost_per_article > 0 ? $cost_per_article : $type_cost;
                $rs->fields['cost_per_article'] = $cost_per_article;
                // added by snug xu 2007-05-17 14:07 - FINISHED
                $articles[] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
		if ($is_pagination) {
			return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $articles);
		} else {
			 return $articles;
		}

        return null;
    }// end getAllGoogleApprovedArticle()

    function getWeekArticleReportByStatus($param = array())
    {
        global $conn, $feedback;
        $conditions[] = "1=1";
        if (isset($param['status']))
        {
            $status = $param['status'];
            if (is_string($status)) 
            {
                $conditions[] = "ar.article_status='{$status}'"; 
            }
            else if (is_array($status))
            {
                $conditions[] = "ar.article_status IN ('" . implode("', '", $status). "')"; 
            }
        }
        if (isset($param['now']))
        {
            $time = $param['now'];
        } else {
            $time = time();
        }
        $start = date("Y-m-d H:i:s", strtotime("-1 week", $time));
        $end   = date("Y-m-d H:i:s", $time);
        $conditions[] = "(ar.approval_date >= '{$start}' AND ar.approval_date <= '{$end}')";
        $sql  = "SELECT ar.article_id, ar.title, ar.approval_date, ck.keyword_id, cc.campaign_name ";
        $sql .= "FROM articles AS ar ";
        $sql .= "LEFT JOIN campaign_keyword as ck ON ck.keyword_id = ar.keyword_id ";
        $sql .= "LEFT JOIN client_campaigns as cc ON ck.campaign_id = cc.campaign_id ";
        $sql .= "WHERE " . implode(" AND ", $conditions);
        $rs  = &$conn->Execute($sql);
        $result = array();
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        return $result;
    }

    function downloadArticleByCampaignID($campaign_id, $cp_completed = 0)
    {
        global $conn, $feedback;

        $q = "WHERE 1 ";

        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }
        /*
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "AND ck.article_type = '".$article_type."' ";
        }
        */
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= "AND ck.copy_writer_id = '".User::getID()."'";
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ck.editor_id = u.user_id) ";
                $q .= "AND ck.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }

        $q = "SELECT ar.*, ck.keyword, ck.article_type, ck.keyword_description, ck.date_start, ". 
             "\nck.date_end, cc.campaign_name, u.user_name AS creator ,uc.user_name AS author, ck.keyword_meta, ck.description_meta ".
             "\nFROM articles AS ar ".
             "\nLEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
             "\nLEFT JOIN users AS u ON (u.user_id = ar.creation_user_id) ".
             "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
             "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "\nLEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
             "\nLEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) " . $q . " ";
        if ($cp_completed == 1) {
            $q .= "\nAND (ar.article_status = '1')";
        } else {
            $q .= "\nAND (ar.article_status = '5' OR ar.article_status = '4' OR ar.article_status = '6')";
        }
        $rs = &$conn->Execute($q);
        $result = array();
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return $result;

    }//end downloadArticleByCampaignID()

	function getArticleCostInfo()
	{
		global $conn;
		$q = "SELECT DISTINCTROW ac.*, cc.campaign_name ";
        $q .= "FROM article_cost AS ac, client_campaigns AS cc ";
        $q .= "WHERE cc.campaign_id = ac.campaign_id";
		$rs = &$conn->Execute($q);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
				$key = $rs->fields['campaign_name'] . ':'. $rs->fields['campaign_id'];
                $result[$key][$rs->fields['article_type']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
			return $result;
        }
		return false;
	}

	function getCampaignIDAndArticleType( $p )
	{
		global $conn;
		$user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
		$qw = '';
		if ($user_id > 0) 
		{
			$qw .= "AND u.user_id = '" . $user_id . "' ";
		}
		$current_month = addslashes(htmlspecialchars(trim($p['month'])));

		if( $current_month == date( 'Ym' )|| $current_month == '')
		{
			$now = time();
			$current_month = date('Ym');
		} else {
			$now = changeTimeFormatToTimestamp($current_month);
		}
        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D' ";
        $qw .= "\n AND u.role = 'copy writer' ";
        $param['type']        = 'type-cost';
        $param['now']         = $now;
        $param['qw_where'] = $qw;
        $param['user_id'] = $user_id;
        $sqls = User::getCPAccountingConditionOrSql($param);

        $q = $sqls['sql'];
		$rs = &$conn->Execute($q);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
				$key = $rs->fields['campaign_name'] . ':'. $rs->fields['campaign_id'];
                // added by snug xu 2007-05-16 23:12 - STARTED
                // assemble total article key
                $total_article_key = $rs->fields['article_type'] . "_num";
                $rs->fields['total_article_key'] = $total_article_key;
                // added by snug xu 2007-05-16 23:12 - FINISHED
                $result[$key][$rs->fields['article_type']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
            // total types for each campaign
            foreach ($result as $key => $value)
            {
                $result[$key]['num'] = count($value);
            }
			return $result;
        }
		return false;

	}

	function updateArticleCost( $p )
	{
		global $conn, $feedback;
		if( count( $p['campaign_id'] ) )
		{
			$conn->StartTrans();
			foreach  ( $p['campaign_id'] as $k => $campaign_id ) 
			{
				$type = addslashes(htmlspecialchars(trim($p['article_type'][$k])));
				$type_cost = addslashes(htmlspecialchars(trim($p['type_cost'][$k])));
				$campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'][$k])));
				$invoice_status = addslashes(htmlspecialchars(trim($p['invoice_status'][$k])));
				$operation = $p['operation'];
				switch( $operation )
				{
					case 'save':
						if( strlen( $invoice_status ) ==0 )
							$invoice_status=0;
						break;
					case 'submit':
						$invoice_status=1;
						break;
				}
				
				$cost_id = addslashes(htmlspecialchars(trim($p['cost_id'][$k])));
				$sql = "REPLACE INTO `article_cost` (`cost_id`,  `article_type`, `campaign_id`, `cost_per_article`, `invoice_status`) VALUES ('{$cost_id}', '{$type}', '{$campaign_id}', '{$type_cost}', '{$invoice_status}'); ";

				$conn->Execute($sql);
			}
			$ok = $conn->CompleteTrans();
		}
		if ($ok) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure,Please try again';
            return false;
        }
	}

    /**
     * Delete article and correlative infomation
     *
     * @param int    $article_id
     *
     * @return boolean
     */
    function del($article_id)
    {
        global $conn, $feedback;

        $article_id = addslashes(htmlspecialchars(trim($article_id)));
        if ($article_id == '') {
            $feedback = "Please Choose a article";
            return false;
        }

        $conn->StartTrans();
        $q = "DELETE FROM comments_on_articles ".
             "WHERE article_id = '".$article_id."' ";
        $conn->Execute($q);

        $q = "DELETE FROM articles_version_history ".
             "WHERE article_id = '".$article_id."' ";
        $conn->Execute($q);

        $q = "DELETE FROM articles ".
             "WHERE article_id = '".$article_id."' ";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure,Please try again';
            return false;
        }

    }//end del()

	function getTotalArticleGroupByCampaignID()
	{
		global $conn;

        // added by snug xu 2006-11-24 13:14 - START
        if (User::getRole() == 'agency') {
            $qw = " AND cc.creation_user_id='" . User::getID() . "'";
        } else {
            $qw = '';
        }
        // added by snug xu 2006-11-24 13:14 - end

		$query = "SELECT COUNT( ck.keyword_id) AS total ,cc.campaign_id, cc.client_id, cc.campaign_name, ";
        $query .= "cc.total_budget, cc.cost_per_article, cc.campaign_site_url, cc.date_start,  cc.date_end, ";
        $query .= "cc.campaign_requirement, cc.campaign_date, cc.date_created, cc.creation_user_id, ";
        $query .= "cc.monthly_recurrent ";
        $query .= " FROM  client_campaigns AS cc ";
        $query .= " LEFT JOIN campaign_keyword AS ck ON cc.campaign_id=ck.campaign_id ";
        $query .= " WHERE cc.status='0' {$qw} group by ck.campaign_id";
		$rs = &$conn->Execute($query);
        $ret = array();
        if ($rs) 
		{
            while (!$rs->EOF) 
			{
                $ret[$rs->fields['campaign_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
		return $ret;
	}

	function getTotalAritcleGroupByArticleTypeByCampaignID( $campaign_id , $is_submitted=false, $is_today=false )
	{
		global $conn;
		$query = "SELECT COUNT( ck.keyword_id) AS type_total, ck.article_type FROM campaign_keyword AS ck left join articles AS a ON a.keyword_id = ck.keyword_id WHERE ck.campaign_id=$campaign_id";
		if( $is_submitted )
		{
			$query .= " AND a.article_status = '4' ";
			if( $is_today )
			{
				$query .= " AND DATEDIFF( a.approval_date, CURDATE( ) ) =0";
			}
		}
		$query .= " group by ck.article_type";
		$rs = &$conn->Execute($query);
        $ret = array();
        if ($rs) 
		{
            while (!$rs->EOF) 
			{
                $ret[$rs->fields['article_type']] = $rs->fields['type_total'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
		return $ret;
	}

	function getTotalSubmittedAritcleByCampaignID( $campaign_id, $is_today=false )
	{
		global $conn;
		$query = "SELECT count( ck.keyword_id ) AS total, ck.campaign_id
			FROM campaign_keyword AS ck, articles AS a
			WHERE ck.campaign_id =$campaign_id
			AND a.keyword_id = ck.keyword_id ";
		if( $is_today ) {
			$query .= " AND DATEDIFF( a.approval_date, CURDATE( ) ) =0";
			$query .= " AND a.article_status = '4' ";
		} else {
			$query .= " AND a.article_status REGEXP  '4|5' ";
		}
		$query .= " GROUP BY ck.campaign_id";
		$rs = &$conn->Execute($query);
        $ret = array();
        if ($rs) 
		{
            while (!$rs->EOF) 
			{
                $ret[$rs->fields['campaign_id']] = $rs->fields['total'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
		return $ret;
	}

    /**
     * Get client's info by $client_id
     *
     * @param int $client_id
     *
     * @return boolean or an array containing all fields in tbl.client
     */
     /*
    function getKeywordInfo($keyword_id)
    {
        global $conn, $feedback;

        $keyword_id = addslashes(htmlspecialchars(trim($keyword_id)));
        if ($keyword_id == '') {
            $feedback = "Please Choose a campaign keyword";
            return false;
        }

        $q = "SELECT ck.*, cc.campaign_name, cl.user_name, cl.company_name ".
             "FROM campaign_keyword AS ck ".
             "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
             "LEFT JOIN client AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE ck.keyword_id = '".$keyword_id."'";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $ret = false;
            if ($rs->fields['keyword_id'] != 0) {
                $ret = $rs->fields; // return an array
            }

            $rs->Close();
            return $ret;
        }

        return false; // return false if client does not exist
    }//end getKeywordInfo()
    */

    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function listKeywordByRole($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "WHERE 1 ";

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND ck.campaign_id = '".$campaign_id."' ";
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND ck.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "AND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "AND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND ck.creation_user_id = '".$creation_user_id."' ";
        }
        /*
        $keyword = addslashes(htmlspecialchars(trim($p['keyword'])));
        if ($keyword != '') {
            $q .= "AND ck.keyword LIKE '%".$keyword."%' ";
        }
        */

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "AND ck.article_type = '".$article_type."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "AND ck.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "AND ck.date_end <= '".$date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "AND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                //$q .= "AND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description, uc.user_name, ue.user_name, cu.user_name)")." ";
                $q .= "AND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)")." ";
            }
        }

        //$ql = "";
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= "AND ck.copy_writer_id = '".User::getID()."' ";
                $q .= "AND ck.keyword_status != '0' ";
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ck.editor_id = u.user_id) ";
                $q .= "AND ck.editor_id = '".User::getID()."' ";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."' ";
            if (isset($p['keyword_status']))
            {
                $keyword_status = addslashes(htmlspecialchars(trim($p['keyword_status'])));
                if (is_array($keyword_status))
                    $q .= " AND ck.keyword_status IN ('" . implode("','", $keyword_status) . "') ";
                else
                    $q .= " AND ck.keyword_status = '{$keyword_status}' ";
            }
        } else {
            return false;
        }

/*     if (trim($p['show_keyword_type']) == 'but_active') {
            //如果已经在tbl.articles存在的keyword.就必须要剔除掉
            $q .= "AND ck.keyword_id NOT IN (SELECT ar.keyword_id FROM articles AS ar WHERE ar.keyword_id = ck.keyword_id) ";
        } elseif (trim($p['show_keyword_type']) == 'show_active') {
            //如果已经在tbl.articles存在的keyword.就必须要剔除掉
            $q .= "AND ck.keyword_id IN (SELECT ar.keyword_id FROM articles AS ar WHERE ar.keyword_id = ck.keyword_id) ";
        } else {
            //do nothing;
        }*/
        
        // added by snug xu 2007-07-24 19:47 - STARTED
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status)) {
            $q .= "AND atc.article_status IN ('". implode("', '", $article_status)."') ";
        } else {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "AND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "AND atc.article_status = '".$article_status."' ";
                }
            }
        }
        // added by snug xu 2007-07-24 19:47 - FINISHED
        
        $sql = "SELECT COUNT(ck.keyword_id) AS count ".
              "FROM campaign_keyword AS ck ".
              "LEFT JOIN articles AS atc ON (ck.keyword_id = atc.keyword_id) ".
              "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
              "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
              "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
              "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
              "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".$q;
        $rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT ck.*, atc.article_id, atc.article_number, atc.article_status, atc.title, atc.current_version_number, atc.creation_user_id AS creator, atc.creation_role, cl.user_name, cl.company_name, cc.campaign_name, uc.user_name AS uc_name , ue.user_name AS ue_name , cu.user_name AS cu_name ".
             "FROM campaign_keyword AS ck ".
             "LEFT JOIN articles AS atc ON (ck.keyword_id = atc.keyword_id) ".
             "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".$q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;

                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);

    }//end listKeywordByRole()


    function searchArticleHistory($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "WHERE 1 ";

        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id != '') {
            $q .= "AND ar.article_id = '".$article_id."' ";
        }

        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND ar.keyword_id = '".$keyword_id."' ";
        }

        $creation_role = addslashes(htmlspecialchars(trim($p['creation_role'])));
        if ($creation_role != '') {
            $q .= "AND ar.creation_role LIKE '%".$creation_role."%' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND ar.creation_user_id = '".$creation_user_id."' ";
        }

        $language = addslashes(htmlspecialchars(trim($p['language'])));
        if ($language != '') {
            $q .= "AND ar.language = '".$language."' ";
        }

        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title != '') {
            $q .= "AND ar.title LIKE '%".$title."%' ";
        }
        $body = addslashes(htmlspecialchars(trim($p['body'])));
        if ($body != '') {
            $q .= "AND ar.body LIKE '%".$body."%' ";
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(ar.title, ar.body, ar.current_version_number, ck.keyword, ck.article_type, ck.keyword_description)")." ";
            }
        }

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= "AND ck.copy_writer_id = '".User::getID()."'";
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ck.editor_id = u.user_id) ";
                $q .= "AND ck.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }

        $rs = &$conn->Execute("SELECT COUNT(ar.article_id) AS count FROM articles_version_history AS ar ".
                              "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
                              "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
                              "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ".$q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT ar.*, ck.keyword, ck.article_type, ck.keyword_description, ck.date_start, ck.date_end, cc.campaign_name, u.user_name AS creator ".
             "FROM articles_version_history AS ar ".
             "LEFT JOIN users AS u ON (u.user_id = ar.creation_user_id) ".
             "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
             "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ".$q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);

    }//end searchArticleHistory()

    // added by snug xu 2007-02-28 14:53 - STARTED    
    /**
     * force approve an article if the article status is rejected 
     * or article status is google duplicated at tiwce or more
     *
     * @param array $p the value was submited by form
     *
     * @return boolean 
     */
    function forceApproveArticle($p = array()) 
    {
    	global $conn, $feedback;
        $url_category = addslashes(htmlspecialchars(trim($p['url_category'])));
        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id == '' || $article_id == 0) {
            $feedback = "Please Choose an article";
            return false;
        }
         $old_article     = self::getInfo($article_id, false);
         $old_status     = $old_article['article_status'];
         $approve_date = $old_article['approval_date'];
         if (user_is_loggedin() && (User::getID() == $old_article['editor_id']) || User::getName() == 'admin') {
             if ($old_status == 2 || ($old_status == '1gd' && $approve_date > 0)) {
                 $new_info                       = $p;
                 $new_info['action']           = 'submit';
                 $new_info['article_status'] = 1;
                 // don't change richtext
                 $new_info['hold_richtext'] = true;
                 $ret = self::setInfo($new_info);
                 if ($ret === false) {
                    return false;
                 } else {
                    if (self::setArticleStatus($article_id, '1gc' , 1)) {
                        $p['approve_action'] = 'approval';
                        if (!$ret = self::approveArticle($p)) {

                        	return false;
                        }
                    } else {
                    	return false;
                    }
                 }
             } else {
                $feedback = "You can't force the article status as approval!";
                return false;
             }
         } else {
            $feedback = "You have no privilege to force the article status as approval!";
            return false;
         }
         return true;
    } // end
    // added by snug xu 2007-02-28 14:53 - FINISHED


    function batchApproveArticles($p = array()) 
    {
        global $conn, $feedback;
        $sets = array();
        $keywords = $p['isUpdate'];
        if (count($keywords) == 0) {
        	$feedback = "Please specify the keywords";
            return false;
        }
        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if (count($article_status) == 0) {
        	$feedback = "Please specify the article status";
            return false;
        }
        if ($article_status == 2) {
        	$sets[] = "google_approved_time = '0000-00-00 00:00:00'";
        }
        $sets[] = "article_status='{$article_status}'";

        $comment = addslashes(htmlspecialchars(trim($p['comment'])));
        $language = addslashes(htmlspecialchars(trim($p['language']))); 
        if (strlen($language) == 0) {
        	$language = 'en';
        }

        $email_keywords = array();
        $pay_report = User::getCpPaymentHistory(array(), false); 
        foreach ($keywords as $k=>$keyword_id) {
            $old_info = self::getInfoByKeywordID($keyword_id);
            // modified by snug xu 2007-05-15 9:55 - STARTED
            // added by snug xu 2007-11-04 16:18 - STARTED
            $old_status = trim($old_info['article_status']);
            // if $old_status is client approval or publish, then check whether allowed change status or not
           if ($old_status == 5 || $old_status == 6) {
               if ($article_status == 6 && $old_status == 5 || $old_status == $article_status) {
                    // do nothing
               } else {
                   $feedback = "Some of articles were finished, you can't change them to other article status.";
                   return false;
               }
           }
            // added by snug xu 2007-11-04 16:18 - FINISHED
            
            if ($keyword_id > 0 && strcasecmp($old_info['article_status'], $article_status) != 0 && $old_info['article_status'] != 5) {
                $conn->StartTrans();
                // if aticle status is changes
                // record this action to aticle action table
                if (strcasecmp($old_info['article_status'], $article_status) != 0)
                {
                     // initialize the article action array
                     self::getArticleActionInfo($action_info, $old_info['article_id']);
                     $action_info['new_status'] = $article_status;
                     ArticleAction::store($action_info);
                }
                // modifed by snug xu 2007-03-14 11:08 - STARTED
                // added comments for each article
                if (strlen($comment)) {
                    $article_id = $old_info['article_id'];
                    self::addComments($comment, $language, $article_id, $old_info);
                }
                // modifed by snug xu 2007-03-14 11:08 - FINISHED
                $sql = "UPDATE articles SET " . implode(", ", $sets) . " WHERE keyword_id = {$keyword_id}";
                $rs = $conn->Execute($sql);
                $ok = $conn->CompleteTrans();
                if ($ok) {
                   // modified by snug xu 2007-10-08 11:37 - STARTED
                   if ((user_is_loggedin() || client_is_loggedin()) && ($article_status == 2 || $article_status == 3)) {
                       // send reject email to user
                       self::sendAnnouceMail("reject", $old_info['keyword_id'], $comment);
                    } else if (user_is_loggedin() && $article_status == '1gd') {
                        self::sendDuplicatedEmail($old_info['article_id']);
                    }
                   // modified by snug xu 2007-10-08 11:37 - FINISHED
                    $feedback = "Success";
                } else {
                    $feedback = 'Failure, Please try again';
                    return false;
                }
            }
            // modified by snug xu 2007-05-15 9:55 - FINISHED
            if (strcasecmp($old_info['article_status'], $article_status) != 0 && $old_info['is_sent'] == 0 && $ok) {
                $allow_status = array('1gc', '3', '4');
                if (in_array($old_info['article_status'], $allow_status)) {
                    $month = $old_info['target_pay_month'];
                    if ($month <= 0) {
                        $month = date("Ym", strtotime($old_info['google_approved_time']));
                    }
                    $payment_status = $pay_report[$old_info['copy_writer_id']][$month]['payment_flow_status'];
                    if ($payment_status == 'cpc' || $payment_status == 'paid') {
                       if ($article_status ==2)
                       {
                           $email_keywords[$old_info['editor_id']][$old_info['copy_writer_id']][] = $old_info;
                           //$send_status = "is_sent=1";
						   $send_status = "is_sent=1";
                           $update_send_status = "UPDATE campaign_keyword set {$send_status} WHERE keyword_id='{$keyword_id}'";
                       }
                    }
                    if (count($email_keywords) && $article_status != 2) {
                        if (User::sendAdjustKeywordsEmail(13, $hint, $email_keywords, $old_info['editor_id'], $old_info['copy_writer_id'])) {
                            $conn->Execute($update_send_status);
                        }
                        $email_keywords = array();
                    }
                }
            }
        }
//        if (count($keywords)) {
//            $sql = "UPDATE articles SET " . implode(", ", $sets) . " WHERE keyword_id IN (" . implode(",", $keywords) . ")";
//            $conn->Execute($sql);
//        }
        $feedback = 'Success';
        return true;
    }
     
     function sendDuplicatedEmail($article_id)
     {
        global $mailer_param;
        $domain = "http://" . $_SERVER['SERVER_NAME'];
        $tables = array(" `articles`  AS ar ", " `campaign_keyword` AS ck ");
        $where = array(" ar.keyword_id=ck.keyword_id ", " ck.copy_writer_id = u.user_id ");
        // $article_ids = !is_array($article_id) ? array($article_id) : $article_id;
        $params = array(
                                'article_id' => $article_id,
                                'table'        => $tables,
                                'where'      => $where,
                            );
        $user = User::getAllCopyWritersByParameters($params);
        if (count($user))
        {
            foreach ($user as $k => $value)
            {
                $address= $value['email'];
                if (strlen($address))
                {
                    if( strlen( $value['phone'] )==0 )
                    {
                        $value['phone'] = "n/a";
                    }
                    $subject = "Possible Duplicated Article";
                    $body = "<div>
                                            Possible duplicated article:<br />
                                            {$domain}/article/article_comment_list.php?article_id={$article_id}<br />
                                            please to re-submit <a href='{$domain}/article/article_set.php?article_id={$article_id}&keyword_id={$article_id}' >here</a><br /><br />
                                            <strong>Writer's Contact Info</strong><br />
                                            Name:&nbsp;{$value['first_name']}&nbsp;{$value['last_name']}<br />
                                            Email:&nbsp;{$value['email']}<br />
                                            Phone:&nbsp;{$value['phone']}<br />
                                    </div>";
                    send_smtp_mail( $address, $subject, $body, $mailer_param );
                }
            }
        }
     }

    /**
     * publish an article if the article is client approval
     *
     * @param array $p the value was submited by form
     *
     * @return boolean 
     */
    function publishArticle($p = array())
    {
        $article_id = $p['article_id'];
        global $conn, $feedback;
        $old_article = self::getInfo($article_id, false);
        // check the user and client login
        if (!user_is_loggedin() && !client_is_loggedin()) {
            $feedback = 'Please sign in this system';
            return false;
        }
    }
    
    /**
     * approve an article if the article is very good and satisfaction
     *
     * @param array $p the value was submited by form
     *
     * @return boolean 
     */
    function approveArticle($p = array())
    {
        global $conn, $feedback;
        ini_set("max_execution_time", 120);
        // added by snug xu 2007-02-28 22:08 - started
        // check the user and client login
        if (!user_is_loggedin() && !client_is_loggedin()) {
            $feedback = 'Please sign in this system';
            return false;
        }
        // added by snug xu 2007-02-28 22:08 - FINISHED

        $action_info = array();
		$url_category = addslashes(htmlspecialchars(trim($p['url_category'])));
        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id == '') {
            $feedback = "Please Choose an article";
            return false;
        }
//        // added by snug xu 2007-09-12 15:18 - STARTED
//        if (isset($p['keyword_meta']) && empty($p['keyword_meta']))
//        {
//            $feedback = "Please specify the keyword meta";
//            return false;
//        }
//        if (isset($p['description_meta']) && empty($p['description_meta']))
//        {
//            $feedback = "Please specify the description meta";
//            return false;
//        }
//        if (isset($p['html_title'])  && empty($p['html_title']))
//        {
//            $feedback = "Please specify the html title";
//            return false;
//        }
//        // added by snug xu 2007-09-12 15:18 - FINISHED

        $old_article = self::getInfo($article_id, false);

		$keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (!client_is_loggedin()) {
            $language = addslashes(htmlspecialchars(trim($p['language'])));
            if ($language == '') {
                //$feedback = "Please enter the language of the article";
                //return false;
                $language = "en";
            }
            $title = addslashes(htmlspecialchars(trim($p['title'])));
            if ($title == '') {
                $feedback = "Please provide the title of the article";
                return false;
            }

            // added by snug xu 2006-11-27 14:53 - START
            // changed paintext to richtext
            $richtext_body = addslashes(htmlspecialchars(trim($p['richtext_body'])));
            if ($richtext_body == '') {
                $feedback = "Please provides article";
                return false;
            }
            $richtext_update = " `richtext_body` = '{$richtext_body}', ";
            $body = change_richtxt_to_paintxt($richtext_body, ENT_QUOTES);
            if (strlen($body))
            {
                $body = addslashes(htmlspecialchars(trim($old_article['body'])));

            }
            $new_body = self::replaceUserArticleTemp($body, $article_id);
            // added by snug xu 2006-11-27 14:53 - END

			$action_status = addslashes(htmlspecialchars(trim($p['action_status'])));
        }

		// added by Snug Xu 2006-10-23 17:46
		// START
		if (User::getRole() == 'project manager' || User::getRole() == 'admin')
		{
            $is_rated = (trim($p['is_rated']) == 'on' || trim($p['is_rated']) == 1)? 1: 0;

            if ($is_rated == 1)
            {
                $rating = addslashes(htmlspecialchars(trim($p['rating'])));
                if ($rating == 0) 
                {
                    $feedback = "Please set the article rating";
                    return false;
                }
            }
		}
		// END
        $is_edit = addslashes(htmlspecialchars(trim($p['is_edit'])));

        $comment = addslashes(htmlspecialchars(trim($p['comment'])));
        /*
        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if ($article_status == '') {
            $article_status = 0;
        }
        */
        // added by snug xu 2007-11-04 16:18 - STARTED
        $approve_action = trim($p['approve_action']);
        // if action is not save, then check the article is finished or not
        if ($approve_action != 'temp') {
           if ($old_article['article_status'] == 5 || $old_article['article_status'] == 6) {
               if ($approve_action == 'publish' && $old_article['article_status'] == 5 || $approve_action == 'approval' && client_is_loggedin()) {
                    // do nothing
               } else {
                   $feedback = "This article was finished, you can't change it to other article status.";
                   return false;
               }
           }
        }
        // added by snug xu 2007-11-04 16:18 - FINISHED
        if (trim($p['approve_action']) == 'temp') {//temp file or not
            //$article_status = 0;//incomplete
            $article_status = $old_article['article_status'];
        } elseif (trim($p['approve_action']) == 'reject')  {
            //$article_status = 1;//writer complete
            if (user_is_loggedin()) {

				// START:modifed by snug xu 2006-10-18 10:53
				// current article status is cp completed or editor rejected,
				// all of those articles are need to wait for google checking
				switch ($old_article['article_status'])
				{
				case '0':
					$feedback = "Copy Writer doesn't finish this article. Please wait google checking this article";
					return false;
					break;
				case '1':
					$feedback = "Please wait the google checking this article";
					return false;
					break;
				case '1gd':
					$feedback = "This article wasn't passed google checking. Please wait google checking this article";
                    return false;
                // strip this restriction by snug xu 2007-06-22 17:07 - STARTED
                // when article status is approval, let user change to editor rejected
//				case '4':
//					$feedback = "The current article status is 'editor approval'. You can't change it as 'editor reject'.";
//                  return false;
//					break;
                // strip this restriction by snug xu 2007-06-22 17:07 - FINISHED
				}
				// END
                $article_status = 2;//user reject(editor)
            } else {

				// START:modifed by snug xu 2006-10-18 10:53
				// current article status is cp completed or editor rejected,
				// all of those articles are need to wait for google checking
				if ($old_article['article_status'] <= "2") 
				{
					$feedback = "Please wait editor approving this article";
                    return false;
				}
				// END
                // added by snug xu 2007-05-18 15:05 - STARTED
                // get article status from page
                $article_status = $p['article_status'];//client reject
                // added by snug xu 2007-05-18 15:05 - FINISHED
            }
        } elseif (trim($p['approve_action']) == 'approval') {
            if (user_is_loggedin()) {
				// START:modifed by snug xu 2006-10-18 10:53
				// current article status is cp completed or editor rejected,
				// all of those articles are need to wait for google checking
				switch ($old_article['article_status'])
				{
				case '0':
					$feedback = "Copy Writer doesn't finish this article. Please wait the google checking this article";
					return false;
					break;
				case '1':
					$feedback = "Please wait the google checking this article";
					return false;
					break;
				case '2':
					$feedback = "This article was rejected by editor. Please wait the google checking this article";
                    return false;
					break;
				case '1gd':
//					$feedback = "This article wasn't passed google checking. Please wait the google checking this article";
//                    return false;
					if (!self::setArticleStatus($article_id, '1gc', '1gd')) 
					{
						$feedback = "Failure, Please try again";
						return false;
					}
					break;
				}
				// END

                $article_status = 4;//user approval(editor)
				//Added By Snug 17:42 2006-9-12
				if( $action_status==0 )
				{
					$counter               = User::getCounter();
					$permission           = User::getPermission();
                    $user_id                = User::getID();
                    $current_frequency = User::getCurrentFrequency();
					$counter++;

					// when user role are program menager or editor, system need to send a email to tracy
                    // permission 3=>4, 2=>3
					if(($permission == 4 || $permission == 3) && $user_id != 3  &&  ($counter == 1 || $counter % $current_frequency == 0)) {
						if( self::sendAuditEmail( $keyword_id, $article_id, $campaign_id, $title, $body ) )
						{
							if( self::updateActionStatus( 1, $article_id ) )
							{
								$_SESSION['counter'] = User::updateCounterOfUser($counter)? $counter:$_SESSION['counter']; // record the aritcle number that editor or program manager disposed from now
							}
						}
					} else {
						if( self::updateActionStatus( 1, $article_id ) )
						{
							$_SESSION['counter'] = User::updateCounterOfUser($counter)? $counter:$_SESSION['counter'];
						}
					}
				}//End Added
            } else {
				if ($old_article['article_status'] <= "2") 
				{
					$feedback = "Please wait editor approving this article";
                    return false;
				}
				if ($old_article['article_status'] == "3") 
				{
					$feedback = "This article was rejected by client. Please wait editor approving this article";
                    return false;
				}
                $article_status = 5;//client approval
            }
        } elseif (trim($p['approve_action']) == 'submit') {//cp confirm
            $article_status = 1;
        // added by snug xu 2007-07-22 1:02 - STARTED
        } elseif (trim($p['approve_action']) == 'publish') {//client publish
            // modified by snug xu 2007-10-07 13:48 - STARTED
            // when old article status is not client approval, article status can't change to published
            if ($old_article['article_status'] == 5) {
                $article_status = 6;
            } else {
                $feedback = "Please wait client approving this article.";
                return false;
            }
            // modified by snug xu 2007-10-07 13:48 - FINISHED
        // added by snug xu 2007-07-22 1:02 - FINISHED
        // added by snug xu 2007-10-07 13:50 - STARTED
        // when article status is copywriter completed, then change article status to '1gd' or '1gc'
        } elseif (trim($p['approve_action']) == '1gc' || trim($p['approve_action']) == '1gd') {
            if ($old_article['article_status'] == '1') {
                $article_status = trim($p['approve_action']);
            } else {
                $feedback = "You have no right to change this article to google clean or google duplicated.";
                return false;
            }
        // added by snug xu 2007-10-07 13:50 - FINISHED
        } else {
            $article_status = $old_article['article_status'];
        }

        // added by snug xu 2006-11-21 19:17 - START
        // initialize article action info
        self::getArticleActionInfo($action_info, $article_id);
        // added by snug xu 2006-11-21 19:17 - END

        //$can_update_article = true;
        if ($old_article['article_status'] == 0) {
            if ($old_article['creation_role'] == 'client') {
                if (Client::getID() != $old_article['creation_user_id']) {
                    //$can_update_article = false;
                    $feedback = "Please wait other complete this article";
                    return false;
                }
            } else {
                // do nothing
            }
        }

        $now = time();
        $conn->StartTrans();
        // length of old article body don't equal to  length of new article body from the page
        // and approval action is not 'temp'
        // and article status is not '0'
        if (client_is_loggedin()) {
        	 $new_len = $old_len = strlen($old_article['body']);
        }
        if (user_is_loggedin()) {
            $new_len = strlen($new_body);
            $old_len  = strlen($old_article['body']);
        }
        if ($old_len != $new_len && $article_status != 0 && trim($p['approve_action']) != 'temp') {//edited
            $version_history_id = $conn->GenID('seq_articles_version_history_version_history_id');
            $q = "INSERT INTO articles_version_history (`version_history_id`, `article_id`, `keyword_id`, `creation_user_id`, `creation_role`, ".
                                       "`creation_date`, `language`, `title`, `body`, `richtext_body`, ".
                                       "`article_status`, `version_number`, `approval_date`) ". 
                 "VALUES ('".$version_history_id."', ".
                         "'".$old_article['article_id']."', ".
                         "'".$old_article['keyword_id']."', ".
                         "'".$old_article['creation_user_id']."', ".
                         "'".$old_article['creation_role']."', ".
                         "'".$old_article['creation_date']."', ".
                         "'".$old_article['language']."', ".
                         "'".addslashes($old_article['title'])."', ".
                         "'".addslashes($old_article['body'])."', ".
                         "'".addslashes($old_article['richtext_body'])."', ".
                         "'".$old_article['article_status']."', ".
                         "'".$old_article['current_version_number']."', ".
                         "'".addslashes($old_article['approval_date'])."')";
            $conn->Execute($q);
            $qu .= "current_version_number = current_version_number + 0.1, ";
			 $qu .="`creation_date` = '" . date('Y-m-d H:i:s', $now) . "', ";//当新的version创建时，就会修改这一状态
             // added by snug xu 2006-11-21 15:49 - START
             // initialize article action new version
             $action_info['new_version'] = $old_article['current_version_number'] + 0.1;
             // added by snug xu 2006-11-21 15:49 - END
        }
        
        // added by snug xu 2007-07-10 18:56 - STARTED
        // add html title sql
        if (isset($p['html_title'])) {
            $html_title      = addslashes(htmlspecialchars(trim($p['html_title'])));
            $html_title_qw =  "html_title = '" . $html_title."', ";

        } else {
            $html_title_qw = '';
        }
        // added by snug xu 2007-07-10 18:56 - FINISHED

        // added by snug xu 2007-03-13 18:19 - STARTED
        if ($article_status == 2 && $old_article['is_sent'] == 0) {
            $allow_status = array('1gc', '3', '4');
            if (in_array($old_article['article_status'], $allow_status)) {
                $cpph_month = $old_article['target_pay_month'];
                if ($cpph_month <= 0) {
                    $cpph_month = date("Ym", strtotime($old_article['google_approved_time']));
                }
                $cpph_param = array('user_id' => $old_article['copy_writer_id'] , 'month' => $cpph_month);
                $email_keywords = array();
                $pay_report  = User::getCpPaymentHistory($cpph_param, false);
                // get payment flow status
                $payment_status = $pay_report[$old_article['copy_writer_id']][$cpph_month]['payment_flow_status'];
                if ($payment_status == 'cpc' || $payment_status == 'paid') {
                    $email_keywords[$old_article['editor_id']][$old_article['copy_writer_id']][]  = $old_article;
                }
                if($article_status == 3) {
                    $hint = "Article(s) have been rejected by client";
                } else if($article_status == 2) {
                    $hint = "Article(s) have been rejected by Editor";
                }
                if (count($email_keywords)) {
                    $send_status = "is_sent=1";
                    $update_send_status = "UPDATE campaign_keyword set {$send_status} WHERE keyword_id='{$old_article['keyword_id']}'";
                }
            }
        }
        // added by snug xu 2007-03-13 18:19 - FINISHED

        if (client_is_loggedin()) {
            //update current version.
            $q = "UPDATE articles ".
                 "SET article_status = '" . $article_status . "', ".
                // added by snug xu 2007-07-10 18:53 - STARTED
                $html_title_qw . 
                // added by snug xu 2007-07-10 18:53 - FINISHED
                 "approval_date = '" . date('Y-m-d H:i:s', $now) . "' ".
                 "WHERE article_id = '" . $article_id . "' ";
            $conn->Execute($q);
        } else {
            //update current version.
            if (User::getRole() == 'copy writer') {
                $qu .= "article_status = '".$article_status."', ";
            } else {
				// added by snug xu 2006-10-23 17:52
				// start
				if ((User::getRole() == 'project manager' || User::getRole() == 'admin') && trim($p['approve_action']) == 'approval')
				{
                    if (strlen($rating)) $qu .= "rating = '".$rating."', ";
					$qu .= "is_rated = '".$is_rated."', ";
				}
				// end
                if ($article_status != 0) 
				{
                    $qu .= "article_status = '" . $article_status . "', ";
                }
            }
            $q = "UPDATE articles ".
                 "SET language = '".$language."', ".$qu.
                     "title = '" . $title . "', ".
                    // added by snug xu 2007-07-10 18:53 - STARTED
                    $html_title_qw . 
                    // added by snug xu 2007-07-10 18:53 - FINISHED
                     "body = '".$body."', ".
                     $richtext_update . 
                     "approval_date = '".date('Y-m-d H:i:s', $now)."' ".
                 "WHERE article_id = '" . $article_id . "' ";
            $conn->Execute($q);
        }

        // modifed by snug xu 2007-03-14 11:08 - STARTED
        if (strlen($comment)) {
            self::addComments($comment, $language, $article_id, $old_article);
        }
        // modifed by snug xu 2007-03-14 11:08 - FINISHED

        if ($article_status == 5) {
            $article_info = self::getInfo($article_id, false);
            $q = "SELECT COUNT(*) AS count FROM cp_campaign_article_summary ".
                 "WHERE campaign_id = '".$article_info['campaign_id']."' ".
                 "AND `month` = '".date('Ym', $now)."' " . 
                 "AND `copy_writer_id` = '".$article_info['copy_writer_id']."' ";
            $rs = $conn->Execute($q);
            $count = 0;
            if ($rs) {
                $count = $rs->fields['count'];
                $rs->Close();
            }
            if ($count > 0) {
                $q = "UPDATE cp_campaign_article_summary ".
                     "SET completed_in_month = (completed_in_month + 1) ".
                     "WHERE campaign_id = '".$article_info['campaign_id']."' ".
                     "AND `month` = '".date('Ym', $now)."' " . 
                     "AND `copy_writer_id` = '".$article_info['copy_writer_id']."' ";
                $conn->Execute($q);
            } else {
                $history_id = $conn->GenID('seq_cp_campaign_article_summary_history_id');
                $q = "INSERT INTO cp_campaign_article_summary (history_id, copy_writer_id, campaign_id, month, completed_in_month, is_paid) ".
                     "VALUES ('".$history_id."', '".$article_info['copy_writer_id']."', '".$article_info['campaign_id']."', '".date('Ym', $now)."', 1, 0)";
                $conn->Execute($q);
            }
        }

		if ($article_status == 2) {
            $q = "UPDATE articles ".
                 "SET google_approved_time = '0000-00-00 00:00:00' ".
                 "WHERE article_id = '".$article_id."' ";
            $conn->Execute($q);
        }
        // added by snug 2007-03-13 20:00 - STARTED
        if ($article_status == 2 && count($email_keywords)) {
//            if (User::sendAdjustKeywordsEmail(13, $hint, $email_keywords, $old_article['editor_id'], $old_article['copy_writer_id'])) {
//            	$conn->Execute($update_send_status);
//            }
        }
        // added by snug 2007-03-13 20:00 - FINISHED
        $ok = $conn->CompleteTrans();

        if ($ok) {
            // added by snug xu 2007-06-28 12:43 - STARTED
            self::updateMetaInfo($p);
            // added by snug xu 2007-06-28 12:43 - FINISED
            // added by snug xu 2006-11-21 15:31 - START
            // if aticle status is changes
            // record this action to aticle action table 
            if (trim($p['approve_action']) != 'temp' && (strcasecmp($action_info['status'], $article_status) != 0 || $action_info['new_version'] != $action_info['version']))
            {
                 $action_info['new_status'] = $article_status;
                 // added by snug xu 2006-11-21 15:42 - END
                 ArticleAction::store($action_info);
            }
            // added by snug xu 2006-11-21 15:31 - END
            $feedback = 'Success';
            if (trim($p['approve_action']) == 'temp') $feedback = '';
            if (trim($p['approve_action']) == 'reject') {
                // modified by snug xu 11:51 2007-05-18 - STARTED                
                if (strcasecmp($old_article['article_status'], $p['article_status']) != 0) {
                    if ( user_is_loggedin()) {
                        self::sendAnnouceMail("reject", $old_article['keyword_id'], $comment);
                      
                    } else  if (client_is_loggedin()) {  
                        // modified by snug xu 2007-05-28 10:55 - STARTED
                        // Writer get emailed when a client rejects an article and the editor makes the changes
                        if ($p['article_status'] == 3 ) {
                            self::sendAnnouceMail("reject", $old_article['keyword_id'], $comment);
                            //self::sendClientRejectedArticleEmail($old_article['keyword_id'],  $comment);
                        } 
                        // modified by snug xu 2007-05-28 10:55 - FINISHED
                    }
                }
                // modified by snug xu 11:51 2007-05-18 - FINISHED
            } else if ($p['approve_action'] == 'approval' && user_is_loggedin()) {
                 self::sendAnnouceMail("approval", $old_article['keyword_id'], $comment);
            } else if (($p['approve_action'] == '1gc' || $p['approve_action'] == '1gd') && user_is_loggedin()) {
                 self::sendDuplicatedEmail($old_article['article_id']);
            }
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            if (trim($p['approve_action']) == 'temp')
                $feedback = '';
            return false;
        }

    }//end approveArticle()
    
    // added by snug xu 2007-03-14 11:09 - STARTED
    // add comments
    function addComments($comment, $language='en', $article_id, $old_article) 
    {
        global $feeback, $conn;
        $qu = "";
        $qi = "";
        $qcw = "";
        if (user_is_loggedin()) {
            $qu .= "creation_user_id = '".User::getID()."', creation_role = '".User::getRole()."', ";
            $qi .= "'".User::getRole()."', '".User::getID()."', ";
            $qcw .= "AND creation_user_id = '".User::getID()."' AND creation_role = '".User::getRole()."' ";
        } elseif (client_is_loggedin()) {
            $qu .= "creation_user_id = '".Client::getID()."', creation_role = 'client', ";
            $qi .= "'client', '".Client::getID()."', ";
            $qcw .= "AND creation_user_id = '".Client::getID()."' AND creation_role = 'client' ";
        } else {
            $feedback = "Please sign in this system";
            return false;
        }

        $q = "SELECT COUNT(*) AS count FROM comments_on_articles ".
             "WHERE article_id = '".$article_id."' AND comment = '".$comment."' ".
             "AND version_number = '".$old_article['current_version_number']."' ".$qcw;
        $rs = &$conn->Execute($q);
        $do_comment = true;
        if ($rs) {
            if ($rs->fields['count'] > 0) {
                $do_comment = false;
            }
            $rs->Close();
        }

        //add comments
        if ($comment != '' && $do_comment) {//do comment
            $comment_id = $conn->GenID('seq_comments_on_articles_comment_id');
            $q = "INSERT INTO comments_on_articles (`comment_id`, `article_id`, `creation_role`, `creation_user_id`, ".
                                       "`creation_date`, `language`, `comment`, `version_number`) ".
                 "VALUES ('".$comment_id."', ".
                         "'".$article_id."', ".$qi.
                         "'".date('Y-m-d H:i:s')."', ".
                         "'".$language."', ".
                         "'".$comment."', ".
                         "'".$old_article['current_version_number']."')";
            $conn->Execute($q);
        }

        if ($conn->Affected_Rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // added by snug xu 2007-03-14 11:09 - FINISHED

    // get operator info of article action
    function getArticleActionInfo(&$info, $article_id)
    {
        $info = self::getInfo($article_id, false);
        if (!isset($info['version']) || empty($info['version'])) 
            $info['version'] = $info['current_version_number'];
        if (!isset($info['new_version']) ||empty($info['new_version'])) 
            $info['new_version'] = $info['version'];
        if (!isset($info['status']) ||empty($info['status'])) 
            $info['status']  = $info['article_status'];
        if (!isset($info['new_copy_writer_id']) || empty($info['new_copy_writer_id']))
            $info['new_copy_writer_id'] = $info['copy_writer_id'];
        $info['created_time']   = date('Y-m-d H:i:s', time());
        if (user_is_loggedin()) {

            $info['opt_id'] = User::getID();
            $info['opt_name'] = User::getName();
            $info['opt_type'] = 0;
        } else if (client_is_loggedin()) {
            $info['opt_id'] = Client::getID();
            $info['opt_name'] = Client::getName();
            $info['opt_type'] = 1;
        } else {
            $info['opt_id'] = 0;
            $info['opt_name'] = 'cronjob';
            $info['opt_type'] = 2;
        }
    }// end getOperatorInfo()


    /**
    * Batch approval article
    *
    * We can approval article as batch.
    *
    * @param array $p 
    * @return boolean if success return true，else return false
    */
    function batchApproveArticle($p = array()) 
    {
        global $conn, $feedback;

        if (empty($p['article_id'])) {
            $feedback = "Please choose one article.";
            return false;
        }

        if (!empty($p['article_status'])) {
            $article_status = $p['article_status'];
        } else {
            $article_status = 5;
        }

        $now = time();
        foreach ($p['article_id'] AS $k => $v) {
            // get action_info by article_id - started
            $article_id   = addslashes(htmlspecialchars(trim($p['article_id'][$k])));
            $old_article = self::getInfo($article_id, false);
            // added by snug xu 2007-11-04 16:18 - STARTED
            $old_status = trim($old_article['article_status']);
            // if $old_status is client approval or publish, then check whether allowed change status or not
            if ($old_status == 5 || $old_status == 6) {
               if ($article_status == 6 && $old_status == 5 || $old_status == $article_status) {
                    // do nothing
               } else {
                   $feedback = "Some of articles were finished, you can't change them to other article status.";
                   return false;
               }
            }
             // initialize the article action array
             self::getArticleActionInfo($action_info, $article_id);
            // get action_info by article_id - finished

            $conn->StartTrans();
            //do not update current version.
            $q = "UPDATE articles ".
                 "SET article_status = '{$article_status}', ".
                     "approval_date = '".date('Y-m-d H:i:s', time())."' ".
                "WHERE article_id = '". $article_id ."' ";
            $conn->Execute($q);
            // added by snug xu 2007-10-03 - STARTED
             $action_info['new_status'] = $article_status;
             ArticleAction::store($action_info);
            // added by snug xu 2007-10-03 - FINISHED
            if ($article_status == 5) {
                $article_info = self::getInfo($p['article_id'][$k], false);
                $q = "SELECT COUNT(*) AS count FROM cp_campaign_article_summary ".
                     "WHERE campaign_id = '".$article_info['campaign_id']."' ".
                     "AND `month` = '".date('Ym', $now)."' ";
                     "AND `copy_writer_id` = '".$article_info['copy_writer_id']."' ";
                $rs = $conn->Execute($q);
                $count = 0;
                if ($rs) {
                    $count = $rs->fields['count'];
                    $rs->Close();
                }

                if ($count > 0) {
                    $q = "UPDATE cp_campaign_article_summary ".
                         "SET completed_in_month = (completed_in_month + 1) ".
                         "WHERE campaign_id = '".$article_info['campaign_id']."' ".
                         "AND `month` = '".date('Ym', $now)."' ";
                         "AND `copy_writer_id` = '".$article_info['copy_writer_id']."' ";
                    $conn->Execute($q);
                } else {
                    $history_id = $conn->GenID('seq_cp_campaign_article_summary_history_id');
                    $q = "INSERT INTO cp_campaign_article_summary (history_id, copy_writer_id, campaign_id, month, completed_in_month, is_paid) ".
                         "VALUES ('".$history_id."', '".$article_info['copy_writer_id']."', '".$article_info['campaign_id']."', '".date('Ym', $now)."', 1, 0)";
                    $conn->Execute($q);
                }
            }

            $ok = $conn->CompleteTrans();
        }

        $feedback = "Operation was finished, Please check the result";
        return true;
    }//end batchApproveArticle()

    /**
     * return the days of one month
     *
     * @param int $month
     * @param int $year
     * @author liuxiaoliang
     *
     * @return int.
     */
    function _getdaynum($month, $year)
    {
        if (checkdate($month, 31, $year)) return 31; 
        if (checkdate($month, 30, $year)) return 30; 
        if (checkdate($month, 29, $year)) return 29; 
        if (checkdate($month, 28, $year)) return 28; 
    }//end _getdaynum();

    //add comments---注意要针对版本号。
    //no set comments

    //approve article..
    //download article by article Id to txt()
    //set article.

    /**
     * Search copy writer articles working on,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function searchCPArticlesWorkingOn($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "WHERE 1 ";

        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id != '') {
            $q .= "AND ar.article_id = '".$article_id."' ";
        }

        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND ck.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "AND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "AND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND ck.creation_user_id = '".$creation_user_id."' ";
        }

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "AND ck.article_type = '".$article_type."' ";
        }

        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if ($article_status != '') {
            if ($article_status == -1) {
                $q .= "AND ck.copy_writer_id = '0' ";
            } else {
                $q .= "AND ar.article_status = '".$article_status."' ";
            }
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(ar.title, ar.body, ar.current_version_number, ck.keyword, ck.article_type, ck.keyword_description)")." ";
            }
        }

        /*
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= "AND ck.copy_writer_id = '".User::getID()."'";
            } elseif (User::getPermission() == 2) {
                //$ql .= "LEFT JOIN users AS uc ON (ck.editor_id = u.user_id) ";
                $q .= "AND ck.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }
        */

        $q .= "AND (ar.article_status= '2' OR ar.article_status= '0')";

        $rs = &$conn->Execute("SELECT COUNT(ck.keyword_id) AS count ".
                              "FROM campaign_keyword AS ck ".
                              "LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                              "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                              //"LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                              //"LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                              "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                              "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".$q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT ck.*, ar.article_id, ar.article_number, ar.article_status, cl.user_name, cc.campaign_name, uc.user_name AS uc_name , ue.user_name AS ue_name , cu.user_name AS cu_name ".
             "FROM campaign_keyword AS ck ".
             "LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
             "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".$q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);

    }//end searchCPArticlesWorkingOn()

    function getCheckedArticle($p = array()) 
    {
        global $conn, $feedback;

        if (empty($p['article_id'])) {//or keyword_id
            $feedback = "Please choose articles.";
            return false;
        }
        //$in_sql = implode('');

        $q = "SELECT ar.*, ck.keyword, ck.copy_writer_id, ck.keyword_meta, ck.description_meta ".
             "FROM articles AS ar ".
             "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
             "WHERE ar.article_id IN (".implode(',', array_values($p['article_id'])).")";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $ret = array();
            while (!$rs->EOF) {
                $rs->fields['richtext_body'] = html_entity_decode($rs->fields['richtext_body'], ENT_QUOTES);
                $ret[] = $rs->fields;
                $rs->MoveNext();
            }

            $rs->Close();
            return $ret;
        }

    }//end batchAssignKeyword()

  /**
   * get copy writer id by aritcle id
   * created time: 2006-10-04 15:51
   * @author snug xu <xuxiannuan@gmail.com>
   * @param int $article_id
   * @return int: copy writer id
   */
   function getCopyWriterIdByArticleId($article_id)
	{
	   global $conn, $feedback;
	   $article_id = mysql_escape_string(trim($article_id));
	   $sql = "select `ck`.`copy_writer_id` from `articles` as `ar`, `campaign_keyword` as `ck` where `ar`.`keyword_id` = `ck`.`keyword_id` and `ar`.`article_id` = '{$article_id}'";
	   $rs = &$conn->Execute($sql);
	   if ($rs)
	   {
		   if (!$rs->EOF)
		   {
			   $copy_writer_id = $rs->fields['copy_writer_id'];
		   }
           $rs->Close();
	   }
	   return $copy_writer_id;
	}


	/**
     * set Target Pay Month
     *
     * @param array $p:根据需要传入必要的参数
     *
     * @return array
     */
	
	function setTargetPayMonth($p)
	{
		global $conn, $feedback;
		if( empty($p) )
		{
			$feedback="Failed";
			return false;
		}
		$target_pay_month = mysql_escape_string( trim( $p['target_pay_month'] ) );
		$current_month = mysql_escape_string(trim($p['current_month']));
		$google_approved_time = mysql_escape_string( trim( $p['google_approved_time']) );
		$is_forced_adjust = mysql_escape_string( trim( $p['is_forced_adjust']) );
		$article_id = mysql_escape_string( trim( $p['article_id']) );
		// added by Snug Xu 2006-10-04 14:30
		$is_delay = mysql_escape_string( trim( $p['is_delay']) ); // 0 means ahead of target pay month; 1 means delay target pay month
		// end
		if( $article_id >0)
		{
			// added by Snug Xu 14:08 2006-10-04
			if ($target_pay_month > 0) {
				$target_time = changeTimeFormatToTimestamp($target_pay_month);
			} else if ($google_approved_time > 0) {
				$target_time = strtotime($google_approved_time);
			} else if (strlen($current_month) == 7) {
				$target_time = strtotime($current_month."-01");
			}

			$last_target_month   = date("Ym", strtotime( "-1 month", $target_time));
			$target_month   = date("Ym", $target_time);
			$next_target_month   = date("Ym", strtotime( "+1 month", $target_time));

			if ($is_delay == 1) {
				$target_pay_month = $next_target_month;
			} else if ($is_delay == 2) {
                $target_pay_month = $target_month;
			} else {
				$target_pay_month = $last_target_month;
			}
			// end

			$copy_writer_id = self::getCopyWriterIdByArticleId($article_id);
			if ($copy_writer_id > 0) 
			{
				// 获得$target_time这个月的历史支付信息
				$p = array(
						'user_id' => $copy_writer_id,
						'month' => date("Ym", $target_time)
						);			
				$current_month_info = User::getPaymentHistoryInfo($p);

				// 获得目标支付月的历史支付信息
				$p['month'] = $target_pay_month;
				$target_month_info = User::getPaymentHistoryInfo($p);
			}

			// 如果历史支付信息存在，
			// 并且原来目标支付月和现在目标支付月的payment_flow_status不是cp disapporve(dwe)和Infinitenine approve
			// 则不允许用户调整文章
			if ((count($target_month_info) && $target_month_info['payment_flow_status'] != 'dwe' && $target_month_info['payment_flow_status'] != 'ap') || (count($current_month_info) && $current_month_info['payment_flow_status'] != 'dwe' && $current_month_info['payment_flow_status'] != 'ap')) {
                if ($is_forced_adjust == 1 && $current_month_info['payment_flow_status'] != 'paid' && $target_month_info['payment_flow_status'] != 'paid')
                {
                    $sql = "update `articles` set `target_pay_month`='{$target_pay_month}' where article_id='{$article_id}'";
                }
                else
                {
                    $feedback = "You can\'t delay payment or add to this pay period, please check!";
                    return false;
                }
			} else {
				$sql = "update `articles` set `target_pay_month`='{$target_pay_month}' where article_id='{$article_id}'";
			}
			$conn->Execute( $sql );
			$feedback = "Success";
			return true;
		}
		$feedback="Move to Next Pay Peried Failed";
		return false;

	}//end setTargetPayMonth()


	/*
	 *Added by Snug 15:56 2006-9-4
	 *Function Descript: update article info by artitcle_id
	 *@param array: $p 可以是任何的articles表中字段
	 *@return bool
	*/
	function updateArticleInfoByArticleID( $p )
	{
		global $feedback, $conn;

		$article_id = mysql_escape_string( htmlspecialchars( trim( $p['article_id'] ) ) );
		if ( $article_id == '' || $article_id==0 ) 
		{
            $feedback = "Please Choose an article";
            return false;
        }
		// added by Snug  Xu 2006-10-04 17:17
		$operation = mysql_escape_string(htmlspecialchars(trim($p['operation'])));
		if (strlen($operation)) 
		{
			$current_month = mysql_escape_string(htmlspecialchars(trim($p['current_month'])));
			$copy_writer_id = self::getCopyWriterIdByArticleId($article_id);
			if ($copy_writer_id > 0) 
			{
				$temp_p = array('user_id' => $copy_writer_id, 'month' => $current_month);
				$current_month_info = User::getPaymentHistoryInfo($temp_p);
			}
			if (count($current_month_info) && $current_month_info['payment_flow_status'] != 'dwe') 
			{
				$feedback = "You can\'t cancel keyword, please check!";
				return false;
			}
			unset($p['operation']);
			unset($p['current_month']);
		}
		// end

		if( count( $p ) )
		{
			foreach( $p as $k => $value )
			{
				if( $k != 'article_id' )
					$fields[$k] =  mysql_escape_string( htmlspecialchars( trim( $value ) ) );
			}
		}

		if( count( $fields ) )
		{
			foreach( $fields as $k => $field )
			{
				$set_array[] = " `$k`='$field' ";
			}
			$conn->StartTrans();
			$sql = "UPDATE `articles` SET " . implode(",  ", $set_array ). " WHERE article_id='{$article_id}'";
			$conn->Execute( $sql );
			$ok = $conn->CompleteTrans();
			if( $ok )
			{
				$feedback = 'This Keyword was Canceled';
				return true;
			}
		}
		$feedback = "Failure,Please try again";
		return false;
	}
    
   // added by snug xu 2007-06-28 12:40 - STARTED
   /**
    * update meta info
    * @param array $p: $p['keyword_meta'], $p['description_meta'], $p['keyword'], $p['body'], $p['keyword_id']
    * @return ture or false
    */
    function updateMetaInfo($p = array())
    {
        global $conn, $feedback;
        $keyword_id = trim($p['keyword_id']);
        if (empty($keyword_id))
        {
            $feedback = 'please specify an article';
            return false;
        }
        if (empty($p['keyword_meta']) && empty($p['description_meta']))
        {
            $info = self::getInfoByKeywordID($keyword_id);
            if (empty($p['keyword_meta']))
            {
                if (!empty($info['keyword_meta']))
                {
                    $p['keyword_meta'] = $info['keyword_meta'];
                }
                /*
                else if (!empty($info['keyword']))
                {
                    $p['keyword_meta'] = $info['keyword'];
                }
                */
            }
            if (empty($p['description_meta']))
            {
                if (!empty($info['description_meta']))
                {
                    $p['description_meta'] = $info['description_meta'];
                }
                /*
                else if (!empty($info['body']))
                {
                    $body_arr = explode(".", $info['body']);
                    $p['description_meta'] = $body_arr[0];
                }
                */
            }
        }
		$url_category = addslashes(htmlspecialchars(trim($p['url_category'])));
        $keyword_meta = addslashes(htmlspecialchars(trim($p['keyword_meta'])));
        $description_meta = addslashes(htmlspecialchars(trim($p['description_meta'])));
        $sql  = "UPDATE `campaign_keyword` ";
        $sql .= "SET `keyword_meta`='{$keyword_meta}', ";
        $sql .= "`description_meta`='{$description_meta}', ";
		$sql .= "`url_category`='{$url_category}' ";
        $sql .= "WHERE `keyword_id`= {$keyword_id} ";
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }
   // added by snug xu 2007-06-28 12:40 - FINISHED

    function setDownLoadTime($p = array())
    { 
        global $conn;
        if (empty($p)) {
            return false;
        }
        if (is_array($p['article_id']) && !empty($p['article_id'])) {
            $conn->StartTrans();
            $q  = " UPDATE articles SET curr_dl_time = '".date('Y-m-d H:i:s', time())."' ".
                  " WHERE article_id IN ( "
                   .addslashes(htmlspecialchars(implode(',', array_values($p['article_id']))))." )";
            $conn->Execute($q);
            $ok = $conn->CompleteTrans();
        }

        if ($ok) {
            return true;
        } else {
            return false;
        }

    }//end setDownLoadTime()

    function sendClientRejectedArticleEmail($keyword_id, $comment) 
    {
        global $conn, $feedback, $mailer_param, $admin_host;
        $host = $admin_host;
        if (client_is_loggedin()) {
           $info = self::getInfoByKeywordID($keyword_id);
           // when client rejected let copywriter know the client's comments
           if ($info['article_status'] == 3) {
               $writer = User::getInfo($info['copy_writer_id']);
               $editor = User::getInfo($info['editor_id']);
               $mailer_param['from']       = $editor['email'];
               $mailer_param['reply_to']   = $editor['email'];
               $body  = "Dear {$writer['first_name']},<br />";
               $body .= "The client has rejected the following article and has provided a brief explanation of what needs to be fixed and re-submitted.<br /><br />";
               $body .= "Can you take a moment to address the change being requested by our client?<br /><br />";
               $body .= "Should you have any questions please address them to the Editor assigned to this campaign as they have also been CC'd on this automated email response.<br /><br />";
               $url = $host . "/article/article_set.php?article_id={$info['article_id']}&keyword_id={$info['article_id']}";
               $body .= "Article Title: <a href=\"{$url}\" />{$info['title']}</a><br /><br />";
               if (strlen($comment))
                    $body .= "{$comment}<br /><br />";
               $body .= "Thank you,";
               $subject = "Infinitenine Team's Announcement";
               $address = $writer['email'];
                if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                    $feedback .= ",but reject email didn't send out";
                    return false;
                } else {
                    //do nothing;
                    return true;
                    //$feedback = "Failuer：".$feedback.". please try again.";
                }
           }
        }
        return false;

    } // end sendClientRejectedArticleEmail
    function sendAnnouceMail($action = "reject", $keyword_id, $comment = '' )
    {
        global $conn, $feedback, $mailer_param;
        $host = "http://" . $_SERVER['HTTP_HOST'];
        $mailer_param['cc'] = array();
        
        // added by snug xu 2007-05-28 15:19 - STARTED
        // when action is approval and comment is empty, return
        if ($action == 'approval' && strlen($comment) == 0)
            return true;
       // added by snug xu 2007-05-28 15:19 - FINISHED

        if ($action == "reject" || $action == 'approval') {
            //require_once CMS_INC_ROOT.'/Campaign.class.php';
            //$keyword_info = Campaign::getKeywordInfo($keyword_id);

            $mailer_param['from_name'] = "Infinitenine Team's Announcement";

            if (user_is_loggedin()) {//editor reject.
                $editor_infos = User::getInfo(User::getID());
                if (strlen($editor_infos['email'])) {
                    $mailer_param['from']       = $editor_infos['email'];
                    $mailer_param['reply_to']  = $editor_infos['email'];
                }
                $q = "SELECT u.email, u.first_name, ck.keyword ".
                 "FROM campaign_keyword AS ck ".
                 "INNER JOIN users AS u ON (ck.copy_writer_id = u.user_id) ".
                 "WHERE ck.keyword_id = '".$keyword_id."'";

                // modified by snug xu 2007-05-28 15:21 - STARTED
                // action is approval, there is no event_id
                if ($action == 'reject') $event_id = 2;
                // modified by snug xu 2007-05-28 15:21 - FINISHED

            } else if (client_is_loggedin()) {
                $host = str_replace("cpclient.", "cp.", $host);
                if ($action == "reject") {
                    $mailer_param['cc'][0] = "stefan@infinitenine.com";
                    $q = "SELECT u.email, u.first_name, ck.keyword, ck.keyword_id, ar.article_id, cc.campaign_name, cc.campaign_id, ar.article_number, cp.user_name as cp_name, ct.user_name as ct_name ".
                     "FROM campaign_keyword AS ck ".
                     "INNER JOIN users AS u ON (ck.editor_id = u.user_id) ".
                     "INNER JOIN users AS cp ON (ck.copy_writer_id = cp.user_id) ".
                     "INNER JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
                     "INNER JOIN client AS ct ON (cc.client_id = ct.client_id) ".
                     "INNER JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                     "WHERE ck.keyword_id = '".$keyword_id."'";
                    $event_id = 5;
                    $cc_event_id = 6;
                }
            }
            $rs = $conn->Execute($q);
            if ($rs) {
                $address     = $rs->fields['email'];
                $first_name = $rs->fields['first_name'];
                $keyword    = $rs->fields['keyword'];
                $ext_info    = $rs->fields;
                $rs->Close();
            }
            if ($event_id > 0) {
                $q = "SELECT * FROM email_templates WHERE event_id = '".$event_id."'";
                $rs = $conn->Execute($q);
                if ($rs) {
                    $mails_template = array();
                    $mails_template = $rs->fields;
                    $rs->Close();
                }
            }
        }

        if ($action == "reject") {
            $subject = "Infinitenine Team's Announcement";

            $body = "";
            $body .= "Dear &nbsp;".$first_name."<br><br>";
            $body .= $mails_template['body']."<br><br>";
            if (client_is_loggedin()) {
                $url = $host . "/article/approve_article.php?article_id={$ext_info['article_id']}&keyword_id={$ext_info['keyword_id']}&campaign_id={$ext_info['campaign_id']}";
                $body .= "Keyword:<a href=\"{$url}\" >" . $keyword . "</a><br />";
                if (strlen($ext_info['article_number'])) {
                    $body .= "Article Number:&nbsp;{$ext_info['article_number']}<br />";
                }
                if (strlen($ext_info['cp_name'])) {
                    $body .= "Copywriter Writer:&nbsp;{$ext_info['cp_name']}<br />";
                }
                $body .= $address ."<br />";
                if (strlen($ext_info['ct_name'])) {
                    $body .= "Client Name:&nbsp;{$ext_info['ct_name']}<br />";
                }
                if (strlen($ext_info['campaign_name'])) {
                    $body .= "Campaign Name:&nbsp;{$ext_info['campaign_name']}<br />";
                }
            } else if (user_is_loggedin()) {
                $body .= "Keyword:".$keyword."<br />".$address ."<br />";
            }
            if ($comment != '') {
                $comment = stripslashes($comment);
                $body .= "Comments:<br />" . nl2br($comment) . "<br />";
            }
             $body .= "<br />";

            $body .= "<br>&copy;Copyright 2006 Copywriter Management System. All Rights Reserved. ";

    //        $address = "leo.liuxl@gmail.com";
            $mailer_param['cc'][] = "listerine@gmail.com";
        } else if ($action == 'approval' && $comment != '') {
            $subject = "Editor Comments";
            $body .= "Dear &nbsp;".$first_name."<br><br>";
            $body .= "Keyword:" . $keyword . "<br />";
            $comment = stripslashes($comment);
            $body .= "Comments:<br />" . nl2br($comment) . "<br />";
        }
        if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
            $feedback .= ",but {$subject} email didn't send out";
            return false;
        } else {
            //do nothing;
            return true;
            //$feedback = "Failuer：".$feedback.". please try again.";
        }
    }// end sendAnnouceMail()


    /**
	 *Added by Snug 16:40 2006-9-12
	 *
	 *Function Description: update Action Status
	 *
	 *@param int $action_status: 0表示未被审核，1表示审核邮件被发出
	 *@return bool
	 */
	function updateActionStatus( $action_status, $article_id )
	{
	    global $conn, $feedback;
		$action_status = mysql_escape_string( htmlspecialchars( trim( $action_status ) ) );
		$article_id = mysql_escape_string( htmlspecialchars( trim( $article_id ) ) );
		if( $article_id <=0 )
		{
			$feedback = "Please Select an Article!";
			return false;
		}
		if( is_numeric( $action_status ) )
		{
			$q = "UPDATE `articles` SET `action_status`='$action_status' where article_id='{$article_id}' ";
			$conn->Execute($q);
		} else {
			$feedback = "You Input The Evil Char!";
			return false;
		}
		return true;
	}//End Added

	/**
	 *Added by Snug 17:18 2006-9-12
	 *
	 *Function Description: send audit email
	 *
	 *@param int $keyword_id
	 *@param int $article_id
	 *@param int $campaign_id
	 *@param string $title
	 *@param string $content
	 *@return bool
	 */
	 function sendAuditEmail( $keyword_id, $article_id, $campaign_id, $title, $content  )
	{
		 global $mailer_param;
		 $host = "http://" . $_SERVER['HTTP_HOST'];
		 $url = $host."/article/approve_article.php?article_id=$article_id&keyword_id=$keyword_id&campaign_id=$campaign_id";
		 $body ="<div>";
		 $body .="<div><b>Article Title</b>&nbsp;&nbsp;</div><div><a href=\"$url\" >$title</a><br /><br /></div>";
		 $content = nl2br( stripslashes( $content ) );
		 $body .="<div><b>Article Content&nbsp;&nbsp;</b></div><div>$content</div>";
		 $body .="</div>";
		 $subject = "Article Approved by {$_SESSION['user_name']} on ".date("m/d/Y");
		 $address = 'stefan@infinitenine.com';
		 return send_smtp_mail( $address, $subject, $body, $mailer_param );
	}//End Added

    /**
     * get artciel list by con
     * @author xiannuan xu
     * @param $p, conditions
     * @param $fields, selected fileds
     * @param $maintable, sql main table
     * @param $left_joins, left join talbes
     * @return 
     */
    function getArticlesList($p = array(), $fields = array(), $maintable = 'articles AS ar', $left_joins = array())
    {   
        global $conn;
        $conditions = array();
        $orderby = isset($p['orderby']) ? $p['orderby'] : "\nORDER BY topic";
        if (!empty($p))
        {   
            $sub_cond = array();
            $article_status = 0;
            if (isset($p['article_status'])) {
                $article_status = $p['article_status'];

                if (is_array($article_status)) {
                    $i = 0;
                    $str = "";
                    $conditions[] = "ar.article_status IN ( " .implode(",", $article_status)." )";
                    foreach ($article_status as $as) {
                        if ($i > 0) {
                            $str = " OR ";
                        }
                        $last_status = self::getArticleLastStatus($as);
                        if ($last_status != 'failed') {
                            $q .= $str . "( aa.new_status = '".$as. "' AND " . " aa.status = '" . $last_status  . "' ) ";
                            $i++;
                        } else {
                            echo "<script>alert('please set the article status');</script>";
                            return null;
                        }
                    }
                    $sub_cond[] = " ( " . $q . " ) ";
                } else {
                    $conditions[] = "ar.article_status = {$article_status}";
                    $last_status = self::getArticleLastStatus($article_status);
                    if ($last_status != 'failed') {
                        $sub_cond[] =  "( aa.new_status = ".$article_status. " AND " .
                                    " aa.status = ".($last_status) . " ) ";
                    } else {
                        echo "<script>alert('please set the article status');</script>";
                        return false;
                    }
                }
            }
            unset($p['article_status']);

            if (isset($p['aa_start']))
            {
                $sub_cond[] =  "aa.created_time >= '{$p['aa_start']}'";
                unset($p['aa_start']);
            }

            if (!isset($p['aa_end'])) $p['aa_end'] = date("Y-m-d H:i:s");
            $sub_cond[] = "aa.created_time <= '{$p['aa_end']}'";
            unset($p['aa_end']);

//            if ($article_status == 6)
//            {
//                $sub_cond[] = "aa.new_status = 6";
//                $sub_cond[] = "aa.status = 5";
//            }
            if (isset($p['aa.curr_flag'])) 
            {
                $sub_cond[] = "aa.curr_flag = '{$p['aa.curr_flag']}'";
            }

            if (!empty($sub_cond)) $conditions[] = implode("\n AND ", $sub_cond);

            unset($p['aa.curr_flag']);
            foreach ($p as $key => $value)
            {
                if (is_array($value)) {
                    $conditions[] = "{$key} IN ('" . implode("', '", $value) . "')";
                } else {
                    $conditions[] = "{$key} = '{$value}'";
                }
            }
            if (isset($p['like']) && !empty($p['like']))
            {
                $likes = $p['like'];
                foreach ($likes as $k => $values)
                {
                    switch($k)
                    {
                    case 'left':
                        foreach ($values as $key => $value)
                        {
                            $conditions[] = "{$key} like '{$value}%'";
                        }
                        break;
                    case 'right':
                        foreach ($values as $key => $value)
                        {
                            $conditions[] = "{$key} like '%{$value}'";
                        }
                        break;
                    case "all";
                        foreach ($values as $key => $value)
                        {
                            $conditions[] = "{$key} like '%{$value}%'";
                        }
                        break;
                    }
                }
            }
        } else {
            $conditions[] = "1=1";
        }

        $query  =  'SELECT ' . (empty($fields) ? "DISTINCT  ar.article_id, ck.campaign_id,  ar.title, ar.html_title, p.pref_value AS topic, ck.description_meta,ck.keyword_meta,  ck.keyword, ar.body, ar.richtext_body  " : implode(", ", $fields));
        $query .= "\nFROM {$maintable}";
        if (empty($left_joins))
        {
            $left_joins[] = "campaign_keyword AS ck ON ck.keyword_id = ar.keyword_id";
            $left_joins[] = "article_action AS aa ON aa.article_id = ar.article_id";
            $left_joins[] = "preference AS p ON (p.pref_id = ck.keyword_category AND p.pref_field = 'keyword_category')";
        }
         $query .= "\nLEFT JOIN " . implode("\nLEFT JOIN ", $left_joins);
         $query .= "\nWHERE " . implode("\nAND ", $conditions);
         $query .= $orderby;

         $r = &$conn->Execute($query);
         $articles = array();
         if ($r) {
            while (!$r->EOF) {
                $articles[] = $r->fields;
                $r->MoveNext();
            }
            $r->Close();
        }
        return $articles;
    }

    /**
     *add by liu shu fen 15:01 2008-1-16
     *@function get article old status
     *@param $status
     $@reutrn $last_status string
     */
    function getArticleLastStatus($status) {
        switch($status) {
            case "6":
            case "5":
                $last_status = $status-1;
                break;
            case "4":
                $last_status = "failed";
                break;
            case "3":
                $last_status = 4;
                break;
            case "2":
                $last_status = "failed";
                break;
            case "1gc":
                $last_status = 1;
                break;
            case "1gd":
                $last_status = 1;
                break;
            case "1":
                $last_status = "failed";
                break;
            default :
                $last_status = "failed";
                break;
        }
        return $last_status;
    }

    function generateXML($items, $item_name)
    {
        global $database;
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<{$database}>";
        foreach($items as $k => $item)
        {
            $xml .= "<{$item_name}>";
            foreach($item as $key => $value)
            {
                $xml .= "<{$key}>{$value}</{$key}>";
            }
            $xml .= "</{$item_name}>";
        }
        $xml .= "</{$database}>";
        return $xml;
    }

    //add by Liu ShuFen 9:39 2007-11-14
    function getDownloadInfo($p) {
        global $conn;
        if ((trim($p['aa_start'])) == "") { 
            if (!empty($p['client_id'])) {
                $q = "SELECT imported_end FROM article_download_log WHERE curr_flag = 1".
                                        " AND client_id = ".$p['client_id'];
                $r = $conn->getAll($q);
                if ( !empty($r) ) {
                    foreach ($r as $value) {
                        $result['aa_start'] = $value['imported_end'];
                    }
                }
               
                $cmp_ids = self::getCampaignsByClientId($p['client_id']);
                
                if ( !empty($cmp_ids) ) {
                    $result['ck.campaign_id'] = $cmp_ids;
                }
            }
        } else {
            $result['aa_start'] = trim($p['aa_start']);
        }
        return $result;
    }//END

    //add by Liu ShuFen 18:44 2007-11-13
    function getCampaignsByClientId($client_id) {
        global $conn;
        if ( !empty($client_id) ) {
            $q = "SELECT campaign_id FROM client_campaigns WHERE client_id = " . $client_id;
            $rs = $conn->getAll($q);

            if ( !empty($rs) ) {
                foreach($rs as $arr) {
                    foreach($arr as $res) {
                        $result[] = $res;
                    }
                }
                return $result;
            } else {
                return null;
            }
        }
    }//END 
    
    /**
     *add by liu shu fen 16:25 2007-11-14
     *@function create a xml format file
     *@param $p array including xml tag
     *       $item_name string
     *@return $xml
     */
    function createXML($p, $item_name) {
        global $database;
        $title   = $p['title'];        
        $mk      = $p['mk'];
        $md      = $p['md'];
        $body    = $p['body'];
        $author  = $p['author'];
        $is_rich = $p['is_rich'];
        $topic   = $p['topic'];
        $result  = $p['result'];
        $ht      = $p['ht'];

        //start to write a xml file
        $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml .= "<{$database}>";
        
        foreach ($result as $item) {
            $xml .= "<{$item_name}>";
            if ($title == 1 ) {
                $xml .= "<title>{$item['title']}</title>";
            }
            if ($ht == 1 ){
                $xml .= "<html-title>{$item['html_title']}</html-title>";
            }
            if ($topic == 1 ) {
                 $xml .= "<topic>{$item['topic']}</topic>";
            }
            if ($mk == 1 ){
                $xml .= "<meta-keyword>{$item['keyword_meta']}</meta-keyword>";
            }
            if ($md == 1 ) {
                $xml .= "<meta-description>{$item['description_meta']}</meta-description>";
            }
            if ($body == 1 ) {
                $rich_content = htmlspecialchars_decode($val['body']);
                $tmp = preg_split("|\r\n|ims", $body, -1, PREG_SPLIT_NO_EMPTY);
                $intro = $tmp[0];
               // added by snug xu 2007-04-27 17:55 - FINISHED

                $xml .= "<intro><![CDATA[" . $intro . "]]></intro>";
                $xml .= "<rich-body><![CDATA[" . $rich_content . "]]></rich-body>";
            }
            $xml .= "</{$item_name}>";
        }
        $xml .= "</{$database}>";
        return $xml;
    }// Function end

    /**
     *add by Liu ShuFen 10:51 2007-11-26
     *@fucntion insert into article_download_log table to record the client download article history
     *@param $p: $p['cid']:campaign ids 
     *@return 
    */   
    function articleDownloadLog($p) {
        global $conn;
        require_once CMS_INC_ROOT . "/Client.class.php";
        
        $client_id = isset($p['client_id'])? $p['client_id'] : Client::getID();
        $conn->StartTrans();
        
        //modify old client download log record, set the curr_flag form 1 to 0
        //it means this record is the old one
        $q = "UPDATE `article_download_log` SET curr_flag = 0 WHERE client_id = ".$client_id ." AND curr_flag = 1 ";
        $conn->Execute($q);

        $cmp_ids = implode(";", $p['cid']);
        $ar_ids = implode(";", $p['article_ids']);
        $imported_end = date("Y-m-d H:i:s");
        
        //insert into new client download record
        $sql = " INSERT INTO `article_download_log` (`client_id` ,`campaign_ids` ,`article_ids` ,".
                                              "`imported_start` ,`imported_end` ,`curr_flag`) ".
                             " VALUES (" . $client_id. ", ".
                                       " '". $cmp_ids ."', ".
                                       " '". $ar_ids ."', ".
                                       " '". $p['time'] ."', ".
                                       " '". $imported_end ."', '1')";
        $conn->Execute($sql); 
        $ok = $conn->CompleteTrans();
        if ($ok) {
            return true;
        } else {
            return false;
        }
    }//END

	/**
	 *add by chester 10:51 2008-09-26
     * @param array $p
     * @return array $result
     */
    function getAcademicinfoByParam($p = array()) {
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
        } else {
            $conditions[] = "(aa.created_time <= '{$publish_end}' )";
        }

        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $campaign_id = $p['campaign_id'];
            if (is_array($campaign_id))
                $conditions[] = "ck.campaign_id IN (" . implode(',', $campaign_id) . ")";
            else if ($campaign_id > 0)
                $conditions[] = "ck.campaign_id = {$campaign_id}";
        }
 
        $sql  = "SELECT ar.title, ar.richtext_body, ar.article_status, ck.keyword_category, ck.keyword_meta, ck.description_meta, ck.keyword, ck.url_category ";
        $sql .= "FROM articles AS ar ";
        $sql .= "LEFT JOIN article_action AS aa ON (ar.article_id = aa.article_id AND aa.status = 5 AND aa.new_status = 6 )";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.keyword_id ";
        $sql .= "WHERE 1=1 ";
        if (!empty($conditions)) $sql .= "AND " . implode(" AND ", $conditions);
        //fwrite($debugh, $sql);
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $result[] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }

	 function academicinfoXML($p = array(), $campaign_id = array()) {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "<campaign>";
        if (!empty($campaign_id))
            $condition = array('campaign_id' => $campaign_id);
        foreach ($p as $k=> $item) {
		$item['url_category'] = trim($item['url_category']);
            $url = !empty($item['url_category']) ? $item['url_category']:'unknown';
            $xml .= "<article url=\"{$url}\">";
			$title = htmlspecialchars (html_entity_decode($item['title'], ENT_QUOTES));
			$xml .= "<title>{$title}</title>";
            $xml .= "<body>";
			$content = html_entity_decode($item['richtext_body'], ENT_QUOTES);
			$intro = self::get_first_paragraph($content);
			$xml .= "<intro><![CDATA[" . $intro . "]]></intro>";
			$xml .= "<block count=\"0\"><![CDATA[" . $content . "]]></block>";
			$xml .= "</body></article>"; 
        }
        $xml .= "</campaign>";
        return $xml;
    }
	function get_first_paragraph($paragraph) {
		$patten = "|(.*)<p[^>]*>(.*)</p>|imsU";
		$patten2 = "|(.*)<br[ /]*>[\s]*<br[ /]*>|imsU";
		$first_paragraph = '';
		if (preg_match_all($patten, $paragraph, $matches, PREG_PATTERN_ORDER)) {
			$first_paragraph = self::get_first_paragraph_from_matches($matches[1], $patten2);
			if (strlen($first_paragraph) == 0)  $first_paragraph = self::get_first_paragraph_from_matches($matches[2], $patten2);
		} else if (preg_match_all($patten2, $paragraph, $matches, PREG_PATTERN_ORDER)) {
			$first_paragraph = self::get_first_paragraph_from_matches($matches[1]);
		}
		return $first_paragraph;
	}

	function get_first_paragraph_from_matches($matches, $patten = null, $check_arr_index = 1) {
		$result = '';
		if (is_array($matches)) {
			foreach ($matches as $match) {
				$match = trim($match);
				if (strlen($match)) {
					if (strlen($patten) && preg_match_all($patten, $match, $sub_matches, PREG_PATTERN_ORDER)) {
						$result = self::get_first_paragraph_from_matches($sub_matches[$check_arr_index], null);
						if (strlen($result)) break;
					} else {
						return $match;
					}
				}
			}
		} else if (is_string($matches)) {
			$result = $matches;
		}
		return $result;
	}//end

}//end class Article
?>
