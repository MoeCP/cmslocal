<?php
/**
* Client Class（用户操作类）
*
* 本类是实现用户(admin,project manager, copywriter, editor)的添加，修改，删除和查找功能。
* This class file contain add,update,delete and search user's function 
*
* @global  string $conn
* @global  string $feadback
* @author  Leo.Liu  <leo.liuxl@gmail.com>
* @copyright Copyright &copy; 2006
* @access  public
*/
class Client {

    /**
     * Login routine
     *
     * @param string $user_name
     * @param string $user_pw
     *
     * @return boolean or an array containing session user info
     */
    function getLogin($user_name, $user_pw) {
        global $conn, $feedback;

        $user_name = trim($user_name);
        $user_pw   = trim($user_pw);

        if ($user_name == '' || $user_pw == '') {
            $feedback = 'Please Enter Client User Name AND Password';//Please type user name and password
            return false;//请输入用户名和密码
        }

        //We should decode the password by md5();
        $q = "SELECT * FROM `client` ".
             "WHERE user_name = BINARY '".addslashes($user_name)."' ".
             "AND user_pw = BINARY '".addslashes($user_pw)."' AND status ='A'";
        $rs = &$conn->Execute($q);
        if (!$rs) {
            $feedback = 'Login Incorrect! Please Try Again...';//Invalid user name or password, Please Try again
            return false;
        } else {
            $client_id  = $rs->fields['client_id'];
            $email  = $rs->fields['email'];
            $status     = $rs->fields['status'];//We should add a status field, better than delete one user directly
            $user_name  = $rs->fields['user_name'];
            $contact_name  = $rs->fields['contact_name'];
            $agency_id  = $rs->fields['agency_id'];
            $rs->Close();
        }

        if ($client_id != 0) { // don't use record count to improve performance

            if ($status == 'A') {

                //$user_perm = User_Perm::getPerm($user_id); // If we extends our permission, add a class user_perm is a good idea,and this class i had finished

                return array('client_id'    => $client_id,
                             'user_name'  => $user_name,
                             'contact_name'  => $contact_name,
                             'email'  => $email,
                             'agency_id'  => $agency_id,
                             'status'     => $status);
            } else {
                $feedback = 'Login incorrect! Please try again';//This account didn't login
                return false;
            }

        } else {
            $feedback = 'Invalid user name or password, Please type again';//Invalid user name or password, Please type again(用户名或密码不正确，请重新填写)
            return false;
        }
    }// end getLogin()

    /**
     * Set login vars in storage
     *
     * @param array $login return from this::getLogin()
     */
    function setLogin($login)
    {
        $_SESSION['client_id']   = $login['client_id'];
        $_SESSION['user_name'] = $login['user_name'];
        $_SESSION['status']    = $login['status'];

        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_email'] = $login['email'];
        $_SESSION['agency_id'] = $login['agency_id'];
        $_SESSION['contact_name'] = $login['contact_name'];
        $_SESSION['last_login_time'] = Logger::getClientLastLogin($login['client_id']);

        Logger::logClientSession($login['client_id'], session_id());
    }//end setLogin()

