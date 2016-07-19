<?php
class Item {

    function save($arr) 
    {
        global $conn;
        $keys = array_keys($arr);
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes($value);
        }
        $q = "INSERT INTO `items` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        return $conn->Execute($q);
    }

    function getItemByName($name)
    {
        $result = Item::getItemByParam(array('name' => $name));
        return isset($result[0]) ? $result[0] : null;
    }

    function getItemByItemId($item_id)
    {
        $result = Item::getItemByParam(array('item_id' => $item_id));
        return isset($result[0]) ? $result[0] : null;
    }

    function getItemList()
    {
        $result = Item::getItemByParam();
        $list = array();
        foreach ($result as $k => $item) {
            $list[$item['item_id']] = $item['name'];
        }
        return $list;
    }

    function getItemByParam($param = array())
    {
        global $conn;
        foreach ($param as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key=> $value) {
                    $v[$key] = addslashes(trim($value));
                }
            } else {
                $v = addslashes(trim($v));
            }
            $param[$k] = $v;
        }
        extract($param);
        $condtions = array();
        if (isset($name) && !empty($name)) {
            $condtions[] = "name='{$name}'";
        }
        if (isset($item_id) && !empty($item_id)) {
            if (is_array($item_id)) {
                $condtions[] = "item_id IN ('" . implode("', '", $item_id) . "')";
            } else {
                $condtions[] = "item_id='{$item_id}'";
            }
        }
        $sql = "SELECT * FROM items ";
        if (!empty($condtions)) {
            $sql .= 'WHERE  ' . implode(" AND ", $condtions);
        }
        return $conn->GetAll($sql);
    }
}
?>
