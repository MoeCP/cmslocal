<?php
function string_encode($article)
{
    // strip the space
    $article = str_replace("’", "'", $article);
    $article = str_replace("‘", "'", $article);
    $article = str_replace("“", "\"", $article);
    $article = str_replace("”", "\"", $article);
    $article = str_replace("\r\n", "\n", $article);
    $article = str_replace("\n", " ", $article);
    $article = htmlspecialchars_decode($article, ENT_QUOTES);
//    $article = urlencode('"' . $article . '"');
    return $article;
}
function copyscape_api_post($copyscapeurl, $post)
{
    $client = new HTTP_Client();
    $client->post($copyscapeurl, $post);
    $result = $client->currentResponse();
    return $result['body'];
}
function copyscape_post($copyscapeurl, $post, $article_id)
{
    $xml = copyscape_api_post($copyscapeurl, $post);
    $obj = get_object_vars(@simplexml_load_string($xml));
    // added by nancy xu 2011-02-25
    // store response to table
    require_once CMS_INC_ROOT . DS . 'ArticleSearchresult.class.php';
    $data = array('article_id'=> $article_id, 'response' => $xml, 'body' => $post['t']);
    ArticleSearchresult::store($data);
    // end
    return $obj;
}

function get_dup_links($obj)
{
    global $limit_dup_url, $admin_host;
    $dup_link = '<strong>Possible Matches:</strong><br />';
    for ($j=0;$j<$limit_dup_url;$j++) {
        if (isset($obj['result'][$j])) {
            $rs = $obj['result'][$j];
            if (isset($rs->url) && !empty($rs->url))  {
                $rs_url = $rs->url;
            } elseif (isset($rs->id) && $rs->id > 0){
                $rs_url = 'https://' . $admin_host . '/article/article_comment_list.php?article_id=' . $rs->id;
            }
            $dup_link .= "URL:&nbsp;{$rs_url}<br />";
            $dup_link .= "Title:&nbsp;{$rs->title}<br />";
            if (is_object($rs->textsnippet)) {
                $textsnippet = (string)$rs->textsnippet;
            } else {
                $textsnippet = $rs->textsnippet;
            }
            $dup_link .= "Textsnippet:&nbsp;{$textsnippet}<br />";
        }
    }
    return $dup_link;
}

function check_article_by_internalscape($item, $url, $match_pct, $dup_links = null)
{
    global $admin_host;
    extract($item);
    if (empty($body)) return false;
    $string = string_encode($body);
    $post = array('t' => $string, 'uid' => $copy_writer_id);
    $obj = copyscape_post($url, $post, $article_id);
    if (isset($obj['error'])) {
        return false;
    }
    $status = '1gc';
    $total = $obj['count'];
    if ($total > 0) {
        $first_result = get_object_vars($obj['result'][0]);
        $exactlysimilarrate = $first_result['exactlysimilarrate'];
        if ($exactlysimilarrate > $match_pct) {
            $status = '1gd';
            return set_article_status($obj, $first_result, $status, $article_id, $keyword, $url, $dup_links);
        }
    }
    return false;
}

function set_article_status($obj, $first_result, $status, $article_key, $keyword, $url, $dup_links = null)
{
    global $admin_host;
    if( $status == '1gc' ) {
        Article::setArticleStatus( $obj, $article_key, $status, 1 );
    } else if ($status == '1gd') { // compare checked query string and total query string
        $url = $first_result['url'];
        if (empty($url)) {
            $id = $first_result['id']; 
            $url = 'https://' . $admin_host . '/article/article_comment_list.php?article_id=' . $id;
        }
        // sent duplicated email to copywriter
        // get article copy wirter info
        $user = get_article_user($article_key);
        if (Article::setCheckingURL( $article_key, $status, $url ,1 ) && !empty($user)) {
            $editor = get_article_user($article_key, 'editor');
            $mailer_param['cc'] = $editor['email'];
            if (!empty($dup_links)) $dup_links .= '<br />';
            $dup_links .= get_dup_links($obj);
            sent_duplicated_article_email($user, $dup_links, $keyword, $article_key, $editor);
        }
    }
    return true;
}

