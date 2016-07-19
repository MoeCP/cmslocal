<?php
class ClientUser{

	private $client_user_id;
	private $user;
	private $apikey;
	private $campaign_id;
	private $client_id;
    private $_table;

	function __construct()
	{
		$this->client_user_id = 0;
		$this->campaign_id = 0;
		$this->client_id = 0;
		$this->user = null;
		$this->apikey = null;
        $this->_table = '`client_users`';
	}

    function ClientUser()
    {
        $this->__construct();
    }

    function apiCheck($apisignature, $apikey)
    {
        global $conn;
        $sql = " SELECT * FROM " . $this->_table;
        $sql .= " WHERE apisig='{$apisignature}' AND apikey='{$apikey}' AND is_active=1 ";
        $result = $conn->GetRow($sql);
        if (!empty($result)) {
            if ($apisignature == md5($result['apikey'] . $result['token'])) {
                return $result;
            }
         // added by nancy xu 2013-01-30 18:59
         // for marster api
         // $apisignature = 5e261b70533f48523b72ce2de3f911ae
         // $apikey = 1ba597e1d29850e3cfc0204613e5ce36
        } else if ($apisignature == md5($apikey . '13wgrKw595cQGDI51891guS57Ntl')) { 
            return true;
        }
        // end
        return false;
    }

    function generateKey($p)
    {
        global $feedback, $conn, $mailer_param;
        foreach ($p as $k => $v) {
            $p[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }
        extract($p);
        if (empty($domain)) {
            $feedback = 'Please provide the domain';
            return false;
        }
        if (empty($apitype)) {
            $feedback = 'PPlease specify what\'s kind of API';
            return false;
        }

        $email = stripslashes($email);
        if (empty($email) || !valid_email($email)) {
            $feedback = "Invalid email, please to check.";
            return false;
        }
        $email = addslashes($email);

        if (!$this->checkUnique(array('domain' => $domain, 'user' => $user), $client_user_id)) {
            $feedback = 'Duplicated domain for this client, please to check';
            return false;
        }
        $token = generate_token();
        //$len =  30 - strlen($apitype);
        $apikey = $apitype . '-'  . rand_str(20);
        $apisig = md5($apikey . $token);
        $p['token'] = $token;
        $p['apikey'] = $apikey;
        $p['apisig'] = $apisig;
        if ($this->store($p)) {
            if ($this->sentAPINotice($p)) {
                $feedback = 'Success';
                return true;
            }
        }
        return false;
    }

    function storeDomain($p)
    {
        global $feedback, $conn, $mailer_param;
        foreach ($p as $k => $v) {
            $p[$k] = mysql_escape_string(htmlspecialchars(trim($v)));
        }
        extract($p);
        if (empty($domain)) {
            $feedback = 'Please provide the domain';
            return false;
        }

        $email = addslashes($email);

        if (!$this->checkUnique(array('domain' => $domain, 'user' => $user), $client_user_id)) {
            $feedback = 'Duplicated domain for this client, please to check';
            return false;
        }
        $p['is_active'] = 1;
        if ($this->store($p)) {
            $feedback = 'Success';
            return true;
        }
        return false;
    }

    function getInfo($client_user_id)
    {
        global $conn;
        $sql  = "SELECT cu.*, c.company_name FROM client_users AS cu ";
        $sql .= "LEFT JOIN client as c on (c.client_id = cu.client_user_id) ";
        $sql .= "WHERE cu.client_user_id={$client_user_id} AND cu.is_active=1";
        return $conn->getRow($sql);
    }

    function sentAPINotice($p)
    {
        global $mailer_param, $feedback;
        $email = $p['email'];
        $data = $p;
        $data['client_name'] = $p['user'];
        $even_id = 26;
        $info = Email::getInfoByEventId($even_id);
        $body = nl2br($info['body']);
        $subject = $info['subject'];
        $body = email_replace_placeholders($body, $data);
        if (!send_smtp_mail($email, $subject, $body, $mailer_param)) {
            $feedback = 'Sent Email failed';
            return false;
        }
        $feedback = 'Sent Successfully';
        return true;
    }

	function store( $hash )
	{
		global $conn, $feedback;
		$bind['client_user_id'] = mysql_escape_string(htmlspecialchars(trim($hash['client_user_id'])));
        $client_user_id = $bind['client_user_id'];
		if (isset($hash['campaign_id'])) 
            $bind['campaign_id'] = $hash['campaign_id'];
		if (isset($hash['client_id'])) 
            $bind['client_id'] = $hash['client_id'];
		if (isset($hash['user'])) 
            $bind['user'] = mysql_escape_string(htmlspecialchars(trim($hash['user'])));
        if (isset($hash['referer'])) 
            $bind['referer'] = mysql_escape_string(htmlspecialchars(trim($hash['referer'])));
        if (isset($hash['apikey'])) 
            $bind['apikey'] = $hash['apikey'];
        if (isset($hash['email'])) 
            $bind['email'] = $hash['email'];
        if (isset($hash['domain'])) 
            $bind['domain'] = $hash['domain'];
        if (isset($hash['token'])) 
            $bind['token'] = $hash['token'];
        if (isset($hash['apisig'])) 
            $bind['apisig'] = $hash['apisig'];
        if (isset($hash['apitype'])) 
            $bind['apitype'] = $hash['apitype'];
        if (isset($hash['description'])) 
            $bind['description'] = $hash['description'];
        if (isset($hash['is_active'])) 
            $bind['is_active'] = $hash['is_active'];

		// assembled sql - START
		$sql = '';
		if (count($bind))
		{
            unset($bind['client_user_id']);
			if ($client_user_id > 0) {
                $sql = "UPDATE  " . $this->_table . " SET  ";
                $sets = array();
                foreach ($bind as $key => $value) {
                    $sets[] = $key . '=\'' . $value .'\'';
                }
                $sql .= implode(', ', $sets);
                $sql .= 'WHERE client_user_id=' . $client_user_id;
            } else {
                $values = "'". implode("', '", $bind) . "'";
                $bind_keys = array_keys($bind);
                $fields = "`" . implode("`, `", $bind_keys) . "`";
                $sql = "INSERT INTO  " . $this->_table . " ({$fields}) VALUES ({$values}) ";
            }
		}
		// assembled sql - FINISHED
		if (strlen($sql))
		{
			$conn->Execute($sql);
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';
                return true;
            } else {
                $feedback = 'Success';
                return false;
            }
		}
		else
		{
			 $feedback = 'Failure, Please try again';
			return false;
		}
	}

