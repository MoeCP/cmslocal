<?php
/**
* Rest API Class
*
* 本类是实现client的添加，获取client的必要参数，以Json形式的返回。
* This class file contain add client ,get client paramaters, return json code
*
* @global  string $conn
* @global  string $feadback
* @author  Nancy.Xu  <nancy@infinitenine.com>
* @copyright Copyright &copy; 2012
* @access  public
*/
/*add client: {"username":"projectmanageradfs","upassword":"123456","company_name":"1333","company_address":"","country":"Tajikistan","city":"za","state":"d","zip":"ddd","email":"dd@infinitne.com","company_url":"","contact_name":"","company_phone":"","bill_email":"","bill_office_phone":"","technical_email":"","technical_office_phone":"","project_manager_id":"388","agency_id":"0","referrer_type":"1","referrer_name":"dafd","referrer_tracking":""}*/

class restapi {
    var $apiKey;
    var $apis = array(
        'client' => array('POST' => 'storeClient', 'OPTIONS' => 'getClientParam', 'GET'=>'getClient'),
    );

	private $codes = array(
		'100' => 'Continue',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'300' => 'Multiple Choices',
		'301' => 'Moved Permanently',
		'302' => 'Found',
		'303' => 'See Other',
		'304' => 'Not Modified',
		'305' => 'Use Proxy',
		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'503' => 'Service Unavailable',
		'504' => 'Keyword Format Not Match',
		'999' => 'Task Processing',
		'1000' => 'Task Pending',
        '1999' => 'Please specify client user name',
        '2000' => 'Please specify client password',
        '2001' => 'Please specify client\'s company name',
        '2002' => 'Please specify city',
        '2003' => 'Invalid email, please to check.',
        '2004' => 'Please specify state',
        '2005' => 'Please specify ZIP',
        '2006' => 'Please specify a project manager',
        '2007' => 'Please specify the referrer type',
        '2008' => 'Please specify the referrer name',
        '2050' => 'Duplicated User Name, please type another name.',
        '2051' => 'Create Failure',

	);

    function __construct()
    {
        $this->JsonApi();
    }

    function JsonApi()
    {
        $this->apiKey = '1ba597e1d29850e3cfc0204613e5ce36';
    }

