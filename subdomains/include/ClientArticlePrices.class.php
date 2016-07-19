<?php
class ClientArticlePrice {
    function getTypePrice($p = array())
    {
        global $conn;
        $conditions = array();
        if (isset($p['max_word']) && $p['max_word'] > 0) {
            $conditions[] = "max_word=" . $p['max_word'];
        }
        if (isset($p['type_id'])) {
            $conditions[] = "type_id=" . $p['type_id'];
        }
        $sql = "SELECT article_price FROM `client_article_prices` WHERE " . implode(" AND ", $conditions);
        return $conn->GetOne($sql);
    }


    function getAllPrice()
    {
        global $conn;
        $sql = "SELECT * FROM `client_article_prices`";
        $result = $conn->GetAll($sql);
        $prices = array();
        foreach ($result as $row) {
            extract($row);
            $prices[$type_id][$max_word] = array($price_id, $article_price);
        }
        return $prices;
    }

    function getAllPrice4API()
    {
        global $conn;
        $sql = "SELECT type_id, max_word  as maxWord, article_price as price FROM `client_article_prices`";
        $result = $conn->GetAll($sql);
        $prices = array();
        foreach ($result as $row) {
            extract($row);
            unset($row['type_id']);
            if (!isset($prices[$type_id])) $prices[$type_id] = array('costPerArticle' => array());
            $prices[$type_id]['costPerArticle'][] = $row;
        }
        return $prices;
    }


}
?>
