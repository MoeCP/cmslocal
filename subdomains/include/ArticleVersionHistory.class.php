<?php
/**
* Article Version History Class（用户操作类）
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

class ArticleVersionHistory {

    /**
     * Get article info by $article_id
     *
     * @param int $article_id
     *
     * @return boolean or an array containing all fields in tbl.articles
     */
    function getInfo($version_history_id)
    {
        global $conn, $feedback;

        $version_history_id = addslashes(htmlspecialchars(trim($version_history_id)));
        if ($version_history_id == '') {
            $feedback = "Please Choose an article version";
            return false;
        }

        //$q = "SELECT ar.*, cc.campaign_name, cc.campaign_id, ck.copy_writer_id, ck.editor_id, ck.keyword, ck.article_type, ck.date_start, ck.date_end, ck.is_sent, ck.keyword_meta, ck.description_meta,ck.mapping_id ".
        $q = "SELECT arvh.*, cc.campaign_name, cc.client_id, cc.campaign_id,ck.keyword ".
             "FROM articles_version_history AS arvh ".
             "LEFT JOIN campaign_keyword AS ck ON (arvh.keyword_id = ck.keyword_id) ".
             "LEFT JOIN client_campaigns  AS cc ON (cc.campaign_id = ck.campaign_id) ".
             "WHERE arvh.version_history_id = '". $version_history_id . "' AND ck.status!='D' ";
        $ret = $conn->GetRow($q);
        $version = $ret['version_number'];
        if ($ret['created_by'] > 0) {
            $created_by = $ret['created_by'];
            if ($ret['created_role'] == 'client') {
                $sql = "SELECT user_name FROM client  WHERE client_id = " . $created_by;
            } else {
                $sql = "SELECT user_name FROM users  WHERE user_id = " . $created_by;
            }
            $ret['submitted_by'] = $conn->GetOne($sql);
        }
        $article_id = $ret['article_id'];
        $ret['richtext_body'] = html_entity_decode($ret['richtext_body'], ENT_QUOTES, "UTF-8");
        $q = "SELECT coa.*, u.user_name AS creator, c.user_name AS ccreator  " . 
             "FROM comments_on_articles AS coa ".
             "LEFT JOIN users AS u ON (u.user_id = coa.creation_user_id) ".
             "LEFT JOIN client AS c ON (c. client_id  = coa.creation_user_id) ".
             "LEFT JOIN articles AS ar ON (ar.article_id = coa.article_id) ".
             "LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ".
             "WHERE coa.article_id = '".$article_id."' AND coa.version_number='" . $version. "' ORDER BY coa.version_number, coa.creation_date";
        $comments = $conn->GetAll($q);
        if (!empty($comments)) $ret['comment'] = $comments;
        return $ret;
    }//end getInfo()

    function getVersionListByArticleID($article_id)
    {
        global $conn;
        $sql  = "SELECT h.version_history_id, h.version_number, h.created_by, h.created_role, u.user_name, c.user_name AS client_name , h.posted_by " 
                  . "FROM articles_version_history AS h "
                  . "LEFT JOIN users AS u ON(u.user_id=h.created_by AND h.created_role <> 'client' AND h.created_by  > 0) " 
                  . "LEFT JOIN client AS c ON(c.client_id=h.created_by AND h.created_role='client') ";
        $sql .= "WHERE article_id=" . $article_id  . ' ORDER BY version_number DESC ';
        $result = $conn->GetAll($sql);
        $list = array();
        foreach ($result as $row) {
            $user_name = '';
            if ($row['created_role'] == 'client') {
                if (user_is_loggedin() && (User::getRole() == 'editor' || User::getRole() == 'copy writer')) {
                    $user_name = ' by client'  ;
                } else {
                    $user_name = ' by ' . $row['client_name'];
                }
            } else if ($row['created_by'] > 0) {
                $user_name = ' by ' . $row['user_name'];
            } else if (!empty($row['created_role'])) {
                $user_name = ' by ' . $row['created_role'];
            }
            if (!empty($row['posted_by'])) {
                $post_arr = unserialize($row['posted_by']);
                if (!empty($post_arr)) {
                    while($last_arr = array_pop($post_arr)) {
                        if ($last_arr['opt_type'] == 0) {
                            $user_name = ' by ' . $last_arr['opt_name'];
                            break;
                        }
                    }
                }
            }
            $list[$row['version_history_id']] = $row['version_number'] . $user_name;
        }
        return $list;
    }// end getVersionListByArticleID()

}//end class Article Version History
?>