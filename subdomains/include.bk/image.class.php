<?php
class Image{

	function __construct()
	{
	}

    function Image()
    {
        $this->__construct();
    }

    public static function getTable()
    {
        return 'images';
    }

    public static function getKey()
    {
        return 'image_id';
    }

    public static function insert($data)
    {
        global $conn;
        $conn->StartTrans();
        $data['image_id'] = $conn->GenID('seq_'. Image::getTable() .'_' .Image::getKey());
        $q = "INSERT INTO " . Image::getTable() . " (`" . implode("`, `", array_keys($data)) . "`) VALUES ('" . implode("', '" , $data).  "')" ;
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
         return $ok ? $data['image_id'] : false;
    }

     /**
     * Set image's info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setInfo($p = array())
    {
        global $conn, $feedback, $handle;
        
        $action_info = $hash = array();
        //global $g_tag;
        $image_id = addslashes(htmlspecialchars(trim($p['image_id'])));
        if ($image_id == '') {
            $feedback = "Please Choose a image";
            return false;
        }
        $hash['image_id'] = $image_id;
        // initialize image action info
        self::getImageActionInfo($action_info, $image_id);
        
        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title == '' && $p['action'] != 'autotemp') {
            $feedback = "Please provide the title of the image";
            return false;
        }

        $hash['title'] = $title;

        $image_name = addslashes(trim($p['image_name']));
        if ($image_name == '' && $p['action'] != 'autotemp') {
            $feedback = "Please provide the image";
            return false;
        }
        $hash['image_name'] = $image_name;

        $hash['image_param'] = addslashes(serialize($p['image_param']));
        
        // added by snug xu 2007-07-10 18:56 - STARTED
        // add html title sql
        if (isset($p['html_title']))
        {
            $html_title      = addslashes(htmlspecialchars(trim($p['html_title'])));
            $html_title_qw =  "html_title = '" . $html_title."', ";

        } else {
            $html_title_qw = '';
        }


        $image_info = self::getInfo($image_id, false);

        $qw = '';

        // added by snug xu 2006-11-27 15:06 - END

        if ((User::getPermission() == 1.2 && $image_info['copy_writer_id'] == User::getID()) || User::getPermission() > 2) {
            //do nothing;
        } else {
            $feedback = "You cann't execute this operation";
            return false;
        }

        $image_status = addslashes(trim($p['image_status']));
        if ($image_status == '') {
            $image_status = 0;
        }
        

        $qu = "";
        if (user_is_loggedin()) {
            $c_user_id = User::getID();
            if ($old_status != $image_status) {
                if ($p['action'] == 'temp' || $p['action'] == 'autotemp') {
                    if ($c_user_id == $image_info['copy_writer_id']) {
                        return true;
                    } else if ($c_user_id == $image_info['editor_id'] || User::getRole() == 'admin') {
                        $image_status = $old_status;
                    }
                } else {
                    if ($c_user_id == $image_info['copy_writer_id']) {
                        $qw .= ' AND (image_status=\'0\' OR image_status=\'2\') ';
                    } else if ($c_user_id == $image_info['editor_id'] || User::getRole() == 'admin') {
                        if ($image_status == 2) {
                            $qw .= ' AND ( image_status=\'4\' OR image_status=\'3\') ';

                        } else if ($image_status == 4) {
                            $qw .= ' AND (image_status=\'2\' OR image_status=\'3\') ';
                        }
                    }
                }
                if (($c_user_id == $image_info['copy_writer_id'] || $c_user_id == $image_info['editor_id'] || User::getRole() == 'admin')) {
                    if ($p['action'] != 'temp' && $p['action'] != 'autotemp') {
                        $hash['image_status'] = $image_status;
                    }
                }
            }
        }

        // added by snug xu 14:19 2006-11-21 - START
        // initialize the image action info
        $action_info['title'] = $title;
        $action_info['new_status'] = $p['image_status'];
        $posted_by = self::getPostByField($action_info);;
        $hash['posted_by'] = addslashes(serialize($posted_by));
        // added by snug xu 14:19 2006-11-21 - END

        $conn->StartTrans();

        if ($p['action'] != 'temp' &&  $p['action'] != 'autotemp' ) {
            if (($old_status == 2 || $old_status == 0) && !empty($hash['image_name'])) {
                require_once CMS_INC_ROOT . '/image_version_history.class.php';
                $q = ImageVersionHistory::generateHistorySql($image_info);
                $conn->Execute($q);
                $vertion = $image_info['current_version_number'] + 0.1;
                $hash['current_version_number'] = $vertion;
                $hash['creation_date'] = date('Y-m-d H:i:s', time());

                // added by snug xu 14:19 2006-11-21 - START
                // set the image action new version
                //$action_info['new_version'] = $vertion;
                // added by snug xu 14:19 2006-11-21 - END
            } else {
                //do nothing;
            }
        }


        // added by snug xu 2007-06-22 15:25 - STARTED
        // when cp add/save image, record it's lastest updated time
        if (User::getRole() == 'designer' || user_is_loggedin()  && isset($p['cp_updated']) && !empty($p['cp_updated'])) {
            $hash['cp_updated'] = date("Y-m-d H:i:s", time());
        }
        $hash['creation_user_id'] = User::getID();
        $hash['creation_role'] = User::getRole();
        // added by snug xu 2007-06-22 15:25 - FINISHED
        $sets = array();
        foreach ($hash as $k => $v) {
            $sets[] = "`{$k}`='" .  $v . "'";
        }
        $sql = "UPDATE  " . self::getTable() .
                       " SET " . implode("," , $sets) . 
                       " WHERE image_id = '" . $image_id . "' " . $qw;
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Success';
            if ($image_status == 0) $feedback = '';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            if ($image_status == 0) $feedback = '';
            return false;
        }
        
    }//end setInfo()

    function approveImage($p)
    {
        global $conn, $feedback;
        if (!user_is_loggedin() && !client_is_loggedin()) {
            $feedback = 'Please sign in this system';
            return false;
        }
        $image_id = addslashes(htmlspecialchars(trim($p['image_id'])));
        if ($image_id == '') {
            $feedback = "Please Choose an image";
            return false;
        }
        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title == '') {
            $feedback = "Please specify the image title";
            return false;
        }
        $approve_action = $p['approve_action'];
        $old_image = self::getInfo($image_id, false);
        $old_status = $old_image['image_status'];
		$keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        require_once CMS_INC_ROOT.'/Client.class.php'; 
        $comment = addslashes(htmlspecialchars(trim($p['comment'])));
        if (client_is_loggedin() && $approve_action == 'reject' && strlen($comment) == 0) {
            $feedback = "Please comment on image, before rejecting it";
            return false;
        }
        if ($approve_action != 'temp' && $approve_action != 'autotemp') {
           if ($old_status == 5 || $old_status == 6) {
               if ($approve_action == 'publish' && $old_status == 5 || $approve_action == 'approval' && client_is_loggedin()) {
                    // do nothing
               } else {
                   $feedback = "This image was finished, you can't change it to other image status.";
                   return false;
               }
           }
        }
        //$image_status = $p['image_status'];
        if ($approve_action == 'reject' || $approve_action == 'approval')  {
            if (user_is_loggedin()) {
                switch ($old_status) {
				case '0':
					$feedback = "Copy Writer doesn't finish this image. Please wait for the copy writer uploading the image";
					return false;
					break;
                 case '2':
                    if ($approve_action == 'approval') {
                        $feedback = "This image has been requested for edit by the editor. Please wait for copy writer re-uploading the image";
                        return false;
                    }
                    break;
                }
                
                if ($permission == 3 && $old_status != '2') {
                    if (strlen($comment) == 0) {
                        $feedback = "Please comment on image, before rejecting it";
                        return false;
                    }
                }
                $image_status = $approve_action == 'approval' ? 4 : 2;
            } else {
                if ($old_status <= "2") {
					$feedback = "Please wait editor approving this image";
                    return false;
				} else if ($old_status == "3") {
                    $feedback = "This image was rejected by client. Please wait editor approving this image";
                    return false;
                }
                $image_status = $approve_action == 'approval' ? 5 : 3;
            }
        } elseif ($approve_action == 'force') {
            if ($old_status <> 2) {
                $feedback = "You can't approving this image";
                return false;
            }
            $image_status = 4;
        } elseif ($approve_action == 'forcec'  || $approve_action == 'forcecr') {
            $image_status = $approve_action == 'forcec' ? 5 : 3;
        } elseif ($approve_action == 'submit') {
            $image_status = 1;
        } elseif ($approve_action == 'publish') {
            if ($old_status == 5) {
                $image_status = 6;
            } else {
                $feedback = "Please wait client approving this image.";
                return false;
            }
        } else {
            $image_status = $old_status;
        }
        self::getImageActionInfo($action_info, $image_id);
        if ($old_status == 0) {
            if ($old_image['creation_role'] == 'client') {
                if (Client::getID() != $old_image['creation_user_id']) {
                    $feedback = "Please wait other complete this image";
                    return false;
                }
            }
        }
        $now = time();
        $conn->StartTrans();
        if (($image_status != 0 &&  $old_status != $image_status && $approve_action != 'temp' && $approve_action != 'autotemp' && $approve_action != 'save') && user_is_loggedin()) {
            require_once CMS_INC_ROOT . '/image_version_history.class.php';
            $q = ImageVersionHistory::generateHistorySql($old_image);
            $conn->Execute($q);
            $vertion = $image_info['current_version_number'] + 0.1;
            $hash['current_version_number'] = $vertion;
            $hash['creation_date'] = date('Y-m-d H:i:s', $now);
            $action_info['new_version'] = $old_image['current_version_number'] + 0.1;
        }
        $hash = array('image_status' => $image_status);
        if (client_is_loggedin() || $approve_action == 'forcec' && $image_status == '5') {
            if ($old_status != $image_status)  {
                if ( $image_status == '3')  {
                    $hash['rejected'] = date('Y-m-d H:i:s', $now);
                } else if ($image_status == 5) {
                    $hash['client_approval_date'] = date('Y-m-d H:i:s', $now);
                }
            }
        } else {
            if ($image_status == 4) {
                $hash['approval_date'] = date('Y-m-d H:i:s', $now);
            } else if ($image_status == 5) {
                $hash['client_approval_date'] = date('Y-m-d H:i:s', $now);
            } else if ($image_status == 2 || $image_status == 3) {
                if ($image_status == 2)  $hash['approval_date'] = '0000-00-00 00:00:00';
                if ($old_status != $image_status) {
                    $hash['rejected'] = date('Y-m-d H:i:s', $now);
                }
            }
        }
        $hash['title'] = $title;
        $action_info['new_status'] = $image_status;
        $posted_by = self::getPostByField($action_info);
        $hash['posted_by'] = serialize($posted_by);
        $sets = array();
        foreach ($hash as $k => $v) {
            $sets[] = "{$k}='" . addslashes($v) . "'";
        }
        $sql = "UPDATE " . self::getTable() . ' SET ' . implode(',' , $sets).  " WHERE image_id = '" . $image_id . "' ";   
        $conn->Execute($sql);
        if (strlen($comment) && $approve_action !='autotemp') {
            self::sentComments($old_image, $comment);
        }
        if ($ok = $conn->CompleteTrans()) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }

   /**
     * this you can set image status that will sign a sign for an image
     *
     * @param int $image_id the image id
     * @param char $status
     * @param char $old_status
     * @param char $copy_writer_id if $copy_writer_id == 0 ignore it 
     *
     * @return boolean
     */
    public static function setImageStatus($image_id, $status, $old_status = 1, $copy_writer_id = 0)
    {
        global $conn, $feedback;

        $action_info = array();

        $image_id = addslashes(htmlspecialchars(trim($image_id)));
        if ($image_id == '') {
            $feedback = "Please choose an image";
            return false;
        }

        $status = addslashes(htmlspecialchars(trim($status)));
        if ($status == '') {
            $feedback = "Please set a Image status";
            return false;
        }

        $old_status = addslashes(htmlspecialchars(trim($old_status)));
        if ($old_status == '') 
		{
            $old_status = 1;
        }

        // added by snug xu 2007-11-04 16:18 - STARTED
        // if $old_status is client approval or publish, then check whether allowed change status or not
        if ($old_status == 5 || $old_status == 6) {
           if ($status == 6 && $old_status == 5 || $old_status == $status) {
                // do nothing
           } else {
               $feedback = "The Image was finished, you can't change them to other Image status.";
               return false;
           }
        }// end

        // added by snug xu 2006-11-21 19:17 - START
        // initialize image action info
        self::getImageActionInfo($action_info, $image_id);
        $action_info['new_status'] = $status;
        // if artile status is changes, store the image action log
        
        if (strcasecmp($action_info['status'], $status) != 0 || $copy_writer_id > 0 && $action_info['copy_writer_id'] != $copy_writer_id) 
        {
            if ($action_info['copy_writer_id'] != $copy_writer_id && $copy_writer_id > 0) 
                $action_info['new_copy_writer_id'] = $copy_writer_id;
            //ImageAction::store($action_info);
        }
        // added by snug xu 2006-11-21 19:17 - END

        $qw = "AND image_status = '".$old_status."'";
        $data['image_status'] = $status; 
        $data['image_id'] = $image_id; 
        $posted_by = self::getPostByField($action_info);
        $data['posted_by'] = addslashes(serialize($posted_by));
        return self::store($data, $qw);
    }//setImageStatus()

