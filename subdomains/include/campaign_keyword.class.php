<?php
class CampaignKeyword{

    function CampaignKeyword()
    {
        $this->__construct();
    }

	function __construct()
	{

	}

    function __getSourceId($cid)
    {
        $source = -1;
        $rs = Campaign::getInfoFields($cid, array('source'));
        if (!empty($rs)) $source = $rs['source'];
        return $source;
    }

    function __checkOrderPrivilege($oid, $api_info) // $oid: campaign order id
    {
        global $feedback, $conn;
        $sql = "SELECT source FROM order_campaigns WHERE order_campaign_id={$oid} ";
        $source = $conn->GetOne($sql);
        $client_user_id = $api_info['client_user_id'];
        if ($source > 0 && $source == $client_user_id) {
            return 1;
        } else if ($source >= 0 ) {
            // $feedback = 'Pemission deny, you can\'t create article for this campaign';
            return 0;
        } else if ($source == -1) {
            $feedback = 'Invalid Campaign ID, please check your post';
        }
        return -1;
    }

    function __checkPrivilege($cid, $api_info)
    {
        global $feedback;
        if ($api_info) {
            if (isset($api_info['client_user_id'])) {
                $client_user_id = $api_info['client_user_id'];
                if ($client_user_id == 3) return 1;
                $source = $this->__getSourceId($cid);
                if ($source > 0 && $source == $client_user_id) {
                    return 1;
                } else if ($source >= 0 ) {
                    // $feedback = 'Pemission deny, you can\'t create article for this campaign';
                    return 0;
                } else if ($source == -1) {
                    $feedback = 'Invalid Campaign ID, please check your post';
                }
            } else {
                return 1;
            }
        }
        return -1;
    }

