<?php
class CommentsOnArticle {
    
    function search($p)
    {
        global $conn, $g_pager_params, $feedback;
        $conditions = array();
        if (user_is_loggedin()) {
            $permission = User::getPermission();
            $user_id = User::getID();
            switch($permission) {
            case 1:
                $conditions[] = 'ck.copy_writer_id =\'' . $user_id . '\'' ;
                break;
            case 2:
                $conditions[] = 'c.agency_id =\'' . $user_id . '\'' ;
                break;
            case 3:
                $conditions[] = 'ck.editor_id =\'' . $user_id . '\'' ;
                break;
             case 4:
                $conditions[] = 'c.project_manager_id =\'' . $user_id . '\'' ;
                 break;
            }
        } else if (client_is_loggedin()) {
            $conditions[] = 'c.client_id =\'' . Client::getID() . '\'' ;
            $conditions[] = 'coa.creation_role IN (\'client\', \'admin\', \'project manager\')' ;
        } else {
            $feedback = 'Permission Deny, please login system!';
            return false;
        }

        if (isset($p['client_id']) &&  $p['client_id'] > 0) {
            $conditions[] = 'c.client_id =\'' . $p['client_id']. '\'' ;
        }

        if (isset($p['editor_id']) &&  $p['editor_id'] > 0) {
            $conditions[] = 'ck.editor_id =\'' . $p['editor_id']. '\'' ;
        }

        if (isset($p['writer_id']) &&  $p['writer_id'] > 0) {
            $conditions[] = 'ck.copy_writer_id =\'' . $p['writer_id']. '\'' ;
        }

        if (isset($p['campaign_id']) &&  $p['campaign_id'] > 0) {
            $conditions[] = 'ck.campaign_id =\'' . $p['campaign_id']. '\'' ;
        }
        
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
               $conditions[] = $search->getLikeCondition("CONCAT(`coa`.`comment`, `ar`.`title`, `cc`.`campaign_name`)")." ";
            }
        }

        $where = !empty($conditions) ? ' WHERE ' .implode(' AND ', $conditions) : '';

        $fromt  = 'FROM comments_on_articles AS coa '. "\n";
        $fromt .= 'LEFT JOIN articles AS ar ON (ar.article_id = coa.article_id) ' . "\n";
        $fromt .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) '. "\n";
        $fromt .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) '. "\n";
        $fromt .= 'LEFT JOIN users AS u ON (u.user_id = coa.creation_user_id	 AND coa.creation_role <>\'client\') '. "\n";
        $fromt .= 'LEFT JOIN `client` AS cl ON (cl.client_id = coa.creation_user_id AND coa.creation_role = \'client\') '. "\n";
        $fromt .= 'LEFT JOIN `client` AS c ON (c.client_id = cc.client_id) '. "\n";
        $sql = "SELECT COUNT(coa.comment_id) " . $fromt . $where;
        $count = $conn->GetOne($sql);
        if (empty($count)) return false;
        $perpage = isset($p['perPage']) && $p['perPage'] > 0 ? $p['perPage'] : 50;
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems'   => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $sql  = 'SELECT coa.*,ck.keyword, ar.title,cc.campaign_id, cc.campaign_name, u.user_name AS author, cl.user_name AS creator ';
        $sql .= $fromt . $where;
        $sql .= ' ORDER BY coa.creation_date DESC ';
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
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
                     'count'  => $count,
                     'result' => $result);
    }
}
?>
