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
        'campaign' => array('GET'=> 'getCampaignDetail', 'OPTIONS' => 'getCampaignParam', 'POST' => 'addCampaign'),
        'domain' => array('GET'=>'getDomains', 'POST' => 'addDomain', 'OPTIONS'=>'getDomainQuestions'),
        'articletype'=> array('GET'=>'getArticleTypes', 'OPTIONS' => 'getArticleTypeQuestions'),
        'editor'=> array('GET'=>'getEdits', 'OPTIONS' => 'getArticleTypeQuestions'),
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
        '2100' => 'Please specify one campaign',
        '2101' => 'Please specify domain',
        '2102' => 'Please specify article type',
        '2103' => 'Please specify campaign name',
        '2104' => 'Please specify client',
        '2105' => 'You specify the campaign id, please to check your data',
        '2106' => 'Please specify a category',
        '2107' => 'Please provide the Start Date of the campaign',
        '2108' => 'Please provide the Due Date of the campaign',
        '2109' => 'Incorrect data,Please try again',
        '2110' => 'Invalid client,Please try again',
        '2111' => 'Duplicated domain, please check your data',
        '2112' => 'Invalid template, please check your data',
        '2113' => 'Invalid campaign, please check your data',
        '2200' => 'Invalid parameter, please specify the date',
	);

    function __construct()
    {
        $this->JsonApi();
    }

    function JsonApi()
    {
        $this->apiKey = '1ba597e1d29850e3cfc0204613e5ce36';
    }

     

    /********************************************/
    // added by nancy xu 2013-01-23 9:08
    /**
     * add campaign functions
     */
    function campaign()
    {
        $numargs = func_num_args();
        if ($numargs > 0) {
            for ($i = 0; $i < $numargs; $i++) {
                $args[$i] = func_get_arg($i);
            }
        }
        $callback = NULL;
        $httpmethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->apis['campaign'][$httpmethod])) {
            $callback = $this->apis['campaign'][$httpmethod];
        } else {
            return json_encode($this->__returnMsgInfo(500));
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


    function getCampaignParam()
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $sql = "SELECT * FROM category WHERE is_hidden = 0 ORDER BY parent_id,  category";
        $result = $conn->GetAll($sql);
        $all_cat = array();
        foreach ($result as $row) {
            $category_id = $row['category_id'];
            $category = $row['category'];
            $parent_id = $row['parent_id'];
            if ($parent_id > 0) {
                if (!isset($all_cat[$parent_id]['chidren'])) $all_cat[$parent_id]['chidren'] = array();
                $all_cat[$parent_id]['chidren'][$category_id] = $category;
            } else {
                $all_cat[$category_id]['name'] = $category;
            }
        }
        return array('category' => $all_cat);
    }
    // added by nancy xu 2014-03-27 14:59
    function getCampaignDetail()
    {
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        if (isset($_REQUEST['cid']) && $_REQUEST['cid'] > 0) {
            return $this->__getCampaignInfo($_REQUEST['cid']);
        } else if (isset($_REQUEST['date']) && $_REQUEST['date']) {
            return $this->__getCampaignByCreated($_REQUEST['date']);
        } else {
            return $this->__returnMsgInfo(2113);
        }
    }

    function __getCampaignByCreated($date)
    {
        global $conn;
        $sql = "SELECT cc.campaign_id, cc.campaign_name, cc.max_word as total_words, at.type_name as article_type, at.editor_cost as editor_cost_per_word, at.cp_cost as writer_cost_per_word, at.pay_by_article, at.cp_article_cost as writer_cost_per_article, at.editor_article_cost as editor_cost_per_article  ";
        $sql .= "FROM client_campaigns AS cc ";
        $sql .= "LEFT JOIN article_type AS at ON (cc.article_type=at.type_id) ";
        $sql .= "WHERE cc.date_created>= '{$date} 00:00:00' and cc.date_created <= '{$date} 23:59:59'";
        $result = $conn->GetAll($sql);
        $rtn = array();
        foreach ($result as $row) {
            extract($row); 
            unset($row['pay_by_article']);
            if ($pay_by_article) {
                unset($row['writer_cost_per_word']);
                unset($row['editor_cost_per_word']);
            } else {
                unset($row['editor_cost_per_article']);
                unset($row['writer_cost_per_article']);
            }
            $rtn[] = $row;
        }
        return $rtn;
    }

    function __getCampaignInfo($campaign_id) 
    {
        global $conn;
        $sql = "SELECT cc.campaign_name, cc.date_end AS due_date, cc.max_word AS total_word, COUNT(ck.keyword_id) AS total_keyword_created ";
        $sql .= "FROM client_campaigns AS cc ";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON ck.campaign_id=cc.campaign_id ";
        $sql .= ' WHERE cc.campaign_id = ' . $campaign_id;
        $sql .= ' GROUP BY cc.campaign_id ';
        $rtn = $conn->GetRow($sql);
        $rtn['total_assigned'] = 0;
        $rtn['total_submitted'] = 0;
        $rtn['total_editor_approval'] = 0;
        $rtn['total_client_approval'] = 0;
        $sql = "SELECT count(ck.keyword_id) as total, ar.article_status ";
        $sql .= "FROM campaign_keyword  as ck ";
        $sql .= "LEFT JOIN  articles  as ar on (ck.keyword_id = ar.keyword_id) ";
        $sql .= "WHERE ck.campaign_id=" . $campaign_id . ' AND ck.copy_writer_id > 0 ' ;
        $sql .= 'GROUP BY ck.campaign_id,ar.article_status  ';
        $result = $conn->GetAll($sql);
        foreach ($result as $k => $row) {
            extract($row);
            $rtn['total_assigned'] += $total;
            if (in_array($article_status, array('1', '1gc', '2', '3', '4', '5', '6'))) {
                if (in_array($article_status, array('4','5', '6'))) {
                     $rtn['total_editor_approval'] += $total;
                     if (in_array($article_status, array('5', '6'))) {
                         $rtn['total_client_approval'] += $total;
                     }
                }
                $rtn['total_submitted'] += $total;
            }
        }
        return $rtn;

    }
    // end


    function addCampaign($data)
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $cmpstep = $data['cmpstep'];
        $cid = $data['cid'];
        if ($cmpstep <> 'basic') {
            if (empty($cid)) {
                return $this->__returnMsgInfo(2100);
            }
        }
        
        switch ($cmpstep) {
        case 'basic':
            return $this->_addCampaignBasic($data);
            break;
        case 'question':
            return $this->_addCampaignQuestion($data);
            break;
        case 'detail':
            return $this->_addCampaignDetail($data);
            break;
        }
    }

    function _addCampaignBasic($data)
    {
        global $conn;
        extract($data);
        if ($cid > 0) {
            return $this->__returnMsgInfo(2105);
        }
        if (!isset($client_id) || empty($client_id)) {
            return $this->__returnMsgInfo(2104);
        }
        if (!isset($campaign_name) || empty($campaign_name)) {
            return $this->__returnMsgInfo(2103);
        }
        if (!isset($source) || empty($source)) {
            return $this->__returnMsgInfo(2101);
        }
        if (!isset($article_type) || empty($article_type)) {
            return $this->__returnMsgInfo(2102);
        }
        if (!isset($template)) {
            $template = 1;
        } else if ($template <> 1 && $template <> 2) {
            return $this->__returnMsgInfo(2112);
        }
        $p = array(
            'client_id' => $client_id, 
            'campaign_name' => $campaign_name, 
            'source' => $source, 
            'article_type' => $article_type, 
            'template' => $template, 
            'date_created' => date("Y-m-d H:i:s"), 
            'creation_user_id' => 0,
            'creation_role' => 'restapi',
            'status' => -1,  // means pending campaigns
        );
        $conn->StartTrans();
        $campaign_id = $conn->GenID('seq_client_campaigns_campaign_id');
        $p['campaign_id'] = $campaign_id;
        $fields = array_keys($p);
        $q = "INSERT INTO client_campaigns ( `" . implode("`, `", $fields) . "`) VALUES ";
        $q .= "('" . implode("', '", $p) . "')";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $rsn =  $this->__returnMsgInfo(200);
            $rsn = array();
            $rsn['cid'] = $campaign_id;
            return $rsn;
        } else {
            return $this->__returnMsgInfo(2051);
        }
    }

    function _addCampaignQuestion($data)
    {
        global $conn;
        extract($data);
        $hash = array();
        
        $questions = (array) $questions;
        //pr($questions);
        foreach ($questions as $k => $v) {
            $v = (array) $v;
            foreach ($v as $subk => $subv) {
                $subv = (array) $subv;
                $hash[$k][$subk] = $subv;
                //if (empty($subv)) unset($questions[$k][$subk]);
            }
        }
        if (!empty($hash)) {
            $questions = addslashes(serialize($hash));
            $sql = "UPDATE client_campaigns SET questions='" . $questions. "' WHERE campaign_id = " . $cid ;
            $conn->Execute($sql);
            if ($conn->Affected_Rows() == 1) {
                return $this->__returnMsgInfo(200);
            } else {
                return $this->__returnMsgInfo(304);
            }
        } else {
            return $this->__returnMsgInfo(204);
        }
    }