    function addComment($p = array())
    {
        global $feedback, $conn;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        if (!isset($p['language'])) $p['language'] = 'en';
        if (!isset($p['creation_role'])) $p['creation_role'] = 'client';
        extract($p);
        if (empty($article_id)) { 
            $feedback = 'Please specify the article';
            return false;
        }
        if ($comment == '') {
            $feedback = 'Please specify the comment for this article';
            return false;
        }
        
        $conditions = array('creation_user_id=' . $client_id, "creation_role='{$creation_role}'");
        $conditions[] = "article_id='{$article_id}'";
        $conditions[] = "comment='{$comment}'";
        $conditions[] = "version_number='{$version_number}'";
        $q = "SELECT COUNT(*) AS count FROM comments_on_articles ". "WHERE " . implode(" AND ", $conditions);
        $count = $conn->GetOne($q);

        if ($count > 0) {
            $feedback = 'You added this comment for this article';
            return false;
        }

        //add comments
        if ($comment != '') {//do comment
            unset($p['client_id']);
            $p['creation_user_id'] = $client_id;
            $p['creation_date'] = date("Y-m-d H:i:s");
            $comment_id = $conn->GenID('seq_comments_on_articles_comment_id');
            $p['comment_id'] = $comment_id;
            $q = "INSERT INTO comments_on_articles (`" . implode("`,`", array_keys($p)). "`) VALUES ('" . implode("', '", $p). "') ";
            $conn->Execute($q);
        }
        if ($conn->Affected_Rows() > 0) {
            return $comment_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }

    function getComments($data, $api_info)
    {
        global $feedback;
        $article_ids = array();
        foreach ($data as $item) {
             $id = $item['articleid'];
             if (empty($id)) continue;
             if (is_array($id)) {
                $article_ids  = array_merge($article_ids, $item['articleid']);
             } else {
                 $article_ids[] = $id;
             }
        }
        $rs = $this->__getArticlesByIds($article_ids, array('ar.article_id', 'ck.campaign_id'), null, array(), false);
        $result = $ids = array();
        foreach ($rs as $item) {
            $rs_privilege = $this->__checkPrivilege($item['campaign_id'], $api_info);
            $article_id = $item['article_id'];
            if ($rs_privilege == 0) {
                $result[] = array('article_id' => $article_id, 'memo' => 'Pemission deny, you can\'t get comments for this article');
            } else {
                $ids[] = $article_id;
            }
        }
        $diff_ids = array_diff($article_ids, $ids);
        foreach ($diff_ids as $id) {
            $result[] = array('article_id' => $id,  'memo' => 'Invalid Article, this article is not exist');
        }
        $comments = $this->getCommentsByArticleIds($ids);
        
        if (!empty($comments)) {
            foreach ($ids as $k => $v) {
                $data = array('article_id' => $v);
                $data['articlecomments'] = array(
                    'results' => array('articlecomment' => $comments[$v])
                );
                $result[] = $data;
             }
        }
        return $result;
    }

    function getCommentsByArticleIds($article_ids)
    {
        global $feedback, $conn;
        if (empty($article_ids)) {
            $feedback = 'Please specify the article';
            return false;
        }
        $sql = "SELECT coa.article_id, coa.comment, coa.version_number, coa.creation_date AS timestamp, IF(coa.creation_role = 'client', c.user_name, coa.creation_role) AS creator ";
        $sql .= "FROM comments_on_articles  AS coa ";
        $sql .= 'LEFT JOIN users AS u ON (u.user_id = coa.creation_user_id) ';
        $sql .= 'LEFT JOIN client AS c ON (c. client_id  = coa.creation_user_id)  ';
        if (is_array($article_ids)) {
            $sql .= ' WHERE coa.article_id IN (\'' . implode("','", $article_ids) .  '\') ';
        } else {
            $sql .= ' WHERE coa.article_id =\'' . $article_ids .  '\'';
        }
        if (client_is_loggedin()) {
            $sql .= ' AND coa.creation_role IN (\'client\', \'admin\', \'project manager\') ';
        }
        $result = $conn->GetAll($sql);
        $data = array();
        foreach ($result as $item) {
            $article_id = $item['article_id'];
            unset($item['article_id']);
            if (!isset($data[$article_id])) {
                $data[$article_id] = array($item);
            } else {
                $data[$article_id][] = $item;
            }
        }
        return $data;
    }

    function addComments($data, $api_info, $transaction)
    {
        global $feedback, $conn;
        if ($transaction) {
            $conn->StartTrans();
            $failed_result = array();
        }
        foreach ($data as $row) {
            $cid = $row['campaignid'];
            $article_id = $row['articleid'];
            $comment = $row['comment'];

            $arr = array(
                'article_id'=> $article_id,
                'campaign_id'=> $cid,
            );
            if (isset($row['sequence'])) {
                $arr['sequence'] = $row['sequence'];
            }
            $failed_arr = $arr;
            if ($cid > 0) {
                $rs_privilege = $this->__checkPrivilege($cid, $api_info);
                if ($rs_privilege == 1) {
                    if ($article_id > 0) {
                        $p['article_id'] = $article_id;
                        $p['comment'] = $comment;
                        $p['client_id'] = isset($api_info['client_id']) ? $api_info['client_id'] : 0;
                        $tmp = $this->__getArticlesByIds($article_id,  array('ar.current_version_number as version_number'), null, array(), false);
                        if (!empty($tmp)) {
                            $p['version_number'] = $tmp[0]['version_number'];
                            if ($comment_id = $this->addComment($p))  $feedback = '';
                        } else {
                            $feedback = 'Invalid Article, please check the article you specify';
                        }
                    } else {
                        $feedback = 'Invalid Article, please check the article you specify';
                    }
                } elseif($rs_privilege == 0) {
                    $comment_id = null;
                    $feedback = 'Pemission deny, you can\'t create comment for this article';
                }
            } else {
                $feedback = 'Pemission deny, please specify the campaign';
            }
            if (!empty($feedback)) {
                $arr['memo'] = $feedback;
                $arr['status'] = 'Declined';
            } else {
                $arr['status'] = 'Accepted';
                $arr['comment_id'] = $comment_id;
            }
            $result[] = $arr;
            if ($transaction) {
                $failed_arr['status'] = 'Declined';
                $failed_arr['memo'] = 'Failure, Please try again';
                $failed_result[] = $failed_arr;
            }
        }
        if ($transaction) {
            $ok = $conn->CompleteTrans();
            if (!$ok) {
                $result = $failed_result;
            }
        }
        return $result;
    }

    function addArticles($data, $campaign_id, $api_info, $transaction = false)
    {
        global $feedback, $conn;
        $result  = array();
        $new_keyword_campaigns = array();
        if ($transaction) {
            $conn->StartTrans();
            $failed_result = array();
        }
        foreach ($data as $row) {
            if (isset($row['campaignid'])) {
                $cid = $row['campaignid'];
            } else {
                $cid = $campaign_id;
            }
            $arr = array(
                'articletype'=> $row['articletype'],
                'vertical'=> $row['vertical'],
                'keyword'=> $row['keyword'],
                'campaignid'=> $cid,
            );
            if (isset($row['sequence'])) {
                $arr['sequence'] = $row['sequence'];
            }
            $failed_arr = $arr;
            if ($cid > 0) {
                $rs_privilege = $this->__checkPrivilege($cid, $api_info);
                if ($rs_privilege == 1) {
                    $new_keyword_campaigns[] = $cid;
                    if (!$transaction) $conn->StartTrans();
                    $article_id = $this->addKeyword($row, $cid);
                    if (!$transaction) $ok = $conn->CompleteTrans();
                    if ($article_id > 0) {
                       $articletype = trim($row['articletype']);
                        if ($article_type == '') {
                            $article_type = 1;
                        }
                        $arr['articletype'] = $article_type;
                        if (!$transaction) {
                            if ($ok) {
                                $arr['article_id'] = $article_id;
                                $arr['status'] = 'Accepted';
                            } else {
                                $feedback = 'Failure, Please try again';
                                $arr['status'] = 'declined';
                                $arr['memo'] = $feedback;
                            }
                        } else {
                            $arr['article_id'] = $article_id;
                            $arr['status'] = 'Accepted';
                            $failed_arr['memo'] = 'Failure, Please try again';
                            $failed_arr['status'] = 'declined';
                        }
                    }
                } elseif($rs_privilege == 0) {
                    $article_id = null;
                    $feedback = 'Pemission deny, you can\'t create article for this campaign';
                }
            } 
            if (empty($article_id)) {
                $arr['status'] = 'declined';
                $arr['memo'] = $feedback;
                if ($transaction) $failed_arr = $arr;
            }
            $result[] = $arr;
            if ($transaction) $failed_result[] = $failed_arr;
        }
        $param = array('has_new' => 1);
        $new_keyword_campaigns = array_unique($new_keyword_campaigns);
        if ($transaction) {
            if (!empty($new_keyword_campaigns)) {
                foreach ($new_keyword_campaigns as $k => $cid) {
                    Campaign::setCampaignFieldsById($param, $cid);
                }
            }
            $ok = $conn->CompleteTrans();
            if (!$ok) {
                $result = $failed_result;
            }
        } else {
            if (!empty($new_keyword_campaigns)) {
                foreach ($new_keyword_campaigns as $k => $cid) {
                    Campaign::setCampaignFieldsById($param, $cid);
                }
            }
        }
        return $result;
    }

    function setArticleStatus($data, $opt, $api_info)
    {
        global $feedback;
        $result = array();
        foreach ($data as $row) {
            $articleid = $row['articleid'];
            if (!empty($articleid) &&is_numeric($articleid)) {
                $hash = array('article_status' => $opt);
                $arr = array('articleid' => $articleid);
                // added by nancy xu 2010-10-23 16:15
                // let user add comments when rejected article or approved article
                $comment = '';
                if (isset($row['memo'])) {
                    $comment = $row['memo'];
                }
                if (isset($row['comment'])) {
                    $comment = $row['comment'];
                }
                if (!empty($comment)) {
                    $hash['comment'] = $comment;
                } // end
                if ($opt == 5) {
                    $hash['client_approval_date'] = date("Y-m-d H:i:s");
                    $arr['status'] = 'Approved';
                    $action = 'capproval';
                } else {
                    $arr['status'] = 'Rejected';
                    if (!empty($comment)) {
                        $hash['rejected_memo'] = $comment;
                    }
                    $action = 'creject';
                }
                //$old_article     = Article::getInfo($articleid, false);

                $campaign_id = isset($row['campaignid']) ? $row['campaignid'] : 0;
                $info = $this->_updateArticleStatusById($hash, $articleid, $campaign_id, $api_info);
                if (!empty($info)) {
                    $arr = array_merge($arr, $info);
                    // sent comments to editor
                    if (!empty($comment)) {
                        Article::autoSendAnnouceMail($action, $articleid, $comment);
                    }
                } else {
                    $arr['status'] = 'Failed';
                }
                if (!empty($feedback)) $arr['memo'] = $feedback;
                $result[] = $arr;
            }
        }
        return  $result;
    }

    function cancelArticles($data, $campaign_id, $api_info)
    {
        global $feedback;
        $result = array();
        $rs_privilege = 1;
        if ($campaign_id > 0) {
            $rs_privilege = $this->__checkPrivilege($campaign_id, $api_info);
            if ($rs_privilege == 0) {
                $feedback = 'Pemission deny, you can\'t cancel article for this campaign';
            } else if ($rs_privilege == 1) {
                $feedback = null;
            }
        }
        foreach ($data as $row) {
            if ($rs_privilege) {
                $fields = array(
                    'ck.status' => 'D',
                    'ck.cancel_memo' => $row['memo'],
                );
                $articleid = $row['articleid'];
                if (!empty($articleid) &&is_numeric($articleid)) {
                    if ($this->_cancelArticleById($fields, $articleid, $campaign_id)) {
                        $arr = array('article_id' => $articleid, 'status' => 'Canceled');
                    } else {
                        $arr = array('article_id' => $articleid, 'status' => 'UnCanceled', 'memo' => $feedback);
                    }
                }
            } else {
                $arr = array('article_id' => $articleid, 'campaign_id'=> $campaign_id, 'status' => 'UnCanceled', 'memo' => $feedback);
            }
            $result[] = $arr;
        }
        return $result;
    }

    function _updateArticleStatusById($fields, $id, $campaign_id, $api_info)
    {
        global $conn, $feedback;
        $id = addslashes(htmlspecialchars(trim($id)));
        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        $sql = "SELECT ck.copy_writer_id, ck.editor_id, ck.campaign_id, ar.is_canceled, ck.article_type, ar.article_id, ar.article_status, ar.title, ar.current_version_number AS version,ar.total_words AS length, ar.body as textBody, ar.richtext_body AS htmlBody, aaf.small_image as smallImage, aaf.large_image as largeImage, aaf.image_credit as imageCredit, aaf.image_caption AS imageCaption, aaf.meta_description as metaDescription, aaf.blurb, aaf.category_id AS category, cc.template "
                ."FROM campaign_keyword AS ck LEFT JOIN articles AS ar on (ck.keyword_id = ar.keyword_id) LEFT JOIN article_additional_fields AS aaf ON (ar.article_id = aaf.article_id) LEFT JOIN client_campaigns  AS cc ON (cc.campaign_id = ck.campaign_id) "
                . "WHERE ar.keyword_id=ck.keyword_id AND ar.article_id='" . $id . "' ";
        if ($campaign_id > 0) $sql .= "  AND ck.campaign_id='" . $campaign_id . "' ";
        $result = $conn->GetRow($sql);
        if (empty($result)) {
            $feedback = 'Invalid Article Id';
            if ($campaign_id > 0) $feedback .= ' for This Campaign';
            return false;
        } else {
            if ($result['template'] <> 2) {
                unset($result['smallImage']);
                unset($result['largeImage']);
                unset($result['imageCredit']);
                unset($result['imageCaption']);
                unset($result['metaDescription']);
                unset($result['blurb']);
                unset($result['category']);
            }
            unset($result['template']);
        }
        $article_status = $result['article_status'];
        // added by nancy xu 2010-10-23 15:45
        $comment = $fields['comment'];
        unset($fields['comment']);
        if (!empty($comment)) {
            $create_info = array();
            $create_info['user_id'] = $api_info['client_id'];
            $create_info['role'] = 'client';
            $article_info = $result;
            $article_info['current_version_number'] = $result['version'];
            Article::autoAddComments($comment, $article_info, $create_info);
        }// end
        if ($article_status == '4') {
            $info = $result;
            $new_status = $fields['article_status'];
            Article::getArticleActionInfo($info, $id, 3, $api_info);
            $info['new_status'] = $new_status;
            ArticleAction::store($info);
            // added by nancy xu 2010-09-23 14:49
            // when articles is set as client approval, we will add this article to article payment log
            if ($new_status == '5') {
                //$info['campaign_id'] = $campaign_id;
                $info['client_id'] = $api_info['client_id'];
                ArticlePaymentLog::storeFromClientApproval(strtotime($fields['client_approval_date']), $info);
            }
            // end
            $sql = "UPDATE articles AS ar SET ";
            $sets = array();
            foreach ($fields as $k => $value) {
                $sets[] = "{$k}='{$value}'";
            }
            $sql .= implode(",", $sets);
            $id = addslashes(htmlspecialchars(trim($id)));
            $sql .= "WHERE ar.article_id='" . $id . "'";
            $conn->Execute($sql);
            unset($result['article_status']);
            unset($result['copy_writer_id']);
            unset($result['version']);
            return $result;
        } else {
            if ($article_status == '5' || $article_status == '6' ) {
                $feedback = 'This article was finished, you can\'t change the status';
            } else {
                $feedback = 'Please wait the editor to approve the article.';
            }
            return false;
        }
    }
    
    function _cancelArticleById($fields, $id, $campaign_id)
    {
        global $conn, $feedback;
        $id = addslashes(htmlspecialchars(trim($id)));
        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        $sql = "SELECT ck.keyword_id, ck.copy_writer_id,ar.article_status FROM campaign_keyword AS ck, articles AS ar WHERE ar.keyword_id=ck.keyword_id AND ar.article_id='" . $id . "' ";
        if ($campaign_id > 0) $sql .= " AND ck.campaign_id='" . $campaign_id . "' ";
        $result = $conn->GetRow($sql);
        if (empty($result)) {
            $feedback = 'Invalid Article ID';
            if ($campaign_id > 0) $feedback .= ' for This Campaign';
            return false;
        }
        $copy_writer_id = $result['copy_writer_id'];
        $keyword_id = $result['keyword_id'];
        if ($copy_writer_id  > 0) {
            $feedback = 'This article was started, can\'t be canceled';
            return false;
            require_once CMS_INC_ROOT . "/article_payment_log.class.php";
            $log = ArticlePaymentLog::getPaymentLogByParam(array('user_id' => $copy_writer_id, 'article_id' => $id, 'role' => 'copy writer'));
            if (!empty($log) && empty($log[0]['is_canceled'])) {
                $month = $log[0]['pay_month'];
                $paid_time = $log[0]['paid_time'];
                if (!empty($paid_time)) {
                    $feedback = 'This article has paid, you can\'t canceled it';
                    return false;
                }
                ArticlePaymentLog::store(array('is_canceled' => 1), $log[0]['log_id']);
            }
            unset($fields['ck.status']);
            $fields['ar.is_canceled'] = 1;
            $fields['ck.status'] = 'D';
        }
        $conn->StartTrans();
        $sql = "UPDATE campaign_keyword AS ck, articles AS ar SET ";
        $sets = array();
        foreach ($fields as $k => $value) {
            $sets[] = "{$k}='{$value}'";
        }
        $sql .= implode(",", $sets);
        $id = addslashes(htmlspecialchars(trim($id)));
        $sql .= "WHERE ar.keyword_id=ck.keyword_id AND ar.article_id='" . $id . "' AND ck.keyword_id=" . $keyword_id;
        $conn->Execute($sql);
        $sql = 'DELETE FROM `article_status` where article_id=\'' . $id . '\'';
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        return $ok;
    }

    /**
     * Add an client's campaign keyword information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function addKeyword($p = array(), $campaign_id)
    {
        global $conn, $feedback;
        if (empty($campaign_id)) {
            $feedback = "Please specify the campaign";
            return false;
        }
        $data = array();
        //generate article number
        $campaign_info = Campaign::getInfo($campaign_id);
        if (empty($campaign_info)) {
            $feedback = "Invalid Campaign,please try again";
            return false;
        }
        foreach ($p as $k => $v) {
            $v = stripslashes($v);
            $p[$k] = addslashes(trim($v));
        }
        $keyword = $p['keyword'];
        if ($keyword == '') {
            $feedback = "Please enter the keyword of the campaign";
            return false;
        }
        $data['keyword'] = $keyword;
        
        $article_type = $p['articletype'];
        if ($article_type == '') {
            $article_type = 1;
        }
        $data['article_type'] = $article_type;

        $title = $p['title'];
        $keyword_description =$p['styleguide'];
        $data['keyword_description'] = htmlspecialchars($keyword_description);
        $deadline = $p['deadline'];
        $data['deadline'] = $deadline;
        $translation = $p['translation'];
        $data['translation'] = $translation;
        $length = $p['length'];
        $data['length'] = $length;
        $vertical = $p['vertical'];
        $data['vertical'] = $vertical;

        $keyword_status = -1;
        $data['keyword_status'] = $keyword_status;

        $copy_writer_id = 0;
        $editor_id = 0;
        $data['copy_writer_id'] = $copy_writer_id;
        $data['editor_id'] = $editor_id;


        $data['campaign_id'] = $campaign_id;
        $company_name = strtoupper($campaign_info['company_name']);
        $numbers = explode(" ", $company_name);//we can use preg_split()
        $article_number = "";
        foreach ($numbers as $k => $v) {
            $article_number .= substr($v, 0, 1);
        }

        if (isset($p['mappingid']) && !empty($p['mappingid'])) {
            $data['mapping_id'] = $p['mappingid'];
        }
        if (isset($p['subcid']) && !empty($p['subcid'])) {
            $data['subcid'] = $p['subcid'];
        }
        foreach ($p as $kk => $vv) {
            if (substr($kk, 0, 8) == 'optional') {
                $data[$kk] = $vv;
            }
        }
        /*if (isset($p['optional1']) && !empty($p['optional1'])) {
            $data['optional1'] = $p['optional1'];
        }
        if (isset($p['optional2']) && !empty($p['optional2'])) {
            $data['optional2'] = $p['optional2'];
        }
        if (isset($p['optional3']) && !empty($p['optional3'])) {
            $data['optional3'] = $p['optional3'];
        }
        if (isset($p['optional4']) && !empty($p['optional4'])) {
            $data['optional4'] = $p['optional4'];
        }*/

        // added by nancy xu 2009-12-16 11:18
        if (isset($p['datestart']) && !empty($p['datestart'])) {
            $date_start = $p['datestart'];
        } else {
            $date_start = date("Y-m-d H:i:s");
        }
        $data['date_start'] = $date_start;
        if (isset($p['dateend']) && !empty($p['dateend'])) {
            $date_end = $p['dateend'];
        } else {
            $date_end = date("Y-m-d H:i:s", strtotime("+7 days"));
        }
        $data['date_end'] = $date_end;
        // end
        $date_created = date('Y-m-d H:i:s');
        $data['date_created'] = $date_created;
        $date_assigned = '0000-00-00 00:00:00';
        $data['date_assigned'] = $date_assigned;
        $keyword_ids = array();
        if ($keyword != '') {
            $keyword_category = 0;
            $q = "SELECT COUNT(*) AS count FROM campaign_keyword ".
                 "WHERE keyword = '".$keyword."' AND campaign_id = '".$campaign_id."' AND article_type = '".$article_type."' AND `keyword_category` = ".$keyword_category;
            $rs = $conn->Execute($q);
            $count = 0;
            if ($rs) {
                $count = $rs->fields['count'];
                $rs->Close();
            }
            if ($count > 0) {
                $duplicated_keywords[] = $keyword;
                // modified by nancy xu 2009-12-03 15:49
                // when keyword are duplicated, the keyword still store to our system 
                // continue;
                // end
            }

            $keyword_id = $conn->GenID('seq_campaign_keyword_keyword_id');
            $keyword_ids[] = $keyword_id;
            $new_keywords[$keyword_id] = stripslashes($keyword);
            $data['creation_user_id'] = 0;
            $data['creation_role'] = 0;
            $data['keyword_id'] = $keyword_id;
            $fields = array_keys($data);
            $q = "INSERT INTO campaign_keyword (" . implode(',', $fields). ") VALUES ('" . implode("', '",  $data) . "')";
            $conn->Execute($q);

            $article_id = $conn->GenID('seq_articles_article_id');
            $data = array(
                'article_id' => $article_id,
                'article_number' => $article_number ."-".($article_type+1)."-".$campaign_id."-".$keyword_id,
                'keyword_id' => $keyword_id,
                'title' => $title,
                'article_status' => 0,
                'current_version_number' => '1.0',
                'language' => '',
                'body' => '',
                'creation_date' => date('Y-m-d H:i:s', time()),
                'creation_user_id' => 0,
                'creation_role' => 0,
            );
            $fields = array_keys($data);
            $q = "INSERT INTO articles (`" . implode("`,`", $fields). "`) VALUES ('" . implode("', '", $data) . "')";
            $conn->Execute($q);
            $q = "INSERT INTO article_status (`article_id`) VALUES ('" . $article_id . "')";
            $conn->Execute($q);
        }
        return $article_id;
    }//end addKeyword()