    /**
     * Add an client and client's information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function add($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;

        if (User::getRole() != 'admin' && User::getRole() != 'agency') {
            $feedback = "Have not the permission add one client user";
            return false;
        }

        if (User::getRole() == 'agency') {
            $p['agency_id'] = User::getID();
        }

        // added by snug xu 2006-11-24  - START
        // get creation user info of this client
        $creation_user = addslashes(htmlspecialchars(trim($p['creation_user'])));
        $creation_role = addslashes(htmlspecialchars(trim($p['creation_role'])));
        // added by snug xu 2006-11-24  - END

        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);        
        if ($user_name == '') {
            $feedback = "Please enter client's user name";
            return false;
        }
        if (!valid_user_name($user_name)) {
            return false;
        }
        $pass = $user_pw;
        if ($pass != $user_pwnew) {
            $feedback = 'Password mismatch, Please enter the password again';//两次填写的新密码不一致，请重新填写
            return false;
        } else {
            unset($p['user_pwnew']);
        }

        if (!valid_pw($pass)) {//this function in the utils.php,
            return false;
        }

        if ($company_name == '') {
            $feedback = "Please provide client's company name";//请填写first name.
            return false;
        }
        if ($city == '') {
            $feedback = "Please provide city";
            return false;
        }

        $email = stripslashes($email);
        if (!valid_email($email)) {
            $feedback = "Invalid email, please to check.";
            return false;
        }
        $email = addslashes($email);

        if ($state == '') {
            $feedback = "Please enter state";
            return false;
        }

        if ($zip == '') {
            $feedback = "Please provide ZIP";
            return false;
        }

        if ($project_manager_id == '') {
            $feedback = "Please provide a project manager";
            return false;
        }

        $q = "SELECT COUNT(*) AS count FROM `client` WHERE user_name = '".$user_name."'";
        $count = $conn->GetOne($q);
        if ($count > 0) {
            $feedback = "The client's user name already registered, please type another name.";//用户名重复
            return false;
        }

        $conn->StartTrans();
        $client_id = $conn->GenID('seq_client_client_id');
        $p['client_id'] = $client_id;
        $keys = array_keys($p);
        $q = "INSERT INTO `client` (`" . implode("`,`", $keys) . "`) VALUES ('" . implode("','", $p) . "')";

        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            self::sendAccountInfo($p);
            return $client_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end add()

    function sendAccountInfo($data)
    {
        global $mailer_param;
        $arr = array(
            "%%COMPANY_NAME%%" => $data['company_name'],
            "%%USER_NAME%%" => $data['user_name'],
            "%%USER_PW%%" => $data['user_pw'],
        );
        $info = getEmailSubjectAndBody(29, $arr);
        extract($info);
        send_smtp_mail($data['email'], $subject, $body, $mailer_param);
    }

    /**
     * Change client's password by user self or admin
     *
     * @param int     $user_id
     * @param string  $new_pw1
     * @param string  $new_pw2
     * @param string  $old_pw
     * @param boolean $require_old_pw  Must set to true when $old_pw != null
     *
     * @return boolean
     */
    function setPasswd($client_id, $new_pw1, $new_pw2, $require_old_pw = true, $old_pw = 'xxx')
    {
        global $conn, $feedback;

        $old_pw  = trim($old_pw);
        $new_pw1 = trim($new_pw1);
        $new_pw2 = trim($new_pw2);
        $client_id = addslashes(htmlspecialchars(trim($client_id)));//Forbid hacker attack database by inject sql;

        if ($new_pw1 == '' || $new_pw2 == '' || ($require_old_pw && $old_pw == '')) {
            $feedback = 'Please enter password';//pls. type password.
            return false;
        }

        if ($new_pw1 != $new_pw2) {
            $feedback = 'Password Mismatch, Please Check Your Input And Enter The Password Again';//两次填写的新密码不一致，请重新填写
            return false;
        }

        if (!valid_pw($new_pw1)) {//utils.php
            return false;
        }

        if ($require_old_pw) {
            $client_info = self::getInfo($client_id);
            if ($old_pw != stripslashes($client_info['user_pw'])) {
                $feedback = 'Incorrect Old Password, Please Try Again';//the old password did not correct(旧密码不正确，请重新填写)
                return false;
            }
        }

        // the password need md5()?
        // all checks passed
        $q = "UPDATE `client` SET user_pw = '".addslashes(htmlspecialchars($new_pw1))."' ".
             "WHERE client_id = '".$client_id."'";

        if ($conn->Execute($q) === false) {
            $feedback = 'Failure, Please try again.';//密码更改失败，请重新操作
            return false;
        } else {
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';//密码更改成功
                return true;
            } else {
                $feedback = 'Failure, Please try again.';//密码更改失败，请重新操作
                return false;
            }
        }
    }// end setPasswd()

    /**
     * Get client's info by $client_id
     *
     * @param int $client_id
     *
     * @return boolean or an array containing all fields in tbl.client
     */
    function getInfo($client_id)
    {
        global $conn;

        $rs = &$conn->Execute("SELECT * FROM `client` WHERE client_id = '".$client_id."'");

        if ($rs) {
            $ret = false;
            if ($rs->fields['client_id'] != 0) {
                $ret = $rs->fields; // return an array
            }

            $rs->Close();
            return $ret;
        }

        return false; // return false if client does not exist
    }//end getInfo()

     /**
     * Set client's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setInfo($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;
        // added by snug xu 2006-11-24 14:24
        // let user who role is agency modify his/her own client
        if (User::getPermission() < 4 && User::getPermission() != 2 && !client_is_loggedin()) {
            $feedback = "Have not the permission add one client user";
            return false;
        }

        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        if ($client_id == '') {
            $feedback = "Please choose an client";
            return false;
        }
        if ($user_name == '') {
            $feedback = "Please input client's user name";
            return false;
        }
        if (!valid_user_name($user_name)) {
            return false;
        }

        $pass = $user_pw;
        $qu = "";
        if ($pass != '') {
            //$pass = addslashes(htmlspecialchars(trim($p['user_pw'])));
            if ($pass != $user_pwnew) {
                $feedback = 'Password mismatch, Please check your input and enter the password again';//两次填写的新密码不一致，请重新填写
                return false;
            }

            if (!valid_pw($pass)) {//this function in the utils.php,
                return false;
            }
            //$qu .="user_pw = '".$pass."', ";
            unset($p['user_pwnew']);
        }

        if (!valid_pw($pass)) {//this function in the utils.php,
            return false;
        }
        
        if ($company_name == '') {
            $feedback = "Please provide client's company name";//请填写first name.
            return false;
        }

        if ($city == '') {
            $feedback = "Please provide city";
            return false;
        }

        $email = stripslashes($email);
        if (!valid_email($email)) {
            $feedback = "Invalid email, please to check.";
            return false;
        }
        $email = addslashes($email);

        if ($state == '') {
            $feedback = "Please enter state";
            return false;
        }

        if ($zip == '') {
            $feedback = "Please provide ZIP";
            return false;
        }
        
        if ($project_manager_id == '') {
            $feedback = "Please provide a project manager";
            return false;
        }

        if ($referrer_type == '') {
            $feedback = "Please specify the referrer type";
            return false;
        }

        if ($referrer_type == 2 && $referrer_name == '') {
            $feedback = "Please specify the referrer name";
            return false;
        }

        $q = "SELECT COUNT(*) AS count FROM `client` ".
             "WHERE user_name = '".$user_name."' AND client_id != '".$client_id."'";
        $count = $conn->GetOne($q);
        if ($count > 0) {
            $feedback = "The client's user name already registered, please enter another name.";//用户名重复
            return false;
        }

        
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = "`{$k}`='{$v}'";
        }
        $sql = "UPDATE `client` SET " . implode(", ", $sets) . " WHERE client_id='" . $client_id. "'" ;
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';//用户信息修改成功
            return true;
        } else {
            $feedback = 'Failure, Please try again';//用户信息修改失败
            return false;
        }
        
    }//end setInfo()

    /**
     * Check if user's session IP addr is in the same class B subnet with user's current IP addr
     *
     * @param string $curr_ip  Current IP address
     *
     * @return boolean
     */
    function chkSessionIP($curr_ip)
    {
        global $feedback;

        $sess_ip = $_SESSION['user_ip'];

        $e_sess_ip = explode(".", $sess_ip);
        $e_curr_ip = explode(".", $curr_ip);

        // require same class B subnet
        if ($e_sess_ip[0] == $e_curr_ip[0] && $e_sess_ip[1] == $e_curr_ip[1]) {
            return true;
        } else {
            $feedback = 'Current IP address and inital IP address mismatch,Please try again';//当前IP地址与初始IP地址不一致，请重新登陆
            return false;
        }
    }//chkSessionIP()

    /**
     * get all user and user's information
     *
     * @param array $mode
     *
     * @return array
     */
    function getAllClients($mode = 'all_infos', $is_active = true)
    {
        global $conn;

        // added by snug xu 2006-11-24 15:15 - START
        // if the role of login user is agency
        // only select the clients that current user created
        $qw = '';
        if (User::getRole() == 'agency') {
            $qw .= " AND agency_id='" . User::getID() . "'";
        } else if (User::getPermission() == 4) {
            $qw .= ' AND project_manager_id=' . User::getID() . ' ';
        }
        // added by snug xu 2006-11-24 15:15 - END

        if ($mode == 'all_infos') {
            $q = "SELECT * FROM `client` WHERE 1 ";
            if ($is_active) $q .= " AND status != 'D'";
            $q .= " {$qw} ORDER BY client_id ";
            $rs = &$conn->Execute($q);
            if ($rs) {
                $client = array();
                while (!$rs->EOF) {
                    $client[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
                return $client;
            }
            return null;
        } else {
            $q = "SELECT client_id, company_name FROM `client` WHERE 1 ";
            if ($is_active) $q .= " AND status != 'D'";
            $q .= " {$qw} ORDER BY company_name ";
            $rs = &$conn->Execute($q);
            if ($rs) {
                $client = array();
                while (!$rs->EOF) {
                    $client[$rs->fields['client_id']] = $rs->fields['company_name'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $client;
            }
            return null;
        }
    }// end getAllClients()

    function getClientsByParam($p = array())
    {
        global $conn;
        $conditions = array( ' 1 ');
        if (isset($p['client_id']) && !empty($p['client_id'])) {
            $client_id = $p['client_id'];
            if (is_array($client_id)) {
                $conditions[] = "cl.client_id IN (" . implode(',', $client_id) . ")";
            } else {
                $conditions[] = 'cl.client_id = ' . $client_id;
            }
        }
        $sql = "SELECT cl.client_id, cl.user_name as client_name, cl.email FROM client AS cl WHERE " . implode("  AND ", $conditions) . ' order by cl.client_id';
        return $conn->GetAll($sql);
    }

    function getPMInfo($p)
    {
        global $conn;
        $sql = "SELECT u.* FROM client AS cl ";
        $sql .= 'LEFT JOIN users AS u ON cl.project_manager_id = u.user_id ';
        $sql .= 'LEFT JOIN client_campaigns AS cc ON cc.client_id = cl.client_id ';
        $sql .= 'LEFT JOIN order_campaigns AS oc ON oc.client_id = cl.client_id ';
        $conditions = array();
        if (isset($p['campaign_id']) && $p['campaign_id'] > 0) {
            $conditions[] = 'cc.campaign_id=' . $p['campaign_id'];
        }
        if (isset($p['order_campaign_id']) && $p['order_campaign_id'] > 0) {
            $conditions[] = 'oc.order_campaign_id=' . $p['order_campaign_id'];
        }
        if (isset($p['client_id']) && $p['client_id'] > 0) {
            $conditions[] = 'cl.client_id=' . $p['client_id'];
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        return $conn->GetRow($sql);
    }

    /**
     * Search Client info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function search($p = array(), $is_home = false, $show_report = true)
    {
        global $conn, $feedback;

        global $g_pager_params, $g_archived_month_time;

        $q = "WHERE 1 ";


       foreach ($p as $k => $v) {
           $p[$k] = addslashes(htmlspecialchars(trim($v)));
       }
       extract($p);

        // if role is agency in session
        // only show his/her own clients list
        if (User::getRole() == 'agency' || User::getRole() != 'agency' && $agency > 0)
        {
            $user_id = (User::getRole() == 'agency')? User::getID() : $agency;
            $q .= " AND (cl.agency_id='" . $user_id . "') ";
        }
        // end
        if ($agency_id > 0) {
            $q .= "AND cl.agency_id = '".$agency_id."' ";
        }
        // added by nancy xu 2011-03-09 10:38
        if ($status != 'All' && strlen($status)) {
            $q .= "AND cl.status = '".$status."' ";
        }
        // end
        if ($client_id != '') {
            $q .= "AND cl.user_id = '".$user_id."' ";
        }

        if ($user_name != '') {
            $q .= "AND cl.user_name LIKE '%".$user_name."%' ";
        }
        if ($company_name != '') {
            $q .= "AND cl.company_name LIKE '%".$company_name."%' ";
        }
        if ($company_address != '') {
            $q .= "AND cl.company_address LIKE '%".$company_address."%' ";
        }

        if ($city != '') {
            $q .= "AND cl.city = '".$city."' ";
        }
        if ($state != '') {
            $q .= "AND cl.state LIKE '%".$state."%' ";
        }
        if ($zip != '') {
            $q .= "AND cl.zip LIKE '%".$zip."%' ";
        }
        if ($email != '') {
            $q .= "AND cl.email LIKE '%".$email."%' ";
        }
        if ($company_url != '') {
            $q .= "AND cl.company_url LIKE '%".$company_url."%' ";
        }

        if ($company_phone != '') {
            $q .= "AND cl.company_phone LIKE '%".$company_phone."%' ";
        }
        if ($company_fax != '') {
            $q .= "AND cl.company_fax LIKE '%".$company_fax."%' ";
        }
        if ($bill_email != '') {
            $q .= "AND cl.bill_email LIKE '%".$bill_email."%' ";
        }
        if ($bill_office_phone != '') {
            $q .= "AND cl.bill_office_phone LIKE '%".$bill_office_phone."%' ";
        }
        if ($bill_cell_phone != '') {
            $q .= "AND cl.bill_cell_phone LIKE '%".$bill_cell_phone."%' ";
        }
        // added by nancy xu 2011-02-01 20:06
        // pm only can view/edit his own client, the other client can't view
        if (User::getPermission() == 4) {
            $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
        } else if ($is_home && $project_manager_id > 0){
            $q .= ' AND cl.project_manager_id=' . $project_manager_id . ' ';
        }
        // end

        //$q .= "AND (cl.permission < '".self::getPermission()."' OR cl.user_id = '".self::getID()."') ";
        if (trim($keyword) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $cond_fields = 'cl.user_name, cl.company_name, cl.company_address, cl.city, cl.email, cl.company_url, cl.company_phone, cl.company_fax, cl.bill_email, cl.bill_office_phone, cl.bill_office_phone, cl.bill_cell_phone, cl.bill_fax, cl.technical_email, cl.technical_office_phone, cl.technical_cell_phone';
                if ($show_report) {
                    $cond_fields .= ',cc.campaign_name';
                }
                $q .= "AND ".$search->getLikeCondition("CONCAT(" . $cond_fields . ")")." ";
            }
        }
        // added by nancy xu 2010-06-04 15:10
        $archived_date = date("Y-m-d", $g_archived_month_time);
        if ($archived >-1) {
            $left_q .= ' AND cc.archived= ' . $archived;
        }
        // end
        $rs = &$conn->Execute("SELECT COUNT(distinct cl.client_id) AS count FROM `client` AS cl LEFT JOIN client_campaigns  AS cc ON ( cc.client_id=cl.client_id $left_q)" . $q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn't find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
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

        // added by snug
        
        /*$sql = "SELECT cl.*, u.user_name as project_manager, count(distinct cc.campaign_id) as total_camp, SUM(cpas.completed_in_month) AS total_completed_articles, SUM(cc.total_budget) AS total_count \n" .
             "FROM `client` AS cl \n" .
             "LEFT JOIN users AS u ON (u.user_id = cl.project_manager_id)  \n" .
             "LEFT JOIN client_campaigns AS cc ON (cc.client_id = cl.client_id $left_q)  \n" .
             "LEFT JOIN cp_campaign_article_summary AS cpas ON (cpas.campaign_id = cc.campaign_id) \n" .
             $q . "\n" .
             " GROUP BY cl.client_id \n";*/
        if (!$show_report &&!$is_home) {
            $sql = "SELECT cl.*  \n" .
             "FROM `client` AS cl \n" .
             $q . "\n" .
             " GROUP BY cl.client_id \n";
        } else {
            $sql = "SELECT cl.*, u.user_name as project_manager, count(distinct cc.campaign_id) as total_camp,  SUM(cc.total_budget) AS total_count \n" .
             "FROM `client` AS cl \n" .
             "LEFT JOIN users AS u ON (u.user_id = cl.project_manager_id)  \n" .
             "LEFT JOIN client_campaigns AS cc ON (cc.client_id = cl.client_id $left_q)  \n" .
             $q . "\n" .
             " GROUP BY cl.client_id \n";
        }
        if ($is_home) {
            $sql .= " ORDER BY cc.campaign_id DESC ";
        }

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        $client_ids = array();
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $client_ids[$i] = $rs->fields['client_id'];
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }
        if ($show_report) {
            // added by snug xu 2007-05-28 11:45 - STARTED
            // get total google clean article for each client
            if (!empty($client_ids)) {
                $conditions = array("cl.client_id IN ('" . implode("', '", $client_ids) . "')");
                if (isset($archived) && $archived > -1) {
                    $conditions[] = 'cc.archived = ' .  $archived . ' ';
                }
                Client::getReportInfo($result, $month, $client_ids, $conditions, $q, 'client_id', $is_home);
            }
            // added by snug xu 2007-05-28 11:45 - FINISHED
        }
        

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);

    }//end search()

    function getTotalArticleReport(&$result, $ids, $field, $conditions, $group_field ='campaign_id')
    {
        global $conn;
        $query  = "SELECT COUNT( article_id )  as " . $field . ",  " . ($group_field == 'client_id' ?  'cl.client_id ' : 'cc.campaign_id' ). " \n";
        $query .= "FROM articles AS ar \n";
        $query .= "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) \n";
        $query .= "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id)  \n";
        if (!empty($ids)) {
            $conditions[] = 'cc.campaign_id IN (\'' . implode("','", $ids) . '\')';
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions) . " \n";
        }
        if ($group_field == 'client_id') {
            $query .= "GROUP BY cl.client_id ";
        } else {
            $query .= "GROUP BY cc.campaign_id ";
        }

        $rs = &$conn->Execute($query);
        if ($rs) {
            while (!$rs->EOF) {
                $id = $rs->fields[$group_field];
                $k = array_search($id, $ids);
                $result[$k][$field] =  $rs->fields[$field];
                if ($field != 'total') {
                    $total = $result[$k]['total'];
                    $result[$k]['pct_' . $field] = calculate_percentage($total, $rs->fields[$field]);
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
    }

    function getAllCampaigns($p)
    {
        global $conn, $feedback, $g_archived_month_time, $g_pager_params;

        $q = "WHERE 1 ";

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(cl.company_name, cc.campaign_name )")." ";
            }
        }
        // modified by nancy xu 2011-02-01 20:05
        if (isset($p['client_id']) && !empty($p['client_id'])) {
            $client_id = $p['client_id'];
            $q .= ' AND cl.client_id=' . $client_id . ' ';
        } else if (User::getPermission() == 4) {
            $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
        }
        // end
        $q .= " AND cc.campaign_id IN (SELECT DISTINCT ck.campaign_id FROM `campaign_keyword` AS ck, `articles` AS ar WHERE ar.keyword_id=ck.keyword_id AND ck.copy_writer_id=0 AND ar.article_status='0') ";
        $rs = &$conn->Execute("SELECT COUNT(cc.campaign_id) AS count FROM `client` AS cl LEFT JOIN client_campaigns  AS cc ON cc.client_id=cl.client_id " . $q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn't find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
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

        // added by snug
        $sql = "SELECT cc.campaign_name,  cl.company_name, cc.campaign_id, cc.date_end, COUNT(ck.keyword_id) AS total \n" .
             "FROM `client_campaigns` AS cc \n" .
             "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) \n" .
             "LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id = cc.campaign_id) \n" .
             $q . "\n" .
             " GROUP BY cc.campaign_id \n";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        $campaign_ids = $result = array();
        $today_time = strtotime(date("Y-m-d"));
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $total = $fields['total'];
                $total_unassigned = $unassigned[$fields['campaign_id']];
                $fields['pct_total_unassign'] = '0%';
                $fields['total_unassign'] = '0';
                $fields['pct_total_canceled'] = '0%';
                $fields['total_canceled'] = '0';
                $fields['pct_total_active'] = '0%';
                $fields['total_active'] = '0';
                $result[$i] = $fields;
                $campaign_ids[$i] = $fields['campaign_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        $conditions = array("ck.copy_writer_id=0", "ar.article_status=0");
        Client::getTotalArticleReport($result, $campaign_ids, 'total_unassign', $conditions);
        $conditions[] = "ck.status='A'";
        Client::getTotalArticleReport($result, $campaign_ids, 'total_active', $conditions);
        $conditions = array("ck.status='D'");
        Client::getTotalArticleReport($result, $campaign_ids, 'total_canceled', $conditions);
        // added by snug xu 2007-05-28 11:45 - FINISHED
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
    }

    function getAllCampaignReport($p)
    {
        global $conn, $feedback, $g_archived_month_time, $g_pager_params;

        $q = "WHERE 1 ";

       if (User::getRole() != 'agency' && isset($p['agency']) && $p['agency'] > 0) {
           $agency = $p['agency'];
           $q .= " AND cl.agency_id ='" . $agency . "' ";
       }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(cl.company_name, cc.campaign_name )")." ";
            }
        }

        // added by nancy xu 2011-02-01 20:04
        if (isset($p['client_id']) && !empty($p['client_id'])) {
            $client_id = $p['client_id'];
            $q .= ' AND cl.client_id=' . $client_id . ' ';
        } else if (User::getPermission() == 4) {
            $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
        } else if (User::getPermission() == 2) {
            $q .= ' AND cl.agency_id=' . User::getID() . ' ';
        }
        // end

        // added by nancy xu 2011-03-08 17:58
        if (isset($p['archived']) && $p['archived'] > -1) {
            $q .= ' AND cc.archived = ' .  $p['archived'] . ' ';
        }
        // end

        $rs = &$conn->Execute("SELECT COUNT(cc.campaign_id) AS count FROM `client` AS cl LEFT JOIN client_campaigns  AS cc ON cc.client_id=cl.client_id " . $q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn't find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
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

        // added by snug
        /*$sql = "SELECT cc.campaign_name, cc.archived, cc.completed_date, cl.company_name, cc.campaign_id, cc.date_end, SUM(cpas.completed_in_month) AS total_completed_articles, SUM(cc.total_budget) AS total_count \n" .
             "FROM `client_campaigns` AS cc \n" .
             "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) \n" .
             "LEFT JOIN cp_campaign_article_summary AS cpas ON (cpas.campaign_id = cc.campaign_id) \n" .
             $q . "\n" .
             " GROUP BY cc.campaign_id DESC \n";*/
        $sql = "SELECT cc.campaign_name, cc.archived, cc.completed_date, cl.company_name, cc.campaign_id, cc.date_end,  cc.campaign_type,SUM(cc.total_budget) AS total_count \n" .
             "FROM `client_campaigns` AS cc \n" .
             "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) \n" .
             $q . "\n" .
             " GROUP BY cc.campaign_id DESC \n";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        $campaign_ids = $result = array();
        $today_time = strtotime(date("Y-m-d"));
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $fields['total'] = 0;
                $due_time = strtotime($fields['date_end']);
                $fields['past_days'] =ceil(( $today_time - $due_time )/86400);
                $fields['total_submit'] = 0;
                $fields['pct_total_submit'] = '0%';
                $fields['total_assign'] = 0;
                $fields['pct_total_assign'] = '0%';
                $fields['total_editor_approval'] = 0;
                $fields['pct_total_editor_approval'] = '0%';
                $fields['total_client_approval'] = 0;
                $fields['pct_total_client_approval'] = '0%';
                $result[$i] = $fields;
                $campaign_ids[$i] = $fields['campaign_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }

        // added by snug xu 2007-05-28 11:45 - STARTED
        // get total google clean article for each client
        if (!empty($campaign_ids)) {
            $conditions = array("cc.campaign_id IN ('" . implode("', '", $campaign_ids) . "')");
            if (isset($archived) && $archived >= 0) {
                $conditions[] = 'cc.archived = ' .  $archived . ' ';
            }
            $month    = isset($p['month']) ? $p['month'] : null;
            $is_home = isset($p['is_home']) ? $p['is_home'] : false;
            Client::getReportInfo($result, $month, $campaign_ids, $conditions, $q, 'campaign_id', $is_home);
        }
        // added by snug xu 2007-05-28 11:45 - FINISHED
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
    }

    function getCampaignReportByClient($client_id, $p)
    {
        global $conn, $feedback, $g_archived_month_time;

        $q = "WHERE 1 ";

        $month    = isset($p['month'])? $p['month'] : null;
        $is_home = isset($p['is_home'])? $p['is_home'] : false;
        $archived_date = date("Y-m-d", $g_archived_month_time);
        if (isset($p['archived']) && $p['archived'] >-1) {
            $q .= ' AND cc.archived = ' .  $p['archived'] . ' ';
        }
        // added by nancy xu 2011-03-08 16:09
        $limit = isset($p['limit'])? $p['limit'] : null;
        // end

        // added by snug xu 2006-11-24 14:03 - START
        // if role is agency in session
        // only show his/her own clients list
        if (User::getRole() == 'agency')
        {
            $q .= " AND cl.agency_id ='" . User::getID() . "' ";
        }
        // added by snug xu 2006-11-24 14:03 - END

        $user_name = addslashes(htmlspecialchars(trim($p['user_name'])));
        if ($user_name != '') {
            $q .= "AND cl.user_name LIKE '%".$user_name."%' ";
        }
        $company_name = addslashes(htmlspecialchars(trim($p['company_name'])));
        if ($company_name != '') {
            $q .= "AND cl.company_name LIKE '%".$company_name."%' ";
        }
        $company_address = addslashes(htmlspecialchars(trim($p['company_address'])));
        if ($company_address != '') {
            $q .= "AND cl.company_address LIKE '%".$company_address."%' ";
        }

        $city = addslashes(htmlspecialchars(trim($p['city'])));
        if ($city != '') {
            $q .= "AND cl.city = '".$city."' ";
        }
        $state = addslashes(htmlspecialchars(trim($p['state'])));
        if ($state != '') {
            $q .= "AND cl.state LIKE '%".$state."%' ";
        }
        $zip = addslashes(htmlspecialchars(trim($p['zip'])));
        if ($zip != '') {
            $q .= "AND cl.zip LIKE '%".$zip."%' ";
        }
        $email = addslashes(htmlspecialchars(trim($p['email'])));
        if ($email != '') {
            $q .= "AND cl.email LIKE '%".$email."%' ";
        }
        $company_url = addslashes(htmlspecialchars(trim($p['company_url'])));
        if ($company_url != '') {
            $q .= "AND cl.company_url LIKE '%".$company_url."%' ";
        }

        $company_phone = addslashes(htmlspecialchars(trim($p['company_phone'])));
        if ($company_phone != '') {
            $q .= "AND cl.company_phone LIKE '%".$company_phone."%' ";
        }
        $company_fax = addslashes(htmlspecialchars(trim($p['company_fax'])));
        if ($company_fax != '') {
            $q .= "AND cl.company_fax LIKE '%".$company_fax."%' ";
        }
        $bill_email = addslashes(htmlspecialchars(trim($p['bill_email'])));
        if ($bill_email != '') {
            $q .= "AND cl.bill_email LIKE '%".$bill_email."%' ";
        }
        $bill_office_phone = addslashes(htmlspecialchars(trim($p['bill_office_phone'])));
        if ($bill_office_phone != '') {
            $q .= "AND cl.bill_office_phone LIKE '%".$bill_office_phone."%' ";
        }
        $bill_cell_phone = addslashes(htmlspecialchars(trim($p['bill_cell_phone'])));
        if ($bill_cell_phone != '') {
            $q .= "AND cl.bill_cell_phone LIKE '%".$bill_cell_phone."%' ";
        }

        //$q .= "AND (cl.permission < '".self::getPermission()."' OR cl.user_id = '".self::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(cl.user_name, cc.campaign_name, cl.company_name, cl.company_address, cl.city, cl.email, cl.company_url, cl.company_phone, cl.company_fax, cl.bill_email, cl.bill_office_phone, cl.bill_office_phone, cl.bill_cell_phone, cl.bill_fax, cl.technical_email, cl.technical_office_phone, cl.technical_cell_phone)")." ";
            }
        }
        $q .= ' AND cl.client_id=' . $client_id . ' ';

        /*$sql = "SELECT cc.campaign_name, cc.campaign_id, cc.date_end, cc.completed_date, cc.archived, SUM(cpas.completed_in_month) AS total_completed_articles, SUM(cc.total_budget) AS total_count \n" .
             "FROM `client` AS cl \n" .
             "LEFT JOIN client_campaigns AS cc ON (cc.client_id = cl.client_id) \n" .
             "LEFT JOIN cp_campaign_article_summary AS cpas ON (cpas.campaign_id = cc.campaign_id) \n" .
             $q . "\n" .
             " GROUP BY cl.client_id, cc.campaign_id \n";*/
            $sql = "SELECT cc.campaign_name, cc.campaign_id, cc.date_end, cc.completed_date, cc.archived,  cc.campaign_type, SUM(cc.total_budget) AS total_count \n" .
             "FROM `client` AS cl \n" .
             "LEFT JOIN client_campaigns AS cc ON (cc.client_id = cl.client_id) \n" .
             $q . "\n" .
             " GROUP BY cl.client_id, cc.campaign_id \n";
        if ($is_home) {
            $sql .= " ORDER BY cc.campaign_id DESC ";
        }
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}\n";
        }
        
        $campaign_ids = $result = array();
        $rs = $conn->Execute($sql);
        $today_time = strtotime(date("Y-m-d"));
        if ($rs) {
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $fields['total'] = 0;
                $due_time = strtotime($fields['date_end']);
                $fields['past_days'] =ceil(( $today_time - $due_time )/86400);
                $fields['total_submit'] = 0;
                $fields['pct_total_submit'] = '0%';
                $fields['total_assign'] = 0;
                $fields['pct_total_assign'] = '0%';
                $fields['total_editor_approval'] = 0;
                $fields['pct_total_editor_approval'] = '0%';
                $fields['total_client_approval'] = 0;
                $fields['pct_total_client_approval'] = '0%';
                $result[$i] = $fields;
                $campaign_ids[$i] = $fields['campaign_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }

        // added by snug xu 2007-05-28 11:45 - STARTED
        // get total google clean article for each client
        if (!empty($campaign_ids)) {
            $conditions = array("cc.campaign_id IN ('" . implode("', '", $campaign_ids) . "')");
            if (isset($archived) && $archived > -1) {
                $conditions[] = 'cc.archived = ' .  $archived;
            }
            Client::getReportInfo($result, $month, $campaign_ids, $conditions, $q, 'campaign_id', $is_home);
        }
        // added by snug xu 2007-05-28 11:45 - FINISHED

        return $result;
    }

    function getReportInfo(&$result, $month, $ids, $conditions, $q, $group_field, $is_home = false)
    {
        if ($is_home) {
            if (!isset($month) || empty($month)) {
                $start_date = date("Y-m-01 00:00:00");
                $end_date = date("Y-m-d H:i:s");
            } else {
                $selected_month = generateDateTimeByMonth($month);
                $start_date = date("Y-m-d H:i:s", $selected_month);
                $selected_time = strtotime("+1 Month", $selected_month);
                $end_date = date("Y-m-d H:i:s", $selected_time);
            }
            $tmp = $conditions;
            $tmp[] = "ar.article_status REGEXP  '^(5|6|99)$'";
            $tmp[] = 'ar.client_approval_date <\'' . $end_date . '\'';
            $tmp[] = 'ar.client_approval_date >\'' . $start_date . '\'';
            Client::getCountGroupByClients($result, 'month_client_approval', $ids, $tmp, $q, $group_field);
            $tmp = $conditions;
            $tmp[] = "ck.copy_writer_id > 0 AND ar.article_status REGEXP  '^(1|1gc|3|4|5|6|99)$'";
            $tmp[] = 'ar.cp_updated <\'' . date("Y-m-d 23:59:59") . '\'';
            $tmp[] = 'ar.cp_updated >\'' . date("Y-m-d 00:00:00") . '\'';
            Client::getCountGroupByClients($result, 'today_submit', $ids, $tmp, $q);
        }
        Client::getCountGroupByClients($result, 'total', $ids, $conditions, $q, $group_field);
        // Client::getCountGroupByClients($result, 'total_gc_articles', $client_ids, array_merge($conditions, array("ar.article_status = '1gc'")));
        Client::getCountGroupByClients($result, 'total_submit', $ids, array_merge($conditions, array( 'ck.copy_writer_id > 0', "ar.article_status REGEXP  '^(1|1gc|3|4|5|6|99)$'")), $q, $group_field);
        Client::getCountGroupByClients($result, 'total_assign', $ids, array_merge($conditions, array( 'ck.copy_writer_id > 0')), $q, $group_field);
        Client::getCountGroupByClients($result, 'total_editor_approval', $ids, array_merge($conditions,array("ar.article_status REGEXP  '^(4|5|6|99)$'")), $q, $group_field);
        Client::getCountGroupByClients($result, 'total_client_approval', $ids, array_merge($conditions,array("ar.article_status REGEXP  '^(5|6|99)$'")), $q, $group_field);
    }

    function getCount($field = 'total_submit', $conditions = array(), $q, $group_field='cl.client_id')
    {
        global $conn;
        $query  = "SELECT COUNT( article_id )  as " .$field;
        if (!empty($group_field)) {
            $query .= ", " . $group_field . "\n";
        }
        $query .= "FROM articles AS ar \n";
        $query .= "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) \n";
        $query .= "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id)  \n";
        $query .= $q . "\n";
        $conditions[] = " ck.status!='D' ";
        $query .= "AND " . implode(' AND ', $conditions) . " \n";
        if (!empty($group_field)) {
            $query .= ' GROUP BY ' . $group_field . " \n";
            $result = $conn->GetAll($query);
        } else {
            $result = $conn->GetOne($query);
        }
        return $result;
    }

    function getCountGroupByClients(&$result, $field = 'total_submit', $client_ids = array(), $conditions = array(), $q, $group_field='client_id')
    {
        global $conn;
        $group = ($group_field == 'client_id' ?  'cl.client_id ' : 'cc.campaign_id' );
        $select  = "SELECT COUNT( article_id )  as " .$field . ",  " . $group. " \n";
        $query = "FROM articles AS ar \n";
        $query .= "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) \n";
        $query .= "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id)  \n";
        $query .= $q . "\n";
        $conditions[] = " ck.status!='D' ";
        if (!empty($conditions)) {
            $query .= "AND " . implode(' AND ', $conditions) . " \n";
        }
        
        if ($group_field == 'client_id') {
            $group_by = "GROUP BY cl.client_id ";
        } else {
            $group_by = "GROUP BY cc.campaign_id ";
        }
        $rs = &$conn->Execute($select . $query . $group_by);
        if ($rs) {
            while (!$rs->EOF) {
                $client_id = $rs->fields[$group_field];
                $k = array_search($client_id, $client_ids);
                $result[$k][$field] =  $rs->fields[$field];
                if ($field != 'total') {
                    $total = $result[$k]['total'];
                    $result[$k]['pct_' . $field] = calculate_percentage($total, $rs->fields[$field]);
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }

        if ($field == 'total_assign' || $field == 'total_submit' || $field == 'total_editor_approval') {
            switch ($field) {
            case 'total_assign':
                $select  = "SELECT MIN( ck.date_assigned )  AS assigned,  " . $group. " \n";
                $query .= ' AND ar.article_status = 0 ';
                $field = 'assigned';
                break;
            case 'total_submit':
                $select  = "SELECT MIN( ar.cp_updated )  AS submitted,  " . $group. " \n";
                $query .= ' AND ar.article_status = 1 ';
                $field = 'submitted';
                break;
            case 'total_editor_approval':
                $select  = "SELECT MIN( ar.approval_date )  AS approved,  " . $group. " \n";
                $query .= ' AND ar.article_status = 4 ';
                $field = 'approved';
                break;
            }
            $sql = $select . $query . $group_by;
            $data = $conn->GetAll($sql);
            $now = time();
            foreach ($data as $k => $row) {
                $key = $row[$group_field];
                $k = array_search($key, $client_ids);
                $days = ($now - strtotime($row[$field]))/86400;
                $result[$k]['old_' . $field] = $days > 3? true : false;
            }
        }
    }



    /**
     * Get Client's ID from session
     *
     * @return int
     */
    function getID()
    {
        return isset($_SESSION['client_id']) ? $_SESSION['client_id'] : 0;
    }

    function getLastLoginTime()
    {
        return isset($_SESSION['last_login_time']) ? $_SESSION['last_login_time'] : 0;
    }


    /**
     * Get Client's name from session
     *
     * @return string
     */
    function getName()
    {
        return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
    }

    function getEmail()
    {
        return isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
    }

    function getContactName()
    {
        return isset($_SESSION['contact_name']) ? $_SESSION['contact_name'] : '';
    }

    function getAgencyId()
    {
        return isset($_SESSION['agency_id']) ? $_SESSION['agency_id'] : '';
    }

    /**
     * Get Client's status from session
     *
     * @return array
     */
    function getStatus()
    {
        return isset($_SESSION['status']) ? $_SESSION['status'] : '';
    }

    /**
     * Set user's status
     *
     * @param int    $user_id
     * @param string $status
     *
     * @return boolean
     */
    function setStatus($client_id, $status)
    {
        global $conn, $feedback;

        // added by snug xu 2006-11-24 20:02
        // let agency have priviledage to change her/his own client status
        if (User::getRole() != 'admin' && User::getRole() != 'agency') {
            $feedback = "Have not the permission modifiy this client";
            return false;
        }
        $client_id = addslashes(htmlspecialchars(trim($client_id)));
        if ($client_id == '') {
            $feedback = "Please choose one client";
            return false;
        }
        // modified by nancy xu 2011-02-25 15:54
        $q = "UPDATE client ".
             "SET status = '".$status."' ".
             "WHERE client_id = '".$client_id."' ";
        $conn->Execute($q);
        return true;
       //end
        /*

        $q = "SELECT ck.keyword_id FROM campaign_keyword AS ck ".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE cl.client_id = '".$client_id."' AND  ck.status!='D'  ";
        $rs = $conn->Execute($q);
        $keywords = array();
        if ($rs) {
            $keywords = $rs->fields;
            $rs->Close();
        }

        $q = "SELECT cc.campaign_id FROM client_campaigns AS cc ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE cl.client_id = '".$client_id."'";
        $rs = $conn->Execute($q);
        $campaigns = array();
        if ($rs) {
            $campaigns = $rs->fields;
            $rs->Close();
        }*/
        /*
        articles
        articles_version_history
        campaign_keyword
        client_campaigns
        client
        comments_on_articles
        cp_campaign_article_summary
        user_payment_history
        */

        /* $conn->StartTrans();
        if (empty($keywords)) {
            //do nothing;
        } else {
            $keyword_ids_str = implode(',', array_values($keywords));
            $q = "SELECT COUNT(article_id) AS count FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.")";
            $rs = $conn->Execute($q);
            if ($rs) {
                $article_count = $rs->fields['count'];
                $rs->Close();
            }
            if ($article_count > 0) {
                $q = "DELETE FROM comments_on_articles ".
                     "WHERE article_id IN (SELECT article_id FROM articles ".
                                          "WHERE keyword_id IN (".$keyword_ids_str."))";
                $conn->Execute($q);
            }
            $q = "DELETE FROM articles_version_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM user_payment_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);
        }
        if (!empty($campaigns)) {
            $campaign_ids_str = implode(',', array_values($campaigns));
            $q = "DELETE FROM campaign_keyword WHERE campaign_id IN (".$campaign_ids_str.") ";
            $conn->Execute($q);
            $q = "DELETE FROM cp_campaign_article_summary WHERE campaign_id IN (".$campaign_ids_str.") ";
            $conn->Execute($q);
        }
        $q = "DELETE FROM client_campaigns WHERE client_id = '".$client_id."' ";
        $conn->Execute($q);
        $q = "DELETE FROM `client` WHERE client_id = '".$client_id."' ";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Success';
            return $user_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }*/
    }//end setStatus()

    function getAllEditorFinishedCampaign($p = array())
    {
        global $conn;
        $conditions = array();
        if (isset($p['is_sent_client'])) {
            $conditions[] = "cc.is_sent_client=" . $p['is_sent_client'];
        }
        $sql = "SELECT  cc.campaign_id, cc.campaign_name, cc.client_id, c.user_name AS client_name, u.email as project_manager_email, c.email, count(ck.keyword_id) as total ";
        $sql .= "FROM client_campaigns AS cc ";
        $sql .= "LEFT JOIN client AS c ON c.client_id=cc.client_id ";
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON ck.campaign_id=cc.campaign_id ';
        $sql .= 'LEFT JOIN users AS u ON u.user_id=c.project_manager_id ';
        $sql .= !empty($conditions) ? ' WHERE ' . implode(" AND ", $conditions) : '' ;
        $sql .= ' GROUP BY cc.campaign_id';
        return $conn->getAll($sql);
    }

    function getAllEditorFinishedArticles($conditions = array(), $groupfield='client_id')
    {
        global $conn;
        $sql = "SELECT cc.client_id, cc.campaign_name, ck.keyword, ar.article_number ";
        $sql .= "FROM client_campaigns AS cc ";
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON ck.campaign_id=cc.campaign_id ';
        $sql .= 'LEFT JOIN articles AS ar ON ar.keyword_id=ck.keyword_id ';
        $sql .= !empty($conditions) ? ' WHERE ' . implode(" AND ", $conditions) : '' ;
        $sql .= ' ORDER BY cc.client_id';
        $result = $conn->getAll($sql);
        $data = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $groupkey = $row[$groupfield];
                if (!isset($data[$groupkey])) {
                    $data[$groupkey] = array();
                }
                $data[$groupkey][] = $row;
            }
        }
        return $data;
    }
    // added by nancy xu 2011-05-18 11:36
    function getClientIdsByAgencyId($agence_id)
    {
        global $conn;
        $sql = 'SELECT client_id FROM client WHERE agency_id=' . $agence_id . ' ';
        $result = $conn->GetAll($sql);
        $client_ids = array();
        foreach ($result as $row) {
            $client_ids[] = $row['client_id'];
        }
        return $client_ids;
    }
    //end

}//end class Client

/**
 * Session wrap function
 *
 * @return boolean
 */
function client_is_loggedin()
{
    global $feedback;

    return ((Client::getID() != 0) && Client::chkSessionIP($_SERVER['REMOTE_ADDR']));
}
?>