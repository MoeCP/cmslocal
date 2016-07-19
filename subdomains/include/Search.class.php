<?php
/**
* Search Class（用于搜索）
*
* 本类是实现模糊搜索功能。
* This class file contain punch-drunk search function 
*
* @global  string $operator
* @global  string $error
* @global  string $words
* @author  Leo.Liu  <leo.liuxl@gmail.com>
* @copyright Copyright &copy; 2006
* @access  public
*/
class Search {

    var $operator;
    var $words;
    var $error;

    function Search($words, $operator)
    {
        $this->error = '';
        $this->cleanSearchWords($words);
        $this->operator = $operator;
    }


    /**
     * cleanSearchWords - clean up the words we are searching for
     *
     * @param string $words words we are searching for
     */
    function cleanSearchWords($words) {
        $words = trim($words);
        if($words == '') {
            $this->error = 'Please enter the keyword';//请填写关键词
            return;
        }

        $words = htmlspecialchars($words);
        $words = strtr($words, array('%' => '', '_' => ' '));
        $words = preg_replace("/[ \t]+/", ' ', $words);

        if(mb_strlen($words) < 2) {//输入框中必须填写的长度，可调整
            $this->error = 'Please type the keywords over two';//请填写2个字以上的关键词
            return;
        }

        $this->words = explode(' ', quotemeta($words));
    }

    /**
     * getLikeCondition - build the LIKE condition of the SQL query for a given field name
     *
     * @param string $field  name of the field in the LIKE condition
     * @return string  the condition
     */
    function getLikeCondition($field)
    {
        return $field." LIKE '%".implode("%' ".$this->operator." ".$field." LIKE '%", $this->words)."%'";
    }


    function getError()
    {
        return $this->error;
    }
}

?>