    function getArticleStatusByCampaignID($campaign_id, $api_info)
    {
        global $feedback;
        $result = array();
        $rs_privilege = $this->__checkPrivilege($campaign_id, $api_info);
        if ($rs_privilege == 1) {
            $result = $this->_getArticleStatus($campaign_id);
            $feedback = null;
        } else if ($rs_privilege == 0) {
            $feedback = 'Pemission deny, you can\'t get article status for this campaign';
        }
        if (!empty($feedback)) {
            $result = array(array('campaign_id' => $campaign_id, 'memo' => $feedback));
        }
        return $result;
    }

    function _getArticleStatus($campaign_id, $ids = null, $fields = array())
    {
        if (empty($fields)) $fields = array('article_id', 'ar.article_status AS status', 'ck.date_end AS estimateddate', 'ck.copy_writer_id');
        $result = $this->__getArticlesByIds($ids, $fields , $campaign_id, array(), false);
        $article_ids = array();
        if (is_array($result)) {
            foreach ($result as $k => $row) {
                unset($result[$k]['copy_writer_id']);
                $result[$k]['status'] = $this->__getArticleStatus($row);
                $article_ids[] = $row['article_id'];
                
            }
        } else {
            $result = array();
        }
        if (!empty($ids)) {
            $ids = array_diff($ids, $article_ids);
            $total = count($result);
            $i = $total;
            foreach ($ids as $id) {
                $result[$i] = array('articleid' => $id, 'status' => 'Not Ready', 'memo' => 'Invailed Article Id');
                $i++;
            }
        } else if (empty($result)) {
            $result = array(array('memo' => 'There is no article for this campaign, please check post campaign id'));
        }
        return $result;
    }

    function getArticleStatus($data, $campaign_id)
    {
        $ids = $this->getArticleIds($data);
        return $this->_getArticleStatus($campaign_id, $ids);
    }

    function downloadArticlesByCampaignId($campaign_id, $api_info, $param = array())
    {
        global $feedback;
        $feedback = null;
        $result = array();
        $rs_privilege = $this->__checkPrivilege($campaign_id, $api_info);
        if ($rs_privilege) {
            $conditions = array();
            if (isset($param['lastgettime'])) {
                $lastgettime = $param['lastgettime'];
                if ($lastgettime > 0) {
                    $conditions[] ='ar.updated > \'' . $lastgettime . '\'';
                }
            }
            if (isset($param['iscompleted']) && strtolower($param['iscompleted']) == "all") {
                //Do Nothing;
            } else {
                $conditions[] = "ar.article_status REGEXP  '^(5|6|99)$'";
            }
            $result = $this->__getArticles(null, $campaign_id, array(), $conditions);
        } else if ($rs_privilege == 0) {
            $feedback = 'Pemission deny, you can\'t download article for this campaign';
            return false;
        }
       return $result;
    }

    function __getArticleStatus($row)
    {
        $status = $row['status'];
        if ($status == 6  || $status == 5) {
            $article_status = 'Completed';
        } else if ($status == 3) {
            $article_status = 'Client reject';
        } else if ($status == 4) {
            $article_status = 'Pending Review';
        } else if ($status == 0 && empty($row['copy_writer_id'])) {
            $article_status = 'Unassigned';
        } else if ($status == 0 &&  $row['copy_writer_id'] > 0) {
            $article_status = 'Writing';
        } else {
            $article_status = 'Not Ready';
        }
        return $article_status;
    }