/*Array
(
    [cmpstep] => question
    [cid] => 703
    [questions] => stdClass Object
        (
            [source] => stdClass Object
                (
                    [purpose] => stdClass Object
                        (
                            [q] => What is the site's main focus and purpose?
                            [v] => 
                        )

                    [audience] => stdClass Object
                        (
                            [q] => What 5 words best describe the site's main audience?
                            [v] => 
                        )

                    [competitor] => stdClass Object
                        (
                            [q] => Please list your top 5 competitors.
                            [v] => 
                        )

                )

            [article_type] => stdClass Object
                (
                    [5] => stdClass Object
                        (
                            [q] => Please provide at least one link to the type of sharable content you you'd like us to emulate (please specify reasons why).
                            [v] => xx
                        )

                    [6] => stdClass Object
                        (
                            [q] => What topic ideas do you have for the content?
                            [v] => xx
                        )

                    [7] => stdClass Object
                        (
                            [q] => What taboo topics or themes must be avoided?
                            [v] => xx
                        )

                    [8] => stdClass Object
                        (
                            [q] => If you have additional requirements, instructions, or resources, please provide.
                            [v] => xx
                        )

                )

        )

)*/
    function _addCampaignDetail($data)
    {
        global $conn;
        extract($data);
//        if (!isset($client_id) || empty($client_id)) {
//            return $this->__returnMsgInfo(2104);
//        }
//        if (!isset($campaign_name) || empty($campaign_name)) {
//            return $this->__returnMsgInfo(2103);
//        }
        // check category 
        if ($category_id == 0) {
            return $this->__returnMsgInfo(2106);
        }
        if ($date_start == '') {
            return $this->__returnMsgInfo(2107);
        }
        if ($date_end == '') {
            return $this->__returnMsgInfo(2108);
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            return $this->__returnMsgInfo(2109);
        }

        if (empty($total_budget)) {
            $data['total_budget'] =0;
        }
        if (empty($cost_per_article)) {
            $data['cost_per_article'] =0;
        }
        if (empty($editor_cost)) {
            $data['editor_cost'] =0;
        }
        if (empty($max_word)) {
            $data['max_word'] = 0;
        }
        if (empty($total_keyword)) {
            $data['total_keyword'] = 0;
        }

        $data['monthly_recurrent'] = 0;
        $data['content_level'] = 0;

        unset($data['cid']);

        $sql = "UPDATE client_campaigns SET ";
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . $v . '\' ';
        }
        $sql .= implode(",", $sets);
        $sql .= "WHERE campaign_id = '".$cid."' ";
        $conn->Execute($sql);
        return $this->__returnMsgInfo(200);
        if ($conn->Affected_Rows() == 1) {
            return $this->__returnMsgInfo(200);
        } else {
            return $this->__returnMsgInfo(304);
        }
    }

    /*
     * domain functions
     */
    function domain()
    {
        $numargs = func_num_args();
        if ($numargs > 0) {
            for ($i = 0; $i < $numargs; $i++) {
                $args[$i] = func_get_arg($i);
            }
        }
        $callback = NULL;
        $httpmethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->apis['domain'][$httpmethod])) {
            $callback = $this->apis['domain'][$httpmethod];
        } else {
            return json_encode($this->__returnMsgInfo(500));
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

    function getDomains()
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $data = $_REQUEST;
        unset($data['apikey']);
        extract($data);
        if (!isset($client_id) || empty($client_id)) {
            return $this->__returnMsgInfo(2104);
        }
        $sql = 'SELECT client_user_id,domain FROM client_users AS cu WHERE cu.client_id= ' . $client_id . ' AND cu.is_active = 1   ';
        $hash = array();
        $result = $conn->GetAll($sql);
        foreach ($result as $row) {
            $hash[$row['client_user_id']] = $row['domain'];
        }
        //$rtn = $this->__returnMsgInfo(200);
        $rtn = array();
        $rtn['domains'] = $hash;
        return $rtn;
    }

    function addDomain($data)
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        extract($data);
        if (!isset($client_id) || empty($client_id)) {
            return $this->__returnMsgInfo(2104);
        }
        if (!isset($domain) || empty($domain)) {
            return $this->__returnMsgInfo(2101);
        }
        $client_info = $this->__getClientInfo($client_id);
        if (empty($client_info)) {
            return $this->__returnMsgInfo(2110);
        }
        // check the domain unique
        $sql = "SELECT COUNT(*) FROM client_users WHERE user='" . addslashes($client_info['user_name']). "' AND domain='" . addslashes($domain) . "'" ;
        $count = $conn->GetOne($sql);
        if ($count > 0) {
            return $this->__returnMsgInfo(2111);
        }
        $data['user'] = $client_info['user_name'];
        $data['email'] = $client_info['email'];
        $data['is_active'] = 1;
        foreach ($data as $k => $v) {
            $data[$k] = addslashes(trim($v));
        }
        $sql = 'INSERT INTO `client_users` (`' . implode("`,`", array_keys($data)). '`) VALUES (\'' . implode("','", $data). '\')';
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            return $this->__returnMsgInfo(201);
        } else {
            return $this->__returnMsgInfo(2109);
        }
    }

    function getDomainQuestions()
    {
        global $conn, $g_questions;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $data = $_REQUEST;
        extract($data);
        if (!isset($cid) || empty($cid)) {
            return $this->__returnMsgInfo(2100);
        }
        $sql = "SELECT * FROM client_campaigns WHERE campaign_id = " . $cid;
        $result = $conn->GetRow($sql);
        $client_id = $result['client_id'];
        $source = $result['source'];
        $article_type = $result['article_type'];
        if (!isset($client_id) || empty($client_id)) {
            return $this->__returnMsgInfo(2104);
        }
        if (!isset($source) || empty($source)) {
            return $this->__returnMsgInfo(2101);
        }
        $conditions = array(' cc.source=' . $source);
        if (isset($article_type)) {
            $conditions[] = 'article_type=' . $article_type;
        }
        $sql = " SELECT questions FROM client_campaigns  WHERE " .implode(" AND ", $conditions) . '  AND questions LIKE \'%"source"%\''. " ORDER BY campaign_id DESC ";
        $questions = $conn->GetOne($sql);
        $questions = unserialize($questions);
        $result = array();
        $s_questions = $questions['source'];
        foreach ($g_questions['source'] as $k => $v) {
            $result[$k] = array('q' => $v, 'v' => (isset($s_questions[$k]) ? $s_questions[$k] : ''));
        }
        $rtn = $this->__returnMsgInfo(200);
        $rtn = array();
        $rtn['questions'] = $result;
        return $rtn;
    }

    function __getClientInfo($client_id)
    {
        global $conn;
        if (empty($client_id)) return false;
        $sql = "SELECT * FROM client WHERE client_id=" . $client_id;
        $result = $conn->GetRow($sql);
        if (!empty($result)) return $result;
        return false;
    }


    /*
     * article type functions
     */
    function articletype()
    {
        $numargs = func_num_args();
        if ($numargs > 0) {
            for ($i = 0; $i < $numargs; $i++) {
                $args[$i] = func_get_arg($i);
            }
        }
        $callback = NULL;
        $httpmethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->apis['articletype'][$httpmethod])) {
            $callback = $this->apis['articletype'][$httpmethod];
        } else {
            return json_encode($this->__returnMsgInfo(500));
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

    function getArticleTypes()
    {
        global $conn;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $sql = "SELECT type_id, type_name FROM article_type WHERE total_nodes=0 AND is_hidden='0' AND parent_id >= 0 AND is_inactive=0  ORDER BY parent_id, type_name ";
        $result = $conn->GetAll($sql);
        $lists = array();
        foreach ($result as $row) {
            $lists[$row['type_id']] = html_entity_decode($row['type_name']);
        }
        //$rtn = $this->__returnMsgInfo(200);
        $rtn = array();
        $rtn['article_types'] = $lists;
        return $rtn;
    }

    function getArticleTypeQuestions()
    {
        global $conn, $g_questions;
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $data = $_REQUEST;
        extract($data);
        if (!isset($cid) || empty($cid)) {
            return $this->__returnMsgInfo(2100);
        }
        $sql = "SELECT * FROM client_campaigns WHERE campaign_id = " . $cid;
        $result = $conn->GetRow($sql);
        $client_id = $result['client_id'];
        $source = $result['source'];
        $article_type = $result['article_type'];
        if (!isset($client_id) || empty($client_id)) {
            return $this->__returnMsgInfo(2104);
        }
        if (!isset($source) || empty($source)) {
            return $this->__returnMsgInfo(2101);
        }
        $conditions = array(' cc.source=' . $source);
        if (isset($article_type)) {
            $conditions[] = 'article_type=' . $article_type;
        }
        $sql = " SELECT questions FROM client_campaigns  WHERE " .implode(" AND ", $conditions) . '  AND questions LIKE \'%"article_type"%\''. " ORDER BY campaign_id DESC ";
        $questions = $conn->GetOne($sql);
        $questions = unserialize($questions);
        $result = array();
        $questions = $questions['article_type'];
        $sql = "SELECT * FROM article_type_questions WHERE type_id=" . $article_type;
        $at_questions = $conn->GetAll($sql);
        foreach ($at_questions as $key => $item) {
            $k = $item['qid'];
            $result[$k] = array('q' => $item['question'], 'v' => (isset($questions[$k]['v']) ? $questions[$k]['v'] : ''));
        }
        $rtn = $this->__returnMsgInfo(200);
        $rtn = array();
        $rtn['questions'] = $result;
        return $rtn;
    }

    // end

    /********************************************/
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
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
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

    /*function _getClientID($param)
    {
        global $conn;
        $user = addslashes(trim($param['puser']));
        $password = addslashes($param['ppwd']);
        $sql = "SELECT client_id,user_pw  FROM  `client` WHERE user_name ='" . $user. "' AND user_pw='" . $password . "'";
        $data = $conn->GetRow($sql);
        $result = empty($data) ? array('client_id' => $data['client_id'], 'token' => md5( 'copypressapi' . $data['user_pw'])) : false;
        return $result;
    }*/

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

    // added by nancy xu 2014-03-25 22:21
    /*
     * editor functions
     */
    function editor()
    {
        $numargs = func_num_args();
        if ($numargs > 0) {
            for ($i = 0; $i < $numargs; $i++) {
                $args[$i] = func_get_arg($i);
            }
        }
        $callback = NULL;
        $httpmethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->apis['editor'][$httpmethod])) {
            $callback = $this->apis['editor'][$httpmethod];
        } else {
            return json_encode($this->__returnMsgInfo(500));
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
            $rs = $this->__returnMsgInfo(405);
        }
        return $rs;
    }

    function getEdits()
    {
        if (!$this->__checkAPIValid($_REQUEST['apikey'])) {
            return $this->__returnMsgInfo(203);
        }
        $conditions = array();
        if (!isset($_REQUEST['date'])) {
            return $this->__returnMsgInfo(2200);
        } else {
            $date = $_REQUEST['date'];
            $conditions[] = "(aa.created_time >= '{$date} 00:00:00' AND aa.created_time <='{$date} 23:59:59')";
        }
        if (isset($_REQUEST['uid'])) {
            $uid = $_REQUEST['uid'];
            $conditions[] = "aa.opt_id={$uid}";
        }
        $sql = "SELECT COUNT(DISTINCT article_id) AS edits, opt_id AS UserId FROM  `article_action` AS aa  ";
        $sql .= " WHERE  `new_status` LIKE '4' AND `status` IN ('1gc', '3')";
        if (!empty($conditions)) {
            $sql .= ' AND ' . implode(" AND ", $conditions);
        }
        $sql .= ' GROUP BY opt_id';
        global $conn;
        return  $conn->GetAll($sql);

    }
    // 
    //http://api.i9cms/restapi/campaign?apikey=1ba597e1d29850e3cfc0204613e5ce36&cid=119
    //http://api.i9cms/restapi/editor?apikey=1ba597e1d29850e3cfc0204613e5ce36&date=2009-11-24&uid=10
    // end

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
