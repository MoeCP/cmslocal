<?php
class WPCredentials {

    private $credential_id;
    private $client_id;
    private $wp_site_name;
    private $wp_url;
    private $wp_login;
    private $wp_pw;
    private $wp_main_site_yn;
    private $created;
    private $created_by;
    private $modified;
    private $modified_by;

    private function __construct()
    {
        $this->credential_id = 0;
        $this->client_id = 0;
        $this->wp_site_name = '';
        $this->wp_url = '';
        $this->wp_login = '';
        $this->wp_pw = '';
        $this->wp_main_site_yn = 0;
        $this->created = '';
        $this->created_by = 0;
        $this->modified = '';
        $this->modified_by = 0;
    }

    function WPCredentials()
    {
        $this->__construct();
    }

    public function getInfo($id)
    {
       $p = array('credential_id'=>$id);
       $list = self::__getResult($p);
       return $list[0];
    }

    function getAllCredentials($p)
    {
        return self::__getResult($p);
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        $id = addslashes(htmlspecialchars(trim($p['credential_id'])));
        if(is_numeric($id) && $id > 0)
            $condition[] = "credential_id={$id}";

        $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
        if(!empty($client_id))
        {
            if(is_numeric($client_id) && $client_id > 0)
                $condition[] = "client_id={$client_id}";
            else if(is_array($client_id))
                $condition[] = "client_id in ('" . implode("', '", $client_id) . "')";
            else if(is_string($client_id))
            {
                $client_id = stripslashes($client_id);
                $condition[] = "client_id in ('{$client_id}')";
            }
        }

        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));

        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";

        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        $sql = " SELECT {$query} FROM client_wp_credentials";
        $sql .= " WHERE {$qw} ";
        $rs = &$conn->Execute($sql);
        $result = array();
        if ($rs)
        {
            while (!$rs->EOF) 
            {
                $fields = strlen($single_column) ? $rs->fields[$single_column] : $rs->fields;
                if(strlen($index)) {
                    $result[$index] = $fields;
                } elseif ($p['id_name_only'] == true) {
                    $result[$rs->fields['credential_id']] = $rs->fields['wp_site_name'];
                }
                else 
                    $result[] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * Search wordpress Crendential info.,
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

        $q = self::searchConditions($p);

        $rs = &$conn->Execute("SELECT COUNT(wpc.credential_id) AS count ".
            "FROM client_wp_credentials AS wpc ".
            "LEFT JOIN client as c ON (wpc.client_id=c.client_id) ".$q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT wpc.*, c.user_name, c.contact_name,c.company_name ".
             "FROM client_wp_credentials AS wpc ".
             "LEFT JOIN client as c ON (wpc.client_id=c.client_id) ".$q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
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

    }//end search()    

    function searchConditions($p)
    {

        $q = "WHERE 1 ";
        $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
        if ($client_id != '') {
            $q .= "AND wpc.client_id = '".$client_id."' ";
        }

        $wp_site_name = addslashes(htmlspecialchars(trim($p['wp_site_name'])));
        if ($wp_site_name != '') {
            $q .= "AND wpc.wp_site_name LIKE '%".$wp_site_name."%' ";
        }
        $wp_login = addslashes(htmlspecialchars(trim($p['wp_login'])));
        if ($wp_login != '') {
            $q .= "AND wpc.wp_login LIKE '%".$wp_login."%' ";
        }
        $wp_url = addslashes(htmlspecialchars(trim($p['wp_url'])));
        if ($wp_url != '') {
            $q .= "AND wpc.wp_url LIKE '%".$wp_url."%' ";
        }

        $keyword = addslashes(htmlspecialchars(trim($p['keyword'])));
        if (trim($keyword) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $cond_fields = 'c.user_name, c.company_name, wpc.wp_site_name, wpc.wp_login, wpc.wp_url';
                $q .= "AND ".$search->getLikeCondition("CONCAT(" . $cond_fields . ")")." ";
            }
        }

        return $q;
    }

    // create WP credentials to store for the clients
    function store($data, $id = null)
    {
        if ($id) {
            $data['modified_by'] = User::getID();
            $data['modified'] = time();
            $ret = self::update($data, $id);
        } else {
            $data['created_by'] = User::getID();
            $data['created'] = time();
            $ret = self::insert($data);
        }
        return $ret;
    }

    function insert($data)
    {
        global $conn;
        $sql = 'INSERT INTO client_wp_credentials (%s) VALUES (%s)';
        $fields = array_keys($data);
        $field = implode(',', $fields);
        $value = "'" . implode("','", $data) . "'";
        $sql = sprintf($sql, $field, $value);
        return $conn->Execute($sql);
    }

    function update($data, $id)
    {
        global $conn;
        $sql = 'UPDATE client_wp_credentials SET %s  WHERE credential_id=%s';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . $v .  '\'';
        }
        $set = implode(",", $sets);
        $sql = sprintf($sql, $set, $id);
        return $conn->Execute($sql);
    }

}
?>