function check_article_by_copyscape($article, $article_key, $keyword, $url, $match_pct)
{
    if (empty($article)) return false;
    $string = string_encode($article);
    $post = array('t' => $string);
    $obj = copyscape_post($url, $post, $article_key);
    if (isset($obj['error'])) {
        // pr($obj['error']);
        //copyscope_credit_alert('Search: ' . $obj['error']);
        return false;
    }

   /*
    * check whether the article is copied from other sites or not
    * if the article is copied from other sites, set status as '1gc'--Google Clean
    * if the article is written by author, set status as '1gd'--Possible Duplication
    */
    $status = '1gc';
    $total = $obj['count'];
    if ($total > 0) {
        $first_result = get_object_vars($obj['result'][0]);
        $wordsmatched = $first_result['wordsmatched'];
        $querywords = $obj['querywords'];
        if (($wordsmatched/$querywords) > $match_pct) {
            $status = '1gd';
        }
    }
    if( $status == '1gc' ) {
        Article::setArticleStatus( $article_key, $status, 1 );
    } else if ($status == '1gd') { // compare checked query string and total query string
        // set duplicated checking url for the first article
        $url = $first_result['url'];
        // sent duplicated email to copywriter
        // get article copy wirter info
        $user = get_article_user($article_key);
        if (Article::setCheckingURL( $article_key, $status, $url ,1 ) && !empty($user)) {
            $editor = get_article_user($article_key, 'editor');
            $mailer_param['cc'] = $editor['email'];
            $dup_links = get_dup_links($obj);
            sent_duplicated_article_email($user, $dup_links, $keyword, $article_key, $editor);
        }
    }
    return true;
}

function add_private_index_to_copyscape($url, $param)
{
    return add_private_index($url, $param);
}

function add_private_index($url, $param, $is_show_user=false)
{
    $p = array(
        'i' => $param['article_id'],
        'a' => $param['title'],
     );
    if ($is_show_user) $p['uid'] = $param['copy_writer_id'];
    $p['cuid'] = $param['creation_user_id'];
    $p['crole'] = $param['creation_role'];
    $p['tid'] = $param['article_type'];
    $p['eid'] = $param['editor_id'];
    $p['sid'] = $param['source'];
    $p['cid'] = $param['client_id'];
    $post = array(
        't' => $param['body'],
    );
    $url = trim($url,"&") . '&' . http_build_query($p);
    $obj = copyscape_post($url, $post, $param['article_id']);
    if (isset($obj['error'])) {
        //copyscope_credit_alert('private index:' . $obj['error']);
        return false;
    }
    return $obj['handle'];
}

function get_article_user($article_id, $role = 'copy writer')
{
    $tables = array(" `articles`  AS ar ", " `campaign_keyword` AS ck ");
    $where = array(" ar.keyword_id=ck.keyword_id ", "ck.status!='D'");
    if ($role == 'copy writer') $where[] = " ck.copy_writer_id = u.user_id ";
    else if ($role == 'editor') $where[] = " ck.editor_id = u.user_id ";
    $params = array(
                        'article_id' => $article_id,
                        'table'        => $tables,
                        'where'      => $where,
                    );
    $users = User::getAllCopyWritersByParameters($params);
    if (!empty($users)) {
        $user = array_shift($users);
    } else {
        $user = null;
    }
    return $user;
}

function sent_duplicated_article_email($cpinfo, $dup_links, $keyword, $article_key, $editor)
{
    global $domain, $mailer_param;
    $address = $editor['email'];
    if (empty($address)) return false;
    if( strlen( $cpinfo['phone'] )==0 )
    {
        $cpinfo['phone'] = "n/a";
    }
    $subject = "Possible Duplicated Article";
    $body = "<div>
                            <strong>Possible duplicated article:</strong><br />
                            {$keyword} <br />
                            {$domain}/article/article_comment_list.php?article_id={$article_key}<br /><br />
                   ". $dup_links . "
                            <br />please to re-submit <a href='{$domain}/article/article_set.php?article_id={$article_key}&keyword_id={$article_key}' >here</a><br /><br />
                            <strong>Writer's Contact Info</strong><br />
                            Name:&nbsp;{$cpinfo['first_name']}&nbsp;{$cpinfo['last_name']}<br />
                            Email:&nbsp;{$cpinfo['email']}<br />
                            Phone:&nbsp;{$cpinfo['phone']}<br />
                    </div>";
    // $ccs = array('cptech@copypress.com', 'tony@infinitenine.com');
    $ccs = array('cptech@copypress.com');
    // if (!empty($editor['email'])) $ccs[] = $editor['email'];
    $ccs = array_unique($ccs);
    $mailer_param['cc'] = $ccs;
    send_smtp_mail( $address, $subject, $body, $mailer_param );
    return true;
}

function copyscope_credit_alert($error)
{
    global $mailer_param;
    $body = $error;
    $subject = 'CopyScape Api Error Alert';
    $address = 'ekaufman@copypress.com';
    //$address = 'tony@infinitenine.com';
    //$mailer_param['cc'] = array('nancy@infinitenine.com', 'technical@copypress.com');
    send_smtp_mail($address, $subject, $body, $mailer_param );
}
?>