    function __getArticles($ids, $campaign_id, $fields = array(), $conditions = array())
    {
        $result = $this->__getArticlesByIds($ids, $fields, $campaign_id, $conditions);
        $completed_ids = array();
        if (is_array($result)) {
            foreach ($result as $k => $row) {
                $completed_ids[] = $row['article_id'];
                $result[$k]['status'] = $this->__getArticleStatus($row);
                unset($result[$k]['copy_writer_id']);
                if (isset($row['textBody']) && !empty($row['textBody'])) {
                    $result[$k]['textBody'] = str_replace("]]>", "", $row['textBody']);
                }
                if (isset($row['title']) && !empty($row['title'])) {
                    $result[$k]['title'] = str_replace("]]>", "", $row['title']);
                }
            }
        } else {
            $result = array();
        }
        $start_index = count($result);
        if (is_array($ids) && !empty($ids)) {
            $ids = array_diff($ids, $completed_ids);
            $memo = 'Invalid Article ID';
            if ($campaign_id > 0) {
                $memo .= ' for This Campaign';
            }
            foreach ($ids as $k => $id) {
                $result[$k+$start_index] = array(
                    'article_id' => $id,
                    'status' => 'Not ready',
                    'memo' => $memo,
                );
            }
        }
        return $result;
    }

    function downloadArticles($data, $campaign_id)
    {
        global $feedback;
        if ($campaign_id > 0) {
            $rs_privilege = $this->__checkPrivilege($campaign_id, $api_info);
            if ($rs_privilege <= 0){
                if ($rs_privilege  == 0) $feedback = 'Pemission deny, you can\'t download article for this campaign';
                $result = array('campaign_id' => $campaign_id, 'memo' => $feedback);
                return $result;
            }
        }
        $ids = $this->getArticleIds($data);
        $result = $this->__getArticles($ids, $campaign_id);
        return $result;
    }

    function getArticlesByFuzzyTitle($data){
        global $conn;

        $result = array();
        if ($data) {
            $titles = array();
            $qw = "";
            //For now we are support search one article only
            foreach ($data as $row) {
                $artitle = addslashes(htmlspecialchars(trim($row['articleid'])));
                //##if (!empty($artitle)) $titles[] = $artitle;
                if (empty($qw)) {
                    $qw = 'ck.keyword LIKE \'%' . $artitle . '%\'';
                } else {
                    //$qw .= ' OR ck.keyword LIKE \'%' . $artitle . '%\'';
                }
            }
            $qw = ' AND (' . $qw . ')';

            /*
            $sql = 'SELECT ar.article_id, ar.body AS textBody, ar.title, ar.richtext_body AS htmlBody, ar.article_status AS status, ck.keyword, ar.approval_date AS editorApprovalDate, ar.approval_date AS editorApprovalDate'
            . ' FROM articles AS ar ' 
            . ' LEFT JOIN campaign_keyword AS ck ON ck.keyword_id=ar.keyword_id ' 
            . $qw .  " AND ck.status!='D'";
            */
            $sql = 'SELECT ar.article_id AS articleid, ar.total_words AS length, ar.title, ck.keyword, ar.body AS textBody, ar.richtext_body AS htmlBody, ar.article_status AS status, ar.approval_date AS editorApprovalDate, ar.client_approval_date AS clientApprovalDate'
            . ' FROM campaign_keyword AS ck ' 
            . ' LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id) ' 
            . " WHERE ck.status!='D' " . $qw;

            //$result = $conn->GetAll($sql);
            $result = $conn->GetRow($sql);

            //return $result;
        }

        if (empty($result)) {
            $result = array(array('memo' => 'No article found in the CMS'));
        }

        return $result;
    }

    function getRecentModifiedArticles($data, $api_info){
        global $conn;
        //print_r($api_info);

        $result = array();
        if ($data) {
            extract($data[0]);

            $titles = array();
            $qw = "cc.client_id = ".$api_info['client_id'];
            $now = time();
            $threemonthago = $now - 86400 * 90;//we only can let user get the last 3 months articles.
            //For now we are support search one article only

            if (isset($startdate)) {
                $startdate = strtotime($startdate);
                if ($startdate < $threemonthago) {// if it over 3 months, we will only get the latest 3 months articles.
                    $startdate = $threemonthago;
                }
                if (isset($enddate)) {
                    $enddate = strtotime($enddate);
                    if ($enddate>=$startdate) {
                        $enddate = date("Y-m-d", $enddate);
                    } else {
                        unset($enddate);
                    }
                }
                $startdate = date("Y-m-d", $startdate);
                if (isset($enddate)) {
                    //###$qw .= " AND (ar.approval_date <= '$enddate' AND ar.client_approval_date <= '$enddate' AND ar.delivered_date <= '$enddate')";
                    $qw .= " AND ( (ar.delivered_date IS NULL AND ar.approval_date <= '$enddate' AND ar.client_approval_date <= '$enddate') OR ar.delivered_date <= '$enddate')";
                }
            } elseif (isset($days) && is_numeric($days)) {
                $daysago = $now - $days * 86400;
                $startdate = date("Y-m-d", $daysago);
            } else {
                $daysago = $now - 7 * 86400;
                $startdate = date("Y-m-d", $daysago);
            }

            //###$qw .= " AND (ar.approval_date >= '$startdate' OR ar.client_approval_date >= '$startdate' OR ar.delivered_date >= '$startdate')";
            $qw .= " AND ( (ar.delivered_date IS NULL AND (ar.approval_date >= '$startdate' OR ar.client_approval_date >= '$startdate')) OR ar.delivered_date >= '$startdate')";
            $qw = ' (' . $qw . ')';

            //### ar.approval_date AS editorApprovalDate, ar.client_approval_date AS clientApprovalDate,
            $sql = 'SELECT ar.article_id AS articleid, ar.total_words AS length, ar.title, ck.keyword, ar.body AS textBody, ar.richtext_body AS htmlBody, ck.campaign_id AS campaignid, ar.article_status AS status, IF(ar.delivered_date IS NULL, IF(ar.client_approval_date="0000-00-00 00:00:00", ar.approval_date, ar.client_approval_date), ar.delivered_date) AS lastUpdatedDate '
            . ' FROM campaign_keyword AS ck ' 
            . ' LEFT JOIN client_campaigns AS cc ON (ck.campaign_id=cc.campaign_id)  ' 
            . ' LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id) ' 
            . " WHERE ck.status!='D' AND (ar.article_status=5 OR ar.article_status=6) AND " . $qw;
            //###. " WHERE ck.status!='D' AND ar.article_status>=4 AND " . $qw;

            //echo $sql; 
            $result = $conn->GetAll($sql);
            //##$result = $conn->GetRow($sql);
        }

        if (empty($result)) {
            $result = array(array('memo' => 'No article found which was recent modified by writer'));
        }

        return $result;
    }

    function __getArticlesByIds($id, $fields = array(), $campaign_id = null, $conditions = array(), $show_tag = true)
    {
        global $conn;
        if ($campaign_id > 0) {
            $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
            $conditions[] = "ck.campaign_id='" . $campaign_id . "'";
        }
        if (empty($fields)) {
            $addqw4dl = "";
            if (empty($campaign_id)) {
                $fields = array(
                    'ar.article_id',
                    'ck.copy_writer_id',
                    'ck.keyword',
                    'ar.article_status AS status',
                    'ar.title',
                    'ar.total_words AS length',
                    'ar.body AS textBody',
                    'ar.richtext_body AS htmlBody',
                    'aaf.small_image as smallImage',
                    'aaf.large_image as largeImage',
                    'aaf.image_credit as imageCredit',
                    'aaf.image_caption AS imageCaption',
                    'aaf.meta_description as metaDescription',
                    'aaf.blurb',
                    'aaf.category_id AS category',
                    'cc.template',
                    'ar.client_approval_date AS clientApprovalDate',
                    'ar.approval_date AS editorApprovalDate',
                    'ck.date_start AS startDate',
                    'ck.date_end AS dueDate',
                    'CONCAT(cpu.first_name, " ", cpu.last_name)  AS Author',
                    'cpu.googleplus_url AS googlePlusURL',
                 );
            } else {
                $fields = array(
                    'ar.article_id',
                    'ck.copy_writer_id',
                    'ck.keyword',
                    'ar.article_status AS status',
                    'ar.title',
                    'ar.total_words AS length',
                    'ar.body AS textBody',
                    'ar.richtext_body AS htmlBody',
                    'aaf.small_image as smallImage',
                    'aaf.large_image as largeImage',
                    'aaf.image_credit as imageCredit',
                    'aaf.image_caption AS imageCaption',
                    'aaf.meta_description as metaDescription',
                    'aaf.blurb',
                    'aaf.category_id AS category ',
                    'cc.template',
                    'ar.client_approval_date AS clientApprovalDate',
                    'ar.approval_date AS editorApprovalDate',
                    'ck.date_start AS startDate',
                    'ck.date_end AS dueDate',
                    'cpu.googleplus_url AS googlePlusURL',
                 );
            }
            $addqw4dl = " LEFT JOIN users AS cpu ON (ck.copy_writer_id = cpu.user_id)  ";
        }
        $qw = ' WHERE 1 ';
        if (!empty($id)) {
            if (is_array($id)) {
                $qw .= ' AND ar.article_id IN (\'' . implode("','", $id) . '\')';
            } else if (is_numeric($id)) {
                $qw .= ' AND ar.article_id = \'' . $id . '\'';
            }
        }
        $qw .= " AND ck.status!='D' ";
        $sql  = 'SELECT ' . implode(', ', $fields) 
            . ' FROM articles AS ar ' 
            . ' LEFT JOIN campaign_keyword AS ck ON ck.keyword_id=ar.keyword_id ' 
            . ' LEFT JOIN article_additional_fields AS aaf ON (ar.article_id = aaf.article_id) ' 
            . ' LEFT JOIN client_campaigns  AS cc ON (cc.campaign_id = ck.campaign_id)  ' 
            .  $addqw4dl
            . $qw;
        if (!empty($conditions)) {
            $sql .= ' AND ' . implode(" AND ", $conditions);
        }
        $result = $conn->GetAll($sql);
        $article_ids = array();
        foreach ($result as $k => $row) {
            $article_ids[] = $row['article_id'];
            $status = $row['status'];
            $template = $row['template'];
            if ($template <> 2) {
                unset($result[$k]['smallImage']);
                unset($result[$k]['largeImage']);
                unset($result[$k]['imageCredit']);
                unset($result[$k]['imageCaption']);
                unset($result[$k]['metaDescription']);
                if ($template == 1) unset($result[$k]['blurb']);
                unset($result[$k]['category']);
            }
            unset($result[$k]['template']);
            if ($status == 5 || $status == 6 || $status == 4) {
                if ($status == 4) unset($result[$k]['clientApprovalDate']);
            } else {
                unset($result[$k]['clientApprovalDate']);
                unset($result[$k]['editorApprovalDate']);
            }
        }
        // added by nancy xu 2011-03-17 9:55
        if ($show_tag) {
            // get aticle tags
            require_once CMS_INC_ROOT.'/DomainTag.class.php';
            // $article_ids = array_unique($article_ids);
            $tags = ArticleTag::getTagsByArticleId($article_ids);
            foreach ($result as $k => $row) {
                $article_id = $row['article_id'];
                if (isset($tags[$article_id]) && !empty($tags[$article_id])) {
                    $names = $tags[$article_id];
                    $arr = array();
                    foreach ($names as $tag_id => $v) {
                        $arr[] = array('tag_id' => $tag_id);
                    }
                    $result[$k]['tags'] = $arr;
                }
            }
        }// end
        return $result;
    }

