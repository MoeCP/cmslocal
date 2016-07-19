<?php
class UserNote{

     /**
     * store GeographicName
     *
     * @param array $p
     *
     * @return boolean/int  if success will return true，else return false
     */
    function store($p = array())
    {
        global $conn, $feedback, $handle;
        
        $note_id = null;
        if (isset($p['note_id']))
        {
            $note_id = $p['note_id'];
        }

        if (isset($p['user_id']) && empty($p['user_id'])) {
            $feedback = 'Please specify the user';
            return false;
        }

        if (isset($p['title']) && empty($p['title'])) {
            $feedback = 'Please specify the title';
            return false;
        }

        if (isset($p['notes']) && empty($p['notes'])) {
            $feedback = 'Please specify the note';
            return false;
        }

        $hash = $p;

        // check it whether or not exist.
        $note_id = self::getIDByName($p['title']);
        $conn->StartTrans();
        
        if ($note_id > 0)
        {
            $set = array();
            $q = 'UPDATE user_notes SET ';
            foreach ($hash as $k => $value)
            {
                $value = addslashes(trim($value));
                $set[] = "{$k} = '{$value}'";
            }
            $q .= implode(',', $set);
            $q .= " WHERE note_id='{$note_id}'";
        }
        else
        {
            $fields = array_keys($hash);
            $q = "INSERT INTO user_notes (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $hash)."')";
        }

        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $note_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getNameByID($id)
    {
        global $conn, $feedback;
        $q = self::__genarateSql(array('note_id' => $id), array('title'));
        $rs = $conn->Execute($q);
        if ($rs) {
            $name = $rs->fields['title'];
            $rs->Close();
        }
        return $name;
    }

    function getIDByName($name)
    {
        global $conn, $feedback;
        $param = array(
            'title' => addslashes(trim($name)),
        );
       
        $q = self::__genarateSql($param, array('note_id'));
        $rs = $conn->Execute($q);
        $geo_id = 0;
        if ($rs) {
            $geo_id = $rs->fields['note_id'];
            $rs->Close();
        }
        return $geo_id;
    }

    function getTotal($param = array())
    {
        global $conn, $feedback;
        $q = self::__genarateSql($param, array('count(note_id) AS num'));
        return $conn->GetOne($q);
    }

    function __getSql($q, $fields = array())
    {
        $field_str = '';
        $field_str = empty($fields) ? 'un.*, u.user_name, u.role , c.user_name AS creator, m.user_name AS editor' : implode(",", $fields);
        $q = "SELECT {$field_str} " 
                . "FROM `user_notes` AS un " 
                ."LEFT JOIN users AS u ON u.user_id=un.user_id "
                ."LEFT JOIN users AS c ON c.user_id=un.created_by "
                ."LEFT JOIN users AS m ON m.user_id=un.modified_by "
                . $q;
        return $q;
    }

    function getInfo($note_id = null)
    {
        global $conn, $feedback;
        if (empty($note_id) || !is_numeric($note_id)) {
            $feedback = 'Invalid note, please choose one note';
            return false;
        }
        $q = ' WHERE note_id=' . $note_id;
        $q = self::__getSql($q);
        $result = $conn->getAll($q);
        if (empty($result)) {
            $feedback = 'This note is existed, please check';
            return false;
        }
        return $result[0];
    }

    /**
     * Search user info.,
     *
     * @param array $p  the form submited value.
     *
     * @return array
     * @access public
     */
    function search($p = array(), $is_pagination =true)
    {
        global $conn, $feedback;
        global $g_pager_params;

        $q = "WHERE 1 ";
        if (isset($p['category_id']) && !empty($p['category_id'])) {
            $q .= ' AND un.category_id = ' . $p['category_id'] ;
        }
        if (isset($p['user_id']) && !empty($p['user_id'])) {
            $q .= ' AND un.user_id = ' . $p['user_id'] ;
        }

        if (isset($p['status']) && !empty($p['status'])) {
            $q .= ' AND u.status = \'' . $p['status'] . '\'' ;
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= " AND ".$search->getLikeCondition("CONCAT(un.title,un.notes)")." ";
            }
        }
        $sql = 'SELECT COUNT(note_id) AS num FROM user_notes  AS un  LEFT JOIN users AS u ON u.user_id=un.user_id ' . $q;
        $count = $conn->GetOne($sql);

        if ($count == 0 || !isset($count)) {
            return false;
        }
        $q = self::__getSql($q);
        if ($is_pagination) {
            $perpage = 50;
            if (trim($p['perPage']) > 0) {
                $perpage = $p['perPage'];
            }

            require_once 'Pager/Pager.php';
            $params = array('perPage'=> $perpage,
                            'totalItems' => $count );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));

            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
            if ($rs) {
                $result = array();
                $i = 0;
                while (!$rs->EOF) {
                    $result[$i] = $rs->fields;
                    $rs->MoveNext();
                    $i++;
                }
                $rs->Close();
            }

            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'count'  => $count,
                         'result' => $result);
        } else {
            return $conn->GetAll($q);
        }

    }

    function getAll($fields, $p)
    {
        global $conn;
        $q = self::__genarateSql($p, $fields);
        $rs = $conn->Execute($q);
        $result = array();
        if ($rs) {
            while(!$rs->EOF)
            {
                $result[] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }

    function getList($p = array())
    {
        $result = self::getAll(array('note_id', 'title'), $p);
        $q = self::__genarateSql($p);
        $rs = $conn->Execute($q);
        $list = array();
        foreach ($result as $k => $item)
        {
            $name = $item['title'];
            $list[$item['note_id']] = $name;
        }
        return $list;
    }

    function __genarateSql($param = array(), $fields = array('*'), $left_join = array() , $order = 'note_id')
    {
        foreach ($param as $k => $value)
        {
            $value = addslashes(trim($value));
             $conditions[] = "{$k}='{$value}'";
        }
        $conditions[] = '1';
         $q = "SELECT " . implode(", ", $fields) . " FROM user_notes  as un ".
             "WHERE " . implode(" AND ", $conditions) . " ORDER BY " . $order;
         return $q;
    }

}//end class Preference

?>