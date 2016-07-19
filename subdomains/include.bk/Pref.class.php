<?php
class Preference {

     /**
     * create one preference option
     *
     * @param array $p
     *
     * @return boolean/int  if success will return true，else return false
     */
    function createPref($p = array())
    {
        global $conn, $feedback;
        $pref_table = $p['pref_table'];
        $pref_field = addslashes(htmlspecialchars(trim($p['pref_field'])));
        $pref_value = addslashes(htmlspecialchars(trim($p['pref_value'])));
        if ($pref_table == '' || $pref_field == '' || $pref_value == '') {
            $feedback = "Setting incompleted, please try again";
            return false;
        }

        // check it whether or not exist.
        $q = "SELECT COUNT(pref_table) AS count FROM preference ".
             "WHERE pref_table = '".$pref_table."' AND pref_field = '".$pref_field."' AND pref_value = '".$pref_value."'";
        $rs = $conn->Execute($q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "This preference exist in database";
            return false;
        }

        $conn->StartTrans();
        $pref_id = $conn->GenID('seq_preference_pref_id');
        $q = "INSERT INTO preference (pref_id, pref_table, pref_field, pref_value) ".
             "VALUES ('" . $pref_id . "', '" . $pref_table . "', '" . $pref_field . "', '" . $pref_value . "')";
        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $pref_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    }//end function createPref
    
    // added by snug xu 2007-05-29 10:20 - STARTED
    /**
     * store batch preference 
     * @param array $p
     * @return boolean
     */
    function storeBatch($p = array())
    {
        global $conn, $feedback;
        $pref_table   = addslashes(htmlspecialchars(trim($p['pref_table'])));
        $pref_field   = addslashes(htmlspecialchars(trim($p['pref_field'])));
        $pref_values = $p['pref_values'];
        if (strlen($pref_table) == 0 || strlen($pref_field) == 0 || count($pref_values) == 0)
        {
            $feedback = "Setting incompleted, please try again";
            return false;
        }
        for ($i =0 ; $i < count($pref_values) ; $i++)
        {
            $param['pref_table'] = $pref_table;
            $param['pref_field']  = $pref_field;
            $param['pref_value'] = $pref_values[$i];
            self::createPref($param);
        }
        if (empty($feedback)) {
            $feedback = "Success";
        }
        return true;
    }
     // added by snug xu 2007-05-29 10:20 - FINISHED
     // added by snug xu 2008-01-15 0:55 - STARTED
     function storePref($p, $is_include_value = true)
     {
        global $conn, $feedback;
        $pref_table = $p['pref_table'];
        $pref_id = isset($p['pref_id']) ? trim($p['pref_id']) : 0;
        $pref_field = addslashes(htmlspecialchars(trim($p['pref_field'])));
        $pref_value = addslashes(htmlspecialchars(trim($p['pref_value'])));
        if ($pref_table == '' || $pref_field == '' || $pref_value == '') {
            $feedback = "Setting incompleted, please try again";
            return false;
        }

        // check it whether or not exist.
        if (empty($pref_id))
        {
            $q = "SELECT pref_id  FROM preference ".
                 "WHERE pref_table = '".$pref_table."' AND pref_field = '".$pref_field."'";
            if ($is_include_value) $q .= " AND pref_value = '".$pref_value."'";
            $rs = $conn->Execute($q);
            if ($rs) {
                $pref_id = $rs->fields['pref_id'];
                $rs->Close();
            }
            if ($pref_id > 0) {
                $p['pref_id'] = $pref_id;
            }
        }

        $conn->StartTrans();
        if ($pref_id)
        {
            $q = "UPDATE preference \n";
            $q .= "SET pref_id = " . $pref_id . ", \n";
            $q .= "pref_table = '{pref_table}', \n";
            $q .= "pref_field = '{pref_field}', \n";
            $q .= "pref_value = '{pref_value}' \n";
            $q .= "WHERE pref_id=" . $pref_id;
        }
        else
        {
            $pref_id = $conn->GenID('seq_preference_pref_id');
            $q = "INSERT INTO preference (pref_id, pref_table, pref_field, pref_value) ".
             "VALUES ('" . $pref_id . "', '" . $pref_table . "', '" . $pref_field . "', '" . $pref_value . "')";
         }
        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $pref_id;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
     }
     // added by snug xu 2008-01-15 0:55 - FINISHED