    function getArticleIds($data)
    {
        $ids = array();
        foreach ($data as $row) {
            $articleid = $row['articleid'];
            if (!empty($articleid) && is_numeric($articleid) && !in_array($articleid, $ids)) {
                $ids[] = addslashes(htmlspecialchars(trim($articleid)));
            }
        }
        return $ids;
    }

    function addCampaigns($data, $api_info)
    {
        global $feedback;
        $result  = array();
        foreach ($data as $row) {
            
            $arr = $this->addCampaign($row, $api_info);
            if (!$arr) {
                if (is_array($row)) $arr = $row;
                else $arr = array();
                $arr['status'] = 'declined';
                $arr['memo'] = $feedback;
            }
            if (is_array($row) && isset($row['sequence'])) $arr['sequence'] = $row['sequence'];
            $result[] = $arr;
        }
        return $result;
    }

    function getAllCampaigns($api_info, $fields = array(), $param = array())
    {
        if ($api_info ) {
            if (is_array($api_info) && isset($api_info['client_user_id'])) {
                $conditions = array('cc.client_id' => $api_info['client_id'], 'cc.source' => $api_info['client_user_id']);
            } else if ($param) {
                $conditions = array();
            } else {
                return array('memo' => 'You have no priviledge to get all campaigns, please specify the condtions');
            }
            foreach ($param as $k => $v) {
                $field = insertUnderlineBeforeCapital($k);
                $conditions['cc.' . strtolower($field)] = $v;
            }
        }
        if (empty($fields)) {
            $fields = array('campaignName', 'campaignId');
        }
        $real_fields = insertUnderlineBeforeCapital($fields);
        $param = $arr = array();
        foreach ($fields as $k => $v) {
            if ($v == 'totalCompletedKeywords' || $v == 'totalKeywords') {
                if ($v == 'totalCompletedKeywords') {
                    $arr[] = "COUNT(ar.article_id) AS " . $v;
                    $param['is_completed'] = true;
                } else if ($v == 'totalKeywords') {
                    $param['is_count'] = true;
                }
            } else {
                $arr[] = "cc." . strtolower($real_fields[$k]) . ' AS ' . $v;
            }
        }
        //$fields = array('cc.campaign_id AS campaign_id', 'cc.campaign_name AS name');
        return $this->__getCampaignInfo($arr, $conditions, $param);
    }

    // added by nancy xu 2010-09-30 19:25
    function __getCampaignInfo($fields, $conditions, $param = array())
    {
        global $conn;
        $sql = "SELECT " . (!empty($fields) ? implode(",", $fields) : ' * ');
        $sql .= " FROM client_campaigns AS cc ";
        $qw = array();
        $group_by = '';
        if (isset($param['is_completed']) && $param['is_completed'] == true) {
            $sql .= " LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id)  ";
            $sql .= " LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id AND ar.article_status REGEXP  '^(5|6|99)$')  ";
            // $qw[] = "ar.article_status REGEXP  '^(5|6|99)$'";
            $group_by = ' GROUP BY cc.campaign_id ';
        }
        if (!empty($conditions)) {
            foreach ($conditions as $k => $v) {
                $v = addslashes($v);
                $qw[] =  "{$k}='{$v}'";
            }
        }
        if (!empty($qw)) {
            $sql .= ' WHERE ' . implode(" AND ", $qw);
        }

        $result = $data = $conn->GetAll($sql . $group_by);
        if (isset($param['is_count']) && $param['is_count']) {
            $cids = $result = array();
            foreach ($data as $row) {
                $campaign_id = $row['campaignId'];
                $cids[] = $campaign_id;
                $row['totalKeywords'] = 0;
                $result[$campaign_id] = $row;
            }
            $sql = "SELECT campaign_id, COUNT(keyword_id) AS total FROM campaign_keyword WHERE campaign_id IN (" . implode(",", $cids). ") GROUP BY campaign_id";
            $data = $conn->GetAll($sql);
            if (!empty($data)) {
                foreach ($data as $row) {
                    $result[$row['campaign_id']]['totalKeywords'] = $row['total'];
                }
            }
        }
        return $result;
    }
    // end


    // get all campaign orders
    function getCampaignOrder($param, $api_info, $fields = array())
    {
        global $g_campaign_order_status;
        $conditions = array('oc.client_id' => $api_info['client_id'], 'oc.source' => $api_info['client_user_id']);
        if (empty($fields)) {
            $fields = array('campaignName', 'status');
        }
        $real_fields = insertUnderlineBeforeCapital($fields);
        $arr = array();
        foreach ($fields as $k => $v) {
            $arr[] = "oc." . strtolower($real_fields[$k]) . ' AS ' . $v;
        }
        $arr[] = "cat.category";
        $arr[] = "at.type_name AS contentType";
        $arr[] = "oc.date_start AS startDate";
        $arr[] = "oc.date_end AS dueDate";
        $arr[] = "oc.date_created AS orderDate";
        $arr[] = "cc.date_created AS createdDate";
        $arr[] = 'oc.campaign_id';
        $result = $this->__getCampaignOrderInfo($arr, $conditions);
        foreach ($result as $k => $row) {
            if ($row['campaign_id']) {
                $status = $g_campaign_order_status[15];
            } else {
                $status  = $g_campaign_order_status[$row['status']];
                unset($row['createdDate']);
            }
            unset($row['campaign_id']);
            $row['status'] = $status;
            $result[$k] = $row;
            
        }
        return $result;
    }

    function __getCampaignOrderInfo($fields, $conditions)
    {
        global $conn;
        $sql = "SELECT " . (!empty($fields) ? implode(",", $fields) : ' * ');
        $sql .= " FROM order_campaigns AS oc ";
        $sql .= " LEFT JOIN category AS cat ON cat.category_id  = oc.category_id ";
        $sql .= " LEFT JOIN article_type AS at ON at.type_id  = oc.article_type ";
        $sql .= " LEFT JOIN client_campaigns AS cc ON cc.campaign_id  = oc.campaign_id ";
        if (!empty($conditions)) {
            $qw = array();
            foreach ($conditions as $k => $v) {
                $v = addslashes($v);
                $qw[] =  "{$k}='{$v}'";
            }
            $sql .= ' WHERE ' . implode(" AND ", $qw);
        }
        
        return $conn->GetAll($sql);
    }

    function addCampaign($p, $api_info)
    {
        global $feedback, $conn;
        $checked = 1;
        if (isset($api_info['client_user_id']) && $api_info['client_user_id']) {
            $p['source'] = $api_info['client_user_id'];
        } else if (!empty($p['source'])) {
            $sql = "SELECT client_id FROM `client_users` WHERE  client_user_id= " . $p['source'];
            $p['client_id'] = $conn->GetOne($sql);
        } else {
            $feedback = 'Please specify the domain when you use the Master API';
            return false;
        }
        if (isset($api_info['client_id']) && $api_info['client_id']) {
            $p['client_id'] = $api_info['client_id'];
        } else if (!isset($p['client_id']) || strlen($p['client_id']) == 0) {
            $feedback = 'Please specify the client when you use the Master API';
            return false;
        }
        if (isset($p['campaignid']) && $p['campaignid'] > 0) {
            $campaign_id = $p['campaignid'];
        } else {
            $campaign_id = 0;
        }
        if ($campaign_id == 0 ) {
            if (!is_array($p)) {
                $feedback = "Please invalid data, please check you post";
                return false;
            }
            $campaign_name = $p['name'];
            if (empty($campaign_name)) {
                $feedback = "Please enter the name of the campaign";
                return false;
            } else {
                $p['campaign_name'] = $campaign_name;
            }

            $client_id = $p['client_id'];
            if (empty($client_id)) {
                $feedback = "Please Choose a client";
                return false;
            } else {
                $p['client_id'] = $client_id;
            }

            // check category 
            $category_id = $p['categoryid'];
            if (empty($category_id)) {
                $feedback = "Please Choose a category";
                return false;
            } else {
                $sql = 'SELECT COUNT(*) FROM category WHERE category_id=' . $category_id;
                $count = $conn->GetOne($sql);
                if (empty($count)) {
                    $feedback = 'Invalid Category ID, please check your xml';
                    return false;
                } else {
                    $p['category_id'] = $category_id;
                }
            }
        } else {
            if (isset($p['categoryid'])) {
                $category_id = $p['categoryid'];
                if (!empty($category_id)) {
                    $sql = 'SELECT COUNT(*) FROM category WHERE category_id=' . $category_id;
                    $count = $conn->GetOne($sql);
                    if (empty($count)) {
                        $feedback = 'Invalid Category ID, please check your xml';
                        return false;
                    } else {
                        $p['category_id'] = $category_id;
                    }
                }
            }
            $checked = $this->__checkPrivilege($campaign_id, $api_info);
        }
         // pr($p, true);
        if ($checked == 1) {
            $result = $this->storeCampaign($p, $campaign_id);
        } else {
            $feedback = 'Pemission deny, you can\'t modify this campaign';
            $result = false;
        }
        return $result;
    }

