<?php
/**
* User Class（用户操作类）
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

require_once CMS_INC_ROOT . "/article_payment_log.class.php";
require_once CMS_INC_ROOT . "/cp_payment_history.class.php";
require_once CMS_INC_ROOT . "/article_cost_history.class.php";
require_once CMS_INC_ROOT.'/Email.class.php';

class User {

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
            $feedback = 'Please Enter User Name AND Password';//Please type user name and password
            return false;//请输入用户名和密码
        }

		//We should decode the password by md5();
        $q = "SELECT * FROM users ".
             "WHERE user_name = BINARY '".addslashes($user_name)."' ".
             "AND user_pw = BINARY '".addslashes($user_pw)."'";
        $rs = &$conn->Execute($q);
        if (!$rs) {
            $feedback = 'Login Incorrect! Please Try Again...';//Invalid user name or password, Please Try again
            return false;
        } else {
            $user_id    = $rs->fields['user_id'];
            $status     = $rs->fields['status'];//We should add a status field, better than delete one user directly
            $user_name  = $rs->fields['user_name'];
            $permission = $rs->fields['permission'];
            $user_type = $rs->fields['user_type'];
            $email = $rs->fields['email'];
            $role       = $rs->fields['role'];
            $counter       = $rs->fields['counter'];
            $pay_pref       = $rs->fields['pay_pref'];
            $auditing_frequency = $rs->fields['auditing_frequency'];
            $rs->Close();
        }

        if ($user_id != 0) { // don't use record count to improve performance

            if ($status == 'A') {
                 // permission 3=>4, 2=>3
				if( ( $permission==3 && $user_id!=3 ) || $permission==4 )
				{
					$current_frequency = $auditing_frequency;
				}
				else 
				{
					$current_frequency = 0;
				}
				//$user_perm = User_Perm::getPerm($user_id); // If we extends our permission, add a class user_perm is a good idea,and this class i had finished

                return array('user_id'    => $user_id,
                             'user_name'  => $user_name,
                             'permission'  => $permission,
                             'status'  => $status,
                             'role'     => $role,
                             'user_type'     => $user_type,
                             'counter'   => $counter,
                             'email'   => $email,
                             'pay_pref'   => $pay_pref,
                             'current_frequency'       => $current_frequency
							);
            } else {
                $feedback = 'Login Incorrect! Please Try Again';//This account didn't login
                return false;
            }

        } else {
            $feedback = 'Invalid User Name Or Password, Please Type Again';//Invalid user name or password, Please type again(用户名或密码不正确，请重新填写)
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
        $_SESSION['user_id'] = $login['user_id'];
        $_SESSION['user_name'] = $login['user_name'];
        $_SESSION['permission'] = $login['permission'];
        $_SESSION['status'] = $login['status'];
        $_SESSION['role'] = $login['role'];
        $_SESSION['current_frequency'] = $login['current_frequency'];
        $_SESSION['counter'] = $login['counter'];
        $_SESSION['user_email'] = $login['email'];
        $_SESSION['pay_pref'] = $login['pay_pref'];
        if ($login['role'] == 'copy writer' || $login['role'] == 'admin') {
            $_SESSION['user_type'] = $login['user_type'];
        }

        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['last_login_time'] = Logger::getUserLastLogin($login['user_id']);

        Logger::logSession($login['user_id'], session_id());
    }//end setLogin()


    function getUserType()
    {
        return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
    }

	/**
	 *Added by Snug 15:38 2006-9-12
	 *
	 *Function Description: get Auditing Frequency by user_id
	 *
	 *@param int $user_id the user that tracy chose
	 *@param int $current_frequency current auditing frequency
	 */
	function getAuditingFrequency( $user_id )
	{
		global $conn;
		$user_id = mysql_escape_string( htmlspecialchars( trim( $user_id ) ) );
		$q = "select `auditing_frequency` from `users` where user_id='$user_id'";
		$rs = &$conn->Execute($q);
		if( $rs )
		{
			$current_frequency = $rs->fields['auditing_frequency'];
			$rs->Close();
		}
		return $current_frequency;
	}//End Added

	/**
	 *Added by Snug 15:38 2006-9-12
	 *
	 *Function Description: update Auditing Frequency by user id 
	 *
	 *@param int $frequency auditing frequency
	 *@param int $user_id user_id
	 *
	 *@return bool
	 */
	 function updateAuditingFrequency( $frequency , $user_id )
	{
		 global $conn, $feedback;
		 $frequency = mysql_escape_string( htmlspecialchars( trim( $frequency ) ) );
		 $user_id = mysql_escape_string( htmlspecialchars( trim( $user_id ) ) );
		 if( $user_id<=0 )
		 {
		 	$feedback = 'Please select a user';
		 	return false;
		 }
		 if( is_numeric( $frequency ) )
		{
			 $q = "UPDATE `users` SET `auditing_frequency`='$frequency' where user_id='$user_id' ";
			 $conn->Execute($q);
		}
		else
		{
			$feedback = "You Input The Evil Char!";
			return false;
		}
		 return true;
	}//End Added

	/**
	 * Function Description: get all available copywriter without been given any job
	 * created time 9:17 2006-09-26
	 * @author Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p
	 * @return array copywriter info
	 */
	 function getAllAvailableCopyWriter($p = array())
	{
		 global $conn, $feedback, $g_pager_params;
		 if(count($p))
		{
			 foreach  ($p as $k => $value) 
			{
				 $p[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
			}
		}

		if (strlen($p['month']) == 0) // $p['month'] linux timestamp
		{
			$p['month'] = time();
		}
		/*$end_time  = date("Y-m-d H:i:s", $p['month']);
		$start_time = date("Y-m-d H:i:s", strtotime("-30 days", $p['month']));
        $time_start = $end_time;
        $time_end = date("Y-m-d H:i:s", strtotime("30 days", $p['month']));*/
        $time_start = date("Y-m-d H:i:s", $p['month']);
        $time_end = date("Y-m-d H:i:s", strtotime("30 days", $p['month']));
        unset($p['month']); // remove linux timestamp
        $uc_qw = " AND uc.c_date <= '{$time_end}' AND uc.c_date >= '{$time_start}'  AND  is_free = 0   ";
		if (strlen($p['perPage']) > 0) {
            $perpage = $p['perPage'];
			unset($p['perPage']);
        } else {
            $perpage= 50;
        }

		if (strlen($p['permission']) == 0) 
		{
			$p['permission'] = 1;
		}

		foreach  ($p as $k => $value) 
		{
			if ($k == 'page') 
			{
				continue;
			}
			$qw[] = "`u`.`{$k}` = '{$value}'";
		}

        // SELECT c_date, user_id FROM user_calendar AS uc where 1
		
		$where = "WHERE `u`.`status` != 'D' AND "
						. implode(" AND ", $qw)
						. "AND `u`.`user_id` "
						. "IN ( "
						. "SELECT DISTINCT `user_id` "
						. "FROM `user_calendar` AS uc "
						. "WHERE 1 {$uc_qw})   ";
		$left_join = "LEFT JOIN `campaign_keyword` AS `ck` ON `ck`.`copy_writer_id` = `u`.`user_id`  AND `status`!='D' ";

		$sql = "SELECT COUNT(DISTINCT `u`.`user_id`) as `count`"
					. "	FROM `users` AS `u` "
					. $where ;
		$rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

		$sql = "SELECT `u`. * "
				. "	FROM `users` AS `u` "
				. $where;
		list($from, $to) = $pager->getOffsetByPageId();
		$rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
		$users = array();
        $ids = array();
		if ($rs) 
		{
			while (!$rs->EOF) 
			{
                $user_id = $rs->fields['user_id'];
				$users[$user_id] = $rs->fields;
                $ids[] = $user_id;
				$rs->MoveNext();
			}
			$rs->Close();
		}

        if (!empty($ids)) {
            $uc_qw .= ' AND uc.user_id IN (' . implode(',', $ids) . ') ';
            $sql = 'SELECT c_date, user_id FROM user_calendar AS uc where 1 ' . $uc_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                if (!isset($users[$user_id]['unavailable'])) {
                    $users[$user_id]['unavailable'] = '';
                }
                $users[$user_id]['unavailable'] .= $row['c_date'] . "\n";
            }
        }
		return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
	}


	function getCopyWriterAvailableAndSpecialty($p = array())
	{
		 global $conn, $feedback, $g_pager_params;

		 if(count($p)) {
			 foreach  ($p as $k => $value) {
				 $p[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
			}
		}
        $keyword = $p['keyword'];
        unset($p['keyword']);

        
		if (strlen($p['month']) == 0) // $p['month'] linux timestamp
		{
			$p['month'] = time();
		}
        $time_start = date("Y-m-d H:i:s", $p['month']);
        $time_end = date("Y-m-d H:i:s", strtotime("30 days", $p['month']));
        unset($p['month']); // remove linux timestamp
        $ucqw = array('is_free = 0');
        if (!empty($p['c_date_start'])) {
            $c_date_start = $p['c_date_start'];
            $ucqw[] = "uc.c_date >= '{$c_date_start}'";
            unset($p['c_date_start']);
        }
        if (!empty($p['c_date_end'])) {
            $c_date_end = $p['c_date_end'];
            $ucqw[] = "uc.c_date <= '{$c_date_end}'";
            unset($p['c_date_end']);
        }
        $ucids = array();
        if (!empty($ucqw)&& count($ucqw) > 1) {
           $sql = "SELECT DISTINCT user_id FROM user_calendar AS uc WHERE " . implode(" AND ", $ucqw);
           $result = $conn->GetAll($sql);
           if (empty($result)) return false;
           foreach ($result as $row) {
                $ucids[] = $row['user_id'];
           }
        }
        
        $uc_qw = " AND uc.c_date <= '{$time_end}' AND uc.c_date >= '{$time_start}'  AND  is_free = 0   ";
		if (strlen($p['perPage']) > 0) {
            $perpage = $p['perPage'];
			unset($p['perPage']);
        } else {
            $perpage= 50;
        }
        $permission = 0;
        if (!isset($p['permission'])) $p['permission'] =0;
       
		foreach  ($p as $k => $value) 
		{
			if ($k == 'page' || $k == 'x' || $k == 'y' || empty($value) && $k != 'permission') {
				continue;
			} else if ($k == 'cp_category') {
                $ucat_qw =  "(ucat.category_id = '{$value}'  OR ucat.parent_id='{$value}')";
//                $qw[] = $ucat_qw;
			} else if ($k == 'permission' && empty($value)) {
                $qw[] = "(`u`.`{$k}` = 1 OR `u`.`{$k}` = 3)";
			} else if ($k == 'pay_level' && $value > 0) {
                $qw[] = "`u`.`{$k}` >= '{$value}'";
            } else {
			    $qw[] = "`u`.`{$k}` = '{$value}'";
            }
		}
        $qw[] = "`u`.`status` != 'D' ";

        // SELECT c_date, user_id FROM user_calendar AS uc where 1
		
		
        $left_join .= ' LEFT JOIN user_calendar AS uc ON (uc.user_id=u.user_id ' .  $uc_qw . ')';
        $left_join .= ' LEFT JOIN users_categories AS ucat ON  (ucat.user_id=u.user_id)';
        $left_join .= ' LEFT JOIN category  AS cat ON  (cat.category_id=ucat.category_id)';
        /*switch($permission) {
        case 0:
            $ck_qw = "(`ck`.`copy_writer_id` = `u`.`user_id` OR `ck`.`editor_id`=`u`.`user_id`)";
            break;
        case 1:
            $ck_qw = "(`ck`.`copy_writer_id` = `u`.`user_id`)";
            break;
        case 3:
            $ck_qw = "(`ck`.`editor_id` = `u`.`user_id`)";
            break;
        }
        if (!empty($tmp)) {
		    $left_join .= ' LEFT JOIN `campaign_keyword` AS `ck` ON ' . $ck_qw.'  AND `ck`.`status`!=\'D\' ';
        }*/
        // get user ids from user canlendar
        $ids = array();
        if (!empty($ucat_qw)) {
            $sql = "SELECT DISTINCT `ucat`.`user_id` FROM  users_categories AS ucat  WHERE  " . $ucat_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                $ids[] = $row['user_id'];
            }
        }
        if (!empty($ids )) {
            $ids = array_unique($ids);
            if (!empty($ucids)) $ids = array_intersect($ids, $ucids);
            if (empty($ids)) return false;
        } else if (!empty($ucids)) {
            $ids = $ucids;
        }
        if (!empty($ids)) $qw[] = "u.user_id IN (" . implode(", ", $ids) . ")";
        $where = "WHERE " . implode(" AND ", $qw)  .  self::getSearchKeyword($keyword);
		$sql = "SELECT COUNT(DISTINCT `u`.`user_id`) as `count`"
					. "	FROM `users` AS `u` "
					. $where ;

        $count = $conn->GetOne($sql);

        if ($count == 0 || !isset($count)) {
            return false;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

		$sql = "SELECT DISTINCT `u`. * "
				. "	FROM `users` AS `u` " 
				. $where . " ORDER BY u.user_name ";
		list($from, $to) = $pager->getOffsetByPageId();
		$rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
		$users = array();
        $ids = array();
		if ($rs) 
		{
			while (!$rs->EOF) 
			{
                $user_id = $rs->fields['user_id'];
				$users[$user_id] = $rs->fields;
                $ids[] = $user_id;
				$rs->MoveNext();
			}
			$rs->Close();
		}

        if (!empty($ids)) {
            $uc_qw .= ' AND uc.user_id IN (' . implode(',', $ids) . ') ';
            $sql = 'SELECT c_date, user_id FROM user_calendar AS uc where 1 ' . $uc_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                if (!isset($users[$user_id]['unavailable'])) {
                    $users[$user_id]['unavailable'] = '';
                }
                $users[$user_id]['unavailable'] .= $row['c_date'] . "\n";
            }
            $sql = 'SELECT ucat.*, c.category FROM users_categories AS ucat LEFT JOIN category AS c ON c.category_id =ucat.category_id  WHERE ucat.user_id IN (' . implode(',', $ids) . ')  ';
            if (!empty($ucat_qw)) $sql .= " AND " . $ucat_qw;
            $sql .= ' ORDER BY c.category  ';
            $result = $conn->GetAll($sql);
            global $g_user_levels;
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                if (!isset($users[$user_id]['specialies'])) {
                    $users[$user_id]['specialies'] = array();
                }
                $is_link = (!empty($row['description']) || !empty($row['sample']));
                $category_id = $row['category_id'];
                $users[$user_id]['specialies'][$category_id]=array( 'name' => $row['category'] . '-' . $g_user_levels[$row['level']] . "\n", 'is_link' => $is_link, 'category_id' => $category_id);
            }
        }
		return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
	}

	function getAllAvailableAndSpecialty($p = array())
	{
		 global $conn, $feedback, $g_pager_params;
		 if(count($p)) {
			 foreach  ($p as $k => $value) {
				 $p[$k] = mysql_escape_string(htmlspecialchars(trim($value)));
			}
		}

		if (strlen($p['month']) == 0) {// $p['month'] linux timestamp
			$p['month'] = time();
		}
        $time_start = date("Y-m-d H:i:s", $p['month']);
        $time_end = date("Y-m-d H:i:s", strtotime("30 days", $p['month']));
        unset($p['month']); // remove linux timestamp
        unset($p['perPage']); 
        $uc_qw = " AND uc.c_date <= '{$time_end}' AND uc.c_date >= '{$time_start}'  AND  is_free = 0   ";
        if (!isset($p['permission'])) $p['permission'] =0;
       
		foreach  ($p as $k => $value) 
		{
			if ($k == 'page' || $k == 'x' || $k == 'y' || empty($value) && $k != 'permission') {
				continue;
			} else if ($k == 'cp_category') {
                $ucat_qw =  "(ucat.category_id = '{$value}'  OR ucat.parent_id='{$value}')";
			} else if ($k == 'permission' && empty($value)) {
                $qw[] = "(`u`.`{$k}` = 1 OR `u`.`{$k}` = 3)";
			} else if ($k == 'pay_level' && $value > 0) {
                $qw[] = "`u`.`{$k}` >= '{$value}'";
            } else {
			    $qw[] = "`u`.`{$k}` = '{$value}'";
            }
		}
        $qw[] = "`u`.`status` != 'D' ";		
        $ids = array();
        if (!empty($ucat_qw)) {
            $sql = "SELECT DISTINCT `ucat`.`user_id` FROM  users_categories AS ucat  WHERE  " . $ucat_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                $ids[] = $row['user_id'];
            }
        }
        if (!empty($ids )) {
            $ids = array_unique($ids);
            $qw[] = "u.user_id IN (" . implode(", ", $ids) . ")";
        }

        $where = "WHERE " . implode(" AND ", $qw);

		$sql = "SELECT DISTINCT `u`.user_id, `u`. user_name,  `u`. first_name, `u`.last_name, `u`.email "
				. "	FROM `users` AS `u` "  . $where;
        $result = $conn->GetAll($sql);
        $users = $ids= array();
        foreach ($result as $row) {
            $user_id = $row['user_id'];
            unset($row['user_id']);
            $users[$user_id] =$row;
            $users[$user_id]['dates_unavailable'] = '';
            $users[$user_id]['specialies'] = '';
            $ids[] = $user_id;
        }
        if (!empty($ids)) {
            $uc_qw .= ' AND uc.user_id IN (' . implode(',', $ids) . ') ';
            $sql = 'SELECT c_date, user_id FROM user_calendar AS uc WHERE 1 ' . $uc_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                $users[$user_id]['unavailable'] .= $row['c_date'] . "\n";
            }
            $sql = 'SELECT ucat.*, c.category FROM users_categories AS ucat LEFT JOIN category AS c ON c.category_id =ucat.category_id  WHERE ucat.user_id IN (' . implode(',', $ids) . ')  ';
            if (!empty($ucat_qw)) $sql .= " AND " . $ucat_qw;
            $sql .= ' ORDER BY c.category  ';
            $result = $conn->GetAll($sql);
            global $g_user_levels;
            foreach ($result as $row) {
                $user_id = $row['user_id'];
                $category_id = $row['category_id'];
                $users[$user_id]['specialies'] .= $row['category'] . '-' . $g_user_levels[$row['level']] . "\n" ;
            }
        }
        return $users;
	}

	/**
	 * Function Description: get all available copywriter without been given any job
	 * created time 9:17 2006-09-26
	 * @author Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p
	 * @return array copywriter info
	 */
	 function getAllAvailableCopyWriterByUserID($user_id)
	{
		 global $conn, $feedback, $g_pager_params;
        $p['month'] = time();
        $time_start = date("Y-m-d H:i:s", $p['month']);
        $time_end = date("Y-m-d H:i:s", strtotime("30 days", $p['month']));
        $user_id = mysql_escape_string(htmlspecialchars(trim($user_id)));
        $uc_qw = " AND uc.c_date <= '{$time_end}' AND uc.c_date >= '{$time_start}'  AND  is_free = 0   ";
        $qw = array();
        $qw[] = '`u`.`user_id`=' . $user_id;

        // SELECT c_date, user_id FROM user_calendar AS uc where 1
		
		$where = "WHERE `u`.`status` != 'D' AND "
						. implode(" AND ", $qw)
						. " AND `u`.`user_id` "
						. "IN ( "
						. "SELECT DISTINCT `user_id` "
						. "FROM `user_calendar` AS uc "
						. "WHERE 1 {$uc_qw})   ";

		$sql = "SELECT `u`. * "
				. "	FROM `users` AS `u` "
				. $where;
        $user = $conn->GetRow($sql);
        if (!empty($user)) {
            $uc_qw .= ' AND uc.user_id IN (' . $user_id . ') ';
            $sql = 'SELECT c_date, user_id FROM user_calendar AS uc WHERE 1 ' . $uc_qw;
            $result = $conn->GetAll($sql);
            foreach ($result as $row) {
                if (!isset($user['unavailable'])) {
                    $user['unavailable'] = '';
                }
                $user['unavailable'] .= $row['c_date'] . "\n";
            }
            if (!empty($ids)) {
            }
            return $user;
        }
        return false;
	}

	/**
	 * Function Description: get All Copywriters who were assigned new task by assign time
	 * @author Snug Xu <xuxiannuan@gmail.com>
	 * @param datetime $start_time
	 * @param datetime $end_time
	 * @return array copywriter info
	 */
	 function getCopywritersFromKeywordAssignmentByAssignTime($start_time = NULL, $end_time = NULL)
	{
		 global $conn, $feedback;
		 $start_time = addslashes(htmlspecialchars(trim($start_time)));
		 $end_time  = addslashes(htmlspecialchars(trim($end_time)));
		 $sql = "SELECT DISTINCT `u`.`user_id`, `u`.`user_name`, `u`.`user_pw`, `u`.`first_name`, `u`.`last_name`, `u`.`user_name`,`s`.`time`  FROM `users` AS `u`, `session` AS `s`, `campaign_keyword` AS `ck` WHERE `s`.`user_id`=`u`.`user_id` AND `u`.`user_id`=`ck`.`copy_writer_id` AND `u`.`status`!='D' AND  ck.status!='D'  ";
		 if (strlen($start_time) && strlen($end_time))
		{
			 $sql .= " (`ck`.`date_assigned`>='{$start_time}' AND `ck`.`date_assigned`<='{$end_time}') ";
		}
		 $rs = &$conn->Execute($sql);
		 if (!$rs)
		{
			 if (!$rs->EOF) 
			{
				 $users[$rs->fields['user_id']] = $rs->fields;
				 $rs->MoveNext();
			}
			$rs->Close();
		}
		return $users;
	}

	/**
	 *Added by Snug 16:40 2006-9-12
	 *
	 *Function Description: update approved article counter of the user
	 *
	 *@param int $counter 
	 *
	 *@return bool
	 */
	 function updateCounterOfUser( $counter )
	{
		 global $conn, $feedback;
		 $counter = mysql_escape_string( htmlspecialchars( trim( $counter ) ) );
		 if( is_numeric( $counter ) )
		{
			 $q = "UPDATE `users` SET `counter`='$counter' WHERE user_id='{$_SESSION['user_id']}'";
			 $conn->Execute($q);
		}
		else
		{
			$feedback = "You Input The Evil Char!";
			return false;
		}
		 return true;
	}//End Added

    /**
     * Add an user and user's information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function add($p = array())
    {
        global $conn, $feedback, $mailer_param;
        global $g_tag;
        $domain = 'http://' . $_SERVER['HTTP_HOST']; 
        foreach ($p as $k => $v) {
            if (is_string($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            } else if ($k == 'address') {
                $p[$k] = addslashes(serialize($v));
            }
        }
        extract($p);

        unset($p['user_pwnew']);

        if ($user_name == '') {
            $feedback = "Please enter user's name";
            return false;
        }
		if (!valid_user_name($user_name)) {
			return false;
		}

        if ($user_pw != $user_pwnew) {
            $feedback = 'Password mismatch, Please enter the password again';//两次填写的新密码不一致，请重新填写
            return false;
        }

        if (!valid_pw($user_pw)) {//this function in the utils.php,
            return false;
        }

        if ($first_name == '') {
            $feedback = "Please provide user's first name";//请填写first name.
            return false;
        }

        if ($last_name == '') {
            $feedback = "Please provide user's last name";
            return false;
        }

        $email = stripslashes($email);
        if (!valid_email($email)) {
            $feedback = "Invalid email, please to check.";
            return false;
        }
        $email = addslashes($email);

        if ($sex == '') {
            $feedback = "Please choose user's gender";
            return false;
        }

        if ($role == '') {
            $feedback = "Please choose user's role";
            return false;
        }
		if ($role > self::getPermission()) {
            $feedback = "Have not the permission add one ".$g_tag['user_permission'][$role]." user";
            return false;
		}
        $p['role'] = $g_tag['user_permission'][$role];
        $p['permission'] = $role;

        if ($pay_pref == 3) {
            if ($paypal_email == '') {
                $feedback = 'Please specify the paypal email address';
                return false;
            }
        }
        
        $paypal_email = stripslashes($paypal_email);
        if ($paypal_email != '' && !valid_email($paypal_email)) {
            $feedback = 'Invalid paypal email address';
            return false;
        }
        $paypal_email = addslashes($paypal_email);


        $q = "SELECT COUNT(*) AS count FROM users WHERE user_name = '".$user_name."'";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "The user\'s name already registered, please type another name.";//用户名重复
            return false;
        }
        // added by nancy xu 2009-11-04 17:32
        if ($candidate_id > 0) {
            $q = "SELECT COUNT(*) AS count FROM users WHERE candidate_id = '".$candidate_id."'";
            $rs = &$conn->Execute($q);
            $count = 0;
            if ($rs) {
                $count = $rs->fields['count'];
                $rs->Close();
            }
            if ($count > 0) {
                $feedback = "This candidate has hired, please to check";//用户名重复
                return false;
            }
        }

        /**************************************************************
         if (!empty($p['email'])) {
            $email = $p['email'];
            $q = "SELECT COUNT(*) AS count FROM users WHERE email = '".$email."'";
            $rs = &$conn->Execute($q);
            $count = 0;
            if ($rs) {
                $count = $rs->fields['count'];
                $rs->Close();
            }
            if ($count > 0) {
                $feedback = "The user\'s email already registered, please type another email.";//用户名重复
                return false;
            }
        }**************************************************************/
        // end

        $conn->StartTrans();
        $user_id = $conn->GenID('seq_users_user_id');
        if (is_array($form_submitted)) $form_submitted = implode("|", $form_submitted);
        $p['form_submitted'] = $form_submitted;
        $p['user_id'] = $user_id;
        $q = "INSERT INTO users (`" .implode("`,`", array_keys($p)) . "`)  VALUES ( '" . implode("', '", $p) . " ')";
        $conn->Execute($q);
        if (!empty($p['candidate_id'])) {
            $info = Candidate::getInfo($p['candidate_id']);
            $categories  = $info['categories'];
            if (is_array($categories)) {   
                foreach ($categories as $row) {
                    $row['user_id'] = $user_id;
                    $row['role'] = $p['role'];
                    unset($row['category']);
                    unset($row['fileField']);
                    unset($row['filename']);
                    unset($row['link']);
                    $parent_id = $row['parent_id'];
                    //unset($row['parent_id']);
                    require_once CMS_INC_ROOT . '/UserCategory.class.php';
                    if ($row['category_id'] > 0) {
                        if (strlen($row['level']) == 0) $row['level'] = 0;
                        UserCategory::store($row);
                    }
                    if ($parent_id > 0) {
                        $info = UserCategory::getInfo($parent_id, $user_id);
                        if (empty($info)) {
                            $arr = array(
                                'parent_id' => 0,
                                'level' => 0,
                                'description' => $row['description'],
                                'sample' => $row['sample'],
                                'user_id' => $user_id,
                                'role' => $p['role'],
                                'category_id' => $parent_id,
                            );
                            if (empty($row['category_id'])) {
                                $arr['level'] = $row['level'];
                            }
                            UserCategory::store($arr);
                        }
                    }
                }
            }
        }
        $ok = $conn->CompleteTrans();
        
        if ($ok) {
            $feedback = 'Success';
            $candidate_id = addslashes(htmlspecialchars(trim($p['candidate_id'])));
            $arr = array(
                "%%LOGIN_LINK%%" => $domain,
                "%%FIRST_NAME%%" => $first_name,
                "%%USER_NAME%%" => $user_name,
                "%%USER_PW%%" => $user_pw,
            );
            if ($candidate_id) {
                $info = getEmailSubjectAndBody(18, $arr);
                $subject = $info['subject'];
                $body    = $info['body'];
                if ($role == 1) {
                    global $g_article_storage;
                    if ($p['role'] == 'editor') {
                        $filename = 'Editor Guide.pdf';
                    } else {
                        $filename = 'Writer Guide.pdf';
                    }
                    $file = $g_article_storage . 'writerfile' . DS . $filename;
                    $mailer_param['attachment'] = array('file'=>$file, 'filename' =>$filename);
                }                
            } else {
                //######## send email to announce #########//
                $info = getEmailSubjectAndBody(19, $arr);
                $subject = $info['subject'];
                $body    = $info['body'];
            }
            send_smtp_mail($email, $subject, $body, $mailer_param);
            if (empty($feedback)) {
                $feedback = 'Success';
            }
            //######## end announce email #########//
            return $user_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end add()

	function sendWelcomeEmail( $user_id )
	{
		global $conn, $feedback, $mailer_param;
        $domain = 'http://' . $_SERVER['HTTP_HOST'];

        require_once MAILER_INC_ROOT.'/class.phpmailer.php';

		$user = User::getInfo($user_id, "u.status != 'D'");
		if (!empty($user)) {
            $arr = array(
                "%%LOGIN_LINK%%" => $domain,
                "%%FIRST_NAME%%" => $user['first_name'],
                "%%USER_NAME%%" => $user['user_name'],
                "%%USER_PW%%" => $user['user_pw'],
            );
            $info = getEmailSubjectAndBody(19, $arr);
            $subject = $info['subject'];
            $body    = $info['body'];

            // $mailer_param['bcc'] = "listerine@gmail.com";
            if (!send_smtp_mail($user['email'], $subject, $body, $mailer_param))
            {
                return false;
            }
            //######## end announce email #########//
            $feedback="Welcome Email Send Seccessfull";
            return true;
		}
		$feedback="Failure";
		return false;
	}


	function sendAutoReminder( $user_id )
	{
		global $conn, $feedback, $mailer_param;
        $domain = 'http://' . $_SERVER['HTTP_HOST'];
        require_once MAILER_INC_ROOT.'/class.phpmailer.php';
		$user = User::getInfo($user_id, "u.status != 'D'");
		if (!empty($user)) {
            if ($user['role'] == 'copy writer') $info = Email::getInfoByEventId(15);
            else $info = Email::getInfoByEventId(14);
			$body = "Dear  &nbsp;".$user['first_name']."<br><br>";
            $body .= nl2br($info['body']);
            $subject = $info['subject'];
            if (!send_smtp_mail($user['email'], $subject, $body, $mailer_param))
            {
                return false;
            }
            //######## end announce email #########//
            return true;
		}
		$feedback="Failure";
		return false;
	}

    /**
     * Change user's password by user self or admin
     *
     * @param int     $user_id
     * @param string  $new_pw1
     * @param string  $new_pw2
     * @param string  $old_pw
     * @param boolean $require_old_pw  Must set to true when $old_pw != null
     *
     * @return boolean
     */
    function setPasswd($user_id, $new_pw1, $new_pw2, $require_old_pw = true, $old_pw = 'xxx')
    {
        global $conn, $feedback;

        $old_pw  = trim($old_pw);
        $new_pw1 = trim($new_pw1);
        $new_pw2 = trim($new_pw2);
		$user_id = addslashes(htmlspecialchars(trim($user_id)));//Forbid hacker attack database by inject sql;

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
            $user_info = User::getInfo($user_id);
            if ($old_pw != stripslashes($user_info['user_pw'])) {
                $feedback = 'Incorrect Old Password, Please Try Again';//the old password did not correct(旧密码不正确，请重新填写)
                return false;
            }
        }

		// the password need md5()?
        // all checks passed
        $q = "UPDATE users SET user_pw = '".addslashes(htmlspecialchars($new_pw1))."' ".
			 "WHERE user_id = '".$user_id."'";

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
        return false;
    }// end setPasswd()

	function setPenName( $p )
	{
		global $conn, $feedback;
		$user_id = addslashes( htmlspecialchars( trim( $p['user_id'] ) ) );
		$pen_name = addslashes( htmlspecialchars( trim( $p['pen_name'] ) ) );
		if ($pen_name=='') {
            $feedback = 'Please enter Pen Name';
            return false;
        }
		$q = "UPDATE users SET pen_name = '".addslashes(htmlspecialchars($pen_name))."' ".
			 "WHERE user_id = '".$user_id."'";
		if ($conn->Execute($q) === false) {
            $feedback = 'Failure, Please try again.';//Pen name更改失败，请重新操作
            return false;
        } else {
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';//Pen name更改成功
                return true;
            } else {
                $feedback = 'Failure, Please try again.';//Pen name密码更改失败，请重新操作
                return false;
            }
        }

	}//end setPenName();

    //this function/method only used for update user addtional information which is not requirement fields.
	function setAddtionalInfo( $p )
	{
		global $conn, $feedback;
		$user_id = addslashes( htmlspecialchars( trim( $p['user_id'] ) ) );
		$googleplus_url = addslashes( htmlspecialchars( trim( $p['googleplus_url'] ) ) );

        foreach ($p as $k => $v) {
            if (is_string($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            } else if ($k == 'address') {
                $p[$k] = addslashes(serialize($v));
            }
        }

        $sql = "UPDATE users SET ";
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\'' .  $v . '\'';
        }
        $sql .= implode(" ,  ", $sets);
        $sql .= ' WHERE user_id = \'' . $user_id. '\' ' ;
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';//用户信息修改成功
            return true;
        } else {
            $feedback = 'Failure, Please try again';//用户信息修改失败
            return false;
        }

	}//end setAddtionalInfo();

	function setByID( $p )
	{
		global $conn, $feedback;
        $sets = array();
        foreach ($p as $k => $v) {
            $p[$k] = $v = addslashes(htmlspecialchars(trim($v)));
            if ($k != 'user_id') {
                $sets[] = $k . '=\'' . $v . '\'';
            }
        }
        
        $user_id = $p['user_id'];
        if (empty($sets)) {
            $feedback = 'empty data, please to check';
        }
        $q = "UPDATE users SET " . implode(", ", $sets) .
			 " WHERE user_id = '".$user_id."'";
		if ($conn->Execute($q) === false) {
            $feedback = 'Failure, Please try again.';//Pen name更改失败，请重新操作
            return false;
        } else {
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';//Pen name更改成功
                return true;
            } else {
                $feedback = 'Failure, Please try again.';//Pen name密码更改失败，请重新操作
                return false;
            }
        }

	}//end setPenName();

    /**
     * Get user's info by $user_id
     *
     * @param int $user_id
     *
     * @return boolean or an array containing all fields in tbl.users
     */
    function getInfo($user_id, $qw = '')
    {
        global $conn;

        $sql = "SELECT * FROM users AS u WHERE user_id = '".$user_id."'";
        if (!empty($qw)) {
            $sql .= ' AND ' . $qw;
        }
        $rs = &$conn->Execute($sql);

        if ($rs) {
            $ret = false;
            if ($rs->fields['user_id'] != 0) {
                $ret = $rs->fields; // return an array
                //$ret['address'] = unserialize($ret['address']);
                $ret['form_submitted'] = explode("|",  $ret['form_submitted']);
                $ret['hide_bank_info'] = substrr($ret['bank_info']);
                $ret['hide_routing_number'] = substrr($ret['routing_number']);
                $ret['hide_ssn'] = substrr($ret['social_security_number']);
            }
            $rs->Close();
            return $ret;
        }

        return false; // return false if user does not exist
    }//end getInfo()

    function getUserByIds($user_id, $where = '')
    {
        global $conn;
        $result = array();
        if (!empty($user_id)) {
            if (is_array($user_id)) {
                $qw = "user_id IN ('". implode("', '", $user_id)."') ";
            } else {
                $qw = "user_id = '".$user_id."'";
            }
            $sql = "SELECT * FROM users WHERE " . $qw;
            if (!empty($where)) {
                 $sql .= ' AND ' . $where; 
            }
            $rs = &$conn->Execute($sql);
            if ($rs)
            {
                while (!$rs->EOF) 
                {
                    $result[$rs->fields['user_id']] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
            }
        }
        return $result;
    }

    function getInfoByCandidateId($candidateId)
    {
        global $conn;

        $rs = &$conn->Execute("SELECT * FROM users WHERE candidate_id = '".$candidateId."'");

        if ($rs) {
            $ret = false;
            if ($rs->fields['user_id'] != 0) {
                $ret = $rs->fields; // return an array
            }
            $rs->Close();
            return $ret;
        }

        return false; // return false if user does not exist
    }

     /**
     * Set user's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setInfo($p = array())
    {
        global $conn, $feedback;
        global $g_tag;
        unset($p['frompage']);
		$qw = '';
        foreach ($p as $k => $v) {
            /*if ($k == 'user_type' && $p['role'] != 1) {
                $v = 0;
            }*/
            if (is_string($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            } else if ($k == 'address') {
                $p[$k] = addslashes(serialize($v));
            }
        }
        extract($p);
        if ($user_id == '') {
            $feedback = "Please choose an user";
            return false;
        }

        if ($user_name == '') {
            $feedback = "Please input User's Name";
            return false;
        }
		if (!valid_user_name($user_name)) {
			return false;
		}

		// added by snug xu 2006-10-27 14:11 - START
		if (User::getName() != 'admin' )
		{
			$qw .= " AND user_name != 'admin'"; 
		}

		if ($role > self::getPermission()) {
            $feedback = "Have not the permission add one ".$g_tag['user_permission'][$role]." user";
            return false;
		}

		// added by snug xu 2006-10-27 14:11 - FINISHED

        if (User::getPermission() >= 4)
        {        	
            $pass = $user_pw;
            unset($p['user_pwnew']);
            $qu = "";
            if ($pass != '') 
            {
                //$pass = addslashes(htmlspecialchars(trim($p['user_pw'])));
                if ($pass != $user_pwnew) 
                {
                    $feedback = 'Password mismatch, Please check your input and enter the password again';//两次填写的新密码不一致，请重新填写
                    return false;
                }

                if (!valid_pw($pass)) {//this function in the utils.php,
                    return false;
                }
            }

            if ($role == '') {
                $feedback = "Please choose user's role";
                return false;
            }
            $p['role'] = $g_tag['user_permission'][$role];
            $p['permission'] = $role;
        } else {
            if (isset($p['role'])) unset($p['role']);
            if (isset($p['permission'])) unset($p['permission']);
        }

        if ($first_name == '') {
            $feedback = "Please enter user's first name";
            return false;
        }

        if ($last_name == '') {
            $feedback = "Please enter user's last name";
            return false;
        }
        $email = stripslashes($email);
        if (!valid_email($email)) {
            $feedback = "Invalid email, please to check.";
            return false;
        }
        $email = addslashes($email);

        if ($pay_pref == 3 && $paypal_email != '') {
            $feedback = "Please enter user's last name";
        } 

        if ($sex == '') {
            $feedback = "Please choose user's gender";
            return false;
        }


        $q = "SELECT COUNT(*) AS count FROM users ".
             "WHERE user_name = '".$user_name."' AND user_id != '".$user_id."'";


        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "The user\'s name already registered, please enter another name.";//用户名重复
            return false;
        }
        if (is_array($p['form_submitted'])) $p['form_submitted'] = implode("|", $p['form_submitted']);
        $sql = "UPDATE users SET ";
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\'' .  $v . '\'';
        }
        $sql .= implode(" ,  ", $sets);
        $sql .= ' WHERE user_id = \'' . $user_id. '\' ' . $qw;
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
     * Set user's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setUserInfo($p = array())
    {
        global $conn, $feedback;
        global $g_tag;

		$qw = '';

        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        if ($user_id == '') {
            $feedback = "Please choose an user";
            return false;
        }

        $q = "SELECT COUNT(*) AS count FROM users ".
             "WHERE user_id != '".$user_id."'";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count == 0) {
            $feedback = "The user is not exist!";//用户名重复
            return false;
        }
        foreach ($p as $k => $v) {
            if (is_array($v)) {
                $v = serialize($v);
            }
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = "{$k}='{$v}'";
        }
		$sql = "UPDATE users ".
                           "SET " . implode(', ', $sets) .
                       "WHERE user_id = '".$user_id."' {$qw}";
		$conn->Execute($sql);
        $feedback = 'Success';//用户信息修改成功
        if (isset($p['photo'])) {
            $sql = "UPDATE phpbb_users SET user_avatar_type=2, user_avatar='{$p['photo']}' WHERE cms_user_id={$user_id}";
            $conn->Execute($sql);
        }
        return true;

    }//end setUserInfo()

     /**
     * Set user's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setCPPaymentFlowStatus($p = array())
    {
        global $conn, $feedback;
        global $g_tag;

        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        if ($user_id == '') {
            $feedback = "Please choose an user";
            return false;
        }
        $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
        if ($payment_flow_status == '') {
            $feedback = "Please set the payment status";
            return false;
        }

		$month = addslashes(htmlspecialchars(trim($p['month'])));
		$month = str_replace('-', "", $month);
        if ($month == '') 
		{
			$month = date( "Ym" );
        }
        $data = $p;
        unset($data['article_ids']);
        unset($data['payment_flow_status']);

		
        $article_ids = addslashes(htmlspecialchars(trim($p['article_ids'])));
        $now = time();
        $data['approved_user'] = 0;
        $data['check_no']        = '';
        $data['invoice_no']       = '';
        $data['reference_no']   = '';
        $data['payment']         = 0;
        $q = "SELECT * FROM cp_payment_history ".
             "WHERE user_id = '".$user_id."' AND month = '".$month."' ";
		$rs = $conn->Execute($q);
        $pf_status = '';
        if ($rs) 
		{
            
            if (!$rs->EOF)
            {
                $is_update = true;
                $fields = $rs->fields;
                foreach ($fields as $k => $v) {
                    if (empty($v) && strlen($v) == 0) unset($fields[$k]);
                 }
                $data = array_merge($data, $fields);
                $pf_status = $rs->fields['payment_flow_status'];
            }
			$rs->Close();
        }
        else
        {
            $is_update = false;
        }
        if ($pf_status == 'paid') {
            $feedback = 'This copywriter had paid.';
            return false;
        }


        if ($payment_flow_status == 'cpc' || $payment_flow_status == 'dwe') {
            if ($pf_status == 'ap' || $pf_status == 'cpc' || $pf_status == 'dwe' || ($pf_status == '' && $payment_flow_status == 'cpc')) {
                //
            } else {
                $feedback = 'Please wait CopyPress confirm';
                return false;
            }

            if ($pf_status == 'cpc' && $payment_flow_status == 'dwe') {
                return false;
            }
        }
        
		if ($payment_flow_status != $pf_status) 
		{
			$result = true;
			if( $payment_flow_status ==  'paid' )
			{
				$date_pay = date('Y-m-d H:i:s', $now);
                $invoice_status = 1;
                // added by nancy xu 2009-12-29 15:16
                $result = ArticleCostHistory::storeArticleCostHistoryByParam($p, $invoice_status, $cost_arr);
                $data['invoice_date'] = $date_pay;
                $data['invoice_status'] = $invoice_status;
                $invoice_date = $date_pay;
                $types = self::sumTypePaymentHistory($user_id, $month, $total);
                $types = serialize($types);
                $data['types'] = $types;
                $data['total'] = $total;
                // end
			}
            else
            {
                $date_pay = '0000-00-00 00:00:00';
                if (!isset($data['set_flow_status_time']))
                    $data['set_flow_status_time'] = date("Y-m-d H:i:s", $now);
            }
            $data['payment_flow_status'] = $payment_flow_status;
            $data['date_pay'] = $date_pay;
            if ($result) {
                $conn->StartTrans();
                $q = CpPaymentHistory::getSqlByData($data, $is_update);
                $conn->Execute( $q );
                if( $payment_flow_status ==  'paid' )
                {
                    $article_ids = trim($article_ids, ";");
                    $article_ids = str_replace(";", "', '", $article_ids);
                    $log_param['article_id'] = $article_ids;
                    $log_param['user_id']  = $user_id;
                    $log_param['orderby']  = 'log_id';
                    $log_ids = ArticlePaymentLog::getLogIDs($log_param);
                    $query = "SELECT log_id  FROM `article_payment_log` where article_id IN ('{$article_ids}') and user_id = '{$user_id}' order by log_id";
                    $rs = &$conn->Execute( $query );
                    $log_ids = array();
                    if ($rs) 
                    {
                        while (!$rs->EOF) 
                        {
                            $log_ids[] = $rs->fields['log_id'];
                            $rs->MoveNext();
                        }
                        $rs->Close();
                    }
                    if (!empty($log_ids)) {
                        $str_log_id = implode("', '", $log_ids);
                        $sql = "DELETE FROM `article_payment_log` where log_id IN ('{$str_log_id}')";
                        $conn->Execute( $sql );
                    }
                    $query = "SELECT ar.article_id, ck.article_type, ck.campaign_id " . 
                            "FROM articles AS ar " . 
                            "LEFT JOIN campaign_keyword AS ck ON ck.keyword_id=ar.keyword_id  " . 
                            "WHERE ar.article_id IN ('{$article_ids}') AND  ck.status!='D' " . 
                            "ORDER BY ck.campaign_id";
                    $rs = &$conn->Execute( $query );
                    if ($rs) 
                    {
                        while (!$rs->EOF) 
                        {
                            $articles[] = $rs->fields;
                            $rs->MoveNext();
                        }
                        $rs->Close();
                    }
                    if(count( $articles ) )
                    {
                        foreach( $articles as $k => $article )
                        {
                            $logs[] = "( '". implode( "', '", $article). "', '{$user_id}' , '{$month}', '". date('Y-m-d H:i:s', $now) . "' )";
                        }
                        $query = "INSERT INTO `article_payment_log` ( `article_id`, `article_type`, `campaign_id`, `user_id`, `month`, `paid_time` ) VALUES ".implode(", " , $logs ). ";";
                        $conn->Execute( $query );
                    }
                }
                $ok = $conn->CompleteTrans();
            }
		}
        if ($ok || $payment_flow_status == $pf_status) 
		{
            $feedback = 'Success';
            if ($payment_flow_status == 'ap' || $payment_flow_status == 'dwe' || ($payment_flow_status == 'cpc' && $pf_status == '')) 
			{
                self::sendPaymentFlow($user_id, $payment_flow_status, $now, $p['memo'], $month);
            }
            return true;
        }
		else
		{
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end setCPPaymentFlowStatus()


     /**
     * Set user's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setPaymentFlowStatus($p = array())
    {
        global $conn, $feedback;
        global $g_tag;
        //$conn->debug = true;

        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        if ($user_id == '') {
            $feedback = "Please choose an user";
            return false;
        } else {
            $qw = ' AND u.user_id=' . $user_id;
        }

        $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
        if ($payment_flow_status == '') {
            $feedback = "Please set the payment status";
            return false;
        }

        $role = addslashes(htmlspecialchars(trim($p['role'])));
        if ($role == '') {
            $role = 'copy writer';
        }
        $qw .= ' AND u.role=\'' . $role . '\'';

		$month = addslashes(htmlspecialchars(trim($p['month'])));
		$month = str_replace(array('-', '(', ')', ' '), "", $month);
        if ($month == '')  {
			$month = changeTimeToPayMonthFormat(time());
        }
        $data = $p;
        unset($data['vendor_id']);
        unset($data['article_ids']);
        unset($data['payment_flow_status']);
        $now = changeTimeFormatToTimestamp($month);

        $data['approved_user'] = 0;
        $data['check_no']        = '';
        $data['invoice_no']       = '';
        $data['reference_no']   = '';
        $data['payment']         = 0;
        $q = "SELECT * FROM cp_payment_history ".
             "WHERE user_id = '".$user_id."' AND month = '".$month."' AND role='" . $role . "'";
		$rs = $conn->Execute($q);
        $pf_status = '';
        if ($rs) 
		{
            if (!$rs->EOF)
            {
                $is_update = true;
                $fields = $rs->fields;
                foreach ($fields as $k => $v) {
                    if (empty($v) && strlen($v) == 0) unset($fields[$k]);
                 }
                $data = array_merge($data, $fields);
                $pf_status = $rs->fields['payment_flow_status'];
            }
			$rs->Close();
        }
        else
        {
            $is_update = false;
        }
        if ($pf_status == 'paid') {
            $feedback = 'This user had paid.';
            return false;
        }


        if ($payment_flow_status == 'cpc' || $payment_flow_status == 'dwe') {
            if ($pf_status == 'ap' || $pf_status == 'cpc' || $pf_status == 'dwe' || ($pf_status == '' && $payment_flow_status == 'cpc')) {
                //
            } else {
                $feedback = 'Please wait CopyPress confirm';
                return false;
            }

            if ($pf_status == 'cpc' && $payment_flow_status == 'dwe') {
                return false;
            }
        }
        
		if ($payment_flow_status != $pf_status) 
		{
           $conn->StartTrans();
			$result = true;
            // add new workflow name cbill(create bill) for payment
            // create bill will  interact with netsuite
			if( $payment_flow_status ==  'paid' || $payment_flow_status == 'cbill') {
				$date_pay = date('Y-m-d H:i:s');
                $invoice_date = date('Y-m-d H:i:s', $now);
                if ($payment_flow_status == 'cbill') {
                    $date_bill = date("Y-m-d H:i:s");
                    $date_pay = '0000-00-00 00:00:00';
                    $data['date_bill'] = $date_bill;
                } else {
                    $data['date_pay'] = $date_pay;
                }
                $invoice_status = 1;
                // added by nancy xu 2009-12-29 15:16
                $p['role'] = $role;
                $data['invoice_date'] = $invoice_date;
                $data['invoice_status'] = $invoice_status;
                $result = ArticleCostHistory::storeArticleCostHistoryByParam($p, $invoice_status, $cost_arr, $date_bill);
                $types = self::sumTypePaymentHistory($user_id, $month, $total, $role);
                $payment = 0;
                foreach ($types as $tk=> $row) {
                    $cost = round($row['cost'], 2);
                    $types[$tk]['cost'] = sprintf("%01.2f",$cost);
                    $payment += $cost;
                }

                // create bill to netsuite
                if ($payment_flow_status == 'cbill') {
                    $user_info = User::getInfo($user_id);
                    $user_info = array_merge($user_info, $p);
                    $user_info['payment'] = sprintf("%01.2f", $payment);
                    $user_info['invoice_no'] = $user_id . '-' . substr($month, 0, 6) . '-' .substr($month, 6, 1);
                    $user_info['month'] = $month;
                    $vendor_bill = payment_plugin($user_info, $types, $now);
                    /*require_once CMS_INC_ROOT.'/netsuite.php';
                    global $g_netsuite_user;
                    $oNetSuite = new NetSuite($g_netsuite_user);
                    $vendor_bill = $oNetSuite->saveBill($user_info, $types, $now);
                    $vendor_bill = array(
                        'customForm' => 103,
                        'postingPeriod' => '2010-08-08T22:48:31-05:00',
                        'dueDate' => '2010-09-15T00:00:00-05:00',
                        'tranDate' => '2010-09-01T00:00:00-05:00',
                        'tranId' => '37-201008',
                        'userTotal' => '119.31',
                        'memo' => 'Aug 2010',
                        'expenseList' => array(
                                'expense' => array(
                                        '0' => array(
                                                'account' => array(
                                                        'internalId' => 223
                                                    ),
                                                'amount' => 119.310,
                                                'memo' => 'Type 1',
                                                'class' => array(
                                                        'internalId' => 5
                                                    ),
                                                'location' => array(
                                                        'internalId' => 4
                                                    ),
                                                'isBillable' => false,
                                            )
                                    )
                            ),
                        'nbill_id' => 1434,
                        'vendor_id' => 13198,
                        'user_id' => 37,
                    );*/
                    if (!empty($vendor_bill)) {
                        $vendor_bill['user_id'] = $user_id;
                        $oPaymentBill = new PaymentBill;
                        //pr($vendor_bill);
                        $oPaymentBill->store($vendor_bill, $types);
                    } else {
                        $ok = $conn->CompleteTrans();
                        return false;
                    }
                }
                $types = serialize($types);
                $data['types'] = $types;
                $data['total'] = $total;
                $data['payment'] = $payment;
                
                // end
			}
            if (!isset($data['set_flow_status_time']))
                $data['set_flow_status_time'] = date("Y-m-d H:i:s");
            $data['payment_flow_status'] = $payment_flow_status;
            if ($result) {
                $q = CpPaymentHistory::getSqlByData($data, $is_update);
                $conn->Execute( $q );
                if( $payment_flow_status ==  'paid' || $payment_flow_status ==  'cbill')
                {
                    $log_param['user_id']  = $user_id;
                    $log_param['role']  = $role;
                    $log_param['pay_month']  = $month;
                    ArticlePaymentLog::updatePaymentInfo($log_param, $payment_flow_status);
                }
            }
            $ok = $conn->CompleteTrans();
		}
        if ($ok || $payment_flow_status == $pf_status) 
		{
            $feedback = 'Success';
            if ($payment_flow_status == 'ap' || $payment_flow_status == 'dwe' || ($payment_flow_status == 'cpc' && $pf_status == '')) 
			{
                self::sendPaymentFlow($user_id, $payment_flow_status, $now, $p['memo'], $month, $role);
            }
            return true;
        }
		else
		{
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end setPaymentFlowStatus()

	function getPaymentHistoryMonthGroupByUserID( $p )
	{
		global $conn, $feedback;
		$user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
		$qw="1 ";
		if( $user_id>0)
			$qw .= " AND user_id='$user_id'";
        // added by nancy xu 2009-12-23 16:23
        if (isset($p['payment_flow_status'])) {
            $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
            if (!empty($payment_flow_status)) 
                $qw .= ' AND  payment_flow_status=\'' . $payment_flow_status . '\'';
        }
        if (isset($p['invoice_status'])) {
            $invoice_status = addslashes(htmlspecialchars(trim($p['invoice_status'])));
            if (strlen($invoice_status)) 
                $qw .= ' AND  invoice_status=\'' . $invoice_status . '\'';
        }
        $order = 'month';
        if (isset($p['orderby'])) {
            $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
            if (strlen($orderby)) 
               $order = $orderby;
        }
        // end
		$sql ="SELECT * FROM `cp_payment_history` WHERE {$qw} ORDER BY  " . $order;
		$rs = &$conn->Execute( $sql );
		$result = array();
		if ($rs)
		{
            while (!$rs->EOF) 
			{
                $month = showMonth($rs->fields['month']);
                $result[$rs->fields['month']] = $month;
				$rs->MoveNext();
            }
            $rs->Close();
        }
		return $result;
	}

    function getHistoryPayment($p)
    {
		global $conn, $feedback;
		
		$qw="1 ";
		if(isset($p['payment_flow_status'])) {
            $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
			if ($user_id > 0) $qw .= " AND user_id='$user_id'";
        }
        // added by nancy xu 2009-12-23 16:23
        if (isset($p['payment_flow_status'])) {
            $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
            if (!empty($payment_flow_status)) 
                $qw .= ' AND  payment_flow_status=\'' . $payment_flow_status . '\'';
        }
        if (isset($p['invoice_status'])) {
            $invoice_status = addslashes(htmlspecialchars(trim($p['invoice_status'])));
            if (strlen($invoice_status)) 
                $qw .= ' AND  invoice_status=\'' . $invoice_status . '\'';
        }
        $order = 'month';
        if (isset($p['orderby'])) {
            $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
            if (strlen($orderby)) 
               $order = $orderby;
        }
    }

	function getMonthesFromCpPaymentHistory()
	{
		global $conn;
		$sql ="SELECT DISTINCT `month` FROM `cp_payment_history` ORDER BY `month` ";
		$rs = &$conn->Execute( $sql );
		$result = array();
		if ($rs)
		{
            while (!$rs->EOF) 
			{
                $month = showMonth($rs->fields['month']);
                $result[$rs->fields['month']] = $month;
				$rs->MoveNext();
            }
            $rs->Close();
        }
		return $result;
	}

	function getPaymentHistoryInfo($p)
	{
		global $conn, $feedback;
		$user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
		$qw="1 ";
		if( $user_id>0)
			$qw .= " and user_id='{$user_id}'";
		$month = addslashes(htmlspecialchars(trim($p['month'])));
		if( strlen($month) )
			$qw .= " and month='{$month}'";
		$sql ="SELECT * FROM `cp_payment_history` WHERE {$qw}";
		$rs = &$conn->Execute( $sql );
		if ($rs)
		{
			if( strlen( $month ))
			{
				if (!$rs->EOF) 
				{
					$cp_payment_info = $rs->fields;
                    $cp_payment_info['month_invoice'] = substr($month, 0, 6);
                    $cp_payment_info['month_order'] = substr($month, -1);
				}
			}
			else
			{
				while (!$rs->EOF) 
				{
                    $month = $rs->fields['month'];
                    $now = changeTimeFormatToTimestamp($month);
					$cp_payment_info[] = $rs->fields;
					$rs->MoveNext();
				}
			}
            $rs->Close();
        }
		return $cp_payment_info;
	}

	function getArticleAmountReport( $p, $role = 'copy writer' )
	{
		$user_id = mysql_escape_string(htmlspecialchars(trim( $p['user_id'] )));
		$current_month = mysql_escape_string(htmlspecialchars(trim( $p['month'] )));
        
        $now = time();
		if( $current_month == changeTimeToPayMonthFormat($now) || $current_month=='' ) {
			$current_month = changeTimeToPayMonthFormat($now);
		} else {
            $now = changeTimeFormatToTimestamp($current_month);
		}
        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw  = "\nAND u.status != 'D' ";
        $qw .= "\nAND u.role = '" .  $role . "'";
        $qw .= "\nAND u.user_id = {$user_id}";
        $param = array('current_month' => $current_month);
        $report = self::getArticleAmount($qw, $now, null , $user_id, $role, $param);
        return $report;
    }

    function getArticleAmount($qw, $now, $result =null, $user_id = null, $role='copy writer', $param = array())
    {
        global $conn;     
        if (!empty($user_id)) $param['user_id'] = $user_id;
        $param['role'] = $role;
        $param['now']         = $now;
        $param['qw_where'] = $qw;
        $param['type']        = 'article-amount';
        $sqls = self::getAccountingConditionOrSql($param, $role);
        $q = $sqls['sql'];
	    $rs = &$conn->Execute($q);
	    if ($rs) {
            $article_types = $campaigns = array();
            $i = 0;
            
            if (empty($all_report['num']))
                $all_report['num'] = 0;
            if (empty($all_report['cost']))
                $all_report['cost'] = 0;
            while (!$rs->EOF) 
			{
                $fields = $rs->fields;
                $article_type = $fields['article_type'];
                $p_article_type = $fields['at_parent_id'];
                $total_article = $fields['total_article'];
                $total_cost = $fields['total_cost'];
                $type_name = $fields['article_type_name'];
                $u_type = $fields['user_type'];
                $total_word = $fields['num'];
                $count_article = $fields['total'];
                // modified by nancy xu 2011-05-26 16:28 - STARTED
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $total = $checked > 0 ? $count_article : $total_word;
                // modified by nancy xu 2011-05-26 16:28 - FINISHED
                $type = $article_type;
                $cid  = $fields['campaign_id'];
                $uid  = $fields['user_id'];
                if (empty($result)) {
                    $article_types[$uid][$type]['checked'] = $checked;
                    $campaigns[$uid][$cid][$type]['checked'] = $checked;
                    if (empty($article_types[$uid][$type]['num']))
                        $article_types[$uid][$type]['num'] = 0;
                    if (empty($article_types[$uid][$type]['cost']))
                        $article_types[$uid][$type]['cost'] = 0;              
                    if (empty($total_article) || empty($total_cost))
                    {
                        if (empty($campaigns[$cid][$type]['num']))
                            $campaigns[$uid][$cid][$type]['num'] = 0;
                        if (empty($campaigns[$uid][$cid][$type]['cost']))
                            $campaigns[$uid][$cid][$type]['cost'] = 0;
                        if (empty($campaigns[$uid][$cid][$type]['per_cost']))
                            $campaigns[$uid][$cid][$type]['per_cost'] = 0;
                        $campaigns[$uid][$cid][$type]['num'] = $total;
                        if ($u_type == 1) {
                            $campaigns[$uid][$cid][$type]['cost'] = $total * $cost_per_unit;
                            $campaigns[$uid][$cid][$type]['per_cost'] = $cost_per_unit;
                        }
                    }
                    else
                    {
                        $campaigns[$uid][$cid][$type]['num'] = $total_article;
                        $campaigns[$uid][$cid][$type]['cost'] = $total_cost;
                        $campaigns[$uid][$cid][$type]['per_cost'] = $cost_per_unit;
                        if ($u_type == 1 &&  $total_cost == 0.000 && $total_article) {
                            $campaigns[$uid][$cid][$type]['cost'] = $total * $cost_per_unit;
                        }
                    }
                    $campaigns[$uid][$cid][$type]['type_name'] = $type_name;
                } else {
                    if (!isset($result[$uid]['pay_amount'])) $result[$uid]['pay_amount'] = 0;
                    if ($result[$uid]['user_type'] == 1  || ($result[$uid]['user_type'] == 2 && ($result[$uid]['payment_flow_status'] == 'paid' || $result[$uid]['payment_flow_status'] == 'cbill'))) {
                        if (empty($total_article) || empty($total_cost)) {
                            $result[$uid]['pay_amount'] += $total * $cost_per_unit;
                        } else {
                            $result[$uid]['pay_amount'] += $total_cost;
                            if ($result[$uid]['user_type'] == 1 && $total_article > 0 && $total_cost == 0.000) {
                                $result[$uid]['pay_amount'] += $total * $cost_per_unit;
                            }
                        }
                    }
                    if (!isset($result[$uid]['pay_count_article'])) $result[$uid]['pay_count_article'] = 0;
                    if ($checked) {
                        $result[$uid]['pay_gct_count'] -= $total_word;
                        $result[$uid]['pay_count_article'] += $total;
                    }

                }
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        if (empty($result)) {
            if (!empty($campaigns)) {
                foreach ($campaigns as $uid => $campaign)
                {
                    foreach ($campaign  as $k => $type) 
                    {
                        foreach ($type as $key => $value)
                        {
                            $article_types[$uid][$key]['num']     += $value['num'];
                            $article_types[$uid][$key]['cost']     += $value['cost'];
                            $article_types[$uid][$key]['type_name'] = $value['type_name'];
                            $all_report[$uid]['num'] += $value['num'];
                            $all_report[$uid]['cost'] += $value['cost'];
                        }
                    }
                }
                if (count($campaigns) == 1 && !is_array($user_id) && $user_id > 0) {
                    $campaigns = $campaigns[$user_id];
                    $article_types = $article_types[$user_id];
                    $all_report = $all_report[$user_id];
                }
                $report = array();
                $report['campaign'] = $campaigns;
                $report['types'] = $article_types;
                $report['all'] = $all_report;
                return $report;
                if (!empty($result)) {
                    return $result;
                }
            }
        } else {
            if (!empty($result)) return $result;
            else return null;
        }
    }

	function updatePaymentHistory( $p )
	{
		global $conn, $feedback;
        global $g_tag;

		$qw = '';
        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        if ($user_id == '') {
            $feedback = "Please choose an user";
            return false;
        }

		$month = addslashes(htmlspecialchars(trim($p['month'])));
        if ($month == '') {
            $feedback = "Failure, Please try again";
            return false;
        }

		$role = addslashes(htmlspecialchars(trim($p['role'])));
		$check_no = addslashes(htmlspecialchars(trim($p['check_no'])));
		$approved_user = addslashes(htmlspecialchars(trim($p['approved_user'])));
		$reference_no = addslashes(htmlspecialchars(trim($p['reference_no'])));
		$invoice_no = addslashes(htmlspecialchars(trim($p['invoice_no'])));
		$operation = addslashes(htmlspecialchars(trim($p['operation'])));
		// added by snug xu 2006-10-27 15:25 - START
		$date_pay = addslashes(htmlspecialchars(trim($p['date_pay'])));
		$invoice_date = addslashes(htmlspecialchars(trim($p['invoice_date'])));
		$invoice_status = addslashes(htmlspecialchars(trim($p['invoice_status'])));
		$notes = addslashes(htmlspecialchars(trim($p['notes'])));
		$payment = addslashes(htmlspecialchars(trim($p['payment'])));
		// added by snug xu 2006-10-27 15:25 - FINISHED

		/**when print invoice , set payment as total money that will pay copywriter current month**/
        if($payment <= 0 || strlen($payment) == 0) {
            $payment_report = self::getArticleAmountReport( $p, $role );
            $payment = $payment_report['all']['cost'];  
        }
		if( $operation=='submit' )
		{
			$qu .= " `invoice_date`='{$invoice_date}', ";
			$qu .= " `date_pay`='{$date_pay}', ";
			$qu .= " `invoice_status`='1', ";
            $types = self::sumTypePaymentHistory($user_id, $month, $total);
            $types = serialize($types);
            $qu .= " `total`='{$total}', ";
            $qu .= " `types`='{$types}', ";
		}
		$sql = "UPDATE `cp_payment_history` ".
					"SET `check_no`='{$check_no}', ".
					"`approved_user`='{$approved_user}', ".
					$qu . 
					"`invoice_no`='{$invoice_no}', ".
					"`reference_no`='{$reference_no}', ".
					" `payment`='{$payment}', ".

					" `notes`='{$notes}' ".
					"WHERE `month`='{$month}' AND user_id='{$user_id}' AND role='{$role}'";
		$conn->Execute($sql);
		 if ($conn->Affected_Rows() === false) {
            $feedback = 'Failure, Please try again';
            return false;
        } else {
            $feedback = 'Success';
            return true;
        }

	}//end updatePaymentHistory

    function sumTypePaymentHistory($user_id = null, $month = null, &$total = 0, $role = 'copy writer')
    {
        global $conn;
        $conditions = array();
        $user_id = addslashes(htmlspecialchars(trim($user_id)));
        $month = addslashes(htmlspecialchars(trim($month)));
        $conditions[] = 'user_id = \'' .$user_id. '\'';
        $conditions[] = '`month` = \'' .$month. '\'';
        $conditions[] = '`role` = \'' .$role. '\'';
        $qw = implode(" AND ", $conditions);
        $q = 'SELECT SUM(total_article) AS total, SUM(total_cost) AS cost, user_id, month, qd_listid,  '
                .'article_type, article_type_name AS name '
                .'FROM article_cost_history AS ach '
                .'WHERE ' . $qw . ' GROUP BY  user_id, `month`, article_type ' ;
        $rs = &$conn->Execute($q);
        $secondcol = null;
        if (!empty($user_id) && !empty($month)) {
            $first2cols = 'article_type';
        } else if (!empty($user_id) && empty($month)) {
            $first2cols = 'month';
            $secondcol = 'article_type';
        } else {
            $first2cols = 'user_id';
            $secondcol = 'month';
        }
        $result = array();
        if ($rs) {
            $ret = false;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $key = $fields[$first2cols];
                $key2 = $fields[$secondcol];
                $article_type = $fields['article_type'];
                unset($fields['user_id']);
                unset($fields['month']);
                unset($fields['article_type']);
                if (!empty($secondcol)) {
                    if ($article_type == $key2)
                        $result[$key][$key2] = $fields;
                    else $result[$key][$key2][$article_type] = $fields;
                } else {
                    $result[$key] = $fields;
                    $total += $fields['total'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }

    function sendPaymentFlow($user_id, $payment_flow_status, $now, $memo = '', $month = '', $role ='copy writer' )
    {
        $current_role = User::getRole();
        if ($current_role == 'copy writer' || $current_role == 'editor') $role = $current_role;
        global $conn, $mailer_param;
        // added by snug xu 2007-05-21 14:55 - STARTED
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
        // added by snug xu 2007-05-21 14:55 - FINISHED
        $domain = 'http://' . $_SERVER['HTTP_HOST'];
        $user_infos = User::getInfo($user_id, "u.status != 'D'");
        if (empty($user_infos)) return true;
        // modified by snug xu 2007-05-10 22:36 - STARTED
        if (trim($month) == '' && trim($now) == '') {
            $now = time();
            $month = date( "Ym" );
        } else if(trim($month) == '' && $month == date("Ym", $now)) {
            $month = date( "Ym", $now );
        }
        $param['user_id'] = $user_id;
        $param['month'] = $month;
        $param['user_type'] = $role;
        // get cp accounting report by user id and month
        $report = self::getAccountingByUserID($param);
        $pay_total  = $report['pay_gct_count'];
        $total_count = $report['gct_count'];
        $payment = isset($report['payment']) && $report['payment'] > 0 ? $report['payment'] : $report['pay_amount'];
        // modified by snug xu 2007-05-10 22:36 - FINISHED
        $type_report = '';
        foreach ($g_article_types as $k => $type)
        {
            $type_report .= "{$type}:&nbsp;" . $report[$k] . "<br />";
        }
        // added by snug xu 2007-02-27 17:43 - STARTED
        $word = ($role == 'copy writer') ? 'written':'edited';
        $p = array(
            'first_name'=>$user_infos['first_name'],
            'total'=>'$' . $payment,
            'datastring'=>showMonth($month),
         );
        if ($payment_flow_status == 'cpc') {
            $p['login_link'] = '<a href="' . $domain . '/client_campaign/client_approval_list.php?month=' . $month . '" >here</a>';
            return Email::sendAnnouceMail(39, $user_infos['email'], $p);
            /*$subject = "Please look over your client approved articles";
            $body = "";
            $body .= "Dear &nbsp;".$user_infos['first_name']."<br /><br />";
            $body .= 'Your total client approved articles was confirmed this month. Please click <a href="' . $domain . '/client_campaign/client_approval_list.php?month=' . $month . '" >here</a> to look over it.';
            $body .= "Thanks for your hard work this month, we have now prepared your total words " . $word . " in [". $month ."] , here's a simple summary:<br />";
            $body .= $type_report;
            $body .= "total:&nbsp;" . $total_count . "<br />".
                     "Pay total:&nbsp;" . $pay_total . "<br />".
                     "This total is based on what you have submitted and has been checked as of midnight of last day of the month.<br /><br />";
            $body .= "<br />CopyPress. ";
            $address = $user_infos['email'];
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                return false;
            } else {
                return true;
            }*/
        }
        // added by snug xu 2007-02-27 17:43 - FINISHED
        if ($payment_flow_status == 'ap') {
            /*$subject = "Please check out your approved articles";
            $body = "";
            $body .= "Dear &nbsp;".$user_infos['first_name']."<br><br>";
            $body .= "Thanks for your hard work this month, we have now prepared your total words " . $word . " in [". $month ."] , here's a simple summary:<br>" . 
                     $type_report.
                     "total:&nbsp;".$total_count."<br>".
                     "pay words total:&nbsp;".$pay_total."<br>".
                     'pay amount:&nbsp;$'.$payment."<br>".
                     "This total is based on what you have submitted and has been checked as of mid night of last day of the month.<br><br>".
                     "You must login into your CMS to approve the numbers or give us your explaination if you believe the number should be different within 24 hours, you can do so by clicking here. <br>{$domain}/login.php<br /><br />".
                    "CopyPress.";

            $address = $user_infos['email'];
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                return false;
            } else {
                return true;
            }*/

            return Email::sendAnnouceMail(38, $user_infos['email'], $p);
        }

        if ($payment_flow_status == 'dwe') {
            $address = 'community@copypress.com';
            $subject = $user_infos['first_name'] . ' Disapproved invoice';
            $body = $user_infos['first_name'] . ' disapproved the invoice.  Please review it immediately. ';
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                return false;
            } else {
                return true;
            }
            /*$mail_to_arr = array('Content' => 'cptech@copypress.com');
            $subject = $user_infos['first_name']." has comments regard total articles completed for last month";
            foreach ($mail_to_arr AS $ku => $vu) {
                $body = "";

                $body .= "Dear &nbsp;".$ku."<br><br>".$subject."<br><br>";
                $body .= $memo."<br><br>Last month's summary<br>";
                $body .= $type_report . 
                         "total:".$total_count."<br>";

                $body .= "<br><br>Thanks. ";
                $body .= "<br><br>Best Regards,<br>";

                $address = $vu;
                if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                    return false;
                } else {
                    return true;
                }
            }*/
        }

        return true;
    }// end sendPaymentFlow()

    /**
     * Get user's info by $user_id
     *
     * @param int $user_id
     *
     * @return boolean or an array containing all fields in tbl.users
     */
    function getPaymentInfoByIDAndMonth($user_id, $month)
    {
        global $conn;

        if (trim($user_id) == '') {
            $user_id = self::getID();
        }
        if (trim($month) == '') {
            $month = date('Ym', time());
        }

        $rs = &$conn->Execute("SELECT * FROM cp_payment_history ".
                              "WHERE user_id = '".$user_id."' AND month = '".$month."'");

        if ($rs) {
            $ret = false;
            if ($rs->fields['user_id'] != 0) {
                $ret = $rs->fields; // return an array
            }

            $rs->Close();
            return $ret;
        }

        return false; // return false if user does not exist
    }//end getPaymentInfoByIDAndMonth()

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
	 *get user information by email
	 *
	 *@param $email
	 *@return  array or bool
	*/
	function getUserByEmail( $email , $user_name)
	{
		global $conn, $feedback;
		$email = mysql_escape_string( htmlspecialchars( trim( $email ) ) );
		$user_name = mysql_escape_string( htmlspecialchars( trim( $user_name ) ) );
        if( strlen( $user_name )) 
        {
            $sql = "select u.* from `users` as u where u.status != 'D'  and u.user_name = '{$user_name}' ";
            $user = $conn->GetRow($sql);
            if (!empty($user)) {
                if ($email == $user['email']) {
                    return $user;
                } else {
                    $feedback='The username and email are not match, please check your inpurt';
                    return false;
                }
            }
        }
       $feedback='This user is not existed';
        return false;
	}

	/**
	 *send password reminder email to user
	 *@param array:user name, password, email...
	 *
	 */
	 function sendPasswordReminderToUser( $user )
	{
		 global $feedback, $mailer_param;
		 $subject = 'Your Login Information';
		 $host = "http://" . $_SERVER['HTTP_HOST'];
		 $content = "<div>".
							"<a href='" . $host . "' >" . $host . "</a><br /><br />";
         $content .="<table >";
         $content .="<tr >";
         $content .="<th >User Name</th >";
         $content .="<th >Password</th >";
         $content .="</tr >";
         $content .="<tr>";
        $content .= "<td>&nbsp;{$user['user_name']}&nbsp;</td>".
                            "<td>&nbsp;{$user['user_pw']}&nbsp;</td>";
        if( strlen( $address )==0 )
            $address = $user['email'];
        $content .="</tr>";
        $content .="</table>";
        $content .= "</div>";
		 
		 if( send_smtp_mail($address, $subject, $content, $mailer_param) ) {
			 $feedback = "Password Reminder Email Send Success";
			 return true;
		} else {
			$feedback = "Password Reminder Email Send Failed\nPelase try again";
			return false;
		}
		
	}

    /**
     * get all user and user's information
     *
     * @param array $mode
     * @param array $user_type
     * @param array $show_working_on_count
     *
     * @return array
     */
    function getAllUsers($mode = 'all_infos', $user_type = 'all', $show_working_on_count = true)
    {
        global $conn;
       $qws = array("u.status != 'D'");
       if ($user_type == 'all_editor') {
           $qws[] = 'u.permission >= 3';
       } else if ($user_type != 'all'){
           $qws[] = "u.role = '" . $user_type . "' ";
       }
       $qw = implode(" AND ", $qws) . ' ';

        if ($mode == 'all_infos') {
            $q = "SELECT * FROM users AS u WHERE ".$qw."ORDER BY u.user_id";
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
                $users = array();
                while (!$rs->EOF) 
				{
                    $users[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
                return $users;
            }
            return null;
        } else if ($mode == 'username_email_only') {
            $q = "SELECT user_name, email FROM users as u WHERE ".$qw."ORDER BY u.user_id ASC";
            $rs = &$conn->Execute($q);
            if ($rs) {
                $users = array();
                while (!$rs->EOF) {
                    $users[$rs->fields['user_name']] = $rs->fields['email'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $users;
            } else {
                return null;
            }
        } else if ($mode == 'id_email_only') {
            $q = "SELECT user_id, email FROM users as u WHERE ".$qw."ORDER BY u.user_id ASC";
            $rs = &$conn->Execute($q);
            if ($rs) {
                $users = array();
                while (!$rs->EOF) {
                    $users[$rs->fields['user_id']] = $rs->fields['email'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $users;
            } else {
                return null;
            }
        } else {
            if ($mode != 'id_active_only') {
                $q = "SELECT user_id, user_name, pay_level FROM users as u WHERE ".$qw."ORDER BY u.user_name ASC";
                $rs = &$conn->Execute($q);
                if ($rs) {
                    $users = array();
                    while (!$rs->EOF) {
                        if ($show_working_on_count == true) {
                            $suff = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0";
                            if ($user_type == 'copy writer' || $user_type == 'designer') {
                                $suff = '[' . $rs->fields['pay_level'] . ']'. $suff;
                            }                            
                        } else {
                            $suff = '';
                        }
                        $users[$rs->fields['user_id']] = $rs->fields['user_name'].$suff;
                        $rs->MoveNext();
                    }
                    $rs->Close();
                } else {
                    return null;
                }
            }
            if ($show_working_on_count == true) {
                $having_w = '';
                if ($user_type != 'designer')  {
                    $left_w = "  AND ck.status!='D' ";
                    if ($user_type == 'copy writer') {
                        $field = 'ck.copy_writer_id';
                        $left_w .= ' AND (ar.article_status =0 OR ar.article_status = 2) ';
                    } else  {
                        $field = 'ck.editor_id';
                        $left_w .= ' AND (ar.article_status =0 OR ar.article_status = 1 OR ar.article_status =\'1gd\' OR ar.article_status = \'1gc\' OR ar.article_status = 3) ';
                    }
                    // $having_w = ' HAVING ' . $left_w;
                   if ($mode == 'id_active_only') {
                        $having_w .= ' HAVING count > 0 ';
                   }

                   $q = "SELECT u.user_id, u.user_name, u.pay_level, u.role, COUNT(ck.keyword_id) AS count ".
                     "FROM users AS u ".
                     "LEFT JOIN campaign_keyword AS ck ON ({$field} = u.user_id)  ". 
                     "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) " . 
                     " WHERE " . $qw . $left_w. 
                     " GROUP BY {$field} {$having_w} ORDER BY u.user_name ASC";

                    $rs = &$conn->Execute($q);
                    if ($rs) {
                        while (!$rs->EOF) {
                            $username = $rs->fields['user_name'];
                            if ($user_type == 'copy writer') {
                                $username .= '[' . $rs->fields['pay_level'].  ']';
                            }
                            $username .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$rs->fields['count'];
                            $users[$rs->fields['user_id']]  = $username;
                            $rs->MoveNext();
                        }
                        $rs->Close();
                    }
                }
                if ($user_type != 'copy writer') {
                    $left_w = "  AND ik.status!='D' ";
                    if ($user_type == 'designer') {
                        $field = 'ik.copy_writer_id';
                        $left_w .= ' AND (im.image_status =0 OR im.image_status = 2) ';
                    } else {
                        $field = 'ik.editor_id';
                        $left_w .= ' AND (im.image_status =0 OR im.image_status = 1 OR im.image_status = 3) ';
                    }

                    // $having_w = ' HAVING ' . $left_w;
                   if ($mode == 'id_active_only') {
                        $having_w .= ' HAVING count > 0 ';
                   }

                   $q = "SELECT u.user_id, u.user_name, u.pay_level, u.role, COUNT(ik.keyword_id) AS count ".
                     "FROM users AS u ".
                     "LEFT JOIN image_keyword AS ik ON ({$field} = u.user_id)  ". 
                     "LEFT JOIN images AS im ON (ik.keyword_id = im.keyword_id) " . 
                     " WHERE " . $qw . $left_w. 
                     " GROUP BY {$field} {$having_w} ORDER BY u.user_name ASC";

                    $rs = &$conn->Execute($q);
                    if ($rs) {
                        while (!$rs->EOF) {
                            $username = $rs->fields['user_name'];
                            if ($user_type == 'designer') {
                                $username .= '[' . $rs->fields['pay_level'].  ']';
                            } else {
                                $username = $users[$rs->fields['user_id']];
                            }
                            $username .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$rs->fields['count'];
                            $users[$rs->fields['user_id']]  = $username;
                            $rs->MoveNext();
                        }
                        $rs->Close();
                    }
                }
                if ($user_type == 'copy writer') {
                    $now = time();
                    $today = date("Y-m-d");
                    $due27 = date("Y-m-d", ($now+604800));// 7 days later
                    $left_w .= " AND (ck.date_end>='" . $today. "' AND ck.date_end<='" . $due27. "')";
                    $q = "SELECT u.user_id, COUNT(ck.keyword_id) AS count ".
                     "FROM users AS u ".
                     "LEFT JOIN campaign_keyword AS ck ON ({$field} = u.user_id)  ". 
                     "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) " . 
                     " WHERE " . $qw . $left_w. 
                     " GROUP BY {$field} {$having_w} ORDER BY u.user_name ASC";
                    $list = array();
                    $rs = &$conn->Execute($q);
                    if ($rs) {
                        while (!$rs->EOF) {
                            $list[$rs->fields['user_id']] = $rs->fields['count'];
                            $rs->MoveNext();
                        }
                        $rs->Close();
                    }
                    foreach ($users as $k => $v) {
                        $users[$k] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . (isset($list[$k])?$list[$k]:'0');
                    }
                }
            }

            return $users;
        }
    }// end getAllUsers()


    function getUserList($mode = 'username_email_only', $user_type ='editor', $param = array())
    {
        global $conn;
        $conditions = array();
        if (!empty($user_type)) {
            $conditions[] = 'u.role=\'' . $user_type .'\'';
        }
        $login_permission = User::getPermission();
        $sql = 'SELECT DISTINCT u.user_id, u.user_name as name ';
        $sql .= 'FROM users AS u ';
        if ($login_permission == 1 || $login_permission == 3) {
           if ( $user_type == 'copy writer') {
               $sql .= 'LEFT JOIN campaign_keyword AS ck ON ck.copy_writer_id=u.user_id ';
            } else {
               $sql .= 'LEFT JOIN campaign_keyword AS ck ON ck.editor_id=u.user_id ';
            }
        }
        $login_user_id = User::getID();
        if ($login_permission < 4) {
           switch($login_permission) {
           case 1:
               $conditions[] = 'ck.copy_writer_id=' . $login_user_id;
               break;
           case 2:
               $sql .= "LEFT JOIN client AS cl ON (cl.project_manager_id=u.user_id) ";
               $conditions[] = 'cl.project_manager_id=' . $login_user_id;
               break;
           case 3:
               $conditions[] = 'ck.editor_id=' . $login_user_id;
               break;
           }
        }
        $sql .= (!empty($conditions)) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $result = $conn->GetAll($sql);
        $list = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $list[$row['user_id']] = $row['name'];
            }
        }
        return $list;
    }

    
    // added by snug xu 2007-05-29 10:24 - STARTED
    /**
     * get all user and user's information
     *
     * @param array $mode
     * @param array $user_ids
     *
     * @return array
     */
    function getAllUsersByUserIDs($mode = 'all_infos', $user_ids)
    {
        global $conn;
        if (count($user_ids) || !empty($user_ids)) {
            if (is_array($user_ids)) {
                $qw .= " AND user_id IN (" . implode(",", $user_ids) . ")";
            } else $qw .=" AND user_id=" . $user_ids . " ";
        }
        if ($mode == 'all_infos') {
            $q = "SELECT * FROM users WHERE status != 'D' ".$qw."ORDER BY user_id";
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
                $users = array();
                while (!$rs->EOF) 
				{
                    $users[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
                return $users;
            }
            return null;
        } else {
            if ($mode == 'id_email_only') {
                $q = "SELECT user_id, email FROM users WHERE status != 'D' ".$qw."ORDER BY email ASC";
                $field = 'email';
            } else {
                $q = "SELECT user_id, user_name FROM users WHERE status != 'D' ".$qw."ORDER BY user_name ASC";
                $field = 'user_name';
            }
            $rs = &$conn->Execute($q);
            if ($rs) {
                $users = array();
                while (!$rs->EOF) {
                    $users[$rs->fields['user_id']] = $rs->fields[$field];
                    $rs->MoveNext();
                }
                $rs->Close();
            } else {
                return null;
            }
            return $users;
        }
    }// end getAllUsersByUserIDs()
    // added by snug xu 2007-05-29 10:24 - FINISHED

	//added by snug 21:06 2006-07-30
	function getAllEditorsByCampaignID( $campaign_id, $qw)
	{
		global $conn, $feedback;
		$sql = "SELECT u.* FROM campaign_keyword AS ck, users AS u, articles AS ar WHERE u.user_id=ck.editor_id  AND ck.campaign_id={$campaign_id}  AND u.status != 'D' AND  ck.status='A'  " . $qw . " GROUP BY ck.editor_id";
        return $conn->GetAll($sql);
	}
	//end

    /**
     * get all user and user's information
     *
     * @param array $mode
     *
     * @return array
     */
    function getAllCPAssignment($user_type = 'all', $perpage = '50', $campaign_id = 0)
    {
        global $conn;
        global $g_pager_params;

		if ($user_type == 'all') {
			$qw = '';
		} else {
			$qw = "AND role = '".$user_type."' ";
		}

        $rs = &$conn->Execute("SELECT COUNT(user_id) AS count FROM users WHERE status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($perpage) > 0) {
            //这里的perpage不能变成
            //$perpage = $_GET['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT * FROM users WHERE status != 'D' ".$qw."ORDER BY user_name ASC";
//ADD BY cxz 2006-8-3 11:20上午
        $q = "SELECT u.*, s.time FROM users AS u ".
             "LEFT JOIN session AS s ON (s.user_id = u.user_id) ".
             "WHERE u.status != 'D' ".$qw."ORDER BY u.user_name ASC";
//ADD END
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        //$rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $users[$rs->fields['user_id']] = $rs->fields;
                if ($user_type == 'copy writer') {
                    $user_id = $rs->fields['user_id'];
                    $users[$rs->fields['user_id']]['count'] = 0;
                    $users[$rs->fields['user_id']]['assigned_count'] = 0;
                    if ($user_id > 0) $user_id_arr[] = $user_id;
                }
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        $qw = "";
        if ($user_type == 'copy writer' && $campaign_id > 0) {
            $qw = "AND ck.campaign_id = '".$campaign_id."' ";
        }
        $qw .= " AND  ck.status!='D' ";

        if ($user_type == 'copy writer') {//copy writer should take him how many articles be pending
		//START:modified by snug 23:14 2006-08-04
            $q = "SELECT u.user_id, u.user_name,  COUNT(ck.keyword_id) AS count, ck.campaign_id, cc.campaign_name ".
                 "FROM users AS u ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id) ".
                 "LEFT JOIN client_campaigns  AS cc ON (cc.campaign_id  = ck.campaign_id ) ".
                 "WHERE u.status != 'D' AND u.role = '".$user_type."' ".
                 "AND u.user_id IN (".implode(",", $user_id_arr).") ".
                 "AND ck.keyword_id IN (SELECT keyword_id FROM articles ".
                                            "WHERE (article_status = 2 OR (article_status =0 )) ".
                                            "AND ck.keyword_id = keyword_id) ".$qw.
                 "GROUP BY ck.copy_writer_id, ck.campaign_id ORDER BY u.user_id ASC";
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
                while (!$rs->EOF) 
				{
                    $pending_articles[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
				$is_same = false;
				for($i=0;$i<count( $pending_articles );$i++)
				{
					$campaign_id = $pending_articles[$i]['campaign_id'];
					$current_key = $pending_articles[$i]['user_id'];
					if( $i===0 ) {						
						$users[$current_key][$campaign_id]=$pending_articles[$i];
					}
					else
					{
						$last_key = $pending_articles[$i-1]['user_id'];
						if( $current_key==$last_key )
						{
							$users[$last_key]['campaigns'][$campaign_id]=$pending_articles[$i];
						}
						else
						{
							$users[$current_key]['campaigns'][$campaign_id]=$pending_articles[$i];
						}
					}
				}
                //return $users;
            };
			//END modified
			//START:added by snug 23:18 2006-08-04
			/**total articles completed up to date**/
			$q = "SELECT u.user_id,  COUNT(ck.keyword_id) AS count ".
                 "FROM users AS u ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id) ".
                 "WHERE u.status != 'D' AND u.role = '".$user_type."' ".
                 "AND u.user_id IN (".implode(",", $user_id_arr).") ".
                 "AND ck.keyword_id IN (SELECT keyword_id FROM articles ".
                                            "WHERE article_status !='0' AND article_status !='1' ".
                                            "AND ck.keyword_id = keyword_id) ".$qw.
                 "GROUP BY ck.copy_writer_id ORDER BY u.user_id ASC";
			 $rs = &$conn->Execute($q);
            if ($rs) 
			{
                while (!$rs->EOF) 
				{
                    $users[$rs->fields['user_id']]['completed_count'] = $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
                //return $users;
            }
			/**total articles completed this month**/
			$today = date("Y-m-d H:i:s");
			$month_start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , 1, date("Y")));
			$q = "SELECT u.user_id,  COUNT(ck.keyword_id) AS count ".
                 "FROM users AS u ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id) ".
                 "WHERE u.status != 'D' AND u.role = '".$user_type."' ".
                 "AND u.user_id IN (".implode(",", $user_id_arr).") ".
                 "AND ck.keyword_id IN (SELECT keyword_id FROM articles ".
                                            "WHERE ( article_status !='0' AND article_status !='1' ) ".
                                            "AND  (`google_approved_time`<='$today' AND `google_approved_time`>'$month_start')  ".
                                            "AND ck.keyword_id = keyword_id) ".$qw.
                 "GROUP BY ck.copy_writer_id ORDER BY u.user_id ASC";
			$rs = &$conn->Execute($q);
            if ($rs) 
			{
                while (!$rs->EOF) 
				{
                    $users[$rs->fields['user_id']]['month_completed_count'] = $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
                //return $users;
            }
			//END ADDED

            $q = "SELECT u.user_id, COUNT(ck.keyword_id) AS count ".
                 "FROM users AS u ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id) ".
                 "WHERE u.status != 'D' AND u.role = '".$user_type."' ".
                 "AND u.user_id IN (".implode(",", $user_id_arr).") ".$qw.
                 "GROUP BY ck.copy_writer_id ORDER BY u.user_name ASC";
            $rs = &$conn->Execute($q);
            if ($rs) {
                while (!$rs->EOF) {
                    $users[$rs->fields['user_id']]['assigned_count'] = $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
                //return $users;
            }

            //current assignment
            $q = "SELECT u.user_id, COUNT(ck.keyword_id) AS count, ck.campaign_id, cc.campaign_name ".
                 "FROM users AS u ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
                 "WHERE u.status != 'D' AND u.role = '".$user_type."' ".
                 "AND u.user_id IN (".implode(",", $user_id_arr).") AND u.role = '".$user_type."' AND  ck.status!='D' ".
                 //"AND ck.date_end = (SELECT MAX(date_end) FROM campaign_keyword ".
                                    //"WHERE copy_writer_id = ck.copy_writer_id AND role= '".$user_type."') ".
                 "GROUP BY ck.copy_writer_id ORDER BY u.user_name ASC";
            $rs = &$conn->Execute($q);
            if ($rs) {
                while (!$rs->EOF) {
                    $users[$rs->fields['user_id']]['current_assignment_count'] = $rs->fields['count'];
                    $users[$rs->fields['user_id']]['current_assignment'] = $rs->fields['campaign_name'];
                    $users[$rs->fields['user_id']]['campaign_id'] = $rs->fields['campaign_id'];
                    $rs->MoveNext();
                }
                $rs->Close();
                //return $users;
            }
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
        //return $users;
    }// end getAllCPAssignment()


    /**
     * get all user and user's pruduct reportinformation
     *
     * @param array $mode
     *
     * @return array
     */
    function getUserProductReport($user_type = 'all', $perpage = '50', $p = array())
    {
        global $conn;
        global $g_pager_params;
        $campaign_id = isset($p['campaign_id']) ? $p['campaign_id'] : 0;
		if ($user_type == 'all') {
			$qw = '';
		} else if ($user_type == 'all editor') {
            $qw = "AND (role = 'admin'  or role = 'project manager' or role = 'editor') ";
		} else {
			$qw = "AND role = '".$user_type."' ";
		}
        if ($campaign_id > 0) {
            $qw .= "AND ck.campaign_id = '".$campaign_id."' ";
        }
        
        $left = '';
        if ($user_type == 'all editor') {
            $left = ' LEFT JOIN campaign_keyword AS ck ON (ck.editor_id = u.user_id) ';
            $qw .= " AND  ck.status!='D'  ";
        } else if ($user_type == 'copy writer') {
            $left = ' LEFT JOIN campaign_keyword AS ck ON (ck.copy_writer_id = u.user_id)';
            $qw .= " AND  ck.status!='D'  ";
        }
        
        $rs = &$conn->Execute("SELECT COUNT(DISTINCT user_id) AS count FROM users as u " . $left. "  WHERE u.status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }
        // if $perpage is less than 0, it's no pagination.
        $is_pagination = strlen($perpage) && $perpage < 0 ? false :true;

        $q = "SELECT DISTINCT u.*, s.time FROM users AS u ".
             "LEFT JOIN session AS s ON (s.user_id = u.user_id) ". $left . 
             "WHERE u.status != 'D' ".$qw."ORDER BY u.user_name ASC";
        if ($is_pagination) {
            if (empty($perpage)) $perpage= 50;
            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        } else {
            $rs = &$conn->Execute($q);
        }
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $users[$rs->fields['user_id']] = $rs->fields;
                $users[$rs->fields['user_id']]['total_camp'] = 0;
                $users[$rs->fields['user_id']]['total'] = 0;
                $users[$rs->fields['user_id']]['total_assigned'] = 0;
                if ($user_type == 'copy writer') {
                    $users[$rs->fields['user_id']]['total_submit'] = 0;
                    $users[$rs->fields['user_id']]['pct_total_submit'] = '0%';
                } else {
                    $users[$rs->fields['user_id']]['total_pending_approval'] = 0;
                    $users[$rs->fields['user_id']]['pct_total_pending_approval'] = '0%';
                }
                $users[$rs->fields['user_id']]['total_editor_approval'] = 0;
                $users[$rs->fields['user_id']]['pct_total_editor_approval'] = '0%';
                $users[$rs->fields['user_id']]['total_client_approval'] = 0;
                $users[$rs->fields['user_id']]['pct_total_client_approval'] = '0%';
                $user_id_arr[] = $rs->fields['user_id'];
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        $qw = "";
        if ($campaign_id > 0) {
            $qw = "AND ck.campaign_id = '".$campaign_id."' ";
        }
        $user_field_name = ($user_type == 'copy writer') ? 'ck.copy_writer_id':'ck.editor_id';
        $qw .= "AND " .$user_field_name. " IN (".implode(",", $user_id_arr).") ";

        // get total campaign for each user
        $q  = "SELECT COUNT(DISTINCT ck.campaign_id) AS total_camp, " .$user_field_name. " AS user_id " ;
        $q .= "FROM campaign_keyword AS ck ";
        $q .= "LEFT JOIN articles  AS ar ON (ar.keyword_id  = ck.keyword_id ) ";
        $q .= 'WHERE 1 '  . $qw . " AND  ck.status!='D'  ";
        $q .= 'GROUP BY ' . $user_field_name;
        $rs = &$conn->Execute($q);
        if ($rs) {
            while (!$rs->EOF) {
                $users[$rs->fields['user_id']]['total_camp'] = $rs->fields['total_camp'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        // get total assigned keyword for each user
        $where = $qw . self::generateDateConditions($p, 'ck.date_assigned');
        self::getCountGroupByUsers($users, 'total', $user_field_name, $where);
        // get total submit keyword for each user
        $where = $qw . "AND ar.article_status REGEXP '^(1|1gc|3|4|5|6|99)$'" . self::generateDateConditions($p, 'ar.cp_updated');
        self::getCountGroupByUsers($users, 'total_submit', $user_field_name, $where);
        $where = $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'" . self::generateDateConditions($p, 'ar.approval_date');
        self::getCountGroupByUsers($users, 'total_editor_approval', $user_field_name, $where);
        $where = $qw . "AND ar.article_status REGEXP '^(5|6|99)$'" . self::generateDateConditions($p, 'ar.client_approval_date');
        self::getCountGroupByUsers($users, 'total_client_approval', $user_field_name, $where);
        if ($user_type == 'all editor' || $user_type == 'editor' || $user_type == 'admin' || $user_type == 'project manager') {
            $where = $qw . "AND ar.article_status REGEXP '^(1gc|3)$'" . self::generateDateConditions($p, 'ar.google_approved_time');
            self::getCountGroupByUsers($users, 'total_pending_approval', $user_field_name, $where);
        }
        if ($is_pagination) {
            $result = array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
        } else {
            $result = $users;
        }
        return $result;
    }// end getUserProductReport()

    function generateDateConditions($p = array(), $field)
    {
        $qw = '';
        if (isset($p['date_start']) && !empty($p['date_start'])) {
            $date_start = $p['date_start'] . ' 00:00:00';
            $qw .= ' AND '.  $field . '>=\'' . $date_start . '\'';
        }
        if (isset($p['date_end']) && !empty($p['date_end'])) {
            $date_end = $p['date_end'] . ' 23:59:59';
            $qw .= ' AND '.  $field . '<=\'' . $date_end . '\'';
        }

        return $qw;
    }

    function getCampaignReportByUser($user_id, $user_type, $p)
    {
        global $conn;
		if ($user_type == 'all') {
			$qw = '';
		} else if ($user_type == 'all editor') {
            $qw = "AND (u.role = 'admin'  OR u.role = 'project manager' OR u.role = 'editor') ";
		} else {
			$qw = "AND u.role = '".$user_type."' ";
		}
        $qw .= ' AND u.user_id=' . $user_id .' ';
        $campaign_id = isset($p['campaign_id']) ? $p['campaign_id'] : 0;
        if ($campaign_id > 0) {
            $qw .= "AND ck.campaign_id = '".$campaign_id."' ";
        }
        $qw .= " AND  ck.status!='D'  "; 
        $user_field_name = ($user_type == 'copy writer') ? 'ck.copy_writer_id':'ck.editor_id';
        $q  = "SELECT DISTINCT cc.campaign_id, cc.campaign_name " ;
        $q .= "FROM campaign_keyword AS ck ";
        $q .= "LEFT JOIN users AS u ON (u.user_id = " . $user_field_name . ") ";
        $q .= "LEFT JOIN articles  AS ar ON (ar.keyword_id  = ck.keyword_id ) ";
        $q .= "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id  = ck.campaign_id ) ";
        $q .= 'WHERE 1 '  . $qw;
        $rs = &$conn->Execute($q);
        $users = array();
        if ($rs) {
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $fields['total'] = 0;
                $fields['total_submit'] = 0;
                $fields['total'] = 0;
                $fields['total_assigned'] = 0;
                if ($user_type == 'copy writer') {
                    $fields['total_submit'] = 0;
                    $fields['pct_total_submit'] = '0%';
                } else {
                    $fields['total_pending_approval'] = 0;
                    $fields['pct_total_pending_approval'] = '0%';
                }
                $fields['total_editor_approval'] = 0;
                $fields['pct_total_editor_approval'] = '0%';
                $fields['total_client_approval'] = 0;
                $fields['pct_total_client_approval'] = '0%';
                $users[$user_id.$rs->fields['campaign_id']] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        // get total assigned keyword for each user
        
        $where = $qw . self::generateDateConditions($p, 'ck.date_assigned');
        self::getCountGroupByUsers($users, 'total', $user_field_name, $where, 'ck.campaign_id', 'campaign_id');
        // get total submit keyword for each user
        $where = $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'" . self::generateDateConditions($p, 'ar.approval_date');
        self::getCountGroupByUsers($users, 'total_editor_approval', $user_field_name, $where, 'ck.campaign_id', 'campaign_id');
        $where = $qw . "AND ar.article_status REGEXP '^(5|6|99)$'" . self::generateDateConditions($p, 'ar.client_approval_date');
        self::getCountGroupByUsers($users, 'total_client_approval', $user_field_name, $where, 'ck.campaign_id', 'campaign_id');
        if ($user_type == 'all editor' || $user_type == 'editor' || $user_type == 'admin' || $user_type == 'project manager') {
            $where = $qw . "AND ar.article_status REGEXP '^(1gc|3)$'" . self::generateDateConditions($p, 'ar.google_approved_time');
            self::getCountGroupByUsers($users, 'total_pending_approval', $user_field_name, $where, 'ck.campaign_id', 'campaign_id');
        } else {
            $where = $qw . "AND ar.article_status REGEXP '^(1|1gc|3|4|5|6|99)$'" . self::generateDateConditions($p, 'ar.cp_updated');
            self::getCountGroupByUsers($users, 'total_submit', $user_field_name, $where, 'ck.campaign_id', 'campaign_id');
        }
        return $users;
    }
    
    /**
     * @param $result  array
     * @param $field string
     * @param $user_field_name string
     * @param $qw string
     * @param $group_by string
     */
    function getCountGroupByUsers(&$result, $field = 'total_submit', $user_field_name, $qw = '', $group_by='', $g_field='')
    {
        global $conn;
        if (!empty( $group_by)) $group_by = ','. $group_by;
        $qw .= " AND  ck.status!='D'  "; 
        $query  = "SELECT COUNT( ar.article_id )  as " .$field.", u.user_id " . $group_by . "\n";
        $query .= "FROM users AS u \n";
        $query .= "LEFT JOIN campaign_keyword AS ck on (". $user_field_name ." = u.user_id) \n";
        $query .= "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= ' WHERE  1 ';
        $query .= $qw . "\n";
        $query .= "GROUP BY  " . $user_field_name . ' ' . $group_by ;
        $rs = &$conn->Execute($query);
        if ($rs) {
            while (!$rs->EOF) {
                if (!empty($group_by)) {
                    if (!empty($g_field)) {
                        $k = $rs->fields['user_id'].$rs->fields[$g_field];
                        $result[$k][$field] = $rs->fields[$field];
                        if ($field != 'total') {
                            $total = $result[$k]['total'];
                            $result[$k]['pct_' . $field] = calculate_percentage($total, $rs->fields[$field]);
                        }
                    } else {
                        $result[][$field] = $rs->fields[$field];
                    }
                } else {
                    $user_id =$rs->fields['user_id'];
                    $result[$user_id][$field] =  $rs->fields[$field];
                    if ($field != 'total') {
                        $total = $result[$user_id]['total'];;
                        $result[$user_id]['pct_' . $field] =   calculate_percentage($total, $rs->fields[$field]);
                    }
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
    }



    /**
     *function get whole fields data 
     *@param $p array
     *       $p['ranking_id']
     *       $p['copywriter_id']
     *       $p['campaign_id']
     *       $p['limit']
     *@return $r(array)/null
     */
    function getAllCpPerformanceReport($p = array()) {//getAllCpPerformanceReport
        global $conn;
        global $g_pager_params;

        $qw = "AND role = 'copy writer' ";
        if (isset($p['search_keyword']) && !empty($p['search_keyword'])) {
            $search_keyword = $p['search_keyword'];
            $qw .= ' AND (user_name LIKE \'%' . $search_keyword . '%\' OR CONCAT(first_name, \' \', last_name) like \'%' . $search_keyword. '%\' ) ';
        }
        $rs = &$conn->Execute("SELECT COUNT(user_id) AS count FROM users  as u WHERE status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count == 0 || !isset($count)) {
            return false;
        }
        if (trim($p['limit']['number']) > 0) {
            $perpage= $p['limit']['number'];
        } else {
            $perpage= 50;
        }
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT u.*, s.time FROM users AS u ".
             "LEFT JOIN session AS s ON (s.user_id = u.user_id) ".
             "WHERE u.status != 'D' ".$qw."ORDER BY u.user_name ASC";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));

        if ($rs) {
            $user_id_arr = $users = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
                $users[$user_id]['total_camp'] = 0;
                $users[$user_id]['total'] = 0;
                $users[$user_id]['total_editor_approval'] = 0;
                $users[$user_id]['pct_total_editor_approval'] = '0%';
                $users[$user_id]['total_client_approval'] = 0;
                $users[$user_id]['pct_total_client_approval'] = '0%';
                $users[$user_id]['readability'] = 0;
                $users[$user_id]['informational_quality'] = 0;
                $users[$user_id]['timeliness'] = 0;
                $users[$user_id]['ranking'] = 0;
                $user_id_arr[] = $rs->fields['user_id'];
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        // get total assigned keyword for each user
        $user_field_name = 'ck.copy_writer_id';
        $qw = "AND " .$user_field_name. " IN (".implode(",", $user_id_arr).") ";
        self::getCountGroupByUsers($users, 'total', $user_field_name, $qw);
        self::getCountGroupByUsers($users, 'total_editor_approval', $user_field_name, $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'");
        self::getCountGroupByUsers($users, 'total_client_approval', $user_field_name, $qw . "AND ar.article_status REGEXP '^(5|6|99)$'");
        $campaigns = array();
        self::getCountGroupByUsers($campaigns, 'total', $user_field_name, $qw, 'ck.campaign_id', 'campaign_id');
        
        $qw = array();
        $qw[] = " WHERE copywriter_id " . " IN (".implode(",", $user_id_arr).") ";
        
        if ( isset($p['ranking_id']) && !empty($p['ranking_id']) ) {
            $qw[] = " ranking_id=" . trim($p['ranking_id']);
        }
        
        if ( isset($p['copywriter_id']) && !empty($p['copywriter_id']) ) {
            $qw[] = " copywriter_id=" . trim($p['copywriter_id']);
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }
        
        $sql = " SELECT * FROM cp_campaign_ranking ";
        
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['copywriter_id'];
                $campaign_id = $rs->fields['campaign_id'];
                $users[$user_id]['readability'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['readability'];
                $users[$user_id]['informational_quality'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['informational_quality'];
                $users[$user_id]['timeliness'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['timeliness'];
                $users[$user_id]['ranking'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['ranking'];
                $rs->MoveNext();
            }
        }

        foreach ($users as $k => $row) {
            if ($row['total'] > 0) {
                $users[$k]['readability'] = round($row['readability']/$row['total'], 2);
                $users[$k]['informational_quality'] = round($row['informational_quality']/$row['total'], 2);
                $users[$k]['timeliness'] = round($row['timeliness']/$row['total'], 2);
                $users[$k]['ranking'] = round($row['ranking']/$row['total'], 2);
            } else {
                $users[$k]['readability'] = $users[$k]['informational_quality'] = $users[$k]['timeliness'] = $users[$k]['ranking'] = 0;
            }
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $users);
    }//function getAllCpPerformanceReport END

    function getMCpUpdated($conditions, $field='MIN(ar.cp_updated)')
    {
        global $conn;
        $sql  = "SELECT {$field}FROM articles AS ar ";
        $sql .= "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ";
        $sql .= " WHERE " . implode(" AND ", $conditions) . ' AND ar.cp_updated IS NOT NULL';
        return $conn->GetOne($sql);
    }

    function getAllUserInfo($p = array())
    {
        global $conn;
        $role = isset($p['role']) ? $p['role'] : 'copy writer';
        $qw = "AND role = '{$role}' ";
        $count = $conn->GetOne("SELECT COUNT(user_id) AS count FROM users  as u WHERE status != 'D' " . $qw);
        if ($count == 0 || !isset($count)) {
            return false;
        }

        $q = "SELECT user_id,user_name,first_name,last_name,email,role FROM users WHERE status != 'D' ".$qw."ORDER BY user_name ASC";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $user_id_arr = $users = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
                $users[$user_id]['editor_approved'] = 0;
                $users[$user_id]['pct_editor_approved'] = '0%';
                $users[$user_id]['client_approved'] = 0;
                $users[$user_id]['pct_client_approved'] = '0%';
                $users[$user_id]['readability'] = 0;
                $users[$user_id]['informational_quality'] = 0;
                $users[$user_id]['timeliness'] = 0;
                $users[$user_id]['ranking'] = 0;
                $rs->MoveNext();
            }
            $rs->Close();
            return $users;

        } else {
            return null;
        }
    }
    
    function getAllCpPerformanceByConditions($users,  $rankings,  $conditions = array())
    {
        global $conn;
        $user_field_name = 'ck.copy_writer_id';
        $user_id_arr = array_keys($users);
        
        // get total assigned keyword for each user
        
        if (!empty($conditions)) $qw = ' AND ' . implode(" AND ", $conditions);
        self::getCountGroupByUsers($users, 'total', $user_field_name, $qw);
        self::getCountGroupByUsers($users, 'editor_approved', $user_field_name, $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'");
        self::getCountGroupByUsers($users, 'client_approved', $user_field_name, $qw . "AND ar.article_status REGEXP '^(5|6|99)$'");
        $campaigns = array();

        self::getCountGroupByUsers($campaigns, 'total', $user_field_name, $qw, 'ck.campaign_id', 'campaign_id');
        
        foreach ($rankings as $fields) {
            $user_id = $fields['copywriter_id'];
            $campaign_id = $fields['campaign_id'];
            $users[$user_id]['readability'] += $campaigns[$user_id.$campaign_id]['total']*$fields['readability'];
            $users[$user_id]['informational_quality'] += $campaigns[$user_id.$campaign_id]['total']*$fields['informational_quality'];
            $users[$user_id]['timeliness'] += $campaigns[$user_id.$campaign_id]['total']*$fields['timeliness'];
            $users[$user_id]['ranking'] += $campaigns[$user_id.$campaign_id]['total']*$fields['ranking'];
        }

        foreach ($users as $k => $row) {
            if ($row['total'] > 0) {
                $users[$k]['readability'] = round($row['readability']/$row['total'], 2);
                $users[$k]['informational_quality'] = round($row['informational_quality']/$row['total'], 2);
                $users[$k]['timeliness'] = round($row['timeliness']/$row['total'], 2);
                $users[$k]['ranking'] = round($row['ranking']/$row['total'], 2);
            } else {
                unset($users[$k]);
                //$users[$k]['readability'] = $users[$k]['informational_quality'] = $users[$k]['timeliness'] = $users[$k]['ranking'] = 0;
            }
        }
        return $users;
    }

    function getAllCpPerformance($p = array()) {//getAllCpPerformanceReport
        global $conn;
        global $g_pager_params;

        $qw = "AND role = 'copy writer' ";
        $rs = &$conn->Execute("SELECT COUNT(user_id) AS count FROM users  as u WHERE status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count == 0 || !isset($count)) {
            return false;
        }

        $q = "SELECT user_id,user_name,first_name,last_name,email,role FROM users WHERE status != 'D' ".$qw."ORDER BY user_name ASC";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $user_id_arr = $users = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $users[$user_id] = $rs->fields;
                $users[$user_id]['editor_approved'] = 0;
                $users[$user_id]['pct_editor_approved'] = '0%';
                $users[$user_id]['client_approved'] = 0;
                $users[$user_id]['pct_client_approved'] = '0%';
                $users[$user_id]['readability'] = 0;
                $users[$user_id]['informational_quality'] = 0;
                $users[$user_id]['timeliness'] = 0;
                $users[$user_id]['ranking'] = 0;
                $user_id_arr[] = $rs->fields['user_id'];
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        // get total assigned keyword for each user
        $user_field_name = 'ck.copy_writer_id';
        $qw = "AND " .$user_field_name. " IN (".implode(",", $user_id_arr).") ";
        self::getCountGroupByUsers($users, 'total', $user_field_name, $qw);
        self::getCountGroupByUsers($users, 'editor_approved', $user_field_name, $qw . "AND ar.article_status REGEXP '^(4|5|6|99)$'");
        self::getCountGroupByUsers($users, 'client_approved', $user_field_name, $qw . "AND ar.article_status REGEXP '^(5|6|99)$'");
        $campaigns = array();
        self::getCountGroupByUsers($campaigns, 'total', $user_field_name, $qw, 'ck.campaign_id', 'campaign_id');
        
        $qw = array();
        $qw[] = " WHERE copywriter_id " . " IN (".implode(",", $user_id_arr).") ";
        
        if ( isset($p['ranking_id']) && !empty($p['ranking_id']) ) {
            $qw[] = " ranking_id=" . trim($p['ranking_id']);
        }
        
        if ( isset($p['copywriter_id']) && !empty($p['copywriter_id']) ) {
            $qw[] = " copywriter_id=" . trim($p['copywriter_id']);
        }
        
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $qw[] = " campaign_id=". trim($p['campaign_id']);
        }
        
        $sql = " SELECT * FROM cp_campaign_ranking ";
        
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $rs = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['copywriter_id'];
                $campaign_id = $rs->fields['campaign_id'];
                $users[$user_id]['readability'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['readability'];
                $users[$user_id]['informational_quality'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['informational_quality'];
                $users[$user_id]['timeliness'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['timeliness'];
                $users[$user_id]['ranking'] += $campaigns[$user_id.$campaign_id]['total']*$rs->fields['ranking'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        foreach ($users as $k => $row) {
            if ($row['total'] > 0) {
                $users[$k]['readability'] = round($row['readability']/$row['total'], 2);
                $users[$k]['informational_quality'] = round($row['informational_quality']/$row['total'], 2);
                $users[$k]['timeliness'] = round($row['timeliness']/$row['total'], 2);
                $users[$k]['ranking'] = round($row['ranking']/$row['total'], 2);
            } else {
                $users[$k]['readability'] = $users[$k]['informational_quality'] = $users[$k]['timeliness'] = $users[$k]['ranking'] = 0;
            }
        }
        return $users;
    }//function getAllCpPerformance END


    /**
     * get all editors' accounting
     * @param array $p
     * @return array  pagination navigation, total users and report info
     */
    function getUsersAccountingReport($p)
    {
        global $conn, $g_pager_params;

        $user_type = mysql_escape_string(htmlspecialchars(trim($p['user_type'])));
        $user_type = strlen($user_type) == 0 ? 'project manager' : $user_type;
        if ($user_type == 'all')  {
            $qw = '';
        } else {
            $qw = " AND `u`.`role` = '{$user_type}' ";
        }
        
        $rs = &$conn->Execute("SELECT COUNT(u.user_id) AS count FROM users AS u ".
                              "WHERE u.status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($p['perPage']) > 0) {
            //这里的perpage不能变成
            $perpage = $p['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        // get all project managers' info for each page
        $sql = "select u.*, 0 as  approval_num, 0 as rejected_num, 0 as total_num ";
        $sql .= "FROM `users` as u ";
        $sql .= "WHERE u.status != 'D' {$qw}";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) 
        {
            while(!$rs->EOF)
            {
                $users[$rs->fields['user_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
  
        // get report info of all project managers for each page
        $current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
        if (strlen($current_month)) 
		{
			$now = changeTimeFormatToTimestamp($current_month);
		}
		else
		{
			$now = time();
            $current_month = changeTimeToPayMonthFormat($now);
		}
        $next_month = date('m', $now) + 1;
		$month_end = date('Y-', $now) . $next_month . "-00 00:00:00";
		$month_start = date('Y-m', $now) . "-01 00:00:00";

        $aa_format = "  ( `aa`.`created_time` >= '%s' AND `aa`.`created_time` <= '%s' AND `aa`.`new_status` %s '%s' AND `aa`.`new_status` != `aa`.`status`) ";
        $status = '4';
        $operate = '=';
        $aa_qw = sprintf($aa_format, $month_start, $month_end, $operate, $status);


        // get user info sql format
        $sql_format = "SELECT %s ";
        $sql_format .= "\nFROM `users` AS u ";
        $sql_format .= "\nLEFT JOIN `article_action` AS `aa`  ON `aa`.`opt_id` = `u`.`user_id` AND `aa`.`opt_type`='0' ";
        $sql_format .= "\nLEFT JOIN `articles` AS `ar`  ON `aa`.`article_id` = `ar`.`article_id` ";
        $sql_format .= "\nWHERE `u`.`status` != 'D' %s AND %s  ";
        $sql_format .= "\nGROUP BY `u`.`user_id` ";
        $query = "`u`.`user_id`, COUNT(DISTINCT `ar`.`article_id`) AS approval_num ";
        $sql = sprintf($sql_format, $query, $qw, $aa_qw);

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));

        if ($rs) {
            while(!$rs->EOF)
            {
                $users[$rs->fields['user_id']]['approval_num']= $rs->fields['approval_num'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        $query = " `u`.`user_id`, COUNT(DISTINCT `ar`.`article_id`) AS rejected_num "; 
        $status = '2';
        $operate = '=';
        $aa_qw = sprintf($aa_format, $month_start, $month_end, $operate, $status);
        $sql = sprintf($sql_format, $query, $qw, $aa_qw);

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
            while(!$rs->EOF)
            {
                $users[$rs->fields['user_id']]['rejected_num']= $rs->fields['rejected_num'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        $query = " `u`.`user_id`, COUNT(DISTINCT `ar`.`article_id`) AS total_num "; 
        $status = '2|4';
        $operate = 'REGEXP';
        $aa_qw = sprintf( $aa_format, $month_start, $month_end, $operate, $status);
        $sql = sprintf($sql_format, $query, $qw, $aa_qw);

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
            while(!$rs->EOF)
            {
                $users[$rs->fields['user_id']]['total_num']= $rs->fields['total_num'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
             'total'  => $pager->numPages(),
             'count'  => $count,
             'result' => $users);
    }

    function getSearchKeyword($keyword)
    {
        if (trim($keyword) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $qw = "AND ".$search->getLikeCondition("CONCAT(u.user_name, u.first_name, u.last_name, u.email, u.address, u.phone, u.cell_phone, u.birthday, u.degree, u.role)")." ";
            }
        }
        return $qw;
    }
    /**
     * get all copy writer's accounting
     *
     * @param array $p
     *
     * @return array
     */
    function getAllAccountingReport($p)
    {
        global $conn, $g_pager_params, $g_tag, $feedback, $g_pay_per_month, $g_delay_days, $g_interval_days;
        $g_article_types = $g_tag['article_type'];
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month))  {
			$now = changeTimeFormatToTimestamp($current_month);
		} else {
			$now = getDelayTime(); //delay $g_delay_days days to show this month report
            $current_month = changeTimeToPayMonthFormat($now);
		}
        $param['batch_status'] = isset($p['batch_status']) ? $p['batch_status'] : 'all' ;
        $param['now']       = $now;
        $param['current_month'] = $current_month;
        $param['show_all'] = isset($p['show_all']) ? $p['show_all'] : true;

        $qw = "";
        $qw .= self::getSearchKeyword($p['keyword']);
        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';
        $roles = $g_tag['user_permission'];
        if (!in_array($user_type, $roles)) {
            $feedback = 'Invalid User Type, Please to check';
            return false;
        }
        if ($p['campaign_id'] > 0) {
            $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
        }
        if (!empty($user_type)) {
            $qw .= "\nAND u.role = '" . $user_type . "' ";
        }

        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D'";
        $param['qw_where'] = $qw;
        $param['type']    = 'multi-cp';
        $param['status'] = trim($p['status']);
        $sqls = self::getAccountingConditionOrSql($param, $user_type);
        $sql .= $sqls['user_count'];

        $rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($p['perPage']) > 0) {
            //这里的perpage不能变成
            $perpage = $p['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = $sqls['user'];
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if ($user_id > 0) {
                    if (isset($rs->fields['form_submitted']) && !empty($rs->fields['form_submitted'])) $rs->fields['form_submitted'] = explode("|", $rs->fields['form_submitted']);
                    $users[$user_id] = $rs->fields;
                    // initialize copywriter accouting report
                    $users[$user_id]['gct_count'] = 0;// total of Google clean this month
                    // initialize total of article type google clean in a month
                    foreach ($g_article_types as $key => $val) {
                        $users[$user_id][$key] = 0; 
                    }
                    $users[$user_id]['pay_gct_count'] = 0; // total should pay this month
                    $user_id_arr[] = $user_id;
                }
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        $sql = $sqls['type_count'];
        $rs  = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                //$key      = $rs->fields['article_type'];
                $key      = $rs->fields['at_parent_id'];
                if (isset($users[$user_id]))
                {
                    $users[$user_id][$key] += $rs->fields['count'];
                    $users[$user_id]['gct_count'] += $rs->fields['count'];
                    //$users[$user_id]['pay_gct_count'] = $users[$user_id]['gct_count'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }

        $sql = $sqls['paid_sql'];
        $rs  = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if (isset($users[$user_id])) {
                    $users[$user_id]['pay_gct_count'] = $rs->fields['count'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        $user_ids = array_keys($users);
        $param = array('user_id'=> $user_ids, 'user_type' => $user_type, 'current_month' => $current_month);
        $users = CpPaymentHistory::getPaymentMonths($param, $users);
        $qw = "\nAND u.role = '{$user_type}'";
        $qw .= "\nAND u.user_id IN " . implode(", ", $user_ids);
        $users = self::getArticleAmount($qw, $now, $users, $user_ids, $user_type, array('current_month' => $current_month));
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
        //return $users;
    }// end getAllAccountingReport()

    function getAllAccountingReportNoPagination($p)
    {
        global $conn;
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
		
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month)) {
			$now = changeTimeFormatToTimestamp($current_month);
		} else {
            $now = getDelayTime();
            $current_month = changeTimeToPayMonthFormat($now);
		}
        $param['now']       = $now;
        $param['current_month'] = $current_month;
        $param['batch_status'] = isset($p['batch_status']) ? $p['batch_status'] : 'all' ;
        $param['show_all'] = isset($p['show_all']) && !empty($p['show_all']) ?  true : false;
        $qw = "";
        $qw .= self::getSearchKeyword($p['keyword']);
        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';
        $qw .= "\nAND u.role = '".addslashes(htmlspecialchars(trim($user_type)))."' ";
        if ($p['campaign_id'] > 0) {
            $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
        }
        // let deleted copywriters show in accounting
        $param['qw_where'] = $qw;
        $param['type'] = 'export';
        $param['status'] = trim($p['status']);
        $param['current_month'] = $current_month;
        $sqls = self::getAccountingConditionOrSql($param, $user_type);
        $sql .= $sqls['user_count'];
        $rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $q = $sqls['user'];
        $pay_prefs = $g_tag['payment_preference'];
        $statuses = $g_tag['status'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $rs->fields['status'] = $statuses[$rs->fields['status']];
                $pay_pref = $pay_prefs[$rs->fields['pay_pref']];
                $payment_flow_status = $rs->fields['payment_flow_status'];
                unset($rs->fields['pay_pref']);
                unset($rs->fields['payment_flow_status']);
                $users[$user_id] = $rs->fields;
                // initialize copywriter accouting report
                // initialize total of article type google clean in a month
                foreach ($g_article_types as $key => $val) {
                    $users[$user_id][$val] = 0; 
                }
                $users[$user_id]['total'] = 0;// total of Google clean this month
                $users[$user_id]['pay_words_total'] = 0; // total should pay this month
                $users[$user_id]['pay_count_article'] = 0; // total article should pay this month
                if ($user_id > 0) $user_id_arr[] = $user_id;
                $users[$user_id]['pay_amount'] = 0;
                $users[$user_id]['payment_preference'] = $pay_pref;
                $users[$user_id]['payment_status'] = $payment_flow_status== 'paid' ? $payment_flow_status : 'Not Paid';
                $address =  $users[$user_id]['address'];
                $address = str_replace("\r\n", "\n", $address);
                $address = str_replace("\n", " ", $address);
                $users[$user_id]['address'] = $address;
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        $sql = $sqls['type_count'];
        $rs  = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $key      = $g_article_types[$rs->fields['at_parent_id']];
                if (isset($users[$user_id]))
                {
                    $users[$user_id][$key] = $rs->fields['count'];
                    $users[$user_id]['total'] += $rs->fields['count'];
                    $users[$user_id]['pay_words_total'] = $users[$user_id]['total'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        $sql = $sqls['paid_sql'];
        $rs  = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if (isset($users[$user_id]))
                {
                    $users[$user_id]['pay_words_total'] = $rs->fields['count'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        
        $user_ids = array_keys($users);
        $qw = "\nAND u.role = '{$user_type}'";
        $qw .= "\nAND u.user_id IN " . implode(", ", $user_ids);
        $users = self::getArticleAmount($qw, $now, $users, $user_ids, $user_type, array('current_month' => $current_month));
        return $users;
    }

    /**
     * get all copy writer's accounting
     *
     * @param array $p
     *
     * @return array
     */
    function getAllCPAccountingReport($p)
    {
        global $conn;
        global $g_pager_params;
        // added by snug xu 2007-05-21 11:14 - STARTED
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
        // added by snug xu 2007-05-21 11:14 - FINISHED
		
		// added by snug xu 2006-10-17 14:50 - STARTED
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month)) 
		{
			$now = changeTimeFormatToTimestamp($current_month);
		}
		else
		{
			$now = getDelayTime(); //delay 15 days to show this month report
            $current_month = changeTimeToPayMonthFormat($now);
		}
        $param['now']       = $now;
        $param['show_all'] = isset($p['show_all']) ? $p['show_all'] : true;
		// added by snug xu 2006-10-17 14:50 - FINISHED


        $qw = "";

        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw .= '';
		} else {
			$qw .= "\nAND u.role = '".addslashes(htmlspecialchars(trim($user_type)))."' ";
            if ($user_type == 'copy writer') {
                if ($p['campaign_id'] > 0) {
                    $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
                }
            }
		}
        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D'";
        $param['qw_where'] = $qw;
        $param['type']    = 'multi-cp';
        $param['status'] = trim($p['status']);
        $sqls = self::getCPAccountingConditionOrSql($param);
        $sql .= $sqls['user_count'];

        $rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($p['perPage']) > 0) {
            //这里的perpage不能变成
            $perpage = $p['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));


        if ($user_type == 'copy writer') {
            $q = $sqls['user'];
        } else {
            $q = "SELECT u.* FROM users AS u WHERE 1 " . $qw . "ORDER BY u.last_name ASC";
        }
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        //$rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if ($user_id > 0) {
                    if (isset($rs->fields['form_submitted']) && !empty($rs->fields['form_submitted'])) $rs->fields['form_submitted'] = explode("|", $rs->fields['form_submitted']);
                    $users[$user_id] = $rs->fields;
                    if ($user_type == 'copy writer') {
                        // initialize copywriter accouting report
                        $users[$user_id]['gct_count'] = 0;// total of Google clean this month
                        // added by snug xu 2007-05-21 11:16 - STARTED
                        // initialize total of article type google clean in a month
                        foreach ($g_article_types as $key => $val) {
                            $users[$user_id][$key] = 0; 
                        }
                        // added by snug xu 2007-05-21 11:16 - FINISHED
                        $users[$user_id]['pay_gct_count'] = 0; // total should pay this month
                        $user_id_arr[] = $user_id;
                    }
                }
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        if ($user_type == 'copy writer') {//copy writer should take him how many articles be pending
            $sql = $sqls['type_count'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    $key      = $rs->fields['article_type'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id][$key] = $rs->fields['count'];
                        $users[$user_id]['gct_count'] += $rs->fields['count'];
                        $users[$user_id]['pay_gct_count'] = $users[$user_id]['gct_count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            $sql = $sqls['ex_paycount'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id]['pay_gct_count'] = $users[$user_id]['gct_count'] - $rs->fields['count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }           

            $sql = $sqls['paid_sql'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id]['pay_gct_count'] = $rs->fields['count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            // modified by snug 2007-05-21 11:22 - STARTED
            // google clean article type report group by user 
            foreach ($users as $user_id => $info) {
				$p['month'] = $current_month;
                $p['payment_flow_status'] = $info['payment_flow_status'];
                // get article ids
                if (!empty($info['gct_count']))
                {
                    $article_id_arr = self::getArticleIdsByParam( $p, $user_id );
                    if( count( $article_id_arr ) )
                    {
                        $article_ids = implode(";", $article_id_arr );
                        $users[$user_id]['article_ids'] = $article_ids;
                    }
                }
                $monthes = self::getPaymentHistoryMonthGroupByUserID( array('user_id'=>$user_id ) );
                //Added by Snug 15:40 2006-09-03
                $monthes = addFirstMonthAndLastMonthToCurrentMonthes( $monthes );
                //End Added
                if( count( $monthes ) )
                {
                    $users[$user_id]['monthes'] = $monthes;
                }
            }
            // modified by snug 2007-05-21 11:22 - FINISHED
        }

        $user_ids = array_keys($users);
        $qw = " \nAND u.role = {$user_type}";
        $qw .= " \nAND u.user_id IN " . implode(", ", $user_ids);
        $users = self::getArticleAmount($qw, $now, $users, $user_ids, $user_type, array('current_month' => $current_month));
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $users);
        //return $users;
    }// end getAllCPAccountingReport()


    function generateForceAjustQw($p, $month_start, $month_end)
    {
        $str_time  = "\n  aa.created_time >= '%s'";
        $str_time .= "\n  AND aa.created_time <= '%s'";
        $str_time .= "\n  AND aa.status = '%s'";
        $str_time .= "\n  AND aa.new_status = '%s'";
        $str_time .= "\n  AND aa.curr_flag = 1";
        $result = array();
        $include_editor_approval = $p['include_editor_approval'];
        $include_google_clean = $p['include_google_clean'];
        $forced_adjust = $p['forced_adjust'];
        if ($forced_adjust == 1)
        {
            if ($include_editor_approval)
            {
                $approval_qw = "(" . sprintf($str_time, $month_start, $month_end, '1gc', '4') . "  AND ar.article_status = 4)";
                $result[] = $approval_qw;
            }
            if ($include_google_clean)
            {
                $gc_qw = "(" . sprintf($str_time, $month_start, $month_end, '1', '1gc') . "  AND ar.article_status = '1gc')";
                $result[] = $gc_qw;
            }
        }
        return $result;
    }

    function getFieldByRole($role)
    {
        if ($role == 'copy writer') {
            $field = 'ck.copy_writer_id';
        } else if ($role == 'editor') {
            $field = 'ck.editor_id';
        }
        return $field;
    }

    function generatePartAccountingSql($field, $current_month, $role = 'copy writer')
    {
        $pubfrom = " FROM articles AS ar
        LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id
        LEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.user_id =  %s) ";
        
        $pubfrom = sprintf($pubfrom, $field);
        $from = " %s
        LEFT JOIN cp_payment_history AS cph ON (cph.user_id = u.user_id AND cph.month = %s) \n
        LEFT JOIN users AS u ON ck.copy_writer_id = u.user_id\n
        ";
        $from = sprintf($from, $from, $current_month);
        $qw_str = " (apl.month=%s OR apl.pay_month = %s) AND apl.role='%s' AND apl.user_id = %s ";
        $qw_user =" apl.role='%s' AND apl.user_id = %s ";
        $qw_user = sprintf($qw_user, $role, $field);
        $qw_month ="(apl.month=%s OR apl.pay_month = %s) ";
        $allqw = sprintf($qw_str, $current_month, $current_month, $role, $field);
        $allactiveqw = $allqw . ' AND apl.is_canceled = 0 ';
        $payqw = " apl.pay_month = %s AND apl.role='%s' AND apl.user_id = %s  AND apl.is_canceled = 0";
        $payqw = sprintf($payqw, $current_month, $role, $field);
        return compact('pubfrom', 'form', 'allqw', 'payqw', 'allactiveqw', 'qw_str', 'qw_month', 'qw_user');
    }

	/**
	 *Added By Snug 11:23 2006-8-29
	 *get a cp accounting report
	 *@param $p: array;
	 *@param return: array
	*/
	function getAccountingByUserID( $p )
	{
		global $conn;

        // added by snug xu 2007-05-21 14:14 - STARTED
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
        // added by snug xu 2007-05-21 14:14 - FINISHED

		$user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw = '';
		} else {
			$qw = "\nAND u.role = '" . addslashes(htmlspecialchars(trim($user_type))) . "' \n";
		}

		$user_id = mysql_escape_string(htmlspecialchars(trim( $p['user_id'] )));
		if( $user_id > 0 )
		{ 
			$qw .= "\n AND u.user_id='{$user_id}' \n";
		}
		$current_month = mysql_escape_string(htmlspecialchars(trim( $p['month'] )));
		if(strlen($current_month) == 0)
		{
			$now = time();
			$current_month = changeTimeToPayMonthFormat($now);
		}
		else
		{
			$now = changeTimeFormatToTimestamp($current_month);
		}
		
        $param['now'] = $now;
        $param['type'] = 'one-cp';
        $param['qw_where'] = $qw;
        $param['user_id'] = $user_id;
        $sqls = self::getAccountingConditionOrSql($param, $user_type);
        //copy writer should take him how many articles be pending
        // get user info and payment info
        $q = $sqls['user'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            while (!$rs->EOF) {
                if (isset($rs->fields['form_submitted']) && !empty($rs->fields['form_submitted'])) $rs->fields['form_submitted'] = explode("|", $rs->fields['form_submitted']);
                $user = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        $q = 'select user_type from users where user_id= ' . $user_id;
        $user['user_type'] = $conn->GetOne($q);
        // get report for certain user_id
        foreach ($g_article_types as $k => $value) {
            $user[$k] = 0;
        }
        $user['gct_count'] = 0;
        $user['pay_gct_count'] = 0;
        // modiified by snug xu 2007-09-04 23:42- STARTED
        // get total of all article type
        $q = $sqls['type_count'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            //$users = array();
            while (!$rs->EOF) {
                $k = $rs->fields['at_parent_id'];
                $user[$k] += $rs->fields['count'];
                $user['gct_count'] += $rs->fields['count'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
        $user['pay_gct_count'] = $user['gct_count'];
    
        // total paid in article_payment_log
        $q  = $sqls['paid_sql'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            while (!$rs->EOF) {
                $user['pay_gct_count'] = $rs->fields['count'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
        
        $user = self::getArticleAmount($qw, $now, array($user_id => $user), $user_id, $user_type, array('current_month' => $current_month));
        $user = $user[$user_id];
		return $user;
	}//End

    function getAccountingConditionOrSql($p = array(), $role = 'copy writer')
    {
        global $conn;
        $conditions = array();
        $type = trim($p['type']);
        
        $user_id = isset($p['user_id']) ? $p['user_id'] : 0;
        $field = self::getFieldByRole($role);
        if (!empty($user_id)) {
            if (is_array($user_id)) {
                $conditions[] = $field . ' IN (' . implode(", ", $user_id) . ')';
            } else if ($user_id > 0) {
                $conditions[] = $field . ' = \'' . $user_id. '\'';
            }
        }
        
        // get time
        $now = isset($p['now']) ? trim($p['now']) : '';
        if (empty($now)) $now = time();
        $current_month = isset($p['current_month']) ? $p['current_month'] : changeTimeToPayMonthFormat($now);
        $next_month     = strtotime('+1 month', $now);
        $last_month      = strtotime('-1 month', $now);
        $month_end      = date('Y-m', $next_month) . "-01 00:00:00";
        $month_start    = date('Y-m', $now)."-01 00:00:00";

        // get additional sql condition
        $qw_where = isset($p['qw_where']) && trim($p['qw_where']) ? $p['qw_where'] : '';
        
        if (!empty($p['status'])) {
            $qw_where .= " \n AND u.status = '{$p['status']}' ";
        }

        $ret = self::generatePartAccountingSql($field, $current_month, $role);
        extract($ret);
        // pr($ret, true);

        if (!empty($conditions)) {
            $w = ' AND  ' .  implode(' AND ', $conditions );
        } else {
            $w = '';
        }
        if ($type != 'keyword-adjust') {
            // paid sql
            $query = "SELECT SUM(ar.total_words) AS count, apl.user_id ";
            $query .= "\n%s ";// from tables
            $query .= "\nWHERE %s ";// where 
            $query .= "\nGROUP BY  apl.user_id";
            $query = sprintf($query, $pubfrom, $payqw . $w . " AND ck.status!='D'" );
        }
        switch ($type)
        {
        case "multi-cp":
        case "one-cp":
        case "export":
            $show_all  = isset($p['show_all']) ? $p['show_all'] : 0;
            // all articles that editor approval this month or moved to this month pay
            // $where = "\nWHERE " . $qw . $w . ' AND apl.is_canceled = 0 ';
            // get total paid in article_payment_log table for certain month or (certain month and user_id)
            if ($type == 'multi-cp' || $type == 'export')
            {
                if ($type == 'export') {
                    $user_sql  = "SELECT DISTINCT u.user_id, u.user_name, u.first_name, u.last_name, u.email, u.paypal_email, u.country, u.address, ";
                    $user_sql .= " u.city, u.state, u.zip, u.country, u.status, u.pay_pref, u.user_type, cph.payment_flow_status \n";
                } else {
                    $user_sql  = "SELECT DISTINCT u.user_id, u.user_name, u.first_name, u.paypal_email, u.country, u.last_name, u.address, u.email, u.vendor_id, u.qb_vendor_id,  ";
                    $user_sql .= "u.pay_pref, u.form_submitted, u.notes, u.status, u.user_type, cph.payment_flow_status, cph.memo, ";
                    $user_sql .= "cph.month,cph.payment,cph.invoice_status \n";
                }
                $user_where = '';
                if ($show_all == 0) {
                    $user_from  = $pubfrom;                
                    $user_from .= "\nLEFT JOIN users AS u ON {$field} = u.user_id";
                    $user_where = ' WHERE ' . $allactiveqw . $w . " AND ck.status!='D'";
                } else {
                    $user_from  = "\nFROM users AS u ";                
                    $user_where = "WHERE 1=1 ";
                }
                $user_from .= "\nLEFT JOIN cp_payment_history AS cph ON (cph.user_id = u.user_id AND cph.month = {$current_month})\n";
                $batch_status = $p['batch_status'];
                if ($batch_status != 'all') {
                    if ($batch_status == '') { $qw_where .= ' AND (cph.payment_flow_status= \'' . $batch_status . '\'  OR cph.payment_flow_status IS NULL ) ';
                    } else if ($batch_status == 'cpc') { 
                        $qw_where .= ' AND ( cph.payment_flow_status= \'cbill\' OR (cph.payment_flow_status= \'' . $batch_status . '\' AND u.vendor_id = 0))';
                    } else {
                        $qw_where .= ' AND cph.payment_flow_status= \'' . $batch_status . '\' ';
                    }
                }
                $user_where .=  $qw_where;
                $user_sql .= $user_from;
                $user_sql .= $user_where;
                $user_sql .= "\nGROUP BY u.user_id";
                $user_sql .= "\nORDER BY u.last_name";
                $user_count_sql  = "SELECT COUNT(DISTINCT u.user_id) AS count";
                $user_count_sql .= $user_from;
                $user_count_sql .= $user_where;
            }
            else
            {
                $user_sql  = "SELECT `payment_flow_status`, `set_flow_status_time`, `date_pay`, `payment`, `memo`";
                $user_sql .= "\nFROM `cp_payment_history` ";
                $cp_id = is_array($user_id) ? $user_id[0] : $user_id;
                $user_sql .= "\nWHERE `month`='{$current_month}' AND `user_id`='{$cp_id}'  ";
                $user_count_sql = '';
            }
            $type_count_sql  = "SELECT SUM( ar.total_words) AS count ,ck.article_type, at.parent_id as at_parent_id, apl.user_id  \n";
            $type_count_sql .= $pubfrom . "\n";
            $type_count_sql .= "LEFT JOIN article_type AS at ON at.type_id = ck.article_type \n";
            $type_count_sql .= ' WHERE ' . $allactiveqw . $w . " AND ck.status!='D'";
            $type_count_sql .= "\nGROUP BY apl.user_id, ck.article_type ";
            $type_count_sql .= "\nORDER BY apl.user_id, ck.article_type ";
            $sql = array(
                'user'             => $user_sql,
                'user_count'    => $user_count_sql,
                'type_count'   => $type_count_sql,
                'paid_sql' => $query,
            );
            break;
        case "keyword-adjust":
            $include_editor_approval = $p['include_editor_approval'];
            $include_google_clean = $p['include_google_clean'];
            $forced_adjust = $p['forced_adjust'];
            $show_current_month = addslashes(htmlspecialchars(trim($p['show_current_month'])));
            $allqw = sprintf($qw_month, $current_month, $current_month);
            $ajust_qw = self::generateForceAjustQw($p, $month_start, $month_end);

            if ($show_current_month == 1) {
                $start_time  = $month_end;
                $end_time   = date('Y-m', strtotime('+2 month', $now)) . "-01 00:00:00";
                $month = nextPayMonth($current_month);
                $ajust_qw = $ajust_qw + self::generateForceAjustQw($p, $start_time, $end_time);
                $next_month_qw =  sprintf($qw_month, $month, $month);
            } else {
                $next_month_qw = '';
            }
            
            if (!empty($ajust_qw)) {
                $pubfrom .= "\nLEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id) ";
                $ex_qw = ' (' . implode(' OR ', $ajust_qw) . ') AND apl.log_id IS NULL ';
            } else {
                $ex_qw = '';
            }
            if (!empty($next_month_qw)) {
                $where .= "\n(" . $allqw . "\n OR " . $next_month_qw . ') ' ."\n AND " . $qw_user;
            } else {
                $where .= $allqw . "\n AND " . $qw_user;
            }
            if (!empty($ex_qw)) $where =  '(' . $where . "\n" . ' OR ' . $ex_qw . ")\n";
            $sql_left = $pubfrom;
            $qw = $where . $w . $qw_where . " AND ck.status!='D'";
            if (empty($forced_adjust)) $qw .= ' AND apl.is_canceled != 1';
            if ($p['is_paid'] && $p['is_paid'] == 1) {
                $qw .= ' AND apl.pay_month=' . $current_month;
            }
            $sql = array(
                'where' =>  $qw ,
                'left' => $sql_left,
            );
            break;
        case "invoice":
        case "type-cost":
        case "article-amount":
            if ($role == 'editor') {
                $cost_field = 'editor_cost';
                $cost_article = 'editor_article_cost';
            } else if ($role == 'copy writer') {
                $cost_field = 'cp_cost';
                $cost_article = 'cp_article_cost';
            }            
            // check whether this user's articles were paid or not this month
            $where  = "\nWHERE " . $payqw . $w . " AND ck.status!='D'";
            $sql_from = $pubfrom;
            $sql_from .= "\n LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ";
            $sql_from .= "\n LEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type) ";
            $sql_from .= "\n LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type) ";
            switch ($type)
            {
            case 'invoice':
                $where .= "\n " . $qw_where;
                $is_all = isset($p['is_all']) ? $p['is_all'] : false;
                $sql_select  = " SELECT  DISTINCTROW ar.title,ar.article_number, ar.article_status, ar.total_words, ck.keyword, ck.campaign_id, ck.date_start, ck.date_end, ck.article_type, \n";
                $sql_select .= " cc.campaign_name, ac.{$cost_field} AS ac_word_cost, at.{$cost_field} AS at_word_cost, \n";
                $sql_select .= "at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked, ach.pay_by_article AS ach_checked,  \n";
                $sql_select .= "ac.{$cost_article} AS ac_article_cost, at.{$cost_article} AS at_article_cost,  ";
                $sql_select .= "ac.invoice_status,  ach.cost_per_article AS ach_type_cost,  \n";
                $sql_select .= " IF (ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name, at.parent_id AS at_parent_id,u.user_type "; 
                $sql_from .= "\n LEFT JOIN users AS u ON ({$field} = u.user_id) ";
                $sql_from .= "\n LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month) ";
                $sql_orderby = "\n ORDER BY ck.article_type ASC";
                $sql = array(
                    'select'   => $sql_select . "\n",
                    'from'     => $sql_from,
                    'orderby' => $sql_orderby,
                );
                $sql['where'] = $is_all ? 'WHERE ' . $qw : $where;
                $sql['where'] = $where;
                break;
            case 'type-cost':
                $where .= "\n " . $qw_where;
                $q  = "SELECT DISTINCT ck.campaign_id, ck.article_type, cc.campaign_name, ac.cost_id,u.user_type, ";
                $q .= "ac.invoice_status, at.type_name as article_type_name, at.parent_id AS at_parent_id, \n";
                $q .= "ac.pay_by_article AS ac_checked, at.pay_by_article AS at_checked, ach.pay_by_article AS ach_checked, ";
                $q .= "ac.{$cost_article} AS ac_article_cost, at.{$cost_article} AS at_article_cost, at.qd_listid, \n";
                $q .= "ac.{$cost_field} AS ac_word_cost, at.{$cost_field} AS at_word_cost, ach.cost_per_article AS ach_type_cost \n";
                $q .= $sql_from;
                $q .= "\n LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month AND ach.role=apl.role) ";
                $q .= "\n LEFT JOIN users AS u ON ({$field} = u.user_id) ";
                $q .= $where;
                $q .= "\n ORDER BY ck.article_type, cc.campaign_id";
                $sql = array(
                    'sql' => $q,
                );
                break;
            case 'article-amount':
                $q  = "SELECT SUM(ar.total_words) AS num, COUNT(ar.article_id) AS total , ck.article_type, at.parent_id AS at_parent_id, ck.campaign_id, {$field} AS user_id, u.user_type, \n";
                $q .= "at.pay_by_article AS at_checked, at.{$cost_field} AS at_word_cost,  at.{$cost_article} AS at_article_cost,  \n";
                $q .= "ac.pay_by_article AS ac_checked, ac.{$cost_field} AS ac_word_cost,  ac.{$cost_article} AS ac_article_cost,  \n";
                $q .= "ac.invoice_status, ach.cost_per_article as ach_type_cost,  ach.total_article, ach.total_cost, ach.pay_by_article AS ach_checked,\n";
                $q .= "IF(ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name \n";
                $q .= $sql_from;
                $q .= "\n LEFT JOIN `users` AS u ON  u.user_id=apl.user_id ";
                $q .= "\n LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id ";
                $q .= "AND ach.month=apl.pay_month) ";
                $q .= $where;
                $q .= "\n GROUP BY apl.user_id, ck.campaign_id, ck.article_type ";
                $q .= "\n ORDER BY apl.user_id, ck.article_type ASC";
                $sql = array(
                    'sql' => $q,
                );
                break;
            }
            break;
        }
        return $sql;
    }

    function getAllCPAccountingReportNoPagination($p)
    {
        global $conn;
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
		
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month)) 
		{
			$now = changeTimeFormatToTimestamp($current_month);
		}
		else
		{
			$now = getDelayTime(); //delay 15 days to show this month report
            $current_month = changeTimeToPayMonthFormat($now);
		}
        $param['now']       = $now;
        $param['show_all'] = isset($p['show_all']) && !empty($p['show_all']) ?  true : false;


        $qw = "";

        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw .= '';
		} else {
			$qw .= "\nAND u.role = '".addslashes(htmlspecialchars(trim($user_type)))."' ";
            if ($user_type == 'copy writer') {
                if ($p['campaign_id'] > 0) {
                    $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
                }
            }
		}
        // let deleted copywriters show in accounting
        $param['qw_where'] = $qw;
        $param['type']    = 'export';
        $param['status'] = trim($p['status']);
        $sqls = self::getCPAccountingConditionOrSql($param);
        $sql .= $sqls['user_count'];
        $rs = &$conn->Execute($sql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }


        if ($user_type == 'copy writer') {
            $q = $sqls['user'];
        } else {
            $q = "SELECT u.username,u.first_name, u.last_name, u.email, u.status , u.pay_pref".
                "FROM users AS u WHERE 1 " . $qw . "ORDER BY u.last_name ASC";
        }
        $pay_prefs = $g_tag['payment_preference'];
        $statuses = $g_tag['status'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $rs->fields['status'] = $statuses[$rs->fields['status']];
                $pay_pref = $pay_prefs[$rs->fields['pay_pref']];
                $payment_flow_status = $rs->fields['payment_flow_status'];
                unset($rs->fields['pay_pref']);
                unset($rs->fields['payment_flow_status']);
                $users[$user_id] = $rs->fields;
                if ($user_type == 'copy writer') {
                    // initialize copywriter accouting report
                    // initialize total of article type google clean in a month
                    foreach ($g_article_types as $key => $val) {
                        $users[$user_id]['type_' . $key] = 0; 
                    }
                    $users[$user_id]['total'] = 0;// total of Google clean this month
                    $users[$user_id]['pay_words_total'] = 0; // total should pay this month
                    if ($user_id > 0) $user_id_arr[] = $user_id;
                }
                $users[$user_id]['pay_amount'] = 0;
                $users[$user_id]['payment_preference'] = $pay_pref;
                $users[$user_id]['payment_status'] = $payment_flow_status== 'paid' ? $payment_flow_status : 'Not Paid';
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        if ($user_type == 'copy writer') {//copy writer should take him how many articles be pending
            $sql = $sqls['type_count'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    //$key      = $rs->fields['article_type'];
                    $key      = $rs->fields['at_parent_id'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id]['type_' . $key] = $rs->fields['count'];
                        $users[$user_id]['total'] += $rs->fields['count'];
                        $users[$user_id]['pay_words_total'] = $users[$user_id]['total'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            $sql = $sqls['ex_paycount'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id]['pay_words_total'] = $users[$user_id]['total'] - $rs->fields['count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }           

            $sql = $sqls['paid_sql'];
            $rs  = &$conn->Execute($sql);
            if ($rs) {
                while (!$rs->EOF) {
                    $user_id = $rs->fields['user_id'];
                    if (isset($users[$user_id]))
                    {
                        $users[$user_id]['pay_words_total'] = $rs->fields['count'];
                    }
                    $rs->MoveNext();
                }
                $rs->Close();
            }
        }
        
        $user_ids = array_keys($users);
        $qw = "\nAND u.role = 'copy writer'";
        $qw .= "\nAND u.user_id IN " . implode(", ", $user_ids);
        $users = self::getArticleAmount($qw, $now, $users, $user_ids, $user_type, array('current_month' => $current_month));
        return $users;
    }

	/**
	 *Added By Snug 11:23 2006-8-29
	 *get a cp accounting report
	 *@param $p: array;
	 *@param return: array
	*/
	function getCPAccountingByUserID( $p )
	{
		global $conn;

        // added by snug xu 2007-05-21 14:14 - STARTED
        global $g_tag;
        $g_article_types = $g_tag['article_type'];
        // added by snug xu 2007-05-21 14:14 - FINISHED

		$user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw = '';
		} else {
			$qw = "\nAND u.role = '" . addslashes(htmlspecialchars(trim($user_type))) . "' \n";
		}

		$user_id = mysql_escape_string(htmlspecialchars(trim( $p['user_id'] )));
		if( $user_id > 0 )
		{ 
			$qw .= "\n AND u.user_id='{$user_id}' \n";
		}
		$current_month = mysql_escape_string(htmlspecialchars(trim( $p['month'] )));
		if(strlen($current_month) == 0)
		{
			$now = time();
			$current_month = changeTimeToPayMonthFormat($now);
		}
		else
		{
			$now = changeTimeFormatToTimestamp($current_month);
		}
		
        if ($user_type == 'copy writer') {
            $param['now'] = $now;
            $param['type'] = 'one-cp';
            $param['qw_where'] = $qw;
            $param['user_id'] = $user_id;
            $sqls = self::getCPAccountingConditionOrSql($param);
            //copy writer should take him how many articles be pending
            // get user info and payment info
            $q = $sqls['user'];
            $rs = &$conn->Execute($q);
			if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    if (isset($rs->fields['form_submitted']) && !empty($rs->fields['form_submitted'])) $rs->fields['form_submitted'] = explode("|", $rs->fields['form_submitted']);
                    $user = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            // get report for certain user_id
            foreach ($g_article_types as $k => $value) {
                $user[$k] = 0;
            }
			$user['gct_count'] = 0;
			$user['pay_gct_count'] = 0;
            // modiified by snug xu 2007-09-04 23:42- STARTED
            // get total of all article type
            $q = $sqls['type_count'];
            $rs = &$conn->Execute($q);
            if ($rs) {
                //$users = array();
                while (!$rs->EOF) {
                    $k = $rs->fields['article_type'];
                    $user[$k] = $rs->fields['count'];
                    $user['gct_count'] += $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            $user['pay_gct_count'] = $user['gct_count'];
            // excluded the articles has paid
            $q  = $sqls['ex_paycount'];
            $rs = &$conn->Execute($q);
            if ($rs) {
                while (!$rs->EOF) {
                    $user['pay_gct_count'] = $user['gct_count'] - $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
            }
        
            // total paid in article_payment_log
            $q  = $sqls['paid_sql'];
            $rs = &$conn->Execute($q);
            if ($rs) {
                while (!$rs->EOF) {
                    $user['pay_gct_count'] = $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            // modiified by snug xu 2007-09-04 23:42 - FINISHED

			//START:Added By Snug 21:40 2006-08-13
			/***count total articles will be paid this month group by each copy writer***/
            

			//Added By Snug 22:27 2006-09-02
			$article_id_arr = self::getArticleIdsByParam( $p, $p['user_id'] );
			if( count( $article_id_arr ) )
			{
				$article_ids = implode(";", $article_id_arr );
				$user['article_ids'] = $article_ids;
			}
			//End Added
        }
        $user_id = $p['user_id'];
        $user = self::getArticleAmount('', $now, array($user_id => $user), $user_id, $user_type, array('current_month' => $current_month));
        $user = $user[$user_id];
		return $user;
	}//End

    function getCPAccountingConditionOrSql($p = array())
    {
        global $conn;
        $conditions = array();
        $type = trim($p['type']);

        $user_id = isset($p['user_id']) ? $p['user_id'] : 0;
        if (!empty($user_id))
        {
            if (is_array($p['user_id']))
            {
                $conditions[] = 'ck.copy_writer_id IN (' . implode(", ", $p['user_id']) . ')';
            }
            else
            {
                $conditions[] = 'ck.copy_writer_id = ' . trim($p['user_id']);
            }
        }

        // get additional sql condition
        $qw_where = isset($p['qw_where']) && trim($p['qw_where']) ? $p['qw_where'] : '';
        if (!empty($p['status'])) {
            if (!empty($qw_where)) $qw_where .= "\n AND ";
            $qw_where .= " u.status = '{$p['status']}' ";
        }
        
        // get time
        $now = isset($p['now']) ? trim($p['now']) : '';
        if (empty($now)) $now = time();
        $current_month = changeTimeToPayMonthFormat($now);
        $next_month     = strtotime('+1 month', $now);
        $last_month      = strtotime('-1 month', $now);
        $month_end      = date('Y-m', $next_month) . "-01 00:00:00";
        $month_start    = date('Y-m', $now)."-01 00:00:00";

        // get time condition
        $str_time  = "\n  aa.created_time >= '%s'";
        $str_time .= "\n  AND aa.created_time <= '%s'";
        $str_time .= "\n  AND aa.status = '%s'";
        $str_time .= "\n  AND aa.new_status = '%s'";
        $str_time .= "\n  AND aa.curr_flag = 1";
//        $str_aa = "\n  AND aa.status = '%s'";
//        $str_aa .= "\n  AND aa.new_status = '%s'";
//        $str_aa .= "\n  AND aa.curr_flag = 1";
        $qw_time = sprintf($str_time, $month_start, $month_end, 4, 5);
        // exculde next month time condition
        $ex_next_str_time = "";

        // get article target payment month condition
        $qw_ar = "ar.target_pay_month = {$current_month}\n";

        // get article status condition
        $qw_ar_status  = "\n   ar.article_status = 5 ";
        $qw_ar_status .= "\n   OR  ar.article_status = 6 ";
        
        // generate where condition for article_payment_log.user_id
        $qw_apl = '';
        if (!empty($user_id))
        {
            if (is_array($user_id))
            {
                $qw_apl = "\n AND apl.user_id IN (" . implode(", ", $user_id) . ")";
            }
            else
            {
                $qw_apl = "\n AND apl.user_id = {$user_id}";
            }
        }
        $ex_qw = '';
        if ($type == 'keyword-adjust')
        {
            $include_editor_approval = $p['include_editor_approval'];
            $include_google_clean = $p['include_google_clean'];
            $forced_adjust = $p['forced_adjust'];
            if ($forced_adjust == 1)
            {
                if ($include_editor_approval)
                {
                    $approval_qw = "(" . sprintf($str_time, $month_start, $month_end, '1gc', '4') . "  and ar.article_status = 4)";
                    $ex_qw .= " OR " . $approval_qw;
                }
                if ($include_google_clean)
                {
                    $gc_qw = "(" . sprintf($str_time, $month_start, $month_end, '1', '1gc') . "  and ar.article_status = '1gc')";
                    $ex_qw .= " OR " . $gc_qw;
                }
            }
        }
        // generate where condition
        $qw  = "(";
        $qw .= "\n apl.month={$current_month}";
        $qw .= "\n AND apl.user_id = ck.copy_writer_id";
        $qw .= $qw_apl;
        $qw .= ")";
        $qw .= "\n OR ";
        $qw .= "(\n {$qw_time}";
        $qw .= "\n  AND ({$qw_ar_status}) ";
        $qw .= "\n  " . $ex_qw;
        $qw .= "\n  OR {$qw_ar})";
        // $qw .= "\n AND ({$qw_ar_status})";
        $qw .= "\n ";
        /**
             (
               apl.month=200708
               AND apl.user_id = ck.copy_writer_id
               AND apl.user_id = 19
             )
             OR (             
              aa.created_time >= '2007-08-01 00:00:00'
              AND aa.created_time <= '2007-09-01 00:00:00'
              AND aa.status = '1gc'
              AND aa.new_status = '4'
              OR ar.target_pay_month = 200708
            )
            AND (
               ar.article_status = 3 
               OR  ar.article_status = 4 
               OR  ar.article_status = 5 
               OR  ar.article_status = 6 
            )             
            AND ck.copy_writer_id = 19
         */
        
        // public tables
        $from  = "\nFROM articles AS ar";
        $from .= "\nLEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id";
        $from .= "\nLEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id AND %s)";
        $from .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.user_id = ck.copy_writer_id) ";

        $query = "SELECT SUM(ar.total_words) AS count, apl.user_id ";
        $query .= "\nFROM articles AS ar ";
        $query .= "\nLEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id";
        $query .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND apl.user_id = ck.copy_writer_id)";
        $query .= "\nWHERE apl.month={$current_month}  AND  ck.status!='D' ";
        if (!empty($user_id)) 
        {
            if (is_array($user_id)) {
                $query .= "\nAND apl.user_id IN (" . implode(", ", $user_id). ") ";
            } else {
                $query .= "\nAND apl.user_id = {$user_id} ";
            }
        }
        $query .= $qw_apl;
        $query .= "\nGROUP BY  apl.user_id";
        $from = sprintf($from, $qw_time);
        switch ($type)
        {
        case "multi-cp":
        case "one-cp":
        case "export":
            $show_all  = isset($p['show_all']) ? $p['show_all'] : 0;
            $ex_qw_apl  = "\n  (apl.month > {$current_month} ";
            $ex_qw_apl .= "\n  OR  apl.month < {$current_month})";
            //$ex_qw_apl .= "\n  AND apl.user_id = ck.copy_writer_id";
            $ex_qw_apl .= "\n  AND ar.article_id = apl.article_id";
            $ex_qw_apl .= $qw_apl;
            $ex_qw_ar   = "(";
            $ex_qw_ar  .= "\n      ar.target_pay_month > {$current_month}";
            $ex_qw_ar  .= "\n      OR ar.target_pay_month < {$current_month}";
            $ex_qw_ar  .= "\n      )";
            $ex_qw_ar  .= "\n      AND ar.target_pay_month > 0";
            if (!empty($conditions)) $ex_qw_ar .= "\n      AND " . implode(" AND ", $conditions);
            // $ex_qw_ar  .= "\n    AND apl.article_id > 0";
            // all articles that editor approval this month or moved to this month pay
            $where = "\nWHERE " . $qw;
            // get total paid in article_payment_log table for certain month or (certain month and user_id)
            if ($type == 'multi-cp' || $type == 'export')
            {
                if ($type == 'export') {
                    $user_sql  = "SELECT DISTINCT u.user_id, u.user_name, u.first_name, u.email, u.last_name, ";
                    $user_sql .= "u.status, u.pay_pref, cph.payment_flow_status ";
                } else {
                    $user_sql  = "SELECT DISTINCT u.user_id, u.user_name, u.first_name, u.last_name,u.pay_pref, ";
                    $user_sql .= "u.form_submitted, u.notes, u.user_id, u.email, u.status, cph.payment_flow_status, cph.memo, ";
                    $user_sql .= "cph.month,cph.payment,cph.invoice_status ";
                }
                $user_where = '';
                if ($show_all == 0)
                {
                    $user_from  = $from;                
                    $user_from .= "\nLEFT JOIN users AS u ON ck.copy_writer_id = u.user_id";
                    $user_from .= "\nLEFT JOIN cp_payment_history AS cph ON (cph.user_id = u.user_id AND cph.month = {$current_month})";
                    $user_where = $where;
                }
                else
                {
                    $user_from  = "\nFROM users AS u ";                
                    $user_from .= "\nLEFT JOIN cp_payment_history AS cph ON (cph.user_id = u.user_id AND cph.month = {$current_month}) ";
                    $user_where = "WHERE 1=1 ";
                }
                $user_where .=  $qw_where;
                $user_sql .= $user_from;
                $user_sql .= $user_where;
                $user_sql .= "\nGROUP BY u.user_id";
                $user_sql .= "\nORDER BY u.last_name";
                $user_count_sql  = "SELECT COUNT(DISTINCT u.user_id) AS count";
                $user_count_sql .= $user_from;
                $user_count_sql .= $user_where;
            }
            else
            {
                $user_sql  = "SELECT `payment_flow_status`, `set_flow_status_time`, `date_pay`, `payment`, `memo`";
                $user_sql .= "\nFROM `cp_payment_history` ";
                $cp_id = is_array($user_id) ? $user_id[0] : $user_id;
                $user_sql .= "\nWHERE `month`='{$current_month}' AND `user_id`='{$cp_id}'  ";
                $user_count_sql = '';
            }
            $type_count_sql  = "SELECT SUM( ar.total_words) AS count ,ck.article_type,  ck.copy_writer_id AS user_id ";
            $type_count_sql .= $from;
            $type_count_sql .= $where;
            if (!empty($conditions)) $type_count_sql .= "\nAND " . implode(" AND ", $conditions);
            //$type_count_sql .= $qw_where;
            $type_count_sql .= "\nGROUP BY ck.copy_writer_id, ck.article_type";
            $type_count_sql .= "\nORDER BY ck.copy_writer_id, ck.article_type";
            // exculde articles was previous last month or will be paid back month 
            $ex_where  = "\nWHERE (";
            $ex_where .= "{$ex_qw_apl}";
            $ex_where .= "\n  OR (";
            //$ex_where .= "\n     AND ";
            $ex_where .= "\n      {$ex_qw_ar}";
            $ex_where .= "\n   )";
            $ex_where .= "\n   OR ar.is_canceled = 1 ";
            $ex_where .= "\n )";
            $ex_where .= "\n AND ({$qw_time}";
            $ex_where .= "\n AND ({$qw_ar_status}\n )";
           //  $ex_where .= "\n OR ar.is_canceled = 1 AND " . implode(" AND ", $conditions);
            $ex_where .= "\n)";
            if (!empty($conditions)) $ex_where .= "\n AND " . implode(" AND ", $conditions); 
            $ex_sql = "SELECT SUM( ar.total_words) AS count , ck.copy_writer_id AS user_id";
            $ex_sql .= $from;
            $ex_sql .= $ex_where;
            $ex_sql .= "\nGROUP BY ck.copy_writer_id";
            $ex_sql .= "\nORDER BY ck.copy_writer_id";
            $sql = array(
                'user'             => $user_sql,
                'user_count'    => $user_count_sql,
                'type_count'   => $type_count_sql,
                'ex_paycount' => $ex_sql,
                'paid_sql' => $query,
            );
            break;
        case "keyword-adjust":
            $qw  = "(";
            $qw .= "\n apl.month={$current_month}";
            $qw .= "\n AND apl.user_id = ck.copy_writer_id";
            $qw .= $qw_apl;
            $qw .= ")";
            $qw .= "\n OR (";
            $qw .= "(\n {$qw_time}";
            $qw .= "\n  AND ({$qw_ar_status}) ";
            $qw .= "\n  " . $ex_qw;
            $qw .= "\n  OR {$qw_ar})";
            // $qw .= "\n AND ({$qw_ar_status})";
            $qw .= "\n ";
            $include_editor_approval = $p['include_editor_approval'];
            $include_google_clean = $p['include_google_clean'];
            $forced_adjust = $p['forced_adjust'];
            $show_current_month = addslashes(htmlspecialchars(trim($p['show_current_month'])));
            if ($show_current_month == 1)
            {
                $start_time  = $month_end;
                $end_time   = date('Y-m', strtotime('+2 month', $now)) . "-01 00:00:00";
                $next_qw_time  = ' OR (' . sprintf($str_time, $start_time, $end_time, 4, 5);
                $next_qw_time .= "\n AND ({$qw_ar_status})";
                $ex_qw = '';
                if ($forced_adjust == 1)
                {
                    if ($include_editor_approval)
                    {
                        $approval_qw = "(" . sprintf($str_time, $start_time, $end_time, '1gc', '4') . "  AND ar.article_status = 4)";
                        $ex_qw .= " OR " . $approval_qw;
                    }
                    if ($include_google_clean)
                    {
                        $gc_qw = "(" . sprintf($str_time, $start_time, $end_time, '1', '1gc') . "  AND ar.article_status = '1gc')";
                        $ex_qw .= " OR " . $gc_qw;
                    }
                }
                $next_qw_time .= "\n " . $ex_qw;
                $next_qw_time .= "\n OR ar.target_pay_month =  " . date("Ym", $next_month) . ")";
            }
            else
            {
                $next_qw_time = '';
            }
            $sql_left  = "\nLEFT JOIN article_action AS aa ON aa.article_id = ar.article_id ";
            $sql_left .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND ck.copy_writer_id = apl.user_id) ";
            //if (!empty($conditions)) $qw .= "\nAND " . implode(" AND ", $conditions);
            $qw .= "\n {$next_qw_time})";
            // $qw .= "\n AND ({$qw_ar_status})";
            if (!empty($conditions)) $qw .= "\nAND " . implode(" AND ", $conditions);
            $qw .= $qw_where;
            $sql = array(
                'where' =>  $qw ,
                'left' => $sql_left,
            );
            break;
        case "invoice":
        case "type-cost":
        case "article-amount":
        case "article-ids":            
            // check whether this user's articles were paid or not this month
            $rs = &$conn->Execute($query);
             $total_paid = 0;
            if ($rs) {
                if (!$rs->EOF)
                {
                    $total_paid = $rs->fields['count'];
                    $rs->MoveNext();
                }
                $rs->Close();
            }
            $qw_paid_ar  = "\n AND ";
            $qw_paid_ar .= "\n    NOT EXISTS ( ";
            $qw_paid_ar .= "\n      SELECT apl.article_id ";
            $qw_paid_ar .= "\n      FROM article_payment_log AS apl ";
            $qw_paid_ar .= "\n      WHERE apl.article_id=ar.article_id ";
            $qw_paid_ar .= "\n      AND apl.month  != '{$current_month}' ";
            // $qw_paid_ar .= "\n      AND apl.user_id = {$user_id}";
            $qw_paid_ar .= "\n      )";
            $qw .= "\n AND (";
            $qw .= "\n ar.target_pay_month IS NULL ";
            $qw .= "\n OR ar.target_pay_month = 0 ";
            $qw .= "\n OR ar.target_pay_month = '' ";
            $qw .= "\n OR ar.target_pay_month = {$current_month} ";
            $qw .= "\n ) ";
            $where  = "\nWHERE " . $qw . $qw_paid_ar;
            $where .= " AND " . implode(" AND ", $conditions);
            $qw_is_canceled = "";
            if ($total_paid > 0)
            {
                $where .= "\n AND (apl.article_id = ar.article_id ";
                $where .= "\n OR ar.is_canceled = 0) ";
            }
            else
            {
                $where .= "\n AND ar.is_canceled = 0 ";
            }
            $sql_from = $from;
            $sql_from .= "\n LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ";
            $sql_from .= "\n LEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type) ";
            $sql_from .= "\n LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type) ";
            switch ($type)
            {
            case 'article-ids':
                $q  = "SELECT DISTINCT ar.article_id";
                $q .= $from;
                $q .= "\n LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= $where;
                $q .= "\n ORDER BY ar.article_id";
                $sql = array(
                    'sql' => $q,
                    //'orderby' => "\n ORDER BY ar.article_id",
                );
                break;
            case 'invoice':
                $where .= "\n " . $qw_where;
                $is_all = isset($p['is_all']) ? $p['is_all'] : false;
                $sql_select  = " SELECT  DISTINCTROW ar.*, ck.keyword, ck.campaign_id, ck.date_start, ck.date_end, ck.article_type, ";
                $sql_select .= " cc.campaign_name, ac.cost_per_article, ac.invoice_status, ";
                $sql_select .= " ach.cost_per_article AS ach_per_article, at.type_cost, ";
                $sql_select .= " IF (ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name "; 
                $sql_from .= "\n LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $sql_from .= "\n LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id='{$user_id}' AND ach.month='{$current_month}') ";
                $sql_orderby = "\n ORDER BY ck.article_type ASC";
                $sql = array(
                    'select'   => $sql_select,
                    'from'     => $sql_from,
                    'orderby' => $sql_orderby,
                );
                $sql['where'] = $is_all ? 'WHERE ' . $qw : $where;
                break;
            case 'type-cost':
                $where .= "\n " . $qw_where;
                $q  = "SELECT DISTINCT ck.campaign_id, ck.article_type, cc.campaign_name, ac.cost_id, ";
                $q .= "ac.invoice_status, at.type_name as article_type_name, ";
                $q .= "IF(ac.cost_per_article IS NOT NULL && ac.cost_per_article != '', ac.cost_per_article, at.type_cost) AS cost_per_article \n";
                $q .= $sql_from;
                $q .= "\n LEFT JOIN users AS u ON (ck.copy_writer_id = u.user_id) ";
                $q .= $where;
                $q .= "\n ORDER BY ck.article_type, cc.campaign_name";
                 $sql = array(
                    'sql' => $q,
                    //'orderby' => "\n ORDER BY ck.article_type, cc.campaign_name",
                );
                break;
            case 'article-amount':
                $q  = "SELECT SUM(ar.total_words) AS num, ck.article_type, ck.campaign_id,ac.cost_per_article,  ck.copy_writer_id,  ";
                $q .= "ac.invoice_status, ach.cost_per_article as ach_type_cost, at.type_cost, ach.total_article, ach.total_cost, ";
                $q .= "IF(ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name ";
                $q .= $sql_from;
                $q .= "\n LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=ck.copy_writer_id  ";
                if (!is_array($user_id) && $user_id > 0)  $q .= " AND ach.user_id='{$user_id}' ";
                else if (is_array($user_id) && !empty($user_id)) $q .= " AND ach.user_id in (" . implode(", ", $user_id) . ") ";
                $q .= "AND ach.month='{$current_month}') ";
                $q .= $where;
                $q .= "\n GROUP BY ck.copy_writer_id, ck.campaign_id, ck.article_type ";
                $q .= "\n ORDER BY ck.copy_writer_id, ck.article_type ASC";
                $sql = array(
                    'sql' => $q,
                    //'groupby' => "\n GROUP BY ck.campaign_id, ck.article_type",
                    //'orderby' => "\n ORDER BY ck.article_type ASC",
                );
                break;
            }
            break;
        }
        return $sql;
    }

    // added by snug xu 2007-03-01 15:24 - STARTED
    /**
     * get All  copywriter payment history info
     * @param $p array: user_id or month
     * @param $key_is_num boolean:
     *     when its value is true, create result array keys by default; 
     *     when its value is false, create result array keys by user id and month
     * @return array
     */
    function getCpPaymentHistory($p, $key_is_num = true) 
    {
        global $conn;
        $condition = array();
        $users      = array();
    	$user_id = mysql_escape_string(htmlspecialchars(trim($p['user_id'])));
    	$month  = mysql_escape_string(htmlspecialchars(trim($p['month'])));
    	$role  = mysql_escape_string(htmlspecialchars(trim($p['role'])));
        $condition[] = "1";
        if ($user_id > 0) {
        	$condition[] = "cph.user_id={$user_id}";
        }
        if (strlen($month) > 0) {
        	$condition[] = "cph.month='{$month}'";
        }
        if (empty($role)) {
            $role = 'copy writer';
        }
        if (!empty($role)) {
            $condition[] = "cph.role='{$role}'";
        }
        $sql  = "SELECT *  \n";
        $sql .= "FROM `cp_payment_history` as cph \n";
        $sql .= "WHERE " . implode(" AND ", $condition) . "\n";
        $rs = &$conn->Execute($sql);

        if ($rs) 
        {
            //$users = array();
            while (!$rs->EOF) 
            {
                $user_id = $rs->fields['user_id'];
                $month  = $rs->fields['month'];
                if($key_is_num) {
                    $users[] = $rs->fields;
                } else {
                    $users[$user_id][$month] = $rs->fields;
                }
                $rs->MoveNext();
            }
            $rs->Close();
            //return $users;
        }
        return $users;
    }
    // added by snug xu 2007-03-01 15:24 - FINISHED


	/**
	 *Added By Snug 20:24 2006-08-28
	 *get all article ids
	 *@param $p: array
	 *@param $user_id: int
	 *@param return: array
	*/
	function getArticleIdsByParam( $p=array(), $user_id)
	{
		global $conn;
        global $g_pager_params;

        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw = '';
		} else {
			$qw = "\n AND u.role = '" . addslashes(htmlspecialchars(trim($user_type))) . "' \n";
		}

		//限制copywriter的user id
		$user_id = addslashes(htmlspecialchars(trim($user_id)));
		if( $user_id >0 )
		{
			 $qw .= "\n AND u.user_id='{$user_id}' \n";
		}
		
		//Modified By Snug 22:23 2006-09-02
		$current_month = mysql_escape_string(htmlspecialchars(trim( $p['month'] )));
		if( $current_month == date( 'Ym' ) || $current_month == '' )
		{
			$now = time();
			$current_month = changeTimeToPayMonthFormat($now);
		}
		else
		{
            $now = generateDateTimeByMonth($current_month);
		}
        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D' ";
        $param['now']         = $now;
        $param['qw_where'] = $qw;
        $param['type']        = 'article-ids';
        $param['user_id']     = $user_id;
        $sqls = self::getCPAccountingConditionOrSql($param);
		//End Modified
        $q = $sqls['sql'];
        $rs = &$conn->Execute($q);
        $users = array();
        if ($rs) 
        {
            //$users = array();
            while (!$rs->EOF) 
            {
                $users[] = $rs->fields['article_id'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $users;
	}

    /**
     * get all user's last login time
     *
     * @param array $p
     *
     * @return array
     */
    function getAllUserLastLoginTime($p)
    {
        global $conn;
        global $g_pager_params;

        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
        $user_type = ($user_type != '') ? $user_type : 'copy writer';

		if ($user_type == 'all') {
			$qw = '';
		} else {
			$qw = "AND u.role = '".addslashes(htmlspecialchars(trim($user_type)))."' ";
		}

        $rs = &$conn->Execute("SELECT COUNT(u.user_id) AS count FROM users AS u ".
                              "WHERE u.status != 'D' ".$qw);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($p['perPage']) > 0) {
            //这里的perpage不能变成
            $perpage = $p['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT u.*, s.time FROM users AS u ".
             "LEFT JOIN session AS s ON (s.user_id = u.user_id) ".
             "WHERE u.status != 'D' " . $qw . "ORDER BY u.user_name ASC";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        //$rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            while (!$rs->EOF) {
                $users[$rs->fields['user_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $users);
        //return $users;
    }// end getAllUserLastLoginTime()

	/*added by Snug 15:48 2006-8-3
	 *Get All Users Info By the parameters
	 *@param $params: array
	 *@param return: array
	*/
	function getAllCopyWritersByParameters( $params)
	{
		global $conn, $feedback;
		$qw = '';
		$qt = '';
		switch( $params['status'] )
		{
			case 'never login':
				 $interval = intval( $params['interval'] );
				 $qw .=" AND ck.date_end < DATE_ADD( CURRENT_DATE( ), INTERVAL ". $interval . " DAY) "; 
				 $query = "SELECT DISTINCTROW u.* 
				 FROM `users` AS u
				 LEFT JOIN   `campaign_keyword` AS ck on ck.copy_writer_id = u.user_id
				 LEFT JOIN   `articles` AS ar on ck.keyword_id = ar.keyword_id
				 WHERE u.status != 'D' 
                 AND  ck.status!='D' 
			     AND u.role = 'copy writer'  
                 {$qw} 
				 AND u.user_id NOT	 
				 IN (
					SELECT distinct u1.user_id
					FROM `users` AS u1
					LEFT JOIN  `campaign_keyword` AS ck1 ON ck1.copy_writer_id = u1.user_id
					LEFT JOIN  `articles` AS ar1 ON ck1.keyword_id = ar1.keyword_id
					WHERE u1.status != 'D'
					AND u1.role = 'copy writer'
					AND ar1.article_status != '0' 
					GROUP BY ck1.copy_writer_id 
				    HAVING count( ck1.keyword_id ) >0
				  )  ";
				break;
			case 'new assign':
				$interval = intval( $params['interval'] );
				$qw .= " AND ( ADDDATE(ck.date_assigned, $interval ) <= NOW() AND s.time <= UNIX_TIMESTAMP(ck.date_assigned) ) ";
				$query = "SELECT DISTINCTROW u.* 
						 FROM users u.*
						LEFT JOIN session s ON u.user_id = s.user_id
						LEFT JOIN campaign_keyword ck ON ck.copy_writer_id = u.user_id
						LEFT JOIN articles ar ON ar.keyword_id = ck.keyword_id 
						WHERE u.role = 'copy wtiter'  AND u.status != 'D' AND  ck.status!='D' 
						$qw
						 AND ck.campaign_id NOT	 
						 IN (
							SELECT DISTINCT (
							ck1.campaign_id
							)
							FROM campaign_keyword AS ck1
							LEFT JOIN articles AS ar1 ON ar1.keyword_id = ck1.keyword_id
							WHERE ar1.article_status > '0'
							GROUP BY ck1.campaign_id, ck1.copy_writer_id
							HAVING count( ck1.keyword_id ) >0
						  )";
				break;
			default:
				$from = " FROM `users`  AS u ";
				$where = " WHERE u.status!='D' AND ";
				$article_id = $params['article_id'];
				if( $article_id )
				{
					$qw .= " AND ar.article_id=" . $article_id . " ";
				}
				if( count( $params['table'] ) )
				{
					$qt .= implode(", ", $params['table'] );
					$qt = trim( $qt , ", ");
					$from .= ", " . $qt . " \n";
				}
				if( count( $params['where'] ) )
				{
					$where .= implode(" AND ", $params['where'] ) . "$qw";
				}
				$query = "SELECT u.* $from $where";
				break;
		}
		$rs = &$conn->Execute($query);
		 if ($rs)
		{
            $users = array();
            while (!$rs->EOF) 
			{
                $users[$rs->fields['user_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
		return $users;
		
	}//end getAllCopyWritersByParameters();

	/**
	 *Added By Snug 16:13 2006-8-29
	 *get all copywriter payment comfirm
	 *@param $p: array
	 *@param return: array
	*/
	function getAllCPPaymentComfirm( $p, $qw='' )
	{
		global $conn;
		$user_id = mysql_escape_string(htmlspecialchars(trim( $p['user_id'] )));
		$month = mysql_escape_string(htmlspecialchars(trim( $p['month'] )));
		$today = date("Y-m-d");
		$sql = "select * from users as u, cp_payment_history as cps where cps.user_id=u.user_id and cps.month='$month' and payment_flow_status='ap' and DATEDIFF(cps.set_flow_status_time, '$today')=0";
        if (!empty($qw)) {
            $sql .= ' AND ' . $qw;
        }

		$rs = &$conn->Execute($sql);
		$users = array();
		 if ($rs)
		{
            $users = array();
            while (!$rs->EOF) 
			{
                $users[$rs->fields['user_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
		}
		return $users;
	}

    function sendAdjustKeywordsEmail($event_id, $hint, $keywords, $editor, $cp) {
        global $conn,  $mailer_param;
        $host = "http://" . $_SERVER['HTTP_HOST'];
        // get copywriter info
        $cp_info     = self::getInfo($cp);
        // get editor info
        $editor_info = self::getInfo($editor);

        // get email body and subject from database
         $mails_template = Email::getInfoByEventId($event_id);
         $body = '<div>';
         foreach($keywords as $id=>$val) {
             $old_editor = self::getInfo($id);
             foreach($val as $k=>$values) {
             	$old_cp = self::getInfo($k);
                if($old_editor['user_id'] != $editor_info['user_id']) {
                    $body .= "Previous Editor: {$old_editor['first_name']}, {$old_editor['last_name']}<br />";
                    $body .= "New Editor: {$editor_info['first_name']}, {$editor_info['last_name']}<br />";
                } else {
                	$body .= "Editor: {$old_editor['first_name']}, {$old_editor['last_name']}<br />";
                }
                if($old_cp['user_id'] != $cp_info['user_id']) {
                    $body .= "Previous Writer: {$old_cp['first_name']}, {$old_cp['last_name']}<br />";
                    $body .= "New Writer: {$cp_info['first_name']}, {$cp_info['last_name']}<br /><br />";
                } else {
                	$body .= "Writer: {$old_cp['first_name']}, {$old_cp['last_name']}<br />";
                }
                if(user_is_loggedin()){
                    if($event_id == 13){
                        $body .= "Rejected by: " . User::getName() . "<br />";
                    }
                    else if($event_id == 12) {
                    	$body .= "Reassigned by: " . User::getName() . "<br />";
                    }
                } else if(client_is_loggedin()) {
                    if($event_id == 13){
                        $body .= "Rejected by: " . Client::getName() . "<br />";
                    }
                    else if($event_id == 12) {
                    	$body .= "Reassigned by: " . Client::getName() . "<br />";
                    }
                }
                $body .= "{$hint}:<br />";
                foreach($values as $key => $v) {
                    $body .= "&nbsp;&nbsp;<a href=\"{$host}/article/article_comment_list.php?article_id={$v['article_id']}\" >{$v['keyword']}</a><br />";
                }
                $body .= "<br />";
             }
         }
         $body .= '</div>';
         $body  = '<div>' . $mails_template['body'] . '<br /><br /><br /></div>' . $body;
        $address = 'cptech@copypress.com';
        if (!send_smtp_mail($address, $mails_template['subject'], $body, $mailer_param)) {
            return false;
            //do nothing;
        } else {
            return true;
            //do nothing;
        }
    }

	/**
	 * get user copywriter rating report by parameter
	 * created time: 2006-10-26 10:42
	 * @auther Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p:
	 *    $p['month']="all", means all aricles; $p['month'] != "all", means aricle that will be paied this month
	 * @return array: cp info, total for each rating, and average rating for this cp
	 */
	function getCpRatingReport($p =array())
	{
		global $conn, $g_tag, $g_pager_params;

		// initialize variable - START
		$qw        = " AND ar.article_status > '1' ";
        $qw       .= " AND ar.is_rated='1' ";
        $qw_user = " AND   `u`.`status`!='D'";
		// initialize variable - FINISHED

		// get report between starting month and ending month - START
		$end_date = mysql_escape_string(htmlspecialchars(trim($p['end_date']))) ;
		$start_date = mysql_escape_string(htmlspecialchars(trim($p['start_date']))) ;
		if (strlen($end_date) && strlen($start_date))
		{
			$end_date .= " 00:00:00";
			$start_date .= " 00:00:00";
			$qw .= " AND (ck.date_created <='{$end_date}' AND ck.date_created>='{$start_date}')";
		}
		// get report between starting month and ending month - FINISHED

        // get report by copy writer id - STARTED
        if (isset($p['copy_writer_id'])) 
        {
            $copy_writer_id = mysql_escape_string(htmlspecialchars(trim($p['copy_writer_id']))) ;
            if (strlen($copy_writer_id) && $copy_writer_id > 0) 
            {
                $qw_user .= " AND `u`.`user_id` = {$copy_writer_id} ";
            }
        }

        // get report by copy writer id - FINISHED


		// pagination - START
        $perpage = 50;
        if (trim($p['perPage']) > 0) 
		{
            $perpage = $p['perPage'];
        }

		// get total copywriter in system - START
        $sql = "SELECT COUNT(u.user_id) AS count FROM users AS u WHERE `u`.`permission`='1' {$qw_user}";
        $rs = &$conn->Execute($sql);
        if ($rs) 
		{
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }
		// get total copywriter in system - FINISHED

        // assemble parameters of pagination - START
		require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
		$pager = &Pager::factory(array_merge($g_pager_params, $params));
        list($from, $to) = $pager->getOffsetByPageId();
		// assemble parameters of pagination - FINISHED

		// get all copywriters' info per page - START
		$sql = "SELECT `user_id`, `user_name`, `user_pw`, `first_name`, `last_name`, `email`   FROM `users` as u WHERE  `u`.`permission`='1' {$qw_user} ORDER BY `user_name` ";
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
		$users = array();
		 if ($rs)
		{
            $users = array();
            while (!$rs->EOF) 
			{
                $users[$rs->fields['user_id']] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
		}
		// get all copywriters' info per page - FINISHED

		// get rating statistic for each copywriter - START
		// loop each user
		foreach ($users as $user_id => $user)
		{
			$total = 0;
			$total_rating = 0;
			// get total of each rating - START
			// loop each rating
			foreach ($g_tag['rating'] as $k => $rating)
			{
				$sql = "SELECT count(`ck`.`keyword_id`) AS num FROM `articles` AS ar,  `campaign_keyword`  AS ck WHERE `ar`.`keyword_id` = `ck`.`keyword_id` AND `ck`.`copy_writer_id` = '{$user_id}' AND  ck.status!='D' AND `ar`.rating='{$rating}' {$qw} ";

				$rs = &$conn->Execute($sql);
				if ($rs)
				{
					if (!$rs->EOF)
					{
						$num = $rs->fields['num'];
					}
					$rs->Close();
				}
				$users[$user_id][$rating] = $num;
				$total += $num;
				$total_rating += ($rating * $num);
			}
			// get total of each rating - FINISHED
			$users[$user_id]['total'] = $total;
			if ($total > 0)
			{
				$users[$user_id]['average'] = bcdiv($total_rating * 1.0, $total, 2);
			}
			else
			{
				$users[$user_id]['average'] = 0;
			}
		}
		// get rating report for each copywriter - FINISHED

		 // return result - START
		 return array('pager'  => $pager->links,
                    'total'  => $pager->numPages(),
                    'count'  => $count,
                    'result' => $users);
		 // return result - FINISHED
	}
	//end getCpRatingReport

    /**
     * Search user info.,
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

        $rs = &$conn->Execute("SELECT COUNT(u.user_id) AS count FROM users AS u ".$q);
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

        $q = "SELECT u.* ".
             "FROM users AS u ".$q;

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
        $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
        if ($user_id != '') {
            $q .= "AND u.user_id = '".$user_id."' ";
        }
        $user_name = addslashes(htmlspecialchars(trim($p['user_name'])));
        if ($user_name != '') {
            $q .= "AND u.user_name LIKE '%".$user_name."%' ";
        }
        $first_name = addslashes(htmlspecialchars(trim($p['first_name'])));
        if ($first_name != '') {
            $q .= "AND u.first_name LIKE '%".$first_name."%' ";
        }
        $last_name = addslashes(htmlspecialchars(trim($p['last_name'])));
        if ($last_name != '') {
            $q .= "AND u.last_name LIKE '%".$last_name."%' ";
        }

        $sex = addslashes(htmlspecialchars(trim($p['sex'])));
        if ($sex != '') {
            $q .= "AND u.sex = '".$sex."' ";
        }
        $email = addslashes(htmlspecialchars(trim($p['email'])));
        if ($email != '') {
            $q .= "AND u.email LIKE '%".$email."%' ";
        }
        $address = addslashes(htmlspecialchars(trim($p['address'])));
        if ($address != '') {
            $q .= "AND u.address LIKE '%".$address."%' ";
        }

        $phone = addslashes(htmlspecialchars(trim($p['phone'])));
        if ($phone != '') {
            $q .= "AND u.phone LIKE '%".$phone."%' ";
        }
        $cell_phone = addslashes(htmlspecialchars(trim($p['cell_phone'])));
        if ($cell_phone != '') {
            $q .= "AND u.cell_phone LIKE '%".$cell_phone."%' ";
        }
        $birthday = addslashes(htmlspecialchars(trim($p['birthday'])));
        if ($birthday != '') {
            $q .= "AND u.birthday LIKE '%".$birthday."%' ";
        }
        $degree = addslashes(htmlspecialchars(trim($p['degree'])));
        if ($degree != '') {
            $q .= "AND u.degree LIKE '%".$degree."%' ";
        }
        $role = addslashes(htmlspecialchars(trim($p['role'])));
        if ($role != '') {
            $q .= "AND u.role LIKE '%".$role."%' ";
        }
        $status = addslashes(htmlspecialchars(trim($p['status'])));
        if ($status != '') {
            if ($status == 'All')
            {//
            }
            else {
                $q .= "AND u.status = '".$status."' ";
            }
        }
        // added by nancy xu 2011-02-18 14:30
        $pay_level = addslashes(htmlspecialchars(trim($p['pay_level'])));
        if ($pay_level != '' && $pay_level > 0) {
            $q .= "AND u.pay_level = '".$pay_level."' ";
        } // end

		$q .= "AND (u.permission <= '".self::getPermission()."' OR u.user_id = '".self::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(u.user_name, u.first_name, u.last_name, u.email, u.address, u.phone, u.cell_phone, u.birthday, u.degree, u.role)")." ";
            }
        }
        return $q;
    }
    function getAllUsersBySearch($p)
    {
        global $conn;
        $q = self::searchConditions($p);
        $q = "SELECT u.user_id, u.user_name, u.first_name, u.last_name, u.email,u.date_join, u.role, u.status ".
             "FROM users AS u ".$q;
        return $conn->GetAll($q);
    }

    /**
     * Get user's ID from session
     *
     * @return int
     */
    function getID()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    }

    function getEmail()
    {
        return isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
    }


    /**
     * Get user's name from session
     *
     * @return string
     */
    function getName()
    {
        return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
    }

    /**
     * Get user's role from session
     *
     * @return array
     */
    function getRole()
    {
        return isset($_SESSION['role']) ? $_SESSION['role'] : '';
    }

    function getPayPref()
    {
        return isset($_SESSION['pay_pref']) ? $_SESSION['pay_pref'] : '';
    }

    function getLastLoginTime()
    {
        return isset($_SESSION['last_login_time']) ? $_SESSION['last_login_time'] : 0;
    }

    /**
     * Get user's role from session
     *
     * @return array
     */
    function getPermission()
    {
        return isset($_SESSION['permission']) ? $_SESSION['permission'] : 0;
    }

    /**
     * Get user's count from session
     *
     * @return array
     */
    function getCounter()
    {
        return isset($_SESSION['counter']) ? $_SESSION['counter'] : 0;
    }

    /**
     * Get user's status from session
     *
     * @return array
     */
    function getStatus()
    {
        return isset($_SESSION['status']) ? $_SESSION['status'] : '';
    }

    /**
     * Get user's current frequency from session
     *
     * @return array
     */
    function getCurrentFrequency()
    {
        return isset($_SESSION['current_frequency']) ? $_SESSION['current_frequency'] : 0;
    }

    /**
     * Set user's status
     *
     * @param int    $user_id
     * @param string $status
     *
     * @return boolean
     */
    function setStatus($user_id, $status)
    {
        global $conn, $feedback;

        $user_info = self::getInfo($user_id);
        if ($user_info['permission'] > self::getPermission()) {
            $feedback = "Have not permission to delete one ".$g_tag['user_permission'][$role]." user";
            return false;
        }

        $q = "UPDATE users ".
             "SET status = '".$status."' ".
             "WHERE user_id = '".$user_id."' ".
             "AND user_name != 'admin'";

        if ($conn->Execute($q) === false) {
            $feedback = 'Failure,Please try again';
            return false;
        } else {
            if ($conn->Affected_Rows() == 1) {
                $feedback = 'Success';
                return true;
            } else {
                $feedback = 'Failure,Please try again';
                return false;
            }
        }
    }//end setStatus()
/*********** BEGIN ADD BY cxz **********************************/
    function getCategories($user_id)
    {
        global $conn;
        $sql = "SELECT uc.user_id, c.*
                FROM users_categories uc LEFT JOIN category c ON uc.category_id=c.category_id
                WHERE user_id={$user_id}
                ORDER BY c.category_id";
        $rs = $conn->Execute($sql);
        $root = array();
        $root = array();
        if ($rs)
        {
            while (!$rs->EOF) {
                $cate = $rs->fields;
                if ($cate['parent_id'] == 0) {
                    $root[$cate['category_id']] = $cate;
                } else {
                    if (!isset($root[$cate['parent_id']])) {
                        $root[$cate['parent_id']] = Category::getInfo($cate['parent_id']);
                    }
                    $root[$cate['parent_id']]['children'][] = $cate;
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        ksort($root);
        return $root;

    }
    
    //add by liu shu fen 15:54 2007-12-17
    function getAllCpByCampaignId($p) {
        global $conn;
        $qw[] = " WHERE 1 AND  `status`!='D' ";
        if ( isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            if (is_array($p['campaign_id'])) {
                //TODO
            } else {
                $qw[] = " campaign_id=" . trim($p['campaign_id']);
            }
            if (!empty($p['editor_id'])) {
                $qw[] = " editor_id=" . trim($p['editor_id']);
            }
            $sql = "SELECT DISTINCT copy_writer_id FROM campaign_keyword";
            if (!empty($qw)) {
                $sql .= implode(" AND ", $qw);
            }
            $res = $conn->getAll($sql);
            if ($res) {
                foreach ($res as $rs) {
                    $cp_ids[] = $rs['copy_writer_id'];
                }
                $cp_res = self::getAllUsersByUserIDs($mode = 'cp_id_name', $cp_ids);
                if (!empty($cp_res)) {
                    return $cp_res;
                }
                else return null;
            } else return null;
        }
    }
    //END
    
    function markBusyUsersAndMoveUpMatchedUsers($p = array(), $all_copy_writer, $selected_cp=null, $is_html = true)
    {
        require_once CMS_INC_ROOT.'/Category.class.php';
        require_once CMS_INC_ROOT.'/UserCalendar.class.php';
        //get copywriters with the same category as selected campaign
        $option_html = '';
        $categories = $p['category_id'];
        $cp_ret = Category::getUserCategoryInfo(array('category_id'=>$categories));
        if (!empty($cp_res)) { 
            $valid_cp_ids = array_keys($cp_ret);
        } else {
            $valid_cp_ids = array();
        }
        //get the copywriter who aren't free during the campaign date_start and date_end time
        $user_calendar = new UserCalendar();
        $c_date_start = $p['c_date_start'];
        $c_date_end   = $p['c_date_end'];
        $param = array(
            '>=' => array('c_date'=>$c_date_start),
            '<=' => array('c_date'=>$c_date_end),
            //'role' => 'copy writer', 
            'is_free'=>0);
        $unfree_cp_ids = $user_calendar->getListByParam($param, array('user_id'));
        if (!empty($unfree_cp_ids)) $unfree_cp_ids = array_unique($unfree_cp_ids);
        $valid_copywriter = array();
        if (!empty($all_copy_writer) && (!empty($valid_cp_ids) || !empty($unfree_cp_ids))) {
            $styles = array();
            foreach ($all_copy_writer as $id => $name) {
                
                if (is_array($unfree_cp_ids) && in_array($id, $unfree_cp_ids)) {
                    // $name = "<font color=\"red\">" . $name . "</font>";
                    $all_copy_writer[$id] = $name;
                    $styles[$id] = "style=\"color:red\"";
                }
                if (is_array($valid_cp_ids) && in_array($id, $valid_cp_ids)) {
                    $valid_copywriter[$id] = $name;
                }
                // if ($is_html) $option_html[] = "<option {$style} value=\"{$id}\">{$name}</option>";
            }
        }
        $all_copy_writer = array('' => '------------------------------------------') + $all_copy_writer;
        if (empty($valid_copywriter)) {
            $valid_copywriter = $all_copy_writer;
        } else {
            $valid_copywriter = $valid_copywriter + $all_copy_writer;
            $result['array'] = $valid_copywriter;
        }
        $valid_copywriter =  $valid_copywriter;
        if ($is_html) {
            // $option_html = "<option value=\"\">[choose]</option>";
            foreach ($valid_copywriter as $id => $name) {
                $style = isset($styles[$id]) ? $styles[$id] : '';
                if ($selected_cp > 0&& $selected_cp == $id) $style .= ' selected';
                $option_html .= "<option {$style} value=\"{$id}\">{$name}</option>";
            }
            $result['html'] = $option_html;
        }
        return $result;
    }

    /**
     * get all history payment of user
     *
     * @param array $p
     *
     * @return array
     */
    function getAllPaymentHistory($p = array(), $is_pagination = true, $is_stats = true)
    {
        global $conn, $g_pager_params;
        $conditions = array();
        if (isset($p['user_id'])) {
		    $user_id = addslashes(htmlspecialchars(trim($p['user_id'])));
            $conditions[] = "user_id='$user_id'";
        }
        if (isset($p['payment_flow_status'])) {
            $payment_flow_status = addslashes(htmlspecialchars(trim($p['payment_flow_status'])));
            if (!empty($payment_flow_status))
                $conditions[] = 'payment_flow_status=\'' . $payment_flow_status . '\'';
        }
        if (isset($p['invoice_status'])) {
            $invoice_status = addslashes(htmlspecialchars(trim($p['invoice_status'])));
            if (strlen($invoice_status)) 
                $conditions[] = 'invoice_status=\'' . $invoice_status . '\'';
        }
        $order = 'month DESC';
        if (isset($p['orderby'])) {
            $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
            if (strlen($orderby)) 
               $order = $orderby;
        }
        $qw = empty($conditions) ? ' 1 ' : implode(" AND ", $conditions);
        $q = 'SELECT cph.user_id, cph.month, cph.payment, cph.types, cph.total '
                    .'FROM cp_payment_history AS cph '
                    . 'WHERE ' . $qw ;
        if (!empty($order)) $q .= ' ORDER BY ' . $order;
        if ($is_pagination) {
            $sql = 'SELECT COUNT(*) AS count FROM cp_payment_history AS cph  WHERE ' . $qw;
            
            $count = &$conn->GetOne($sql);
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
            
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        } else {
            $rs = &$conn->Execute($q);
        }
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $payment = $rs->fields['payment'];
                if (!empty($rs->fields['types'])) {
                    $types= unserialize(htmlspecialchars_decode($rs->fields['types']));
                    if ($payment == 0) {
                        foreach ($types as $row) {
                            $payment += $row['cost'];
                        }
                    }
                    $rs->fields['types'] = $types;
                    $rs->fields['payment'] = $payment;
                }
                
                $month = $rs->fields['month'];
                $rs->fields['month_format'] = substr($month,4,2) . '(' . substr($month,6) . ')' . '/' . substr($month,0,4);
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }
        
        if ($is_pagination) {
            $stats = User::getStatsForParam($p);
            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'stats'  => $stats,
                         'count'  => $count,
                         'result' => $result);
        } else {
            if ($is_stats) {
                $stats = User::getStatsForParam($p, $result);
                return array('result' => $result, 'stats' => $stats);
            } else {
                return $result;
            }
        }
        
    }// end getAllPaymentHistory()

   function getStatsForParam($p, $result = array())
   {
       global $conn;
       if (empty($result)) {
           $result = User::getAllPaymentHistory($p, false, false);
       }
        
      $types = array();
      $total = $payment = 0;
      foreach ($result as $row) {
        $data = $row['types'];
        $total += $row['total'];
        $payment += $row['payment'];
        foreach ($data as $type_id => $type) {
            if (isset($types[$type_id])) {
                $types[$type_id]['total'] += $type['total'];
                $types[$type_id]['cost'] += $type['cost'];
            } else {
                $types[$type_id] = $type;
            }
        }
      }
      return array('types' => $types, 'total' => $total, 'payment' => $payment); 
   }

   function sumTotalWordsForUsers($user_id = array())
   {
       global $conn;
       if (empty($user_id)) {
           $qw = '1';
       } else if (is_array($user_id) ) {
           $qw = 'cph.user_id IN (' . implode(', ', $user_id) . ') ';
       } else {
           $qw = 'cph.user_id=' . $user_id;
       }
       $q = 'SELECT SUM(cph.total) AS total_word, cph.user_id FROM cp_payment_history AS cph ' 
              . 'WHERE ' . $qw . ' group by cph.user_id' ;
       $result = $conn->GetAll($q);
       if (empty($user_id) || is_array($user_id)) {
           $list = array();
           foreach ($result as $row) {
               $list[$row['user_id']] = $row['total_word'];
           }
       } else {
           $list=$result[0]['total_word'];
       }
       return $list;
   }
////////////////forecast user's payroll/////////////////
    function forecastMonthes()
    {
        global $g_pay_per_month, $g_payment_settings, $conn;
        if (empty($g_payment_settings)) {
            require_once CMS_INC_ROOT . '/PaymentSetting.class.php';
            $g_payment_settings = PaymentSetting::getAll();
        }
        $i = time();
        $date_end = $conn->GetOne("SELECT MAX(date_end) FROM campaign_keyword");
        $time = strtotime($date_end);
        $month = date("m", $time);
        $year = date("Y", $time);
        $end_time = mktime(0, 0, 0, $month+1, 1 , $year);
        $no_time = ($i > $end_time);
        $ret = array();
        while($i <= $end_time){
            $month = changeTimeToPayMonthFormat($i);
            $pay_per_month = getPayPerMonth($month);
            $date = date("j", $i);
            $interval = 31/$pay_per_month;
            for ($j = ceil($date/$interval); $j <= $pay_per_month;$j++){
                 $ym = date("Ym", $i) ;
                 $ret[date("Ym", $i) . $j] = date("Y-m", $i) . '(' .$j .')';
                 $i += $interval * 86400;
            }
            if($no_time) break;
        }
        return $ret;
    }

    function forecastConditionOrSql($p = array())
    {
        $ret = array();
        $p_type = $p['type'];
        $conditions = array("ck.status!='D'", 'ck.editor_status=1', 'ck.cp_status=1');
        $role = $p['user_type'];
        if (isset($p['start_date']) && $p['start_date'])
            $conditions[] = "ck.date_end >='" . $p['start_date'] . "'";
        if (isset($p['end_date']) && $p['end_date'])
            $conditions[] = "ck.date_end <'" . $p['end_date'] . "'";
       
        
        $from  = 'FROM articles AS ar ';
        $from .= 'LEFT JOIN campaign_keyword AS ck ON ck.keyword_id = ar.article_id ';
        $from .= 'LEFT JOIN client_campaigns AS cc ON cc.campaign_id = ck.campaign_id ';
        if ($role == 'copy writer') {
            $field = 'ck.copy_writer_id';
        } else {
            $field = 'ck.editor_id';
        }
        if ($p_type <> 'f-keyword-adjust') {
            if (isset($p['user_id']) && !empty($p['user_id'])) {
                $conditions[] = is_array($p['user_id']) ? " u.user_id IN (" . implode(',', $p['user_id']) . ")" : " u.user_id = " . $p['user_id'] ;
            }
            $conditions[] = "u.role='" . $role . "'";
            $conditions[] = "u.status='A'";
            $from .= 'LEFT JOIN users AS u ON (u.user_id = ' . $field . ') ';
        }

        $where = implode(" AND ", $conditions) . ((isset($p['qw_where'])  && $p['qw_where']) ? $p['qw_where'] :'' );
        $qw = " WHERE " . $where;


        switch ($p_type) {
        case 'f-multi-cp':
        case 'f-export':
            $ret['user_count'] = "SELECT COUNT(DISTINCT  u.user_id) AS count " . $from . $qw;
            $ret['user'] = " SELECT DISTINCT u.user_id, u.user_name, u.first_name, u.last_name, u.address, u.email, u.vendor_id, u.qb_vendor_id,  u.pay_pref, u.form_submitted, u.notes, u.status, u.user_type  " . $from . $qw . ' GROUP BY u.user_id ORDER BY u.last_name';
            break;
        case 'f-type-count':
            $from .= 'LEFT JOIN article_type AS at ON at.type_id = ck.article_type  ';
            $ret['sql'] = "SELECT SUM(if (ar.total_words>0 , ar.total_words, if (cc.max_word >0, cc.max_word, 0))) AS count, at.parent_id as at_parent_id, u.user_id " . $from . $qw  . ' GROUP BY  u.user_id, ck.article_type ORDER BY u.user_id, ck.article_type ';
            break;
        case 'f-article-amount':
            $from .= 'LEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type) ';
            $from .= 'LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  ';            
            $ret['sql'] = 'SELECT SUM(if (ar.total_words>0 , ar.total_words, if (cc.max_word >0, cc.max_word, 0))) AS num, COUNT(ar.article_id) AS total , ck.article_type, at.parent_id AS at_parent_id, ck.campaign_id, ck.copy_writer_id AS user_id, u.user_type,  at.pay_by_article AS at_checked, at.cp_cost AS at_word_cost,  at.cp_article_cost AS at_article_cost, ac.pay_by_article AS ac_checked, ac.cp_cost AS ac_word_cost,  ac.cp_article_cost AS ac_article_cost, ac.invoice_status, at.type_name AS article_type_name ' . $from .$qw . ' GROUP BY u.user_id, ck.campaign_id, ck.article_type ORDER BY u.user_id, ck.article_type ASC   ';
            break;
        case 'f-keyword-adjust':
            $from .= 'LEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type) ';
            $from .= 'LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  ';       
            $ret['from'] = $from;
            $ret['where']= $where;
            break;
        }
        return $ret;
    }

    function forecastArticleAmount($result =null, $param = array())
    {
        global $conn;
        $param['qw_where'] = $qw;
        $param['type']        = 'f-article-amount';
        $sqls = self::forecastConditionOrSql($param);
        $q = $sqls['sql'];
	    $rs = &$conn->Execute($q);
	    if ($rs) {
            $article_types = $campaigns = array();
            $i = 0;
            
            if (empty($all_report['num']))
                $all_report['num'] = 0;
            if (empty($all_report['cost']))
                $all_report['cost'] = 0;
            while (!$rs->EOF) 
			{
                $fields = $rs->fields;
                $article_type = $fields['article_type'];
                $p_article_type = $fields['at_parent_id'];
                $total_article = $fields['total_article'];
                $total_cost = $fields['total_cost'];
                $type_name = $fields['article_type_name'];
                $u_type = $fields['user_type'];
                $total_word = $fields['num'];
                $count_article = $fields['total'];
                // modified by nancy xu 2011-05-26 16:28 - STARTED
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $total = $checked > 0 ? $count_article : $total_word;
                // modified by nancy xu 2011-05-26 16:28 - FINISHED
                $type = $article_type;
                $cid  = $fields['campaign_id'];
                $uid  = $fields['user_id'];
                if (empty($result)) {
                    $article_types[$uid][$type]['checked'] = $checked;
                    $campaigns[$uid][$cid][$type]['checked'] = $checked;
                    if (empty($article_types[$uid][$type]['num']))
                        $article_types[$uid][$type]['num'] = 0;
                    if (empty($article_types[$uid][$type]['cost']))
                        $article_types[$uid][$type]['cost'] = 0;              
                    if (empty($total_article) || empty($total_cost))
                    {
                        if (empty($campaigns[$cid][$type]['num']))
                            $campaigns[$uid][$cid][$type]['num'] = 0;
                        if (empty($campaigns[$uid][$cid][$type]['cost']))
                            $campaigns[$uid][$cid][$type]['cost'] = 0;
                        if (empty($campaigns[$uid][$cid][$type]['per_cost']))
                            $campaigns[$uid][$cid][$type]['per_cost'] = 0;
                        $campaigns[$uid][$cid][$type]['num'] = $total;
                        if ($u_type == 1) {
                            $campaigns[$uid][$cid][$type]['cost'] = $total * $cost_per_unit;
                            $campaigns[$uid][$cid][$type]['per_cost'] = $cost_per_unit;
                        }
                    }
                    else
                    {
                        $campaigns[$uid][$cid][$type]['num'] = $total_article;
                        $campaigns[$uid][$cid][$type]['cost'] = $total_cost;
                        $campaigns[$uid][$cid][$type]['per_cost'] = $cost_per_unit;
                        if ($u_type == 1 &&  $total_cost == 0.000 && $total_article) {
                            $campaigns[$uid][$cid][$type]['cost'] = $total * $cost_per_unit;
                        }
                    }
                    $campaigns[$uid][$cid][$type]['type_name'] = $type_name;
                } else {
                    if (!isset($result[$uid]['pay_amount'])) $result[$uid]['pay_amount'] = 0;
                    if ($result[$uid]['user_type'] == 1  || ($result[$uid]['user_type'] == 2 && ($result[$uid]['payment_flow_status'] == 'paid' || $result[$uid]['payment_flow_status'] == 'cbill'))) {
                        if (empty($total_article) || empty($total_cost)) {
                            $result[$uid]['pay_amount'] += $total * $cost_per_unit;
                        } else {
                            $result[$uid]['pay_amount'] += $total_cost;
                            if ($result[$uid]['user_type'] == 1 && $total_article > 0 && $total_cost == 0.000) {
                                $result[$uid]['pay_amount'] += $total * $cost_per_unit;
                            }
                        }
                    }
                    if (!isset($result[$uid]['pay_count_article'])) $result[$uid]['pay_count_article'] = 0;
                    if ($checked) {
                        $result[$uid]['pay_gct_count'] -= $total_word;
                        $result[$uid]['pay_count_article'] += $total;
                    }

                }
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        if (empty($result)) {
            if (!empty($campaigns)) {
                foreach ($campaigns as $uid => $campaign)
                {
                    foreach ($campaign  as $k => $type) 
                    {
                        foreach ($type as $key => $value)
                        {
                            $article_types[$uid][$key]['num']     += $value['num'];
                            $article_types[$uid][$key]['cost']     += $value['cost'];
                            $article_types[$uid][$key]['type_name'] = $value['type_name'];
                            $all_report[$uid]['num'] += $value['num'];
                            $all_report[$uid]['cost'] += $value['cost'];
                        }
                    }
                }
                if (count($campaigns) == 1 && !is_array($user_id) && $user_id > 0) {
                    $campaigns = $campaigns[$user_id];
                    $article_types = $article_types[$user_id];
                    $all_report = $all_report[$user_id];
                }
                $report = array();
                $report['campaign'] = $campaigns;
                $report['types'] = $article_types;
                $report['all'] = $all_report;
                return $report;
                if (!empty($result)) {
                    return $result;
                }
            }
        } else {
            if (!empty($result)) return $result;
            else return null;
        }
    }

    function forecastPayroll($p)
    {
        global $conn, $g_pager_params, $g_tag, $feedback, $g_pay_per_month, $g_delay_days, $g_interval_days;
        $g_article_types = $g_tag['article_type'];
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month) == 0)  {
			$current_month = changeTimeToPayMonthFormat(time());
		}
        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
         $user_type = ($user_type != '') ? $user_type : 'copy writer';
        $param = getForecastDates($current_month);
        $param['user_type'] = $user_type;

        $qw = "";
        $qw .= self::getSearchKeyword($p['keyword']);
        $roles = $g_tag['user_permission'];
        if (!in_array($user_type, $roles)) {
            $feedback = 'Invalid User Type, Please to check';
            return false;
        }
        if ($p['campaign_id'] > 0) {
            $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
        }
        if ($p['client_id'] > 0) {
            $qw .= "\nAND cc.client_id = '" . addslashes(htmlspecialchars(trim($p['client_id']))) . "' ";
        }
        if (!empty($user_type)) {
            $qw .= "\nAND u.role = '" . $user_type . "' ";
        }
        //$qw .= "\nAND u.status = 'A' ";

        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D'";
        $param['qw_where'] = $qw;
        $param['type']    = 'f-multi-cp';        
        $param['user_type']    = $user_type;
        $param['status'] = trim($p['status']);
        $sqls = self::forecastConditionOrSql($param);
        $sql = $sqls['user_count'];
        $count = $conn->GetOne($sql);

        if ($count == 0 || !isset($count)) {
            return false;
        }

        //$perpage = 50;
        if (trim($p['perPage']) > 0) {
            //这里的perpage不能变成
            $perpage = $p['perPage'];
        } else {
            $perpage= 50;
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = $sqls['user'];
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if ($user_id > 0) {
                    if (isset($rs->fields['form_submitted']) && !empty($rs->fields['form_submitted'])) $rs->fields['form_submitted'] = explode("|", $rs->fields['form_submitted']);
                    $users[$user_id] = $rs->fields;
                    // initialize copywriter accouting report
                    $users[$user_id]['gct_count'] = 0;// total of Google clean this month
                    // initialize total of article type google clean in a month
                    foreach ($g_article_types as $key => $val) {
                        $users[$user_id][$key] = 0; 
                    }
                    $users[$user_id]['pay_gct_count'] = 0; // total should pay this month
                    $user_id_arr[] = $user_id;
                }
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }
        $user_ids = array_keys($users);
        $param['user_id'] = $user_id_arr;
        $param['type']    = 'f-type-count';
        $sqls = self::forecastConditionOrSql($param);
        $sql = $sqls['sql'];
        $rs  = &$conn->Execute($sql);
        $pay_total = 0;
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $key      = $rs->fields['at_parent_id'];
                if (isset($users[$user_id]))
                {
                    $u_count = $rs->fields['count'];
                    $users[$user_id][$key] += $u_count;
                    $users[$user_id]['gct_count'] += $u_count;
                }
                $pay_total += $users[$user_id]['payment'];
                $rs->MoveNext();
            }
            $rs->Close();
        }
        
        $users = self::forecastArticleAmount($users,  $param);
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'pay_total'  => $pay_total,
                     'result' => $users);
        //return $users;        
    }

    function forecastPayrollNoPagination($p)
    {
        global $conn, $g_pager_params, $g_tag, $feedback, $g_pay_per_month, $g_delay_days, $g_interval_days;
        $g_article_types = $g_tag['article_type'];
		// get timestamp of month that user chose
		$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month) == 0)  {
			$current_month = changeTimeToPayMonthFormat(time());
		}
        $user_type = addslashes(htmlspecialchars(trim($p['user_type'])));
         $user_type = ($user_type != '') ? $user_type : 'copy writer';
        $param = getForecastDates($current_month);
        $param['user_type'] = $user_type;

        $qw = "";
        $qw .= self::getSearchKeyword($p['keyword']);
        $roles = $g_tag['user_permission'];
        if (!in_array($user_type, $roles)) {
            $feedback = 'Invalid User Type, Please to check';
            return false;
        }
        if ($p['campaign_id'] > 0) {
            $qw .= "\nAND ck.campaign_id = '" . addslashes(htmlspecialchars(trim($p['campaign_id']))) . "' ";
        }
        if ($p['client_id'] > 0) {
            $qw .= "\nAND cc.client_id = '" . addslashes(htmlspecialchars(trim($p['client_id']))) . "' ";
        }
        if (!empty($user_type)) {
            $qw .= "\nAND u.role = '" . $user_type . "' ";
        }
        //$qw .= "\nAND u.status = 'A' ";

        // added by snug xu 2007-09-21 13:15, let deleted copywriters show in accounting
        // $qw .= "\n AND u.status != 'D'";
        $param['qw_where'] = $qw;
        $param['type']    = 'f-export';        
        $param['user_type']    = $user_type;
        $param['status'] = trim($p['status']);
        $sqls = self::forecastConditionOrSql($param);
        $sql = $sqls['user_count'];
        $count = $conn->GetOne($sql);

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $q = $sqls['user'];
        $pay_prefs = $g_tag['payment_preference'];
        $statuses = $g_tag['status'];
        $rs = &$conn->Execute($q);
        if ($rs) {
            $users = array();
            $user_id_arr = array();
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                if ($user_id > 0) {
                    $rs->fields['status'] = $statuses[$rs->fields['status']];
                    $pay_pref = $pay_prefs[$rs->fields['pay_pref']];
                    $payment_flow_status = $rs->fields['payment_flow_status'];
                    unset($rs->fields['pay_pref']);
                    unset($rs->fields['payment_flow_status']);
                    $users[$user_id] = $rs->fields;
                    // initialize copywriter accouting report
                    $users[$user_id]['gct_count'] = 0;// total of Google clean this month
                    // initialize total of article type google clean in a month
                    foreach ($g_article_types as $key => $val) {
                        $users[$user_id][$key] = 0; 
                    }
                    $users[$user_id]['total'] = 0;// total of Google clean this month
                    $users[$user_id]['pay_words_total'] = 0; // total should pay this month
                    $users[$user_id]['pay_count_article'] = 0; // total article should pay this month
                    if ($user_id > 0) $user_id_arr[] = $user_id;
                    $users[$user_id]['pay_amount'] = 0;
                    $users[$user_id]['payment_preference'] = $pay_pref;
                    $address =  $users[$user_id]['address'];
                    $address = str_replace("\r\n", "\n", $address);
                    $address = str_replace("\n", " ", $address);
                    $users[$user_id]['address'] = $address;
                    $user_id_arr[] = $user_id;
                }
                $rs->MoveNext();
            }
            $rs->Close();

        } else {
            return null;
        }
        $user_ids = array_keys($users);
        $param['user_id'] = $user_id_arr;
        $param['type']    = 'f-type-count';
        $sqls = self::forecastConditionOrSql($param);
        $sql = $sqls['sql'];
        $rs  = &$conn->Execute($sql);
        if ($rs) {
            while (!$rs->EOF) {
                $user_id = $rs->fields['user_id'];
                $key      = $rs->fields['at_parent_id'];
                if (isset($users[$user_id]))
                {
                    $u_count = $rs->fields['count'];
                    $users[$user_id][$key] += $u_count;
                    $users[$user_id]['gct_count'] += $u_count;
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        
        $users = self::forecastArticleAmount($users,  $param);
        return $users;       
    }
/*********** END ADD BY cxz **********************************/
    function getManagerByCampaignId($campaign_id, $qw = '' )
    {
         global $conn;
         $sql = 'SELECT u.first_name, u.last_name, u.email, u.user_name ';
         $sql .= ' FROM client AS c ';
         $sql .= ' LEFT JOIN users AS u ON (u.user_id=c.project_manager_id) ';
         $sql .= ' LEFT JOIN client_campaigns AS cc ON (cc.client_id=c.client_id) ';
         $sql .= ' WHERE cc.campaign_id=' . $campaign_id;
         if (!empty($qw)) {
             $sql .= ' AND ' . $qw;
         }
         return $conn->GetRow($sql);
    }

    function QATask($user_id)
    {
        global $conn;
        if (empty($user_id)) return false;
        $sql ='SELECT cc.campaign_name, ck.campaign_id, ar.article_id, count(ck.keyword_id) as total ';
        $sql .= 'FROM campaign_keyword as ck ';
        $sql .= 'LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ';
        $sql .= 'LEFT JOIN client_campaigns as cc on (cc.campaign_id =  ck.campaign_id) ';
        $sql .= 'LEFT JOIN `article_extra_info` AS aei ON (aei.article_id = ar.article_id) ';
        $sql .= 'WHERE ar.article_status = 4 ';
        if ($user_id > 0) $sql .= 'AND ck.qaer_id = ' . $user_id  . ' AND (aei.qa_complete IS NULL  OR aei.qa_complete = 0) ';
        $sql .= ' GROUP BY ck.campaign_id';
        return $conn->GetAll($sql);
    }
}//end class User

/**
 * Session wrap function
 *
 * @return boolean
 */
function user_is_loggedin()
{
	global $feedback;


	return ((User::getID() != 0) && User::chkSessionIP($_SERVER['REMOTE_ADDR']));
}
?>