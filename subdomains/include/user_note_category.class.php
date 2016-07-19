<?php
class UserNoteCategory{

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
        
        $category_id = null;
        if (isset($p['category_id']))
        {
            $category_id = $p['category_id'];
        }

        if (isset($p['name']) && empty($p['name'])) {
            $feedback = 'Please enter name';
            return false;
        }

        $hash = $p;

        // check it whether or not exist.
        $category_id = self::getIDByName($p['name']);
        if ($category_id > 0) {
            $feedback = 'Please duplicated name, please check';
            return false;
        }
        $conn->StartTrans();
        if ($category_id > 0)
        {
            $set = array();
            $q = 'UPDATE user_note_category SET ';
            foreach ($hash as $k => $value)
            {
                $value = addslashes(trim($value));
                $set[] = "{$k} = '{$value}'";
            }
            $q .= implode(',', $set);
            $q .= " WHERE category_id='{$category_id}'";
        }
        else
        {
            $fields = array_keys($hash);
            $q = "INSERT INTO user_note_category (" . implode(", ", $fields). ") ".
             "VALUES ('" . implode("', '", $hash)."')";
        }
        //fwrite($handle, $q . "\n");
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $geo_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function store

    function getNameByID($id)
    {
        global $conn, $feedback;
        $q = self::__genarateSql(array('category_id' => $id), array('name'));
        $rs = $conn->Execute($q);
        if ($rs) {
            $name = $rs->fields['name'];
            $rs->Close();
        }
        return $name;
    }

    function getIDByName($name)
    {
        global $conn, $feedback;
        $param = array(
            'name' => addslashes(trim($name)),
        );
       
        $q = self::__genarateSql($param, array('category_id'));
        $rs = $conn->Execute($q);
        $geo_id = 0;
        if ($rs) {
            $geo_id = $rs->fields['category_id'];
            $rs->Close();
        }
        return $geo_id;
    }

    function getTotal($param = array())
    {
        global $conn, $feedback;
        $q = self::__genarateSql($param, array('count(category_id) AS num'));
        $rs = $conn->Execute($q);
        if ($rs) {
            if (!$rs->EOF)
            {
                $num = $rs->fields['num'];
            }
            $rs->Close();
        }
        return $num;
    }

    /**
     * Search user info.,
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

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= " AND ".$search->getLikeCondition("CONCAT(unc.name,unc.description)")." ";
            }
        }

        $sql = "SELECT count(category_id) " . "FROM `user_note_category` AS unc " . $q;
        $count = $conn->GetOne($sql);

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $perpage = 50;
        if (trim($p['perPage']) > 0) {
            $perpage = $p['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array('perPage'=> $perpage,
                        'totalItems' => $count );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = self::__getSql($q);

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

    }

    function __getSql($q)
    {
        $q = "SELECT unc.*, c.user_name AS creator " 
               . "FROM `user_note_category` AS unc "
               . "LEFT JOIN users AS c ON c.user_id=unc.created_by "
               . $q;
        return $q;
    }

    function getInfo($category_id)
    {
        global $conn;
        if (empty($category_id) || !is_numeric($category_id)) {
            $feedback = 'Invalid note, please choose one note';
            return false;
        }
        $q = ' WHERE category_id=' . $category_id;
        $sql = self::__getSql($q);
        $result = $conn->GetAll($q);
        if (empty($result)) {
            $feedback = 'This category is existed, please check';
            return false;
        }
        if (!empty($result)) {
            return $result[0];
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
        global $conn;
        $result = self::getAll(array('category_id', 'name'), $p);
        $list = array();
        foreach ($result as $k => $item)
        {
            $name = $item['name'];
            $list[$item['category_id']] = $name;
        }
        return $list;
    }

    function __genarateSql($param = array(), $fields = array('*'), $order = 'category_id')
    {
        foreach ($param as $k => $value)
        {
            $value = addslashes(trim($value));
             $conditions[] = "{$k}='{$value}'";
        }
        $conditions[] = '1';
         $q = "SELECT " . implode(", ", $fields) . " FROM user_note_category ".
             "WHERE " . implode(" AND ", $conditions) . " ORDER BY " . $order;
         return $q;
    }

}//end class Preference

?>