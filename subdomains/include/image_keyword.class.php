<?php
class ImageKeyword{

	function __construct()
	{
	}

    function ImageKeyword()
    {
        $this->__construct();
    }

    public static function getTable()
    {
        return 'image_keyword';
    }

    public static function getKey()
    {
        return 'keyword_id';
    }

    public static function add($p)
    {
         global $conn, $feedback;
		//Start:Added By Snug 10:50 2006-8-25
		/****如果当前的keyword已经存在，将这个keyword放在这个数组中****/
		$duplicated_keywords = array();
		//Ended Added
        //global $g_tag;

        // added by snug xu 2006-11-24 15:30 - START
        // let agency user add new client compaign
        if (User::getPermission() < 4 && User::getPermission() != 2) { // 3=>4
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        // added by snug xu 2006-11-24 15:30 - END

        $new_keywords = array(
            'keywords' => array(),
        );

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        $keyword = addslashes(htmlspecialchars(trim($p['keyword'])));
        if ($keyword == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }
        //$keywords = explode("\n", $keyword);
        $keywords = splitFieldByChar($keyword);

        $mapping_id = addslashes(htmlspecialchars(trim($p['mapping_id'])));
        if ($mapping_id != '') {
            $mappings = splitFieldByChar($mapping_id);
        } else {
            $mappings = array();
        }
        //modified by nancy xu 2012-04-20 15:31
        $options = array();
        foreach ($p as $k => $item) {
            if (substr($k, 0, 8) == 'optional') {
                $options[$k] = splitFieldByChar($item);
            }
        }
        // end
        
        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type == '') {
            $feedback = "Please provide image type";
            return false;
        }
        if (isset($p['keyword_status']))
        {
            $keyword_status = addslashes(htmlspecialchars(trim($p['keyword_status'])));
        }
        else
        {
            $keyword_status = -1;
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        $subcids = splitFieldByChar($p['subcid']);
        //$subcid = addslashes(htmlspecialchars(trim($p['subcid'])));
        if (empty($subcid)) $subcid = 0;
        if ($copy_writer_id != '' || $editor_id != '') {
            if ($copy_writer_id == '') {
                $copy_writer_id = 0;
            } else {
                if ($copy_writer_id > 0 ) {
                    // added by nancy xu 2010-03-19 13:51
                    require_once CMS_INC_ROOT . "/request_extension.class.php";
                    RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $campaign_id));
                    // end
                }
            }
            if ($editor_id == '') {
                $feedback = 'Please provide an editor';
                return false;
            }
        } else {
            $copy_writer_id = 0;
            $editor_id = 0;
        }


        //generate article number
        $campaign_info = Campaign::getInfo($campaign_id);
        if (empty($campaign_info)) {
            $feedback = "Invalid Campaign, please try again";
            return false;
        }
        $company_name = strtoupper($campaign_info['company_name']);
        $numbers = explode(" ", $company_name);//we can use preg_split()
        $image_number = "";
        foreach ($numbers as $k => $v) {
            $image_number .= substr($v, 0, 1);
        }
        // added by nancy xu 2009-12-16 11:18
        if (isset($p['date_start']) && !empty($p['date_start'])) {
            $date_start = $p['date_start'];
        } else {
            $date_start = $campaign_info['date_start'];
        }
        if (isset($p['date_end']) && !empty($p['date_end'])) {
            $date_end = $p['date_end'];
        } else {
            $date_end = $campaign_info['date_end'];
        }
        // end
        $date_created = date('Y-m-d H:i:s', time());
        if ($copy_writer_id > 0) $date_assigned = $date_created;
        else $date_assigned = '0000-00-00 00:00:00';
        $conn->StartTrans();
        $keyword_ids = array();
        foreach ($keywords as $k => $v) {
            if ($v != '') {
                $q = "SELECT COUNT(*) AS count FROM  " . self::getTable() . " WHERE keyword = '".$v."' AND campaign_id = '".$campaign_id."' AND image_type = '".$image_type."' AND `status`!='D' ";
                $rs = $conn->Execute($q);
                $count = 0;
                if ($rs) {
                    $count = $rs->fields['count'];
                    $rs->Close();
                }
                if ($count > 0) {
					$duplicated_keywords[] = $v;
                    // modified by nancy xu 2009-12-03 15:49
                    // when keyword are duplicated, the keyword still store to our system 
                    // continue;
                    // end
                }

                $keyword_id = $conn->GenID('seq_'. self::getTable() .'_' .self::getKey());
                $keyword_ids[] = $keyword_id;
                $new_keywords['keywords'][$keyword_id] = stripslashes($v);
                // modified by nancy xu 2012-04-20 15:31
                foreach ($options as $ok => $item) {// optinal1-7
                    if (!isset($new_keywords[$ok])) $new_keywords[$ok] = array();
                    $new_keywords[$ok][$keyword_id] = $item[$k];
                }// end
                if (!empty($mappings)) {
                    $mapping_id = trim($mappings[$k]);
                } else {
                    $mapping_id = '';
                }
                $hash = array('keyword_id' => $keyword_id);
                $hash['campaign_id'] = $campaign_id;
                $hash['copy_writer_id'] = $copy_writer_id;
                $hash['editor_id'] = $editor_id;
                $hash['date_assigned'] = $date_assigned;
                $hash['keyword'] = $v;
                $hash['image_type'] = $image_type;
                $hash['keyword_description'] = addslashes(htmlspecialchars(trim($p['keyword_description'])));
                $hash['date_start'] = $date_start;
                $hash['date_end'] = $date_end;
                $hash['creation_user_id'] = User::getID();
                $hash['creation_role'] = User::getRole();
                $hash['date_created'] = $date_created;
                $hash['keyword_status'] = $keyword_status;
                $hash['mapping_id'] = $mapping_id;
                $hash['subcid'] = strlen($subcids[$k]) ? $subcids[$k] : 0;
                foreach ($options as $ok => $item) {
                    $hash[$ok] = $item[$k];
                }
                $q = "INSERT INTO " . self::getTable() . " (`" . implode('`,`', array_keys($hash)). "`) VALUES ('" . implode("','", $hash). "')";

                $conn->Execute($q);
                $image_id = $conn->GenID('seq_'. Image::getTable() .'_' .Image::getKey());
                $data = array(
                    'image_id' => $image_id,
                    'image_number' => $image_number."-".($image_type+1)."-".$campaign_id."-".$keyword_id,
                    'keyword_id' => $keyword_id,
                    'creation_user_id' => User::getID(),
                    'creation_role' => User::getRole(),
                    'image_status' => 0,
                    'current_version_number' => 1.0,
                );
                $q = "INSERT INTO " . Image::getTable() . " (`" . implode("`, `", array_keys($data)) . "`) VALUES ('" . implode("', '" , $data).  "')" ;
                $conn->Execute($q);

            }
        }
        $ok = $conn->CompleteTrans();

        if ($ok) {
			if (count($duplicated_keywords))
			{
				$feedback = 'Duplicated keywords:\n  ' . implode('\n  ' , $duplicated_keywords);
				$feedback = preg_replace("/[\f\n\r\v]/", " ", $feedback);
			}
			else
			{
				$feedback = 'Success';
			}
             
            /*global $mailer_param;
            $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_info['campaign_id']));
  
            if (!empty($keyword_ids)) {
                if ($editor_id > 0) {
                    if (!empty($client_pm)) {
                        $mailer_param['cc'] = $client_pm['email'];
                    }
                    if (!empty($copy_writer_id)) $copy_writer_keywords = $new_keywords;
                    $editor_keywords = $new_keywords;
                    if ($copy_writer_id > 0)Campaign::sendAssignMail($copy_writer_id, $copy_writer_id, $campaign_info['date_end'], $copy_writer_keywords);
                    if ($editor_id > 0)Campaign::sendAssignMail($editor_id, $copy_writer_id, $campaign_info['date_end'], $editor_keywords, false);
                    if (empty($feedback)) $feedback ='Success';
                } else {
                     $link = 'http://' . $_SERVER['HTTP_HOST']. '/client_campaign/image_keyword_list.php?campaign_id=' . $campaign_info['campaign_id']; 
                     $info = array(
                         'campaign_name' => $campaign_info['campaign_name'],
                         'client_name' => $campaign_info['user_name'],
                         'login_link' => $link,
                     );
                     unset($mailer_param['cc']);
                     Email::sendNewKeywordMail($info, $client_pm['email']);
                }
            }*/
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }// end