    function getPref($pref_table, $field = '')
    {
        global $conn, $feedback;

        //$pref_table = $table;
        $pref_field = addslashes(htmlspecialchars(trim($field)));
        //$pref_value = addslashes(htmlspecialchars(trim($p['pref_value'])));

        if ($field == '') {
            $q = "SELECT * FROM preference ".
                 "WHERE pref_table = '".$pref_table."' ".
                 "ORDER BY pref_ordering, pref_id ASC";
        } else {
            $q = "SELECT * FROM preference ".
                 "WHERE pref_table = '".$pref_table."' ".
                 "AND pref_field = '".$pref_field."' ".
                 "ORDER BY pref_ordering, pref_id ASC";
        }
        $rs = $conn->Execute($q);
        
        if ($rs) {
            $arr = array();
            while (!$rs->EOF) {
                //$arr[] = $rs->fields['pref_value'];
                //$arr[] = $rs->fields;
                $arr[$rs->fields['pref_field']][] = $rs->fields['pref_value'];
                $rs->MoveNext();
            }
            $rs->Close();
            if (!$arr) {
                return null;
            }
            return $arr;
        }
        return null;

    }//end function getPref

     /**
     *
     * @param int $pref_id  选项ID
     *
     * @return array
     */
    function getPrefById($pref_id)
    {
        global $conn, $feedback;

        $q = "SELECT * FROM preference WHERE pref_id = '".$pref_id."'";
        $rs = $conn->Execute($q);
        
        if ($rs) {
            $arr = array();
            $arr = $rs->fields;
            $rs->Close();
            return $arr;
        }
        return null;

    }//end function getPrefById

    /**
     *@function update preference table
     *@param $p array 
     *@return boolean true/false
     */
    function updatePref($p) {
        global $conn, $feedback;
        
        $qw[]  = " WHERE 1 ";

        if (isset($p['pref_id']) && !empty($p['pref_id'])) {
            $qw[] = " pref_id=" . trim($p['pref_id']);
        }
        if (isset($p['pref_table']) && !empty($p['pref_table'])) {
            $qw[] = " pref_table='" . $p['pref_table'] . "' ";
        }

        if (isset($p['pref_field']) && !empty($p['pref_field'])) {
            $qw[] = " pref_field='" . $p['pref_field'] . "' ";
        }
            
        if (isset($p['pref_values']) && !empty($p['pref_values'])) {
            $set[] = " pref_value=" . trim($p['pref_value']);
        } else {
            $feedback = "Please enter value frist!";
            return false;
        }
        
        $conn->StartTrans();
        $sql = " UPDATE preference SET ";
        if (!empty($set)) {
            $sql .= implode(",", $set);
        }
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, please try again.';
            return false;
        }
    } //Function updatePref END


    /**
     *@function get whole information about the record
     *@param $pref_table, $pref_field, $pref_value
     *@return row/null 
     */
    function getPrefAllInfo($pref_table, $pref_field, $pref_value='') {
        global $conn;
        
        $sql = "SELECT * FROM preference ".
               "WHERE pref_table = '" . trim($pref_table) . "' " ;
        if (!empty($pref_field)) {
            $sql .= "AND pref_field = '" . trim($pref_field) . "' ";
        }
        if (!empty($pref_value)) {
            $sql .= " AND pref_value = '" . trim($pref_value) . "'";
        }
        
        $r = $conn->getAll($sql);
        if ($r) {
            return $r;
        } else {
            return null;
        }
    } //Funciton getPrefAllInfo END

}//class Preference END
?>