    function getImageActionInfo(&$info, $image_id, $opt_type =null, $opt_info = array())
    {
        if (empty($info)) $info = self::getInfo($image_id, false);
        if (!isset($info['version']) || empty($info['version'])) 
            $info['version'] = $info['current_version_number'];
        if (!isset($info['new_version']) ||empty($info['new_version'])) 
            $info['new_version'] = $info['version'];
        if (!isset($info['status']) || empty($info['status'])) 
            $info['status']  = $info['image_status'];
        if (!isset($info['new_copy_writer_id']) || empty($info['new_copy_writer_id']))
            $info['new_copy_writer_id'] = $info['copy_writer_id'];
        $info['created_time']   = date('Y-m-d H:i:s', time());
        if (user_is_loggedin()) {
            $info['opt_id'] = User::getID();
            $info['opt_name'] = User::getName();
            $info['opt_type'] = 0;
        } else if (client_is_loggedin()) {
            $info['opt_id'] = Client::getID();
            $info['opt_name'] = Client::getName();
            $info['opt_type'] = 1;
        } else if ($opt_type > 0 && !empty($opt_info)) {
            $info['opt_id'] = $opt_info['client_user_id'];
            $info['opt_name'] = $opt_info['user'];
            $info['opt_type'] = $opt_type;
        } else {
            $info['opt_id'] = 0;
            $info['opt_name'] = 'cronjob';
            $info['opt_type'] = 2;
        }
    }