    public static function setStatus($status, $keyword_id, $conditions = array())
    {
        global $conn, $feedback;
        if (User::getPermission() < 4) { // 3=>4
            $feedback = "Have not the permission to delete one keyword";
            return false;
        }
        if (empty($conditions)) {
            $feedback = 'You can\'t delete entire keywords';
            return false;
        }
        if (empty($campaign_id)) {
            $feedback = 'Please specify the camapign';
            return false;
        } else {
            $conditions[] = 'ik.keyword_id=\'' . $keyword_id . '\'';
            $conditions[] = 'im.image_id=ik.keyword_id';
        }
        $conditions[] = "ik.keyword_id=im.keyword_id";
        $sql = "UPDATE " . self::getTable(). " AS ik, " . Image::getTable()." AS im SET ik.status='{$status}' WHERE " . implode(" AND ", $conditions) ;
        $conn->Execute($sql);
    }

    function searchCampaign($p)
    {
        global $conn, $feedback;

        global $g_pager_params, $g_archived_month_time;

        $q = "WHERE 1 ";

        // added by nancy xu 2011-02-17 17:24
        $direction = $sort = '';
        if (isset($p['sort']) && !empty($p['sort'])) {
            $sort = $p['sort'];
        } else {
            $sort = 'cc.campaign_id';
        }
        if (isset($p['direction']) && !empty($p['direction'])) {
            $direction = $p['direction'];
        }
        $order_by = ' ORDER BY ' . $sort  . ' '. $direction;
        //end

        // added by snug xu 2006-11-24 15:52 - START
        // if the role of user is agency, only show user's own client campaign
        if (User::getRole() == 'agency')
        {
            $q .= " AND cl.agency_id='" . User::getID() . "'";
        }
        // added by snug xu 2006-11-24 15:52 - END
        // added by nancy xu 2010-06-04 15:10
        
        $archived_date = date("Y-m-d", $g_archived_month_time);
        if (isset($p['archived']) && $p['archived'] > -1) {
            $q .= ' AND cc.archived = '.  $p['archived']  . ' ';
        }
        // end
        // added by nancy xu 2011-02-02 0:13
        if (User::getPermission() == 4) {
            $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
        }
        //end
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }

        $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
        if ($client_id != '') {
            $q .= "AND cc.client_id = '".$client_id."' ";
        }

        $campaign_name = addslashes(htmlspecialchars(trim($p['campaign_name'])));
        if ($campaign_name != '') {
            $q .= "AND cc.campaign_name LIKE '%".$campaign_name."%' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "AND cc.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "AND cc.date_end <= '".$date_end."' ";
        }
        $campaign_requirement = addslashes(htmlspecialchars(trim($p['campaign_requirement'])));
        if ($campaign_requirement != '') {
            $q .= "AND cc.campaign_requirement LIKE '%".$campaign_requirement."%' ";
        }

