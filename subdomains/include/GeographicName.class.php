<?php
class GeographicName{

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
        
        foreach ($p as $k => $value)
        {
            $p[$k] = addslashes(trim($value));
        }
        $geo_id = null;
        if (isset($p['geo_id']))
        {
            $geo_id = $p['geo_id'];
        }
        $hash['parent_id'] = $p['parent_id'];
        $hash['type']       = $p['type'];
        $hash['name']      = $p['name'];

        // check it whether or not exist.
        $geo_id = self::getIDByNameAndType($p['name'], $p['type'], $p['parent_id']);
        $conn->StartTrans();
        if ($geo_id > 0)
        {
            $q = 'UPDATE geographic_names SET';
            foreach ($hash as $k => $value)
            {
                $q .= "{$k} = '{$value}'";
            }
            $q .= " WHERE geo_id='{$geo_id}'";
        }
        else
        {
            $geo_id = $conn->GenID('seq_geographic_names_geo_id');
            $hash['geo_id'] = $geo_id;
            $fields = array_keys($hash);
            $q = "INSERT INTO geographic_names (" . implode(", ", $fields). ") ".
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
        $q = self::__genarateSql(array('geo_id' => $id), array('name'));
        $rs = $conn->Execute($q);
        if ($rs) {
            $name = $rs->fields['name'];
            $rs->Close();
        }
        return $name;
    }

    function getIDByNameAndType($name, $type, $parent_id)
    {
        global $conn, $feedback;
        $param = array(
            'name' => addslashes(trim($name)),
            'type' => $type,
            'parent_id' => $parent_id,
        );
       
        $q = self::__genarateSql($param, array('geo_id'));
        $rs = $conn->Execute($q);
        $geo_id = 0;
        if ($rs) {
            $geo_id = $rs->fields['geo_id'];
            $rs->Close();
        }
        return $geo_id;
    }

    function getGeosByParentId($parent_id)
    {
        global $conn, $feedback;
        $result = array();
        $param = array(
            'parent_id' => $parent_id
        );
        $q = self::__genarateSql($param, array('geo_id', 'type', 'name'), 'name');
        $rs = $conn->Execute($q);
        if ($rs) {
            while(!$rs->EOF)
            {
                $result[$rs->fields['geo_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }

    function getTotal($param = array())
    {
        global $conn, $feedback;
        $q = self::__genarateSql($param, array('count(geo_id) AS num'));
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

    function getGeoListByParentId($parent_id)
    {
        $result   = self::getGeosByParentId($parent_id);
        $geoList = array();
        foreach ($result as $k => $item)
        {
            $name = $item['name'];
            $geoList[$item['geo_id']] = $name;
        }
        return $geoList;
    }

    function __genarateSql($param = array(), $fields = array('*'), $order = 'geo_id')
    {
        foreach ($param as $k => $value)
        {
             $conditions[] = "{$k}='{$value}'";
        }
         $q = "SELECT " . implode(", ", $fields) . " FROM geographic_names ".
             "WHERE " . implode(" AND ", $conditions) . " ORDER BY " . $order;
         return $q;
    }

}//end class Preference

?>