    function setStatus()
    {

    }

    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function listKeywordByRole($p = array())
    {
        global $conn, $feedback,  $g_pager_params;

        $q = "WHERE 1 ";

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND ik.campaign_id = '".$campaign_id."' ";
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND ik.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "AND ik.copy_writer_id = '".$copy_writer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "AND ik.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND ik.creation_user_id = '".$creation_user_id."' ";
        }

        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type != '') {
            $q .= "AND ik.image_type = '".$image_type."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "AND ik.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "AND ik.date_end <= '".$date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "AND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ik.keyword, ik.keyword_description)")." ";
            }
        }

        //$ql = "";
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1.2) {
                $q .= "AND ik.copy_writer_id = '".User::getID()."' ";
                // if keyword_status =0, copywriter start to write image for the keywords after client who is in possession of those keywords approved them
                //  keyword_status = -1 means don't need to let client approval
                //  keyword_status =0 means need to let client approval
                //  keyword_status = 1 means client approved those keywords
                $q .= "AND ik.keyword_status != '0' ";
            } elseif (User::getPermission() == 3) { // 2=>3
                $q .= "AND ik.editor_id = '".User::getID()."' ";
            } else {
                //do nothing
            }

            if (User::getPermission() == 1.2) {
                $q .= ' AND ik.cp_status=1 ';
            }
            if (User::getPermission() == 3) {
                $q .= ' AND ik.editor_status=1 ';
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."' ";
            if (isset($p['keyword_status']))
            {
                $keyword_status = addslashes(htmlspecialchars(trim($p['keyword_status'])));
                if (is_array($keyword_status))
                    $q .= " AND ik.keyword_status IN ('" . implode("','", $keyword_status) . "') ";
                else
                    $q .= " AND ik.keyword_status = '{$keyword_status}' ";
            }
        } else {
            return false;
        }
        
        // added by snug xu 2007-07-24 19:47 - STARTED
        $image_status = $p['image_status'];
        if (is_array($image_status) && !empty($image_status)) {
            $q .= "AND im.image_status IN ('". implode("', '", $image_status)."') ";
        } else {
            $image_status = addslashes(htmlspecialchars(trim($image_status)));
            if ($image_status != '') {
                if ($image_status == -1) {
                    $q .= "AND ik.copy_writer_id = '0' ";
                } else {
                    $q .= "AND im.image_status = '".$image_status."' ";
                }
            }
        }
        // added by snug xu 2007-07-24 19:47 - FINISHED
        $q .= " AND ik.status!='D'  ";
        $left_join  = "LEFT JOIN " . self::getTable() . " AS im ON (ik.keyword_id = im.keyword_id) \n";
        $left_join .= "LEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) \n";
        $left_join .= "LEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) \n";
        $left_join .= "LEFT JOIN users AS cu ON (ik.creation_user_id = cu.user_id) \n";
        $left_join .= "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ik.campaign_id) \n";
        $left_join .= "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) \n";
        $sql = "SELECT COUNT(ik.keyword_id) AS count \n".
              "FROM " . ImageKeyword::getTable() . " AS ik \n". $left_join .$q;
        $rs = &$conn->Execute($sql);
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

        $q = "SELECT ik.*, im.image_id, im.image_number, im.image_status, im.title, im.current_version_number, ".
               "im.creation_user_id AS creator, im.creation_role, cl.user_name, cl.company_name, ".
               //$sql_field . 
               "cc.campaign_name, uc.user_name AS uc_name , uc.email as uc_email, ue.email as ue_email, ue.user_name AS ue_name , cu.user_name AS cu_name \n".
             "FROM " . ImageKeyword::getTable() . " AS ik \n". $left_join .$q;
        $q .= ' ORDER BY ik.keyword_id DESC ';
        
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                //$field = self::__getCost($rs->fields);
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

    }//end listKeywordByRole()
    
    function search($p)
    {
        global $conn, $feedback;

        global $g_pager_params, $g_archived_month_time;

        // added by sort part
        // added by nancy xu 2011-02-17 17:24
        $direction = $sort = '';
        if (isset($p['sort']) && !empty($p['sort'])) {
            $sort = $p['sort'];
        } else {
            $sort = 'ik.keyword_id';
        }
        if (isset($p['direction']) && !empty($p['direction'])) {
            $direction = $p['direction'];
        }
        $order_by = ' ORDER BY ' . $sort  . ' '. $direction . ' ';
        //end
       

        $q = "WHERE 1 ";
        // added by nancy xu 2010-06-04 13:43
        $archived = isset($p['archived']) ? $p['archived'] : 0;
        $approval_date = date('Y-m-d H:i:s', $g_archived_month_time);
        if ($archived == 1) {
            $q .= "\n AND " . '(im.image_status = 5 || im.image_status = 6) && im.client_approval_date < \'' . $approval_date. '\'';
        } else {
            $q .= "\n AND " . ' ((im.image_status != 5 &&  im.image_status != 6) || ((im.image_status = 5 || im.image_status = 6) && im.client_approval_date >=  \'' . $approval_date. '\')) ';
        }
        // end

        $image_id = addslashes(htmlspecialchars(trim($p['image_id'])));
        if ($image_id != '') {
            $q .= "AND im.image_id = '".$image_id."' ";
        }

        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "AND im.keyword_id = '".$keyword_id."' ";
        }

        $creation_role = addslashes(htmlspecialchars(trim($p['creation_role'])));
        if ($creation_role != '') {
            $q .= "AND im.creation_role LIKE '%".$creation_role."%' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "AND im.creation_user_id = '".$creation_user_id."' ";
        }
        
        $image_status = $p['image_status'];
        if (is_array($image_status) && !empty($image_status)) {
            $q .= "AND im.image_status IN ('". implode("', '", $image_status)."') ";
        }
        else {
            $image_status = addslashes(htmlspecialchars(trim($image_status)));
            if ($image_status != '') {
                $q .= "AND im.image_status = '".$image_status."' ";
            }
        }

        $title = addslashes(htmlspecialchars(trim($p['title'])));
        if ($title != '') {
            $q .= "AND im.title LIKE '%".$title."%' ";
        }

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }
        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type != '') {
            $q .= "AND ik.image_type = '".$image_type."' ";
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(im.title, im.current_version_number, ik.keyword, ik.image_type, ik.keyword_description)")." ";
            }
        }

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1.2) {
                //$ql .= "LEFT JOIN users AS u ON (ik.copy_writer_id = u.user_id) ";
                $q .= "AND ik.copy_writer_id = '".User::getID()."'";
                $q .= ' AND ik.keyword_status != 0 ';
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ik.editor_id = u.user_id) ";
                $q .= "AND ik.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
            if (User::getPermission() == 1.2) {
                $q .= ' AND ik.cp_status=1 ';
            }
            if (User::getPermission() == 3) {
                $q .= ' AND ik.editor_status=1 ';
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }

        $q .= " AND ik.status!='D'  ";
        $left_join  = "\nLEFT JOIN " .ImageKeyword::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ";
        $left_join .= "\nLEFT JOIN client_campaigns AS cc ON (ik.campaign_id = cc.campaign_id) ";
        $left_join .= "\nLEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) ";
        $rs = &$conn->Execute("SELECT COUNT(im.image_id) AS count FROM " . self::getTable() . " AS im ". $left_join .$q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $perpage = 50;
        if (trim($p['perPage']) > 0) {
            $perpage = $p['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $left_join .= "\nLEFT JOIN users AS u ON (u.user_id = im.creation_user_id) ";
        $left_join .= "\nLEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) ";
        $left_join .= "\nLEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) ";
          $q = "\nSELECT im.image_id, im.image_number,im.image_status, im.cp_updated, im.current_version_number, ".
               "\n im.keyword_id,  im.creation_role, ".
               "\nik.keyword, ik.image_type, ik.keyword_description, ik.date_start, ik.date_end, cc.campaign_name, " . 
               $sql_field. 
               "\n ik.copy_writer_id, ik.editor_id , u.user_name AS creator, ".
               "\nCONCAT(uc.first_name, ' ', uc.last_name) AS copywriter , CONCAT(ue.first_name, ' ', ue.last_name) AS editor, ".
               "\nue.email AS editor_email, uc.email AS writer_email \n".
             "FROM " . self::getTable() . " AS im \n" . $left_join . $q;
        $q .= $order_by;
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $field = $rs->fields;
                if ($field['image_status'] == '1') {
                    $time  = strtotime($field['cp_updated']) + 259200;
                    $field['editor_due_date'] = date("Y-m-d", $time);
                }
                $result[$i] = $field;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
    }





    public static function store($data, $qw)
    {
        global $conn, $feedback;
        if (isset($data['image_id'])) {
            $image_id = $data['image_id'];
            unset($data['image_id']);
            $sets = array();
            foreach ($data as $k => $v) {
                $sets[] = "{$k}='" . addslashes($v) . "'";
            }
            $sql = "UPDATE " . self::getTable() . ' SET ' . implode(',' , $sets).  " WHERE image_id = '" . $image_id . "' ". $qw;
            $conn->Execute($sql);
            if ($conn->Affected_Rows()) 
            {
                $feedback = 'Success';
                return $image_id;
            } else {
                $feedback = 'Failure, Please try again';
                return false;
            }
        } else {
            return false;
        }
    }

    function getInfo($image_id, $is_contain_comments = true)
    {
        $info =  self::getInfoSql(array('image_id' => $image_id));
        if (!empty($info) && $is_contain_comments)  {
            require_once CMS_INC_ROOT . '/image_comment.class.php';
            $info['comment'] = ImageComment::getCommentsByImageID($image_id);
        }
        return $info;
    }

    function getInfoByKeywordID($keyword_id)
    {
        return self::getInfoSql(array('keyword_id' => $keyword_id));
    }

    function getInfoSql($param)
    {
        global $conn;
        if (empty($param)) return false;
        $qw_arr = array("ik.status!='D'");
        extract($param);
        if (isset($keyword_id) && $keyword_id > 0) $qw_arr[] = "ik.keyword_id = {$keyword_id}";
        if (isset($image_id) && $image_id > 0) $qw_arr[] = "im.image_id = {$image_id}";
    	$sql = "SELECT ik.*, im.* , cc.client_id, cc.campaign_name, cc.campaign_id, cl.company_name, cl.project_manager_id " 
                . "FROM " . ImageKeyword::getTable() . " AS ik " 
                . "LEFT JOIN " . self::getTable() ." AS im ON im.keyword_id=ik.keyword_id "
                . "LEFT JOIN client_campaigns as cc on (cc.campaign_id = ik.campaign_id) "
                . "LEFT JOIN client as cl on (cl.client_id = cc.client_id) "
                . "WHERE " . (!empty($qw_arr)? implode(" AND ", $qw_arr) : ' 1 ');
        $arr = $conn->GetAll($sql);
        if (!empty($arr)) { 
            $result = $arr[0];
            $result['image_param'] = unserialize($result['image_param']);
        } else { 
            $result = array();
        }
        return $result;
    }

    function getPostByField($hash)
    {
        if ($hash['status'] == 'A') {
            $hash['status'] = $hash['image_status'];
        }
        $posted_by = array();
        if (isset($hash['posted_by']) && !empty($hash['posted_by'])) {
            $posted_by = unserialize($hash['posted_by']);
        } 
        
        $field = '';
        $status = $hash['status'];
        $new_status = $hash['new_status'];
        if (($status == '0' || $status == '2') && $new_status == '1' ) {
            $field = 'submitted';
        } elseif ($status == '4' && $new_status == '5' ) {
            $field = 'client_approved';
        } elseif ($status == '5' && $new_status == '6' ) {
            $field = 'published';
        } else if ($status == '4' && $new_status == '3' ){
            $field = 'client_rejected';
        } else if (($status == '1' || $status == '3'  || $status == '4') && $new_status == '2' ){
            $field = 'rejected';
        } else if ($status == '2' && $new_status == '4'){
            $field = 'force_approved';
        } else if (($status == '1' || $status == '3') && $new_status == '4'){
            $field = 'approved';
        // added by nancy xu 2012-05-24 15:06
        // disabled the image
        } else if ($hash['new_status'] == '99') {
            $field = 'disabled';
        }
        // end
        if (!empty($field)) {
            $posted_by[$field] = array(
                    'opt_id' => $hash['opt_id'],
                    'date_time' => $hash['created_time'],
                    'opt_name' => $hash['opt_name'],
                    'opt_type' => $hash['opt_type'],
             );
        }
        return $posted_by;
    }

    function sentComments($p, $comment)
    {
       global $feedback, $admin_host, $mailer_param, $g_to_email;
        if (empty($comment)) {
            $feedback = 'Please input the comment';
            return false;
        }
        $comment = addslashes(htmlspecialchars(trim($comment)));
        
        if (ImageComment::addComments($comment, $p['image_id'], $p)) {
            $editor = User::getInfo($p['editor_id']);
            $writer = User::getInfo($p['copy_writer_id']);
            $pm = User::getInfo($p['project_manager_id']);
            // $host = "http://" . $_SERVER['HTTP_HOST'];
            $url = $admin_host . "/graphics/image_comment_list.php?image_id={$p['image_id']}&keyword_id={$p['keyword_id']}&campaign_id={$p['campaign_id']}";
            $keywordlink = "<a href=\"{$url}\">".$p['keyword']."</a>";
            if (!empty($editor ) || !empty($writer)) {
                // added by nancy xu 2010-03-22 11:47
                if (client_is_loggedin() || user_is_loggedin() && User::getRole() != 'copy writer' && User::getRole() != 'designer') {
                    require_once CMS_INC_ROOT . '/Notification.class.php';
                    $note = array(
                        'campaign_id' => $p['campaign_id'], 
                        'generate_date' => date("Y-m-d H:i:s"), 
                        'field_name' => 'comment', 
                        'total' => 1, 
                        'campaign_name' => $p['campaign_name']);
                    if (client_is_loggedin()) {
                        $note_user_id = $editor['user_id'];
                        $note_role = $editor['role'];
                    } else if (user_is_loggedin()) {
                        $note_user_id = $writer['user_id'];
                        $note_role = $writer['role'];
                    }
                    $note['user_id'] = $note_user_id;
                    $note['role'] = $note_role;
                    $note['notes'] =sprintf('You have an editor comment for keyword %s from campaign %s.', $p['keyword'], $p['campaign_name']);
                    Notification::save($note);
                }
                // end
                $content = "Dear %%FRIST_NAME%%\n";
                $content .= "This comment has been made on an image.
        Keyword:  {$keywordlink}
        Campaign: {$p['campaign_name']}
        Writer Name: {$writer['user_name']}
        Editor Name: {$editor['user_name']}
        Comment From: ". User::getName() ."
        Comment: " . $comment . "\n
        Sincerely,\n
        CopyPress ";
                $content = nl2br($content);
                $subject = 'New Comment on ' . $p['image_number'];
                $users = array($editor, $writer, $pm);
                $max_index = count($users) -1;
                foreach ($users as $k => $user) {
                    if (!empty($user)) {
                        $body = str_replace("%%FRIST_NAME%%", $user['first_name'], $content);
                        if ($max_index == $k) $mailer_param['cc'] = $g_to_email;
                        send_smtp_mail($user['email'], $subject, $body, $mailer_param);
                    }
                }
            }
        }
        return true;
    }

    function downloadImageByCampaignID($campaign_id, $p = array())
    {
        global $conn, $feedback;

        $q = "WHERE 1 ";

        $cp_completed = isset($p['cp_completed']) && $p['cp_completed'] > 0 ? $p['cp_completed'] : 0;

        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }

        if (isset($p['submit_date_start']) && !empty($p['submit_date_start'])) {
            $submit_date_start = $p['submit_date_start'];
            $q .= "\nAND im.cp_updated >= '".$submit_date_start."' ";
        }

        if (isset($p['submit_date_end']) && !empty($p['submit_date_end'])) {
            $submit_date_end = $p['submit_date_end'];
            $q .= "\nAND im.cp_updated <= '".$submit_date_end."' ";
        }

        $image_type = addslashes(htmlspecialchars(trim($p['image_type'])));
        if ($image_type != '') {
            $q .= "\nAND ik.image_type = '".$image_type."' ";
        }

        $image_status = $p['image_status'];
        if (is_array($image_status) && !empty($image_status)) {
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
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ik.keyword, ik.keyword_description)")." ";
            }
        }

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (user_is_loggedin()) {
            if (User::getPermission() == 1) {
                //$ql .= "LEFT JOIN users AS u ON (ik.copy_writer_id = u.user_id) ";
                $q .= "AND ik.copy_writer_id = '".User::getID()."'";
            } elseif (User::getPermission() == 3) { // 2=>3
                //$ql .= "LEFT JOIN users AS uc ON (ik.editor_id = u.user_id) ";
                $q .= "AND ik.editor_id = '".User::getID()."'";
            } else {
                //do nothing
            }
        } elseif (client_is_loggedin()) {
            $q .= "AND cl.client_id = '".Client::getID()."'";
        } else {
            return false;
        }

        if (isset($p['dlall']) && $p['dlall'] == 1) {
            //do nothing for now; just search the status like controller did;
        } else {
            if ($cp_completed == 1) {
                $q .= "\nAND (im.image_status = '1')";
            } else {
                $q .= "\nAND (im.image_status = '5' OR im.image_status = '4' OR im.image_status = '6')";
            }
        }

        $q .= " AND ik.status!='D'  ";

        $q = "SELECT im.*, ik.* , cc.campaign_name, u.user_name AS creator , uc.user_name AS author ".
             "\nFROM " . self::getTable() . " AS im ".
             "\nLEFT JOIN " . ImageKeyword::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ".
             "\nLEFT JOIN users AS u ON (u.user_id = im.creation_user_id) ".
             "\nLEFT JOIN users AS uc ON (ik.copy_writer_id = uc.user_id) ".
             "\nLEFT JOIN users AS ue ON (ik.editor_id = ue.user_id) ".
             "\nLEFT JOIN client_campaigns AS cc ON (ik.campaign_id = cc.campaign_id) ".
             "\nLEFT JOIN `client` AS cl ON (cc.client_id = cl.client_id) " 
            . $q;

        $result = $conn->GetAll($q);
        foreach ($result as $k => $row) {
            $image_ids[] = $row['image_id'];
        } 
        return $result;

    }//end downloadImageByCampaignID()


    function getCheckedImage($p = array()) 
    {
        global $conn, $feedback;

        if (empty($p['image_id'])) {//or keyword_id
            $feedback = "Please choose Images.";
            return false;
        }
         $q = "SELECT im.*, ik.* ".
             "FROM " . self::getTable() . " AS im ".
             "LEFT JOIN " . ImageKeyword::getTable() . " AS ik ON (ik.keyword_id = im.keyword_id) ".
             "WHERE im.image_id IN (".implode(',', array_values($p['image_id'])).")";
        return $conn->GetAll($q);
    }//end getCheckedImage()

    function setDownLoadTime($p = array())
    { 
        global $conn;
        if (empty($p)) {
            return false;
        }
        if (is_array($p['image_id']) && !empty($p['image_id'])) {
            $conn->StartTrans();
            $q  = " UPDATE " . self::getTable()  . " SET curr_dl_time = '".date('Y-m-d H:i:s', time())."' ".
                  " WHERE image_id IN ( "
                   .addslashes(htmlspecialchars(implode(',', array_values($p['image_id']))))." )";
            $conn->Execute($q);
            $ok = $conn->CompleteTrans();
        }

        if ($ok) {
            return true;
        } else {
            return false;
        }

    }
}
?>