    function checkUnique($p, $client_user_id = 0)
    {
        global $conn;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }            
        extract($p);
        if (!empty($client_id)) {
            $conditions[] = 'cu.client_id=' . $client_id;
        }
        if (!empty($user)) {
            $conditions[] = 'cu.user=\'' . $user . '\'';
        }
        if (!empty($domain)) {
            $conditions[] = 'cu.domain=\'' . $domain . '\'';
        }
        if ($client_user_id > 0) {
            $conditions[] = 'cu.client_user_id <> ' . $client_user_id;
        }
        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM client_users AS cu " . " LEFT JOIN client AS c ON ( c.client_id=cu.client_id) ";
        $sql  = "SELECT count(*)  " . $from . $where ;
        $count  = $conn->GetOne($sql);
        return  $count > 0 ?  false : true;
    }

    function getDomains($p=array())
    {
       $result = $this->getResult($p);
       $domains = array();
       foreach ($result as $row) {
           $domains[$row['client_user_id']] = $row['domain'];
       }
       return $domains;
    }

    function getIDByParam($p)
    {
        global $conn;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }            
        extract($p);
        if (!empty($client_id)) {
            $conditions[] = 'cu.client_id=' . $client_id;
        }
        if (!empty($user)) {
            $conditions[] = 'cu.user=\'' . $user . '\'';
        }
        if (!empty($domain)) {
            $conditions[] = 'cu.domain=\'' . $domain . '\'';
        }
        $conditions[] = 'cu.is_active=1';
        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM client_users AS cu " . " LEFT JOIN client AS c ON ( c.client_id=cu.client_id) ";
        $sql  = "SELECT client_user_id  " . $from . $where ;
        return $conn->GetOne($sql);
    }


    function getResult($p = array())
    {
        global $conn, $feedback;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }            
        extract($p);
        if (!empty($client_id)) {
            $conditions[] = 'cu.client_id=' . $client_id;
        }
        if (!empty($user)) {
            $conditions[] = 'cu.user=\'' . $user . '\'';
        }
        if (!empty($domain)) {
            $conditions[] = 'cu.domain=\'' . $domain . '\'';
        }

        $conditions[] = "cu.is_active = 1";

        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM client_users AS cu " . " LEFT JOIN client AS c ON ( c.client_id=cu.client_id) ";
        $sql  = "SELECT *  " . $from . $where ;
        
        return $conn->GetAll($sql);

    }


    function search($p = array())
    {
        global $conn, $feedback, $g_pager_params;
        $conditions = array("1");
        if (!empty($p)) {
            foreach ($p as $k => $v) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            }            
            extract($p);
            if (!empty($client_id)) {
                $conditions[] = 'cu.client_id=' . $client_id;
            }
            if (!empty($apitype)) {
                $conditions[] = 'cu.apitype=\'' . $apitype . '\'';
            }

            if (!empty($domain)) {
                $conditions[] = 'cu.domain like\'%' . $domain . '%\'';
            }
        }
        $conditions[] = 'cu.is_active=1';
        $where  = ' WHERE ' . implode(" AND ", $conditions);
        $from = " FROM client_users AS cu " . " LEFT JOIN client AS c ON ( c.client_id=cu.client_id) ";
        $sql  = "SELECT COUNT(*)  " . $from . $where ;
        $count = $conn->GetOne($sql);
        if ($count == 0 || !isset($count)) {
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
        
        $sql = 'SELECT cu.*, c.company_name, count(dt.domain_tag_id) AS total_tags ' 
                 . $from . ' LEFT JOIN domain_tags  AS dt ON dt.source = cu.client_user_id ' 
                 . $where . ' GROUP BY cu.client_user_id ' ;
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