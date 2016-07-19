<?php
class ImageVersionHistory{


	function __construct()
	{
	}

    function ImageVersionHistory()
    {
        $this->__construct();
    }

    function getTable()
    {
        return 'image_version_history';
    }

    function generateHistorySql($data)
    {
        $hash = $data;
        unset($hash['status']);
        if (isset($hash['client_id'])) unset($hash['client_id']);
        unset($hash['keyword']);
        unset($hash['keyword_status']);
        unset($hash['campaign_name']);
        unset($hash['campaign_id']);
        unset($hash['is_sent']);
        unset($hash['translation']);
        unset($hash['deadline']);
        unset($hash['vertical']);
        unset($hash['url_category']);
        unset($hash['rejected_memo']);
        unset($hash['cancel_memo']);
        unset($hash['company_name']);
        $hash['version_number'] = $hash['current_version_number'];
        unset($hash['current_version_number']);
        foreach ($hash as $k => $v) {
            if (empty($v)) unset($hash[$k]);
            else $hash[$k] = addslashes(trim($v));
        }
        $hash['createtime'] = time();
        if (user_is_loggedin()) {
            $hash['creator'] = User::getID();
            $hash['cpermission'] = User::getRole();
        } else if (client_is_loggedin()) {
            $hash['creator'] = Client::getID();
            $hash['cpermission'] = 'client';
        } else {
            $hash['created_by'] = '0';
            $hash['role'] = 'cronjob';
        }
        $q = 'INSERT INTO ' . self::getTable() . ' (`' . implode('`,`', array_keys($hash)). '`) values (\'' . implode("','", $hash) . '\')';
        return $q;
    }

    function getVersionListByImageID($image_id)
    {
        global $conn;
        $sql  = "SELECT h.version_history_id, h.version_number, h.created_by, h.created_role, u.user_name, c.user_name AS client_name , h.posted_by " 
                  . "FROM " . self::getTable(). " AS h "
                  . "LEFT JOIN users AS u ON(u.user_id=h.created_by AND h.created_role <> 'client' AND h.created_by  > 0) " 
                  . "LEFT JOIN client AS c ON(c.client_id=h.created_by AND h.created_role='client') ";
        $sql .= "WHERE image_id=" . $image_id  . ' ORDER BY version_number DESC ';
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
}
?>