    function storeCampaign($p, $campaign_id=0)
    {
        global $conn, $feedback;
        $hash = array();


        if (isset($p['datestart']))  $date_start = $p['datestart'];
        if (isset($p['dateend']))  $date_end = $p['dateend'];
        if (isset($p['template']))  $template = $p['template'];
        if ($campaign_id == 0) {
            if (!isset($date_start) || empty($date_start) ) {
                $feedback = "Please provide the start date of the campaign";
                return false;
            }
            if (!isset($date_end) ||  empty($date_end) ) {
                $feedback = "Please provide the start date of the campaign";
                return false;
            }
        }
        if (isset($template) && $template <> 1 && $template <> 2) {
            $feedback = "Invalid template, please check your data";
            return false;
        }
        if (isset($date_start)) {
            $hash['date_start'] = $date_start;
        }
        if (isset($date_end)) {
            $hash['date_end'] = $date_end;
        }

        if (isset($date_start) && isset($date_end) && strtotime($date_end) <= strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }
        if ($campaign_id == 0) {
            $monthly_recurrent = $p['monthlyrecurrent'];
            if ($monthly_recurrent == '') {
                $monthly_recurrent = 0;
            } else {
                $monthly_recurrent = 1;
            }
            $hash['monthly_recurrent'] = $monthly_recurrent;
            $hash['writer_expertise'] = isset($p['writerexpertise']) ? $p['writerexpertise'] : '';
            $hash['sample_content'] = isset($p['samplecontent']) ? $p['samplecontent'] : '';
            $hash['content_level'] = isset($p['contentlevel']) ? $p['contentlevel'] : 0;
            if (empty($hash['content_level'])) $hash['content_level'] = 0;
            $hash['keyword_instructions'] = isset($p['keywordinstructions']) ? $p['keywordinstructions'] : '';
            $hash['campaign_site_url'] = isset($p['campaignsiteurl']) ? $p['campaignsiteurl'] : '';
            $hash['campaign_requirement'] = isset($p['campaignrequirement']) ? $p['campaignrequirement'] : '';
            $hash['title_param'] = isset($p['titleparam'])? $p['titleparam'] : 1;
            $hash['meta_param'] = isset($p['metaparam'])? $p['metaparam'] : 0;
            $hash['source'] = isset($p['source'])? $p['source'] : 0;
            $hash['creation_user_id'] = 0;
            $hash['date_created'] = date("Y-m-d H:i:s");
            $hash['total_budget'] = isset($p['totalbudget']) ? $p['totalbudget'] : 0;
            $hash['template'] = isset($p['template']) ? $p['template'] : 1;

            $cost_per_article =  isset($p['costperarticle']) ? $p['costperarticle'] : 0;
            if (!is_numeric(trim($cost_per_article))) {
                $feedback = "Cost per article must be a integer";
                return false;
            } else {
                $hash['cost_per_article'] = $cost_per_article;
            }
            $editor_cost = isset($p['editorcost']) ? $p['editorcost'] : 0;
            if (!is_numeric(trim($editor_cost))) {
                $feedback = "Editor cost per word per article must be a integer";
                return false;
            } else {
                $hash['editor_cost'] = $editor_cost;
            }
            if (isset($p['campaign_name'])) $hash['campaign_name'] = $p['campaign_name'];
            if (isset($p['category_id'])) $hash['category_id'] = $p['category_id'];
            if (isset($p['client_id'])) $hash['client_id'] = $p['client_id'];
        } else {
            if (isset($p['monthlyrecurrent'])) {
                $monthly_recurrent = $p['monthlyrecurrent'];
                if ($monthly_recurrent == '') {
                    $monthly_recurrent = 0;
                } else {
                    $monthly_recurrent = 1;
                }
                $hash['monthly_recurrent'] = $monthly_recurrent;
            }
            if (isset($p['writerexpertise'])) $hash['writer_expertise']=$p['writerexpertise'];
            if (isset($p['samplecontent'])) $hash['sample_content']=$p['samplecontent'];
            if (isset($p['contentlevel'])) $hash['content_level'] = $p['contentlevel'];
            if (isset($p['keywordinstructions'])) $hash['keyword_instructions']=$p['keywordinstructions'];
            if (isset($p['campaignsiteurl'])) $hash['campaign_site_url']=$p['campaignsiteurl'];
            if (isset($p['campaignrequirement'])) $hash['campaign_requirement']=$p['campaignrequirement'];
            if (isset($p['titleparam'])) $hash['title_param'] =$p['titleparam'];
            if (isset($p['metaparam'])) $hash['meta_param'] =$p['metaparam'];
            // if (isset($p['source'])) $hash['source'] =$p['source'];
            if (isset($p['totalbudget'])) $hash['total_budget'] = $p['totalbudget'];
            if (isset($p['costperarticle'])) $hash['cost_per_article'] =  $p['costperarticle'];
            if (isset($p['editorcost'])) $hash['editor_cost'] = $p['editorcost'];
            if (isset($p['campaign_name'])) $hash['campaign_name'] = $p['campaign_name'];
            if (isset($p['category_id'])) $hash['category_id'] = $p['category_id'];
        }

        $conn->StartTrans();
        $is_update = $campaign_id > 0 ? true : false;
        if (!$is_update) {
            $campaign_id = $conn->GenID('seq_client_campaigns_campaign_id');
            if (!empty($p['maxword'])) {
                $hash['max_word'] = $p['maxword'];
            }
        }
        $hash['campaign_id'] = $campaign_id;
        $fields = array_keys($hash);
        foreach ($hash as  $k => $v) {
            $v = stripslashes($v);
            $hash[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        if ($is_update) {
            $sets = array();
            foreach ($hash as $k => $v) {
                if ($k == 'campaign_id') continue;
                $sets[] = "`{$k}`='{$v}'";
            }
            $q = 'UPDATE client_campaigns SET ' . implode(',', $sets) . ' WHERE campaign_id=' . $campaign_id;
        } else {
            $q = "INSERT INTO client_campaigns ( `" . implode("`, `", $fields)."`) ".
             "VALUES ('" . implode("','", $hash)."')";
        }
        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            /*require_once CMS_INC_ROOT . '/User.class.php';
            require_once CMS_INC_ROOT . '/Client.class.php';
            $info = Client::getInfo($hash['client_id']);
            $admins = User::getAllUsers('username_email_only', 'admin');
            Campaign::sendAnnouceMail($hash, $info['user_name'], $admins);*/
            $result = array(
                'status' => 'created',
                'campaignid' => $campaign_id,
            );
            return $result;
        } else {
            return false;
        }
    }

    function getClientInfoByClientId($client_id) 
    {
        global $conn, $feedback;
        $sql = "SELECT cl.*, u.email AS pm_email FROM `client` AS cl ";
        $sql .= " LEFT JOIN users as u on cl.project_manager_id = u.user_id ";
        $sql .= " WHERE client_id = '".$client_id."' ";
        return $conn->GetRow($sql);
    }



    function addCampaignOrders($data, $api_info)
    {
        global $feedback;
        $result  = array();
        $client = $this->getClientInfoByClientId($api_info['client_id']);
        require_once CMS_INC_ROOT . '/OrderCampaign.class.php';
        // require_once CMS_INC_ROOT . '/User.class.php';
        require_once CMS_INC_ROOT . '/ClientArticlePrices.class.php';
        // $all_admin = User::getAllUsers('id_email_only', 'admin');
        $pm_email = $client['pm_email'];
        foreach ($data as $row) {
            $row['client_id'] = $api_info['client_id'];
            $row['source'] = $api_info['client_user_id'];
            if ((!isset($row['orderedby']) || empty($row['orderedby'])) && !empty($client['contact_name']))
                $row['orderedby'] = $client['contact_name'];
            $arr = $this->addCampaignOrder($row, $pm_email, $client['user_name']);
            //$arr = $this->addCampaignOrder($row, $all_admin, $client['user_name']);
            if (!$arr) {
                if (is_array($row)) $arr = $row;
                else $arr = array();
                $arr['status'] = 'declined';
                $arr['memo'] = $feedback;
            }
            if (is_array($row) && isset($row['sequence'])) $arr['sequence'] = $row['sequence'];
            $result[] = $arr;
        }
        return $result;
    }

    function getCount($table, $key, $value)
    {
        global $conn;
        $sql = "select count(*) from {$table} where {$key}='{$value}'";
         return $conn->GetOne($sql);
    }

    function addCampaignOrder($p, $tos, $client_name)
    {
        global $conn, $feedback;
        $hash = array();
        if (!is_array($p)) {
            $feedback = "Please invalid data, please check you post";
            return false;
        }
        $campaign_name = $p['name'];
        if (empty($campaign_name)) {
            $feedback = "Please enter the name of the campaign";
            return false;
        } else {
            $hash['campaign_name'] = $campaign_name;
        }

        $client_id = $p['client_id'];
        if (empty($client_id)) {
            $feedback = "Please Choose a client";
            return false;
        } else {
            $hash['client_id'] = $client_id;
        }

        // check category 
        $category_id = $p['categoryid'];
        if (empty($category_id)) {
            $feedback = "Please Choose a category";
            return false;
        } else {
            $count = $this->getCount('category', 'category_id', $category_id);
            if (empty($count)) {
                $feedback = 'Invalid Category ID, please check your xml';
                return false;
            } else {
                $hash['category_id'] = $category_id;
            }
        }

        $article_type = $p['articletype'];
        if (empty($article_type)) {
            $feedback = 'Please specify the article type';
            return false;
        } else {
            $count = $this->getCount('article_type', 'type_id', $article_type);
            if (empty($count)) {
                $feedback = 'Invalid  Article Type, please check your xml';
                return false;
            } else {
                $hash['article_type'] = $article_type;
            }
        }

        $hash['total_budget'] = isset($p['totalbudget']) ? $p['totalbudget'] : 0;
        $cost_per_article =  isset($p['costperarticle']) ? $p['costperarticle'] : 0;
        if (!is_numeric(trim($cost_per_article))) {
            $feedback = "Cost per article must be a integer";
            return false;
        } else {
            $hash['cost_per_article'] = $cost_per_article;
        }
        $editor_cost = isset($p['editorcost']) ? $p['editorcost'] : 0;
        if (!is_numeric(trim($editor_cost))) {
            $feedback = "Editor cost per word per article must be a integer";
            return false;
        } else {
            $hash['editor_cost'] = $editor_cost;
        }

        $date_start = $p['datestart'];
        if (empty($date_start)) {
            $feedback = "Please provide the start date of the campaign";
            return false;
        } else {
            $hash['date_start'] = $date_start;
        }
        $date_end = $p['dateend'];
        if (empty($date_end)) {
            $feedback = "Please provide the Due Date of the campaign";
            return false;
        } else {
            $hash['date_end'] = $date_end;
        }

        if (strtotime($date_end) <= strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }
        $monthly_recurrent = $p['monthlyrecurrent'];
        if ($monthly_recurrent == '') {
            $monthly_recurrent = 0;
        } else {
            $monthly_recurrent = 1;
        }
        $hash['monthly_recurrent'] = $monthly_recurrent;
        $hash['writer_expertise'] = isset($p['writerexpertise']) ? $p['writerexpertise'] : '';
        $hash['sample_content'] = isset($p['samplecontent']) ? $p['samplecontent'] : '';
        $hash['content_level'] = isset($p['contentlevel']) ? $p['contentlevel'] : '';
        $hash['keyword_instructions'] = isset($p['keywordinstructions']) ? $p['keywordinstructions'] : '';
        $hash['campaign_site_url'] = isset($p['campaignsiteurl']) ? $p['campaignsiteurl'] : '';
        $hash['campaign_requirement'] = isset($p['campaignrequirement']) ? $p['campaignrequirement'] : '';
        $hash['special_instructions'] = isset($p['specialinstructions']) ? $p['specialinstructions'] : '';
        $hash['title_param'] = isset($p['titleparam'])? $p['titleparam'] : 1;
        $hash['meta_param'] = isset($p['metaparam'])? $p['metaparam'] : 0;
        $hash['source'] = isset($p['source'])? $p['source'] : 0;
        $hash['date_created'] = date("Y-m-d H:i:s");
        if ($content_level == '') {
            $content_level = 0;
        }
        $hash['content_level'] = $content_level;
        if (isset($p['isconfirm']) && strlen($p['isconfirm'])) {
            $hash['is_confirm'] = $p['isconfirm'];
        } else {
            $hash['is_confirm'] = 1;
        }
        if (isset($p['articletone']) && strlen($p['articletone'])) {
            $hash['article_tone'] = $p['articletone'];
        }
        if (isset($p['qty']) && $p['qty'] > 0) {
            $qty = $p['qty'];
            $hash['qty'] = $qty;
        } else {
            $qty = 0;
        }
        if (isset($p['maxword']) && $p['maxword'] > 0) {
            $max_word = $p['maxword'];
            $hash['max_word'] = $max_word;
        } else {
            $feedback = "Please specify the Max number of Words";
            return false;
        }
        if (isset($p['minword']) && $p['minword'] > 0) {
            $min_word = $p['minword'];            
        } else {
            $min_word = 50;
        }
        $hash['min_word'] = $min_word;

        if ($min_word > $max_word) {
            $feedback = 'Min number of words is more than max number, please try again';
            return false;
        }

        if (isset($p['orderedby']) && strlen($p['orderedby'])) {
            $ordered_by = $p['orderedby'];
            $hash['ordered_by'] = $ordered_by;
        } else {
            $feedback = "Please specify ordered by";
            return false;
        }

        if (isset($p['targetaudience']) && $p['targetaudience']) {
            $hash['target_audience'] = $p['targetaudience'];
        }
        if (isset($p['saletype']) && $p['saletype']) {
            $hash['sale_type'] = $p['saletype'];
        }
        if (isset($p['ismentioned']) && $p['ismentioned']) {
            $is_mentioned = $p['ismentioned'];
            $hash['is_mentioned'] = $is_mentioned;
        }
        if (isset($p['bizname']) && $p['bizname']) {
            $hash['biz_name'] = $p['bizname'];
        } else if ($is_mentioned == 1) {
            $feedback = "Please specify the business name";
            return false;
        }
        if (isset($p['highlightdesc']) && $p['highlightdesc']) {
            $hash['highlight_desc'] = $p['highlightdesc'];
        }
        if (isset($p['particulardesc']) && $p['particulardesc']) {
            $hash['particular_desc'] = $p['particulardesc'];
        }
        if (isset($p['isinsertimg']) && strlen($p['isinsertimg'])) {
            $hash['is_insert_img'] = $p['isinsertimg'];
        }
        $data = $p['contentDetails'];

        $conn->StartTrans();
        $order_id = $conn->GenID('seq_order_campaigns_order_campaign_id');
        $hash['order_campaign_id'] = $order_id;
        $fields = array_keys($hash);
        $info = $hash;
        foreach ($hash as  $k => $v) {
            $v = stripslashes($v);
            $hash[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        $q = "INSERT INTO order_campaigns ( `" . implode("`, `", $fields)."`) ".
             "VALUES ('" . implode("','", $hash)."')";
        $conn->Execute($q);
        $subtotal = $this->storePayment($max_word, $article_type, $qty, $order_id, $hash['client_id']);
        $this->storeOrderKeyword($data, $order_id);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $info['client_name'] = $client_name;
            global $g_to_email;
            OrderCampaign::sendAnnouceMail($info, $tos, 23, $g_to_email);
            $result = array(
                'status' => 'created',
                'subtotal' => $subtotal,
                'orderid' => $order_id,
            );
            return $result;
        } else {
            $feedback = 'Failed, please try again';
            return false;
        }
    }

    function storeOrderKeyword($data, $order_id)
    {
        global $g_option_fields, $conn;
        $fields = $data['fieldMapping'];
        $keywords = $data['contentRow'];
        if (empty($fields) && empty($keywords)) {
            return true;
        }
        $arr = array();
        $optiona_fields = array_flip($g_option_fields);
        if (empty($fields)) $fields = array();
        if (empty($keywords)) $keywords = array();
        foreach ($fields as $k => $v) {
            $tmp = addCharBeforeCapitalLetterAndNumber($k);            
            $arr[$optiona_fields[$tmp]] = $v;
        }
        $hash = array('order_id' => $order_id, 'fields' => $arr);
        foreach ($keywords as $row) {
            foreach ($row as $k => $v) {
                $tmp = addCharBeforeCapitalLetterAndNumber($k);
                $field = $optiona_fields[$tmp];
                if (!isset($hash[$field])) $hash[$field] = array();
                $hash[$field][] = $v;
            }
        }
        foreach ($hash as $k => $v) {
            if ($k != 'order_id') {
                if (!empty($v)) {
                    $hash[$k] = addslashes(serialize($v));
                } else  {
                    unset($hash[$k]);
                }
            }
        }
        $hash['created'] = date("Y-m-d H:i:s");
        $fields = array_keys($hash);
        $q = "INSERT INTO `order_campaign_keywords` (`" . implode('`,`', $fields)."`) VALUES ('" . implode("','", $hash). "')";
        $conn->Execute($q);
        $ok = $conn->Affected_Rows();
        if ($ok == 1) {
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function storePayment($max, $article_type, $qty, $order_id, $client_id)
    {
        global $feedback, $conn;
        $sql = "SELECT article_price, price_id FROM `client_article_prices` WHERE max_word={$max} AND type_id={$article_type}";
        $row = $conn->GetRow($sql);
        if (empty($row)) {
            $price_id = $article_price = 0;
        } else  {
            $article_price = $row['article_price'];
            $price_id = $row['price_id'];
        }
        $subtotal = $qty * $article_price;
        $arr = array(
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'qty' => $qty,
            'article_price' => $article_price,
            'order_id' => $order_id,
            'price_id' => $price_id,
        );
        $q = "SELECT * FROM `order_campaign_payments` WHERE order_id = '{$order_id}'";
        $data = $conn->GetRow($q);
        $payment_id = $data['payment_id'];
        if (empty($payment_id)) {
            $arr['created'] = date("Y-m-d H:i:s");
            $arr['created_by'] = $client_id;
            $arr['creation_role'] = 'client';
            $keys = array_keys($arr);
            $q = "INSERT INTO `order_campaign_payments` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            $data = array_merge($data, $arr);
            unset($data['payment_id']);
            $q = "UPDATE `order_campaign_payments` SET ";
            $sets = array();
            foreach ($data as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE payment_id='{$payment_id}'";
        }
        $conn->Execute($q);
        return $subtotal;
    }

    function confirmCampaignOrders($data, $api_info, $transaction)
    {
        return $this->setOrderCampaignStatus($data, $api_info, $transaction, 7, 31);
    }

    function payCampaignOrders($data, $api_info, $transaction)
    {
        return $this->setOrderCampaignStatus($data, $api_info, $transaction, 10, null);

    }

    function setOrderCampaignStatus($data, $api_info, $transaction, $status, $event_id)
    {
        global $feedback, $conn;
        require_once CMS_INC_ROOT . '/OrderCampaign.class.php';
        if ($transaction) {
            $conn->StartTrans();
            $failed_result = array();
        }
        $sent_infos = array();
        foreach ($data as $row) {
            $order_id = $row['orderid'];
            $arr = array(
                'orderid'=> $order_id,
            );
            if (isset($row['sequence'])) {
                $arr['sequence'] = $row['sequence'];
            }
            $failed_arr = $row;
            if ($order_id > 0) {
                $rs_privilege = $this->__checkOrderPrivilege($order_id, $api_info);
                if ($rs_privilege == 1) {
                    if ($order_id > 0) {
                        if (!$transaction) {
                            $conn->StartTrans();
                        }
                        $info = OrderCampaign::getInfo($order_id);
                        $cstatus = intval($info['status']);
                         $c_opt = ($status == 10 ? 'pay' : 'confirm');
                        if (($cstatus >= 4 && $cstatus < 7) && $status == 7 || ($cstatus >=7 && $cstatus < 10) && $status == 10) {
                            $feedback = null;
                            $p = array('order_campaign_id' => $order_id, 'status' => $status);
                            $hash = array();       
                            if ($status == 10) {
                                if (empty($row['transnum'])) {
                                    $feedback = 'Please speciy the Transaction Number';
                                } else {
                                    $hash['trans_num'] = $row['transnum'];
                                }
                                if (empty($row['account'])) {
                                    $feedback='Please speciy the Merchant Account';
                                } else {
                                    $hash['account'] = $row['account'];
                                }
                                if (empty($row['	transdate'])) {
                                    $hash['trans_date'] = date("Y-m-d");
                                } else {
                                    $hash['trans_date'] = $row['transdate'];
                                }
                            }
                            if (empty($feedback)) {
                                $hash['status'] = $status;
                                if (OrderCampaign::update($p) && OrderCampaign::updatePaymentByOrderID($hash,$order_id)) {
                                    $sent_infos[] = $info;
                                    $feedback = null;
                                } else {
                                    $feedback = 'Failure, Please try again';
                                }
                            }
                        } else {
                            $feedback = 'Pemission deny, you can\'t ' . $c_opt . ' this campaign order';
                        }
                        if (!$transaction) {
                            $conn->CompleteTrans();
                        }
                    } else {
                        $feedback = 'Invalid Campaign Order, please check the campaign order you specify';
                    }
                } elseif($rs_privilege == 0) {
                    $feedback = 'Pemission deny, you have no privilege to ' . $c_opt . ' for this campaing order';
                }
            } else {
                $feedback = 'Pemission deny, please specify the campaign order';
            }
            if (!empty($feedback)) {
                $arr['memo'] = $feedback;
                $arr['status'] = 'Declined';
            } else {
                $arr['status'] = 'Accepted';
                $arr['orderid'] = $order_id;
            }
            $result[] = $arr;
            if ($transaction) {
                $failed_arr['status'] = 'Declined';
                $failed_arr['memo'] = 'Failure, Please try again';
                $failed_result[] = $failed_arr;
            }
        }
        if ($transaction) {
            $ok = $conn->CompleteTrans();
            if (!$ok) {
                $result = $failed_result;
                $is_sent = false;
            } else {
                $is_sent = true;
            }
        } else {
            $is_sent = true;
        }
        if ($is_sent && !empty($sent_infos) && $event_id > 0) {
            $client = $this->getClientInfoByClientId($api_info['client_id']);
            // require_once CMS_INC_ROOT . '/User.class.php';
            // $all_admin = User::getAllUsers('id_email_only', 'admin');
            global $g_to_email;
            foreach ($sent_infos as $info) {
                $info['client_name'] = $client['user_name'];
                OrderCampaign::sendAnnouceMail($info, $client['pm_email'], $event_id, $g_to_email);
            }
        }
        return $result;
    }

    // added by nancy xu 2010-10-23 19:44
    // get all verticals
    function getVertical($param = array(), $api_info = array())
    {
        global $conn;
        $sql = "SELECT category_id AS verticalId, category AS verticalName FROM category";
        return $conn->GetAll($sql);
    }

    // get all article type
    function getArticleType($param = array(), $api_info = array()){
        global $conn;
        require_once CMS_INC_ROOT . '/ClientArticlePrices.class.php';
        $prices = ClientArticlePrice::getAllPrice4API();
        $sql ='SELECT type_name AS typeName, type_id AS typeId FROM  article_type WHERE parent_id >= 0 AND is_inactive = 0 ';
        $result = $conn->GetAll($sql);
        foreach ($result as $k => $type) {
            extract($type);
            if (isset($prices[$typeId])) $result[$k]['articlePrices'] = $prices[$typeId];
        }
        return $result;
        //return $conn->GetAll($sql);
    }

    // get article comments
    function getArticleComment($article_id){
    }
    // end

    // added by nancy xu 2011-03-14 10:40
    // tag actions
    function addArticleTag($result, $api_info)
    {
        $source = $api_info['client_user_id'];
        require_once CMS_INC_ROOT . '/DomainTag.class.php';
        return DomainTag::addTags2Article($result, $source);
    }

    function delArticleTag($result, $api_info)
    {
        $source = $api_info['client_user_id'];
        require_once CMS_INC_ROOT . '/DomainTag.class.php';
        return DomainTag::delTags4Article($result, $source);
    }

    function getTags($api_info)
    {
        require_once CMS_INC_ROOT . '/DomainTag.class.php';
        $source = $api_info['client_user_id'];
    }

    function updateTag($result, $api_info)
    {
        require_once CMS_INC_ROOT . '/DomainTag.class.php';
        $source = $api_info['client_user_id'];
        return DomainTag::addBatchTags($result, $source);
    }
    // end

    function getUserProfile($param = array(), $api_info) 
    {
       global $conn;
       $result = array();
        if (isset($param['copywriterID']) && !empty($param['copywriterID'])) {
            $qws = array('permission=1');
            if (is_array($param['copywriterID'])) {
               $qws[] = 'user_id IN (' . implode(',',  $param['copywriterID'] ). ')';
            } else {
               $qws[] = 'user_id =' . $param['copywriterID'];
            }
            $sql = "SELECT first_name as firstName, last_name as lastName, 	pen_name as penName, bio, photo as photoUrl FROM users WHERE " . implode(" AND ", $qws);
            $data = $conn->GetAll($sql);
            if (!empty($data)) {
                foreach ($data as $row) {
                    $result[] = array('writerProfile' => $row);
                }
                $result = $data;
            } else {
                $result = array(array('memo' => 'The user you specified was not copy writer, please to check your input'));
            }
        } else {
            $result = array(array('memo' => 'Please specify the copy writer'));
        }
        return $result;
    }

    // added by nancy xu 2013-05-09 9:28
    function getDomain($param, $api_info)
    {
        global $conn;
        if (empty($api_info)) {
            return false;
        }
        if (isset($param['clientID']) && !empty($param['clientID'])) {
            $client_id = $param['clientID'];
            if (is_array($client_id)) {
                $conditions[] = ' client_id IN (' . implode(',',  $client_id ). ')';
            } else {
                $conditions[] = " client_id =  " . $client_id;
            }
        }
        $conditions = array('1');
        if ($client_id > 0) {
            $conditions[] = " client_id =  " . $client_id;
        }
        $sql = "SELECT client_id , domain, client_user_id AS domain_id FROM client_users WHERE " . implode(" AND ", $conditions) . " ORDER BY client_id, domain";
        return $conn->GetAll($sql);
    }

    function addDomains($data, $api_info)
    {
        global $feedback;
        if (empty($api_info)) {
            return false;
        }
        $result  = array();
        foreach ($data as $row) {
            $arr = $this->addDomain($row);

            if (!$arr) {
                if (is_array($row)) $arr = $row;
                else $arr = array();
                $arr['status'] = 'declined';
                $arr['memo'] = $feedback;
            } else {
                if (is_array($row)) $arr = $row;
                else $arr = array();
                $arr['status'] = 'accepted';
            }
            if (is_array($row) && isset($row['sequence'])) $arr['sequence'] = $row['sequence'];
            $result[] = $arr;
        }
        return $result;
    }

    function addDomain($data)
    {
        global $conn, $feedback;
        extract($data);

        if (!isset($client_id) || empty($client_id)) {
            $feedback = 'Pleace specify the client id';
            return false;
        }

        if (!isset($domain) || empty($domain)) {
            $feedback = 'Pleace specify the domain';
            return false;
        }

        $sql = "SELECT * FROM client WHERE client_id=" . $client_id;
        $client_info = $conn->GetRow($sql);

        // check the domain unique
        $sql = "SELECT COUNT(*) FROM client_users WHERE user='" . addslashes($client_info['user_name']). "' AND domain='" . addslashes($domain) . "'" ;
        $count = $conn->GetOne($sql);
        if ($count > 0) {
            $feedback = 'Duplicated domain for this client, please to check your data';
            return false;
        }

        $data['user'] = $client_info['user_name'];
        $data['email'] = $client_info['email'];
        $data['is_active'] = 1;
        foreach ($data as $k => $v) {
            $data[$k] = addslashes(trim($v));
        }

        $sql = 'INSERT INTO `client_users` (`' . implode("`,`", array_keys($data)). '`) VALUES (\'' . implode("','", $data). '\')';
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Created';
            return true;
        } else {
            $feedback = 'Create Failure';
            return false;
        }
    }
    // end
}
?>