        //$q .= "AND (cc.permission < '".User::getPermission()."' OR cc.user_id = '".User::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(`cl`.`user_name`, `cl`.`company_name`, `cl`.`company_address`, `cl`.`city`, `cl`.`state`, `cl`.`zip`, `cl`.`company_phone`, `cl`.`email`, `cc`.`campaign_name`, `cc`.`campaign_requirement`)")." ";
            }
        }
        $q .= "AND cl.client_id = cc.client_id ";

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "AND cc.client_id = '".Client::getID()."' ";
        }

        $count_q = $q;
        $user_id = User::getID();
        if (User::getPermission() == 1.2 || User::getPermission() == 3) {
            $count_q .= "AND cc.campaign_id=ik.campaign_id AND ik.keyword_status != '0'  AND ik.`status`!='D' ";
            if (User::getPermission() == 1.2) {
                $count_q .= " AND ik.copy_writer_id = '".$user_id."' ";
            } else if (User::getPermission() == 3) {
                $count_q .= " AND ik.editor_id = '".$user_id."' ";
            }
        }
        $csql = "SELECT COUNT(DISTINCT cc.campaign_id) AS count FROM client_campaigns AS cc ";
        $csql .= "LEFT JOIN `client` AS cl  ON  cl.client_id = cc.client_id ";
        $csql .= "LEFT JOIN " . self::getTable() . " AS ik ON cc.campaign_id=ik.campaign_id " . $count_q;
        $rs = &$conn->Execute($csql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        if (User::getPermission() == 1.2) {
            $q .= " AND ik.copy_writer_id = '".User::getID()."' " ;
            $q .= " AND ik.keyword_status != '0'  " ;
            $q .= " AND cc.campaign_id=ik.campaign_id  " ;
            $q .= " AND `ik`.`status`!='D'  " ;
        } else if (User::getPermission() == 3) {
            $q .= " AND ik.editor_id = '".User::getID()."' " ;
            $q .= " AND cc.campaign_id=ik.campaign_id  " ;
            $q .= " AND `ik`.`status`!='D'  " ;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems'   => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT  cl.*, cc.*, csg.style_id, cc.is_import_kw, ". 
             // get total designer finished image 
              "COUNT( im.image_id) as total_gc, " . 
             " u.user_name AS project_manager, ik.copy_writer_id, creator.user_name AS creator_user \n".  
             "FROM `client` AS cl \n" .
             "LEFT JOIN client_campaigns AS cc ON (cl.client_id = cc.client_id) \n" .
             "LEFT JOIN `campaign_style_guide` AS csg ON (csg.campaign_id=cc.campaign_id) \n" .
             "LEFT JOIN " . self::getTable(). " AS ik ON (ik.campaign_id=cc.campaign_id) \n" .
             "LEFT JOIN users AS creator ON (creator.user_id=cc.creation_user_id) \n" .
             "LEFT JOIN " . Image::getTable() . " AS im ON (ik.keyword_id=im.keyword_id AND im.image_status = '1' )  \n" .
             "LEFT JOIN `users` AS u ON (u.user_id = cl.project_manager_id) \n".
             $q . 
             // " AND ik.status!='D' "  . 
             // get total designer finished image
             " GROUP BY cc.campaign_id "
              .  $order_by;

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
        if (!empty($result)) {
            foreach ($result as $kr => $vr) 
			{
                $clause_from = ' LEFT JOIN ' . self::getTable(). ' AS ik ON (im.keyword_id = ik.keyword_id) ';
				$clause_where= " AND  ik.campaign_id = {$vr['campaign_id']} AND ik.status!='D' ";
				if (User::getPermission() == 1.2) {
					$clause_where .= " AND ik.copy_writer_id=". User::getID() . " ";
                    $clause_where .= " AND ik.keyword_status != '0' ";
					$key_count = self::countImageBySubWhere( 'all', 2 , $clause_from, $clause_where );
				} else {
					$key_count = self::countKeywordByCampaignID($vr['campaign_id']);
				}
                if ($key_count > 0) {
					if( client_is_loggedin() ) {
						$clause_from .= "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ik.campaign_id) ";
						$clause_where .= "AND cc.client_id = '".Client::getID()."' ";
						$image_count = self::countImageBySubWhere('4|5|6|99',  2 , $clause_from, $clause_where );
					} else {
						$image_count = self::countImageBySubWhere('0|2', 3 , $clause_from, $clause_where );
					}
                    $result[$kr]['progress'] = ($image_count / $key_count) * 100;
                    if (empty($key_count)) $result[$kr]['progress'] = 100;
                } else {
                	$result[$kr]['progress'] = 0;
                }
            }
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);

    }


    /**
     * Search Image keywords info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function search($p = array(), $show_kd_groupby_topic = false)
    {
        global $conn, $feedback, $g_archived_month_time;

        global $g_pager_params, $g_assign_interval;
         
        $show_cb = false;

        $q = "";
        // added by snug xu 2006-11-24 20:34
        // if login user is agency, it is not allowed that he/she see the other keyword
        if (User::getRole() == 'agency' )
        {
            $q .= "\n AND cl.agency_id = '" . User::getID() . "'";
        }
        // added by nancy xu 2010-06-04 13:43
        /*$archived = isset($p['archived']) ? $p['archived'] : 0;
        $approval_date = date('Y-m-d H:i:s', $g_archived_month_time);
        if ($archived == 1) {
            $q .= "\n AND " . '(im.image_status = 5 || im.image_status = 6) && im.client_approval_date < \'' . $approval_date. '\'';
        } else {
            $q .= "\n AND " . ' ((im.image_status != 5 &&  im.image_status != 6) || ((im.image_status = 5 || im.image_status = 6) && im.client_approval_date >=  \'' . $approval_date. '\')) ';
        }*/
        // end
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            if (is_numeric($campaign_id)) {
                $q .= "\nAND ik.campaign_id =  '".$campaign_id."' ";
            } else {
                $q .= "\nAND ik.campaign_id IN ( ".$campaign_id." ) ";
            }
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "\nAND ik.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "\nAND ik.copy_writer_id = '".$copy_writer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "\nAND ik.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "\nAND ik.creation_user_id = '".$creation_user_id."' ";
        }

        $subcid = addslashes(htmlspecialchars(trim($p['subcid'])));
        if ($subcid != '') {
            $q .= "AND ik.subcid LIKE '%".$subcid."%' ";
        }

        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type != '') {
            $q .= "\nAND ik.image_type = '".$image_type."' ";
        }

        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "\nAND ik.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "\nAND ik.date_end <= '".$date_end."' ";
        }

        if (isset($p['submit_date_start']) && !empty($p['submit_date_start'])) {
            $submit_date_start = $p['submit_date_start'];
            $q .= "\nAND im.cp_updated >= '".$submit_date_start."' ";
        }

        if (isset($p['submit_date_end']) && !empty($p['submit_date_end'])) {
            $submit_date_end = $p['submit_date_end'];
            $q .= "\nAND im.cp_updated <= '".$submit_date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "\nAND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }
        
        $image_status = $p['image_status'];
        if (is_array($image_status) && !empty($image_status))
        {
            $q .= "\nAND im.image_status IN ('". implode("', '", $image_status)."') ";
        } else {
            $image_status = addslashes(htmlspecialchars(trim($image_status)));
            if ($image_status != '') {
                if ($image_status == -1) {
                    $q .= "\nAND ik.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND im.image_status = '".$image_status."' ";
                }
            }
        }
		//START: ADDED By Snug 16:02 2006-8-17
		$is_today = addslashes(htmlspecialchars(trim($p['is_today'])));
		if ($is_today ==1 ) 
		{
			$q .= "\n AND DATEDIFF( im.approval_date, CURDATE( ) ) =0";
        }
		//END ADDED
        

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ik.keyword, ik.keyword_description, im.image_number, im.title)")." ";
            }
        }
        //$q .= "AND cl.client_id = cc.client_id ";
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "\nAND cc.client_id = '".Client::getID()."' ";
        } else {
            $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
            if ($client_id > 0) {
                $q .= "\nAND cc.client_id = '". $client_id ."' ";
            }
        }
        
        $where = "\nWHERE 1 {$q} AND ik.status!='D' AND cc.campaign_type = 2 "; // 1:article; 2:image
		$query = "\nSELECT COUNT(DISTINCT ik.keyword_id) AS count ".
                "\nFROM " .self::getTable(). " AS ik ".
                "\nLEFT JOIN " . Image::getTable() . " AS im ON (im.keyword_id = ik.keyword_id) ".
                "\nLEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) ".
                "\nLEFT JOIN users AS cu ON (ik.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
                $where;
        $rs = &$conn->Execute($query);
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

        if ($show_kd_groupby_topic == true) {

        }

        $q = "SELECT DISTINCT `ik`.`keyword_id`, `ik`.`campaign_id`, `cc`.`campaign_name`, `ik`.`copy_writer_id`, \n". 
            "`ik`.`editor_id`, `ik`.`keyword`, `ik`.`image_type`, `ik`.`keyword_description`, `ik`.`date_assigned`, \n" . 
            "`ik`.`date_start`, `ik`.`date_end`, `ik`.`creation_user_id`, `ik`.`creation_role`, `ik`.`cp_status`,`ik`.`cp_accept_time`, `ik`.`editor_status`,ik.subcid,  \n" .
            "`ik`.`status`,  `it`.parent_id ,`ik`.`is_sent`,`im`.`cp_updated`, \n" . 
            "im.image_id, im.image_number, im.approval_date,im.client_approval_date,  \n" . 
            "im.image_status,  cl.user_name, cl.company_name, cc.campaign_name , \n" . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name, uc.pay_level, CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name , cu.user_name AS cu_name  \n" . 
             "FROM " .self::getTable(). " AS ik \n".
             "LEFT JOIN " . Image::getTable() . " AS im ON (im.keyword_id = ik.keyword_id) ".
             "LEFT JOIN image_type AS `it` ON (ik.image_type = `it`.`type_id`) \n".
             "LEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) \n".
             "LEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) \n".
             "LEFT JOIN users AS cu ON (ik.creation_user_id = cu.user_id) \n".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) \n".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id)".
             $where  ;
        $q .= "\nGROUP BY im.image_id";
        $q .= "\nORDER BY ik.keyword_id DESC  ";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        $keyword_ids = array();
        $permission = User::getPermission();
        $now_time = time();
        $role = User::getRole();
        if ($rs) {
            $result = array();
            $kb = array();
            $i = 0;
            $show_assign_cb = false;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                // added by nancy xu 2012-05-08 13:25
                $fields['show_assign_cb'] = ($fields['cp_status'] == 1 && $fields['editor_status']== 1) ? false : true;
                if ($fields['image_status'] == 0 && ($fields['cp_status'] == -1 || $fields['editor_status'] ==-1)&& $fields['editor_id'] > 0 && $fields['copy_writer_id'] > 0 && $fields['show_assign_cb']) {
                    $cp_accept_time = $fields['cp_accept_time'];
                    $interval = ($now_time - strtotime($fields['date_assigned']))/3600;
                    if ($interval <= $g_assign_interval) {
                        $fields['show_assign_cb'] = false;
                    } else if ($cp_accept_time) {
                        $interval = ($now_time - strtotime($cp_accept_time))/3600;
                        if ($interval <= $g_assign_interval) {
                            $fields['show_assign_cb'] = false;
                        }
                    }
                }              
                if (!$show_assign_cb && $fields['show_assign_cb']) $show_assign_cb = true;
                // end

                $result[$i] = $fields;

                // added by snug xu 2007-03-14 9:09  - STARTED
                if ($rs->fields['image_status'] == '3') {
                    $show_cb = true;
                }
				if ($show_kd_groupby_topic == true) {
                    if ($rs->fields['keyword_description'] != '') {
                        $kb[$rs->fields['keyword_id']] = $rs->fields['keyword_description'];
                    }
                }
                $keyword_ids[] = $rs->fields['keyword_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
		//Start:Added By Snug 23:02 2006-08-15
		/***get notes by keyword_id***/
        //$result = self::getNotesByKeywords($result, $keyword_ids);
		//END ADDED

        if ($show_kd_groupby_topic == true) {
            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'kb'     => $kb,
                         'count'     => $count,
                         'show_assign_cb'     => $show_assign_cb,
                         'result' => $result);
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     'show_assign_cb' => $show_assign_cb, 
                     'show_cb' => $show_cb
                     );

    }//end search()

    /**
    * Batch assign keyword to editor and designers
    *
    * We can change article type,editor,designer that they are opposite the keyword 
    *
    * @param array $p 
    * @return boolean if success return true，else return false
    */
    function batchAssignKeyword($p = array()) 
    {
        global $conn, $feedback;
        require_once CMS_INC_ROOT . "/request_extension.class.php";
        if (empty($p['keyword_id'])) {
            $feedback = 'Please provide at least one keyword';
            return false;
        }
        $is_forced = trim($p['is_forced']);
        if (empty($p['keyword_id']))  {
            $feedback = "Please choose one keyword.";
            return false;
        } else {
			$keywords = implode("','", $p['keyword_id'] );
			$keywords = trim($keywords, "','" );
			$clause_where = " AND im.keyword_id IN ('". $keywords. "')";
			$count = self::countImageBySubWhere( '5|6|99', 2, '', $clause_where );
			if ($count >0 ) {
				$feedback = "You choose the completed image. Please to cheik.";
				return false;
			}
		}
        $qu = "";
        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $no_date_end = $no_date_start = $no_cp = $no_editor = false;
        if ($copy_writer_id != '') {
            $qu .= "copy_writer_id = '".$copy_writer_id."', ";
        } else {
            $no_cp = true;
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $qu .= "editor_id = '".$editor_id."', ";
        } else {
            $no_editor = true;
        }

        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));

        if ($editor_id == '' && $copy_writer_id == '' && $date_start == '' && $date_end == '') {
            $feedback = 'Please choose a designer or a editor for keyword';
            return false;
        }

        // added by nancy xu 2011-7-29 15:15
        // check if is no editor or no designer
        $is_no = (strlen($editor_id) && $editor_id == 0 || strlen($copy_writer_id) && $copy_writer_id == 0) ? true : false;
        // end
        
        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type != '') {
            $qu .= "image_type = '".$image_type."', ";
        }
        
        if (!empty($date_end) && !empty($date_start) && strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }

        if (!empty($date_end)) {
            $qu .= "date_end = '".$date_end."', ";
        } else {
           $no_date_end = true;
        }
        if (!empty($date_start)) {
            $qu .= "date_start = '".$date_start."', ";
        } else {
            $no_date_start = true;
        }

		$keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
		if ($keyword_description != '') {
			if (trim($p['new_or_append']) == 'Append') {
				$qu .= "keyword_description = CONCAT(`keyword_description`, '".$keyword_description."'), ";
			} elseif (trim($p['new_or_append']) == 'New') {
				$qu .= "keyword_description = '".$keyword_description."', ";
			} else {
				//do nothing;
			}
		}

		/*$notes = mysql_escape_string(htmlspecialchars(trim($p['notes'])));
		if( strlen($notes) ) {
			if (trim($p['new_or_append']) == 'Append')  {
				$en .= "notes = CONCAT_WS('\n', `notes`, '".$notes."'), ";
			} elseif (trim($p['new_or_append']) == 'New')  {
				$en .= "notes = '".$notes."', ";
			}  else {
				//do nothing;
			}
		}*/
		$campaign_id = mysql_escape_string(htmlspecialchars(trim($p['campaign_id'])));
		if( $campaign_id>0 ) {
			$en .= "campaign_id={$campaign_id}, ";
		} else {
			$feedback = 'There is no campaign';
			return false;
		}

        		    
        if (!isset($p['is_forced_not_free']) || empty($p['is_forced_not_free'])) {
            $param = array(
                'user_id' => $copy_writer_id,
                'is_free' => 0,
                '>=' => array('c_date' => $date_start),
                '<=' => array('c_date' => $date_end),
            );
            $dates = UserCalendar::getListByParam($param);
            if (!empty($dates)) {
                $feedback = 'The designer is not free in ' . implode(', ', $dates) . ".\\n please try again";
                return false;
            }
            // added by nancy xu 2010-01-18 13:53
            // check whether editor is free or not
            if ($editor_id > 0) {
                $param['user_id'] = $editor_id;
                $dates = UserCalendar::getListByParam($param);
                if (!empty($dates)) {
                    $feedback = 'The editor is not free in ' . implode(', ', $dates) . ".\\n please try again";
                    return false;
                }
            }
            // end
        }

        // get all cp payment report for check each keyword payment status
        $email_keywords = array();
        //$pay_report = User::getCpPaymentHistory(array(), false); 

        $date_assignment = date('Y-m-d H:i:s', time());
        $copy_writer_keywords = array();
        $all_cp_ids = $all_editor_ids = $editor_ids = $cp_ids = $campaign_ids = $keyword_ids = $editor_keywords = array();
        foreach ($p['keyword_id'] AS $k => $v) {
			$keyword_id = mysql_escape_string(htmlspecialchars(trim($p['keyword_id'][$k])));
            $is_reassigned = true;
            // get keyword and its article information by keyword_id
            // set keyword status as writing after reassignment
            $old_info = Image::getInfoByKeywordID($keyword_id);
            $campaign_id = $old_info['campaign_id'];
            $old_status = $old_info['image_status'];
            if ($no_cp) $copy_writer_id = $old_info['copy_writer_id'];
            if ($no_editor) $editor_id = $old_info['editor_id'];
            if ($no_date_end) $date_end = $old_info['date_end'];
            if ($no_date_start) $date_start = $old_info['date_start'];
            if ($copy_writer_id != '' && $copy_writer_id != $old_info['copy_writer_id']  
                ||  $editor_id != ''  && $editor_id != $old_info['editor_id'] || !$no_date_end || !$no_date_start) {
                if ($is_no && $old_status != '0') {
                    $feedback = 'Image status is not writing, you can\'t reassign it to No Editor or No Copy Writer';
                    return false;
                }
                
                /*if (!$is_no || $copy_writer_id != '' || $editor_id != '') {
                    if (!isset($date_ends[$date_end])) $date_ends[$date_end] = array();
                    if (!isset($campaign_ids[$campaign_id])) $campaign_ids[$campaign_id] = array();
                    // get keywords that images.is_sent = 0 
                    // and cp_payment_history.payment_flow_status is 'cpc' or 'paid'
                   $allow_status = array('3', '4');
                    if (in_array($old_status, $allow_status)) {
                        $month = changeTimeToPayMonthFormat(strtotime($old_info['google_approved_time']));
                        $payment_status = $pay_report[$old_info['copy_writer_id']][$month]['payment_flow_status'];
                        if ($payment_status == 'cpc' || $payment_status == 'paid') {
                           // if $is_foreced = true and new copy wirter is not equal to raw designer, 
                           // collect the abnormal reassignment keywords 
                           if ($is_forced || $copy_writer_id == $old_info['copy_writer_id']) {
                              if ($copy_writer_id != $old_info['copy_writer_id'] && $old_info['is_sent'] == 0)
                                  $email_keywords[$old_info['editor_id']][$old_info['copy_writer_id']][] = $old_info;
                           } else {
                               $is_reassigned = false;
                               unset($p['keyword_id'][$k]);
                               $image_id = $old_info['image_id'];
                               if ($payment_status == 'cpc') {
                                   $cpc_images[$image_id] = $old_info['keyword'];
                               } else if ($payment_status == 'paid') {
                                   $paid_images[$image_id] = $old_info['keyword'];
                               }
                           }
                        }
                    }
                }*/

                if ($is_reassigned) {
                    if ($old_info['image_id'] && !$is_no) {
                        $p['image_id'] = $old_info['image_id'];
                        self::eRaseImageInfo($p);
                        // added by nancy xu 2012-08-02 18:34
                        /*foreach ($old_info as $kk => $vv) {
                            if ($old_info['copy_writer_id'] != $copy_writer_id || $old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id)  {
                                if ($kk == 'keyword' || $kk== 'date_end'|| substr($kk,0,8) == 'optional') {
                                    if ($kk == 'keyword' ) {
                                        $kk = 'keywords';
                                    } else if ($kk == 'date_end') {
                                        $kk = 'dateend';
                                    }
                                    if ($old_info['copy_writer_id'] != $copy_writer_id) {
                                        $copy_writer_keywords[$campaign_id][$date_end][$kk][$keyword_id] = $vv;
                                    }
                                    $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id][$kk][$keyword_id] = $vv;
                                }
                            }
                        }*/ // end
                        if ($old_info['copy_writer_id'] != $copy_writer_id) { //modified by leo.
                            if ($old_info['editor_id'] != $editor_id) $editor_ids[] = $editor_id;
                            $cp_ids[]      = $copy_writer_id;
                            $all_editor_ids[] = $editor_id;
                            $all_cp_ids[] = $copy_writer_id;
                            $campaign_ids[$campaign_id][$date_end] = $date_end;
                            $ret = Image::setImageStatus($old_info['image_id'], 0, $old_info['image_status'], $copy_writer_id);
                            if ($old_info['is_sent'] == 1)
                                $qu .= " is_sent=0, ";
                            RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $old_info['campaign_id']));
                        } else if ($old_info['is_sent'] == 0 && count($email_keywords)) {
                            $qu .= " is_sent=1, ";
                        }
                        // modified by nancy.
                        if ($old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id) { 
                            $campaign_ids[$campaign_id][$date_end] = $date_end;
                            $editor_ids[] = $editor_id;
                            $all_editor_ids[] = $editor_id;
                            $all_cp_ids[] = $copy_writer_id;
                        }
                    }
                    $qset = $qu;
                    if ( in_array($old_info['image_status'], array('0','1','2','3'))) {
                        if ($copy_writer_id != $old_info['copy_writer_id'] ) {
                            $qset .= "`cp_accept_time` = null,  `cp_status`=-1, ";
                        } else if ($old_info['cp_status']) {
                            $qset .= "`cp_accept_time` = '" . $date_assignment . "', ";
                        }
                        if ($editor_id != $old_info['editor_id'] && $old_info['editor_status']!=-1) {
                            $qset .= " `editor_status`=-1, " ;
                        }
                    }
                    
                    if ($no_cp && $no_editor || $is_no ) {
                        $qset = trim($qset, ' ,');
                    } else {
                       $qset .= "`date_assigned` = '".$date_assignment."' ";
                    }
                    $conn->Execute("UPDATE  " . self::getTable() .
                               // "SET copy_writer_id = '".$copy_writer_id."', ".
                                " SET " .  $qset . " WHERE keyword_id = '".$keyword_id."' ");

                    /*$note_id = mysql_escape_string(htmlspecialchars(trim($p['note_id'][$k])));
                    if( strlen( $notes ) ) {
                        if (empty($copy_writer_id)) $copy_writer_id = 0;
                        if (empty($editor_id)) $editor_id = 0;
                        if( $note_id > 0) {
                            $query = "UPDATE editor_notes ".
                                    "SET copy_writer_id='{$copy_writer_id}', ".
                                    $en . 
                                    "editor_id='{$editor_id}', ".
                                    "keyword_id='{$keyword_id}' ".
                                    "WHERE note_id='{$note_id}'";
                        } else {
                            $query = "INSERT INTO editor_notes (notes, keyword_id, campaign_id, copy_writer_id, editor_id)".
                            " VALUES ('{$notes}', '{$keyword_id}', '{$campaign_id}', '{$copy_writer_id}', '{$editor_id}') ";
                        }
                        $conn->Execute( $query );
                    }*/
                }
            } else {
            	unset($p['keyword_id'][$k]);
            }
        }
        /*if (!$is_no) {
            if (count($copy_writer_keywords) || count($editor_keywords)) {
                if (!$no_editor)  $editor_ids = array($editor_id);
                if (!$no_cp) $cp_ids = array($copy_writer_id);
                if (!empty($copy_writer_keywords) && !empty($cp_ids)) self::sendBatchAssignKeywordMail($all_editor_ids, $cp_ids, $campaign_ids, $copy_writer_keywords, true);
                if (!empty($editor_keywords)  && !empty($editor_ids)) self::sendBatchAssignKeywordMail($editor_ids, $all_cp_ids, $campaign_ids, $editor_keywords, false);
            }
            if (count($email_keywords)) {
                // send email to tara and tony
                self::sendAccountingChangeEmail($email_keywords, $copy_writer_id, $editor_id);
            }
        }*/

        $feedback = "Success";
        // get feedback
        if (!$is_no) {
            if (isset($cpc_images) && count($cpc_images)) {
                $feedback .= "\\n";
                $feedback .= "One or more image(s) have been confirmed by designer.\\n";
                $feedback .= "You can reassign those images forcedly.(please click forced assign checkbox).";
            }
            if (isset($paid_images) && count($paid_images)) {
                $feedback .= "\\n";
                $feedback .= "One or more image(s) have been paid by Infinitenine.\\n";
                $feedback .= "You can reassign those images forcedly.(please click forced assign checkbox).";
            }
        }
        return true;
    }//end batchAssignKeyword()

	/*add by snug 14:44 2006-07-30
	 *@param $image_status:'all' means no status restriction;$image_status:'4|5' means mutil status
	 *@param $is_single_status:0 means neq status; 1 means single status ; 2 means multil status
	 *@param  $clause_from: decided by user
	 *@param  $clause_where: decided by user
	*/
	function countImageBySubWhere( $image_status = 'all', $is_single_status=1, $clause_from , $clause_where )
	{
		global $conn, $feedback;
		$where = "WHERE 1=1 ";
		if ($image_status != 'all')
		{
			switch( $is_single_status )
			{
				case 0:
					$where .=" AND im.image_status != '".$image_status."' ";
					break;
				case 1:
					$where .= "AND im.image_status = '".$image_status."' ";
					break;
				case 2:
					$where .=" AND im.image_status REGEXP '^(".$image_status.")$' ";
					break;
				case 3:
					$where .=" AND im.image_status NOT REGEXP '^(".$image_status.")$' ";
					break;
			}
		}
		$where = $where . $clause_where;
		$query = "SELECT COUNT( im.image_id ) AS count FROM " . Image::getTable() . " AS im $clause_from $where";
        return $conn->GetOne($query);
	}//end

    function countKeywordByCampaignID($campaign_id)
    {
        global $conn, $feedback;
        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        $q = "SELECT COUNT(*) AS count FROM " . self::getTable(). " AS ik WHERE campaign_id = '".$campaign_id."' AND ik.status!='D' ";
        $count = $conn->GetOne();
        return $count;
    }//end countKeywordByID()

    function eRaseImageInfo($p = array())
    {
        global $conn, $feedback, $handle;
        
        $action_info = array();
        //global $g_tag;
        // if togle $p['is_reserve_content'], reserve the image content, else erase the image conetent
        $is_reserve_content = isset($p['is_reserve_content']) ? $p['is_reserve_content'] : 0;

        $image_id = addslashes(htmlspecialchars(trim($p['image_id'])));
        if ($image_id == '') {
            $feedback = "Please Choose a image";
            return false;
        }

        if (empty($is_reserve_content))
        {
            $qu .= "title = '', ";
            $qu .= "html_title = '', ";
            $qu .= "image_name = '', ";
            $qu .= "image_param = '' ";
            $sql = "UPDATE " .Image::getTable() . " SET ";
            $sql .= $qu;
            $sql .= "WHERE image_id = '{$image_id}'";
            $conn->Execute($sql);
        }
        return true;
    }

    function getAssignedKeywords($p = array())
    {
        global $g_pager_params, $conn, $g_assign_interval;
        if (user_is_loggedin()) {
            $role = User::getRole();
            $user_id = User::getID();
        } else {
            $role = 'client';
        }
        $conditions = array('ik.copy_writer_id > 0 ', 'ik.editor_id > 0');
        if ($role == 'editor' || $role == 'designer') {
            //$conditions[] = '(ik.editor_status = -1 OR ik.cp_status = -1)';
        }
        if ($role == 'designer') {
            $conditions[] = 'im.image_status=0';
        } else {
            $conditions[] = "im.image_status IN ('0','1','2', '3') ";
        }
        if (isset($p['editor_status']) && strlen($p['editor_status'])) {
            $conditions[] = 'ik.editor_status=' . $p['editor_status'];
        }
        if (isset($p['cp_status']) && strlen($p['cp_status'])) {
            $conditions[] = 'ik.cp_status=' . $p['cp_status'];
        }
        if (isset($p['copy_writer_id']) && strlen($p['copy_writer_id'])) {
            $conditions[] = 'ik.copy_writer_id=' . $p['copy_writer_id'];
        }

        if (isset($p['campaign_id']) && strlen($p['campaign_id'])) {
            $conditions[] = 'ik.campaign_id=' . $p['campaign_id'];
        }

        if (isset($p['editor_id']) && strlen($p['editor_id'])) {
            $conditions[] = 'ik.editor_id=' . $p['editor_id'];
        }
        if ($role == 'editor') {
            $conditions[] = 'ik.editor_id=' . $user_id;
            //$conditions[] = 'ik.cp_status=1';
        }else if ($role == 'designer') {
            $conditions[] = 'ik.copy_writer_id=' . $user_id;
        }
        if (isset($p['image_status']) && strlen($p['image_status'])) {
            $conditions[] = 'im.image_status=' . $p['image_status'];
        }
        $where = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $from_q = ' FROM ' . self::getTable() . ' AS ik ';
        $from_q .= 'LEFT JOIN ' .Image::getTable(). ' AS im ON im.keyword_id = ik.keyword_id ' ;
        
        $count_q = 'SELECT COUNT(ik.keyword_id) ' . $from_q .  $where;
        $count = $conn->GetOne($count_q);
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

        list($from, $to) = $pager->getOffsetByPageId();
        $q =" SELECT `cc`.`campaign_name`, ik.editor_status, ik.cp_status, ik.editor_id, ik.copy_writer_id, ik.keyword, im.image_number, im.image_status, ik.image_type,ik.keyword_id, ik.campaign_id, im.image_id, ik.date_start, ik.date_end, ik.date_assigned, ik.cp_accept_time  ,CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name, ue.user_name AS ue_name,  cp.user_name AS cp_name, CONCAT(cp.first_name, ' ', cp.last_name) AS cp_name " 
            . $from_q
            . 'LEFT JOIN users AS ue ON (ue.user_id = ik.editor_id) '
            . 'LEFT JOIN users AS cp ON (cp.user_id = ik.copy_writer_id) '
            . 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) '
            . $where . ' ORDER BY `ik`.date_assigned DESC '
            ;
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        $result  = array();
        $now = time() ;
        $show_cb = false;
        $keyword_ids = array();
        if ($rs) {
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $assigned_time = strtotime($fields['date_assigned']);
                if ($fields['cp_status'] == -1) { 
                    $cp_interval = ($now - $assigned_time)/3600;
                    $old_cp_status = $fields['cp_status'];
                    if ($cp_interval >= $g_assign_interval) {
                        $fields['cp_status'] = 0;
                    }
                }
                if ($fields['editor_status'] == -1) { 
                    $cp_accept_time = $fields['cp_accept_time'];
                    $interval = $cp_accept_time > 0 ?  (($now - strtotime($cp_accept_time))/3600) :($old_cp_status == -1 &&$fields['cp_status'] == 0 ? $cp_interval:0);
                   // if (empty($interval) && $fields['cp_status'] == 0) $interval = $cp_interval;
                    if ($interval >= $g_assign_interval) {
                        $fields['editor_status'] = 0;
                    }
                }
                if ((($role == 'admin' || $role == 'project manager') && ($fields['cp_status']== 0 || $fields['editor_status'] == 0)|| $role == 'editor'  && $fields['editor_status'] == -1   || $role == 'designer' && $fields['cp_status'] == -1) ) { // && $fields['cp_status'] == 1
                    if (!$show_cb ) $show_cb = true;
                    $fields['show_cb'] = true;
                }
                $keyword_ids[] = $fields['keyword_id'];
                $result[] = $fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        //$result = self::getNotesByKeywords($result, $keyword_ids);
        return array('pager'  => $pager->links,
             'total'  => $pager->numPages(),
             'result' => $result,
             'count' => $count,
             'show_cb' => $show_cb,
        );
    }

    function updateKeyword($p = array(), $conditions = array())
    {
        global $conn, $feedback;
        extract($p);
        if (empty($keyword_id)) {
            $feedback = 'Invalid Keyword, please specify the keyword';
            return false;
        }
        $sets = array();
        unset($p['keyword_id']);
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\'' .  addslashes($v) . '\'';
        }
        $where = ' WHERE keyword_id = \'' . $keyword_id . '\'';
        if (!empty($conditions)) {
            $where .= ' AND ' . implode( ' AND ', $conditions);
        }
        // modified by nancy xu 2012-08-05 18:23
        $result = array();
        /*if ((isset($p['cp_status']) && $p['cp_status'] <> -1) ||  (isset($p['editor_status']) && $p['editor_status'] <>-1)) {
            $result = self::generateDataToSendEmail($where, $p);
        } else {
            
        }*/
        // end
        $sql = 'UPDATE ' . self::getTable() . ' SET ' . implode(',', $sets) . $where;
        $conn->Execute($sql);
        // added by nancy xu 2012-07-26 14:27
        // generate assigned denied notification
        if (isset($p['editor_status']) || isset($p['cp_status'])) {
            $p['keyword_id'] = $keyword_id;
            self::generateNoteForDeny($p);
        }
        // end
       /*if (!empty($result)) {
            extract($result);
            if (!empty($editor_ids) && !empty($data)) {
                // send email to editor
                foreach ($data as $rows) {
                    self::sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $rows, false);
                }
            }
       }*/
       $feedback = 'Success!';
        return true;
    }

    function batchUpdateKeyword($p = array()) 
    {
        global $feedback, $conn;
        if (!isset($p['keyword_id']) || empty($p['keyword_id'])) {
            $feedback = 'Please choose keywords';
            return false;
        }
        if (is_array($p['keyword_id'])) {
            $keyword_ids = $p['keyword_id'];
        } else {
            $keyword_ids = array($p['keyword_id']);
        }
        unset($p['keyword_id']);
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\''. addslashes($v) . '\'';
        }
        $where = ' WHERE keyword_id IN( \'' . implode("','", $keyword_ids) . '\')';
        // we didn't send email to editor/designer again.
        // $result = self::generateDataToSendEmail($where, $p);
        $sql = 'UPDATE ' . self::getTable(). '  SET ' . implode(',', $sets) . $where;
        $conn->Execute($sql);
        // added by nancy xu 2012-07-26 14:27
        // generate assigned denied notification
        if (isset($p['editor_status']) || isset($p['cp_status'])) {
            $p['keyword_id'] = $keyword_ids;
            self::generateNoteForDeny($p);
        }
        // end
        /*extract($result);
        if (!empty($result) && !empty($editor_ids) && !empty($data)) {
            // send email to editor
            foreach ($data as $rows) {
                self::sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $rows, false);
            }
        }*/
        $feedback = 'Success!';
        return true;
    }

    function generateNoteForDeny($p)
    {
        global $conn, $g_note_fields;
        $fields = $g_note_fields;

        $user_status = isset($p['editor_status']) ? $p['editor_status'] : (isset($p['cp_status']) ? $p['cp_status']:-1);
        
        if ($user_status == 0) {
            $role = isset($p['editor_status']) ? 'editor':(isset($p['cp_status']) ? 'designer':'');            
            if ($role) { 
                if (isset($p['keyword_id']) && !empty($p['keyword_id'])) {
                    require_once CMS_INC_ROOT . DS . 'Notification.class.php';
                    $keyword_id = $p['keyword_id'];
                    if (is_array($keyword_id)) {
                        foreach ($keyword_id as $k => $v) {
                            $keyword_id[$k] = addslashes($v);
                        }
                        $where  = " WHERE `ik`.keyword_id  IN ('" . implode("','", $keyword_id). '\')';
                    } else {
                        $where  = ' WHERE `ik`.keyword_id  = \'' . addslashes($keyword_id). '\'';
                    }
                    $permission = User::getPermission();
                    if ($permission == 1  || $permission == 3) {
                        $user_id = User::getID();
                        $role = User::getRole();
                        $user_name = User::getName();
                        $join_part = $select_part = '';
                    } else {
                        $join_part = 'LEFT JOIN users AS  u ON (' . ($role == 'editor' ? '`ik`.editor_id' : '`ik`.copy_writer_id') . '=u.user_id)';
                        $select_part = ', u.user_name ';
                    }
                    $sql = "SELECT `ik`.keyword, `ik`.keyword_id, cc.campaign_name, cc.campaign_id, pm.user_id as pm_id, pm.role AS pm_role " . $select_part;
                    $sql .= "FROM " .self::getTable()." AS ck ";
                    $sql .= "LEFT JOIN client_campaigns AS cc ON `ik`.campaign_id=cc.campaign_id ";
                    $sql .= "LEFT JOIN client AS cl ON cl.client_id=cc.client_id ";
                    $sql .= "LEFT JOIN users AS pm ON pm.user_id=cl.project_manager_id ";
                    $sql .= $join_part . $where;
                    $result = $conn->GetAll($sql);
                    $date = date('Y-m-d H:i:s');
                    foreach ($result as $k => $row) {
                        if ($permission <> 1  && $permission <> 3) {
                            $user_name = $row['user_name'];
                        }
                        $campaign_id = $row['campaign_id'];
                        $keyword_id = $row['keyword_id'];
                        $keyword_link = '<a href="/graphics/assign_keyword.php?keyword_id=' . $keyword_id . '&frm=acceptance" target="_blank">' . $row['keyword'] . '</a>';
                        $campaign_link = '<a href="/graphics/acceptance.php?campaign_id=' . $campaign_id . '" target="_blank">' .  $row['campaign_name'] . '</a>';
                        $hash = array(
                            'keyword_id' => $keyword_id,
                            'campaign_id' => $campaign_id,
                            'campaign_name' => $row['campaign_name'],
                            'user_id' => $row['pm_id'],
                            'role' => $row['pm_role'],
                            'total' => 1,
                            'generate_date' => $date,
                            'field_name' => 'assigned_denied',
                            'notes' =>  sprintf($fields['assigned_denied'], $keyword_link, $campaign_link,$user_name),
                        );
                        Notification::save($hash);
                    }
                }
            }
        }
    }

    function getCampaignIDByKeywordID($keyword_id)
    {
        global $conn, $feedback;
        $keyword_id = mysql_escape_string( trim( $keyword_id ) );
        $sql = "SELECT `campaign_id` FROM " . self::getTable() . " WHERE keyword_id='$keyword_id' AND   `status`!='D' ";
        $campaign_id = $conn->GetOne($sql);
        if (strlen($campaign_id) == 0) $campaign_id = 0;
        return $campaign_id;
    }

    function getInfo($keyword_id)
    {
        global $conn, $feedback;
        $keyword_id = addslashes(htmlspecialchars(trim($keyword_id)));
        if ($keyword_id == '') {
            $feedback = "Please Choose a campaign keyword";
            return false;
        }

        $q = "SELECT ik.*, cc.campaign_name, cc.meta_param, cc.title_param, cc.max_word, cc.pay_type, cl.client_id,  cl.user_name, cl.company_name, csg.style_id, uc.user_name AS uc_name, ue.user_name AS ue_name, cu.user_name AS pm_name ".
             "FROM " . self::getTable() ." AS ik ".
             "LEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) ".
             "LEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) ".
             "LEFT JOIN users AS cu ON (ik.creation_user_id = cu.user_id) ".
             "LEFT JOIN client_campaigns AS cc ON (ik.campaign_id = cc.campaign_id) ".
             "LEFT JOIN campaign_style_guide AS csg ON (csg.campaign_id = cc.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE ik.keyword_id = '".$keyword_id."' AND ik.status!='D' ";
        $arr = $conn->GetAll($q);
        return !empty($arr) ? $arr[0] : false;
    }

    function reportCampaignByRole($is_archived = -1)
    {
        global $conn/*, $feedback*/;

        $total_keyword = 0;
        $total_image_in_queue = 0;
        $total_image_deliverd = 0;
        $total_aritcle_pending = 0;

        $qw = "WHERE 1 AND ik.status!='D'  ";

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $qw .= " AND cc.client_id = '".Client::getID()."' GROUP BY ik.campaign_id ";

            //total keyword
            $q = "SELECT COUNT(ik.keyword_id) AS count, cc.campaign_id, cc.campaign_name, cc.date_start, cc.date_end  FROM " . self::getTable() . " AS ik ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ". $qw ;
            $rs = &$conn->Execute($q);
			
            if ($rs) {
				    $result = array();
					$total_keyword=0;
                    $i = 0;
                    while (!$rs->EOF) {
                        $result[$rs->fields['campaign_id']] = $rs->fields;
						$total_keyword += $rs->fields['count'];
                        $rs->MoveNext();
                        $i ++;
                    }
                   $rs->Close();
            }
            $qw_all = $qw;
            if ($is_archived > -1) {
                $qw .= " AND cc.archived=" . $is_archived . ' ';
            }
            // total client approval articles this month
            $month_start = date("Y-m-01 00:00:00");
            $month_end = date("Y-m-d H:i:s");
            $q = "SELECT COUNT(im.image_id) AS count  ".
                 "FROM " . Image::getTable() . " AS im ".
                 "LEFT JOIN " . self::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                 "AND (im.image_status = '5' OR im.image_status = '6'  OR im.image_status = '99') " .
                "AND (im.client_approval_date>='{$month_start}' AND im.client_approval_date<='{$month_end}' ) ".
                "AND cc.client_id = '". Client::getID() . "' AND ik.status!='D'  " ;
            $total_month = $conn->GetOne($q);
            // tatal article which image status = 5 and image status = 6
            $q = "SELECT COUNT(im.image_id) AS count, cc.campaign_id ".
                 "FROM " . Image::getTable() . " AS im ".
                 "LEFT JOIN " . self::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                 "AND (im.image_status = '5' OR im.image_status = '6' OR im.image_status = '99' ) ".$qw;
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
				  $total_image_deliverd=0;
                    $i = 0;
					while (!$rs->EOF) {
                        $campaign_id = $rs->fields['campaign_id'];
                        $count = $rs->fields['count'];
                        $result[$campaign_id]['total_image_download'] = $count;
                        $result[$campaign_id]['total_finished'] = $count;
                        $result[$campaign_id]['percent'] = ($count/$result[$campaign_id]['count'])*100;
						$total_image_deliverd += $rs->fields['count'];
                        $rs->MoveNext();
                        $i ++;
                    }
                   $rs->Close();
            }

            //total article which article status = 4;
            $q = "SELECT COUNT(im.image_id) AS count, cc.campaign_id ".
                 "FROM " .Image::getTable() . " AS im ".
                 "LEFT JOIN " . self::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                 "AND im.image_status = '4' ".$qw;
            $rs = &$conn->Execute($q);
            if ($rs) {
					$total_aritcle_pending=0;
                    $i = 0;
					while (!$rs->EOF) {
                        $campaign_id = $rs->fields['campaign_id'];
                        $count = $rs->fields['count'];
                        $result[$campaign_id]['total_image_download'] += $count;
                        $result[$campaign_id]['total_image_approved'] = $count;
                        $result[$campaign_id]['percent'] = ($result[$campaign_id]['total_image_download']/$result[$campaign_id]['count'])*100;
						$total_aritcle_pending += $count;
                        $rs->MoveNext();
                        $i ++;
                    }
                $rs->Close();
            }
            if ($total_keyword > 0) {
			    $percent = ( ($total_aritcle_pending+$total_image_deliverd)*1.0/$total_keyword )*100;
            } else {
            	$percent = 0;
            }

            $total_image_in_queue = $total_keyword - $total_image_deliverd-$total_aritcle_pending;
            return array('total_keyword'          => $total_keyword,
                         'total_month' => $total_month,
                         'total_image_deliverd' => $total_image_deliverd,
                         'total_image_in_queue' => $total_image_in_queue,
                         'percent' => $percent,
                         'total_aritcle_pending'  => $total_aritcle_pending,
						 'report' => $result );
        } elseif (user_is_loggedin()) {

            $role = User::getRole();
            // added by nancy xu 2010-01-15 11:25
            $result = Notification::getNoticesByUserID(User::getID());
            $rs_stats = array();
            //do nothing;
            $now = time();

            $user_id = User::getID();
            if ($role == 'designer') {
                $qw .= "AND ik.copy_writer_id = '". $user_id ."' ";
                $qw .= "AND ik.keyword_status != 0 ";
            } else {
                $qw .= "AND ik.editor_id = '". $user_id ."' ";
            }

            $qw_all = $qw;
            if ($is_archived > -1) {
                $qw .= " AND cc.archived=" . $is_archived . ' ';
            }

            global $g_archived_month_time;
            $sql = 'SELECT cc.campaign_id, count(im.image_id) AS total '. "\n";
            $sql .= 'FROM client_campaigns AS cc'. "\n";
            $sql .= 'LEFT JOIN  ' . self::getTable() . ' AS ik ON (ik.campaign_id=cc.campaign_id) ' . "\n";
            $sql .= 'LEFT JOIN ' . Image::getTable() . ' AS im ON ik.keyword_id=im.keyword_id '. "\n";
            $sql .= $qw . ' and  cc.date_end < \'' . date("Y-m-d H:i:s", $g_archived_month_time). '\' '. "\n";
            $sql .= ' AND (im.image_status = \'5\' OR im.image_status =\'6\' OR im.image_status = \'99\') '. "\n";
            $sql .= 'GROUP BY ik.campaign_id ';
            $rs = &$conn->Execute($sql);
            $oldCampaigns = array();
            if ($rs) {
                while (!$rs->EOF) {
                    $oldCampaigns[$rs->fields['campaign_id']] = $rs->fields['total'];
                    $rs->MoveNext();
                    $i++;
                }
                $rs->Close();
            }
            // end
            if ($role == 'designer') {
                $q = "SELECT cc.campaign_id, cc.date_end, u.email as editor, cc.campaign_name, COUNT(ik.keyword_id) AS count FROM " . self::getTable() . " AS ik ".
                     "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                     "LEFT JOIN users AS u ON (u.user_id = ik.editor_id) ".
                     $qw.
                     "GROUP BY cc.campaign_id ORDER BY cc.date_end DESC";
            } else {
                 $q = "SELECT cc.campaign_id, cc.campaign_name, cc.date_end, u.email AS project_manager , COUNT(ik.keyword_id) AS count " 
                    ." FROM " . self::getTable(). " AS ik ".
                     "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) ".
                     "LEFT JOIN client AS c ON (c.client_id = cc.client_id) ".
                     "LEFT JOIN users AS u ON (u.user_id = c.project_manager_id) ".
                    $qw.
                     "GROUP BY cc.campaign_id ORDER BY cc.date_end DESC";
            }
            $rs = &$conn->Execute($q);
            $campaign_ids = array();
            if ($rs) {
                $i = 0;
                while (!$rs->EOF) {
                    $fields =  $rs->fields;
                    $count =  $fields['count'];
                    $campaign_id = $fields['campaign_id'];
                    $campaign_ids[] = $campaign_id;
                    $rs_stats[] = array('total' => $count, 'campaign_id' => $campaign_id);
                    $rs->MoveNext();
                    $i++;
                    $result['report'][$campaign_id] = $count;
                    $result['campaign'][$campaign_id] = $fields['campaign_name'];
                    $result['campaign']['date_end'][$campaign_id] = $fields['date_end'];
                    if (isset($fields['editor'])) 
                        $result['campaign']['editor'][$campaign_id] = $fields['editor'];
                    if (isset($fields['project_manager'])) 
                        $result['campaign']['pm'][$campaign_id] = $fields['project_manager'];
                }
                $rs->Close();
                if (is_array($result['report'])) {
                    $result['total_articles'] = array_sum(array_values($result['report']));
                } else {
                    $result['total_articles'] = 0;
                }
            }

            $group_field = 'campaign_id';
            self::getCountGroupByClients($rs_stats, 'total_client_approval', $campaign_ids, array( "im.image_status REGEXP  '^(5|6|99)$'"), $qw, $group_field, false);
            self::getCountGroupByClients($rs_stats, 'total_started', $campaign_ids, array( 'ik.copy_writer_id > 0', "im.image_status REGEXP  '^(1|2|3|4|5|6|99)$'"), $qw, $group_field, false);
            if ($role == 'designer') { 
                self::getCountGroupByClients($rs_stats, 'text_report', $campaign_ids, array( 'ik.copy_writer_id > 0', "im.image_status REGEXP  '^(1|3|4|5|6|99)$'"), $qw, $group_field, false);
                self::getCountGroupByClients($rs_stats, 'total_assign', $campaign_ids, array( 'ik.copy_writer_id > 0'), $qw, $group_field, false);
                self::getCountGroupByClients($rs_stats, 'working_on', $campaign_ids, array( "im.image_status REGEXP  '^(0|2)$'"), $qw, $group_field, false);
                self::getCountGroupByClients($rs_stats, 'total_rejected', $campaign_ids, array( "im.image_status = 2"), $qw, $group_field, false);
            } else {
                self::getCountGroupByClients($rs_stats, 'completed_report', $campaign_ids, array( "im.image_status REGEXP  '^(4|5|6|99)$'"), $qw, $group_field, false);
                self::getCountGroupByClients($rs_stats, 'pending_report', $campaign_ids, array( "im.image_status REGEXP  '^(3)$'"), $qw, $group_field, false);
            }
            // added by nancy xu 2012-06-25 10:40
            if (!empty($campaign_ids)) {
                $q = "SELECT style_id, campaign_id FROM campaign_style_guide WHERE campaign_id IN (" . implode(",", $campaign_ids). ")";
                $styles = $conn->GetAll($q);
                $style_ids = array();
                foreach ($styles as $row) {
                    $style_ids[$row['campaign_id']] = $row['style_id'];
                }
            }
			// end 
            // added report that waiting to accept
            if ($role == 'designer' || $role == 'editor') {
                $new_stats = $new_campaign_ids =  array();
                foreach ($rs_stats as $k => $row) {
                    $started_articles = isset($row['total_started']) && $row['total_started'] ? $row['total_started'] : 0;
                    $rs_total = $row['total'];
                    $campaign_id = $row['campaign_id'];
                    $new_total = $started_articles > 0 ? ($rs_total - $started_articles):$rs_total;
                    if ($new_total > 0) {
                        $style_id =isset($style_ids[$k]) ? $style_ids[$k] : '';
                        $new_stats[] = array('total' => $new_total, 'campaign_id'=> $campaign_id, 'style_id' => $style_id);
                        $new_campaign_ids[] = $campaign_id;
                    }
                }
                if (!empty($new_campaign_ids) && !empty($new_stats)) {
                    $new_field = ($role == 'designer' ? 'ik.cp_status' : ' ik.editor_status');
                    $new_qw =  $qw . ' AND ' . $new_field . '=1';
                    self::getCountGroupByClients($new_stats, 'total_assigned', $new_campaign_ids, array( "im.image_status = '0'"), $new_qw, $group_field, false);
                    $now_time = time()-86400;
                    $assigned_limit = date("Y-m-d H:i:s", $now_time);
                    $new_qw =  $qw . ' AND ' . $new_field . '=-1 and ik.date_assigned >= \'' . $assigned_limit . "'";
                    self::getCountGroupByClients($new_stats, 'working_on', $new_campaign_ids, array( "im.image_status = '0'"), $new_qw, $group_field, false);
                    $new_qw =  $qw . ' AND (' . $new_field . '=-1 and ik.date_assigned < \'' . $assigned_limit . '\' OR ' . $new_field . '<>-1)';
                    self::getCountGroupByClients($new_stats, 'total_finished', $new_campaign_ids, array( "im.image_status = '0'"), $new_qw, $group_field, false);
                    if (!empty($new_stats)) {
                        foreach ($new_stats as $k => $sitem) {
                            if ($sitem['total_finished'] == $sitem['total']) {
                                unset($new_stats[$k]);
                            }
                        }
                    }
                }
            }
            $result['new_report'] = $new_stats;
            foreach ($rs_stats as $row) {
                $k = $row['campaign_id'];
                $v = $row['total'];
                /*$q = "SELECT style_id FROM campaign_style_guide WHERE campaign_id={$k}";
                $result['img_report'][$k]['style_id'] = $conn->GetOne($q);*/
                $result['img_report'][$k]['style_id'] = isset($style_ids[$k]) ? $style_ids[$k] : '';
                $result['img_report'][$k]['campaign_id'] = $k;
                $result['total_client_approval'][$k] = $row['total_client_approval'];
                if ($role == 'designer') {
                    $result['text_report'][$k] = $row['text_report'];
                    $result['img_report'][$k]['percent'] =$row['pct_text_report'];
                    $result['img_report'][$k]['working_on'] = $row['working_on'];
                    $result['img_report'][$k]['total_rejected'] = $row['total_rejected'];
                    $result['total_assign'][$k] = $row['total_assign'];
                } else {
                    $pending_report = $row['pending_report'];
                    if (empty($pending_report)) $pending_report = 0;
                    $result['pending_report'][$k] = $pending_report;
                    $result['completed_report'][$k] = $row['completed_report'];
                    $result['img_report'][$k]['pending'] = $row['pct_pending_report'];
                    $result['img_report'][$k]['working_on'] = $pending_report; 
                    $result['img_report'][$k]['percent'] = $row['pct_completed_report']; 
                }
            }
            $conditons = array();
            if ($role == 'editor') {
                $conditons[] = 'ik.editor_id > 0';
            } else {
                $conditons[] = 'ik.copy_writer_id > 0';
            }
            $image_type_report =  self::getCount('total', $conditons, $qw_all, 'im.image_status');
            if (is_array($result['text_report'])) {
                // $result['total_completed_so_far'] = array_sum(array_values($result['text_report']));
                $total_completed_so_far = array_sum(array_values($result['text_report']));
                $result['total_completed_so_far'] = Campaign::sumFieldReport($image_type_report, 'image_status', array('1','3','4','5','6','99'));
                if (is_array($result['total_assign'])) {
                    // $result['total_assigned_so_far'] = array_sum(array_values($result['total_assign']));
                    $result['total_assigned_so_far'] = Campaign::sumFieldReport($image_type_report, 'image_status');
                }
            } else if (is_array($result['pending_report'])) {
                // $result['total_pending'] = array_sum(array_values($result['pending_report']));
                $result['total_pending'] = Campaign::sumFieldReport($image_type_report, 'image_status', array('1gc','3'));                
            } else {
                $result['total_completed_so_far'] = 0;
                $total_completed_so_far = 0;
                $result['total_pending'] = 0;
            }
            if (is_array($result['total_client_approval'])) {
                // $result['total_client_approved_so_far'] = array_sum(array_values($result['total_client_approval']));
                $result['total_client_approved_so_far'] = Campaign::sumFieldReport($image_type_report, 'image_status', array('5', '6', '99')); 
            }
            if (is_array($result['completed_report'])) {
                // $result['total_completed'] = array_sum(array_values($result['completed_report']));
                $result['total_completed'] = Campaign::sumFieldReport($image_type_report, 'image_status', array('4', '5', '6', '99'));
            } else {
                $result['total_completed'] = 0;
            }
            
            $result['total_assigned'] = Campaign::sumFieldReport($image_type_report, 'image_status', array('0', '2')); ;

            
            if ($role == 'designer' || $role == 'editor') {
               /*$rs = Image::getAllClientApprovedImage($user_id, $role, changeTimeToPayMonthFormat(getDelayTime()), false, true, false, true);
               $result['1gc_this_month'] = $rs['count'];
               $result['total_word_client_approved_so_far'] = User::sumTotalWordsForUsers($user_id);
               $result['total_word_this_month'] = $rs['total_word'];
               if (strlen($result['1gc_this_month']) == 0) $result['1gc_this_month'] = 0;*/
               return $result;
            }
        }

        return null;
    }//end reportCampaignByRole()

    function getCount($field = 'total_submit', $conditions = array(), $q, $group_field='cl.client_id')
    {
        global $conn;
        $query  = "SELECT COUNT( image_id )  as " .$field;
        if (!empty($group_field)) {
            $query .= ", " . $group_field . "\n";
        }
        $query .= "FROM " . Image::getTable() . " AS im \n";
        $query .= "LEFT JOIN " .self::getTable(). " AS ik ON (ik.keyword_id = im.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ik.campaign_id ) \n";
        $query .= "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id)  \n";
        $query .= $q . "\n";
        $conditions[] = " ik.status!='D' ";
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
        $select  = "SELECT COUNT( image_id )  as " .$field . ",  " . $group. " \n";
        $query = "FROM " . Image::getTable() . " AS im \n";
        $query .= "LEFT JOIN "  .self::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ik.campaign_id ) \n";
        $query .= "LEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id)  \n";
        $query .= $q . "\n";
        $conditions[] = " ik.status!='D' ";
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
                $select  = "SELECT MIN( ik.date_assigned )  AS assigned,  " . $group. " \n";
                $query .= ' AND im.image_status = 0 ';
                $field = 'assigned';
                break;
            case 'total_submit':
                $select  = "SELECT MIN( im.cp_updated )  AS submitted,  " . $group. " \n";
                $query .= ' AND im.image_status = 1 ';
                $field = 'submitted';
                break;
            case 'total_editor_approval':
                $select  = "SELECT MIN( im.approval_date )  AS approved,  " . $group. " \n";
                $query .= ' AND im.image_status = 4 ';
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
}
?>