    function client()
    {
        $numargs = func_num_args();
        if ($numargs > 0) {
            for ($i = 0; $i < $numargs; $i++) {
                $args[$i] = func_get_arg($i);
            }
        }
        $callback = NULL;
        $httpmethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->apis['client'][$httpmethod])) {
            $callback = $this->apis['client'][$httpmethod];
        } else {
            $desc = $this->codes['500'];
            $rtn = array('status'      => 500,
                         'description' => $desc);
            return json_encode($rtn);
        }
        
        if (is_callable(array($this, $callback))) {
            // get the request data
            $data = NULL;
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $data = array();
            } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $_POST;
                if (empty($_POST)) {
                    $data = (array) json_decode(file_get_contents('php://input'));
                }
            } else if ($tmp = file_get_contents('php://input')) {
                $data = json_decode($tmp);
            }
            $p = $_REQUEST;
            $p['sssdata']= json_encode($data);
            // execute the function/method and return the results
            header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
            header('Content-Type: text/plain');
            $rs = json_encode(call_user_func(array($this, $callback), $data));
            // store data logs 
            $p['sssreply'] = $rs;
            $p['parsed'] = date("Y-m-d H:i:s");
            
            $oLog = new DataLog;
            $oLog->dataDispose($p, 'json');
            // store end
        } else {
            $desc = $this->codes['405'];
            $rtn = array('status'      => 405,
                         'description' => $desc);
            $rs = json_encode($rtn);
        }
        return $rs;
    }

    function getClientParam()
    {
        global $g_referrer_types;
        $all_pm = User::getAllUsers($mode = 'id_name_only', $user_type = 'project manager', false);
        $all_pm += User::getAllUsers($mode = 'id_name_only', $user_type = 'admin', false);
        $data = array(
            'project_manager' => $all_pm
        );
        $pref = Preference::getPref("client", 'country');
        if (!empty($pref)) {
            $data['country'] = array_combine($pref['country'], $pref['country']);
        }
        $data['agency'] = User::getAllUsers($mode = 'id_name_only', $user_type = 'agency',false);
        $data['referrer_type'] = $g_referrer_types;
        return $data;
    }

    function getClient()
    {
        if (isset($_REQUEST['puser']) && $_REQUEST['ppwd']) {
            return $this->_getClientID($_REQUEST);
        } else if (isset($_REQUEST['cid'])) {
            //$this->__getClientInfo($_REQUEST['cid']);
        }
    }

    function _getClientID($param)
    {
        global $conn;
        $user = addslashes(trim($param['puser']));
        $password = addslashes($param['ppwd']);
        $sql = "SELECT client_id FROM  client where user_name ='" . $user. "' AND user_pw='" . $password . "'";
        $id = $conn->GetOne($sql);
        return empty($id) ? false : $id;
    }

    function storeClient($data)
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }

        if (isset($data['username'])) {
            $data['user_name'] = $data['username'];
            unset($data['username']);
        }

        if (isset($data['upassword'])) {
            $data['user_pw'] = $data['upassword'];
            unset($data['upassword']);
        }

        if (!isset($data['user_name']) || empty($data['user_name'])) {
            return $this->__returnMsgInfo(1999);
        }
        
        if (!isset($data['user_pw']) || empty($data['user_pw'])) {
            return $this->__returnMsgInfo(2000);
        }

        if (!isset($data['company_name']) || empty($data['company_name'])) {
            return $this->__returnMsgInfo(2001);
        }

        if (!isset($data['city']) || empty($data['city'])) {
            return $this->__returnMsgInfo(2002);
        }

        if (!isset($data['email']) || empty($data['email']) || !valid_email($data['email'])) {
            return $this->__returnMsgInfo(2003);
        }

        if (!isset($data['state']) || empty($data['state'])) {
            return $this->__returnMsgInfo(2004);
        }

        if (!isset($data['zip']) || empty($data['zip'])) {
            return $this->__returnMsgInfo(2005);
        }

        if (!isset($data['project_manager_id']) || empty($data['project_manager_id'])) {
            return $this->__returnMsgInfo(2006);
        }

        if (!isset($data['referrer_type']) || empty($data['referrer_type'])) {
            return $this->__returnMsgInfo(2007);
        } else if ($data['referrer_type'] == 2 && (!isset($data['referrer_name']) || empty($data['referrer_name']))) {
            return $this->__returnMsgInfo(2008);
        }

        $q = "SELECT COUNT(*) AS count FROM `client` WHERE user_name = '".$data['user_name']."'";
        $count = $conn->GetOne($q);
        if ($count > 0) {
            return $this->__returnMsgInfo(2050);
        }
        // get all columns of the client
        $sql = "SHOW COLUMNS FROM `client`";
        $rows = $conn->GetAll($sql);
        if (!isset($data['client_id']) || empty($data['client_id'])) {
            $conn->StartTrans();
            $data['creation_user'] = 1;
            $data['creation_role'] = 'admin';
            $hash = array('client_id' => $conn->GenID('seq_client_client_id'));
            foreach ($rows as $k => $row) {
                $field_name = $row['Field'];
                if (isset($data[$field_name])) {
                    if ($field_name == 'agency_id' && empty($data[$field_name])) {
                        $data[$field_name] = 0;
                    }
                    $hash[$field_name] = $data[$field_name];
                }
            }
            $q = "INSERT INTO `client` (`" . implode("`,`", array_keys($hash)) . "`) VALUES ('" . implode("','", $hash) . "')";
            $conn->Execute($q);
            $ok = $conn->CompleteTrans();
            if ($ok) {
                return $this->__returnMsgInfo(201);
            }
        }
        return $this->__returnMsgInfo(2051);
    }

    function __returnMsgInfo($code)
    {
        $desc = $this->codes[$code];
        $rtn = array('status'      => $code,
                     'description' => $desc);
        return $rtn;
    }

    function __checkAPIValid($ak){
        if ($this->apiKey == $ak) {
            return true;
        }

        return false;
    }

}