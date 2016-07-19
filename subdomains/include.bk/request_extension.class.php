<?php
require_once CMS_INC_ROOT.'/Campaign.class.php';
class RequestExtension{
	private $extension_id;
	private $copy_writer_id;
	private $campaign_id;
	private $editor_id;
	private $subject;
	private $reason;
	private $days_asked;

	private function __construct()
	{
		$this->extension_id = 0;
		$this->copy_writer_id = 0;
		$this->editor_id = 0;
		$this->campaign_id = 0;
		$this->subject = '';
		$this->reason = '';
		$this->days_asked = '';
	}

    function RequestExtension()
    {
        $this->__construct();
    }

    public static function getCountByParam($p)
    {
        global $conn;
        if (isset($p['copy_writer_id']) && !empty($p['copy_writer_id'])) {
            $copy_writer_id = addslashes(trim($p['copy_writer_id']));
            $qws[] = "re.copy_writer_id='" . $copy_writer_id . "'";
        }
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $campaign_id = addslashes(trim($p['campaign_id']));
            $qws[] = "re.campaign_id='" . $campaign_id . "'";
        }
        if (isset($p['editor_id']) && !empty($p['editor_id'])) {
            $editor_id = addslashes(trim($p['editor_id']));
            $qws[] = "re.editor_id='" . $editor_id . "'";
        }
        $qws[] = "re.is_bk=0";
        $sql = "SELECT count(*) FROM request_extension AS re ";
        $sql .= "WHERE " . implode(" AND ",  $qws);
        return $conn->GetOne($sql);
    }

    public static function getInfoByParam($p = array())
    {
        global $conn, $g_pager_params;
        $qws = array();
        if (isset($p['copy_writer_id']) && !empty($p['copy_writer_id'])) {
            $copy_writer_id = addslashes(trim($p['copy_writer_id']));
            $qws[] = "re.copy_writer_id='" . $copy_writer_id . "'";
        }
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $campaign_id = addslashes(trim($p['campaign_id']));
            $qws[] = "re.campaign_id='" . $campaign_id . "'";
        }
        if (isset($p['extension_id']) && !empty($p['extension_id'])) {
            $extension_id = addslashes(trim($p['extension_id']));
            $qws[] = "re.extension_id='" . $extension_id . "'";
        }
        if (isset($p['editor_id']) && !empty($p['editor_id'])) {
            $editor_id = addslashes(trim($p['editor_id']));
            $qws[] = "ck.editor_id='" . $editor_id . "'";
        }
        if (User::getPermission() == 4) {
            $qws[] = ' cl.project_manager_id=' . User::getID() . ' ';
        }
        $qws[] = "re.is_bk=0";
        $qws[] = "(re.editor_id = 0 OR (re.editor_id > 0 AND re.editor_id = ck.editor_id))\n";
        $role = User::getRole();
        $sql  = 'SELECT re.*, u.user_name, ed.user_name as editor, cc.campaign_name, cc.date_start, cc.date_end, count(ck.keyword_id) as total_writing, ck.editor_id AS ck_editor_id , eck.user_name AS ckeditor ';
        $sql_from = "FROM `request_extension` AS re ";
        $sql_from .= 'LEFT JOIN client_campaigns AS cc ON cc.campaign_id=re.campaign_id ';
        $sql_from .= 'LEFT JOIN client AS cl ON cl.client_id=cc.client_id ';
        $sql_from .= 'LEFT JOIN users AS u ON u.user_id=re.copy_writer_id ';
        $sql_from .= 'LEFT JOIN users AS ed ON ed.user_id=re.editor_id ';
        $sql_from .= 'LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id=re.campaign_id AND re.copy_writer_id = ck.copy_writer_id) ';
        $sql_from .= 'LEFT JOIN users AS eck ON eck.user_id=ck.editor_id ';
        $sql_from .= 'LEFT JOIN articles AS ar ON ar.keyword_id = ck.keyword_id ';
        $condition = "ar.article_status REGEXP '^(0|1gd|2)$' AND ck.status!='D' AND ck.keyword_status!= 0 ";
        $qws[] = $condition;
        require_once CMS_INC_ROOT.'/Search.class.php';
        $search = new Search($p['cp_keyword'], "AND"); // use AND operator
        if ($search->getError() != '') {
            //do nothing
            $feedback = $search->getError();
        } else {
            $qws[] = $search->getLikeCondition("CONCAT(u.user_name, u.first_name, u.last_name)")." ";
        }
        $search = new Search($p['c_keyword'], "AND"); // use AND operator
        if ($search->getError() != '') {
            //do nothing
            $feedback = $search->getError();
        } else {
            $qws[] = $search->getLikeCondition("CONCAT(cc.campaign_name)")." ";
        }

        $qw = !empty($qws) ? ' WHERE ' . implode(" AND ", $qws) : '';
        if ($role == 'editor') $qw .= ' AND  ck.editor_id=' . User::getID();
        $sql .= $sql_from . $qw . ' GROUP BY re.campaign_id, re.copy_writer_id, ck.editor_id  ';
        $count = $conn->GetOne("SELECT COUNT( DISTINCT re.campaign_id, re.copy_writer_id, ck.editor_id) AS count " . $sql_from . $qw);
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
        $rs = &$conn->SelectLimit($sql, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            if ($role == 'editor') {
                $editor_id = User::getID();
            } else {
                $editor_id = null;
            }
            $editor_ids = array();
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $editor_ids[] = $row['editor_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
            foreach ($result as $k => $row) {
                $campaign_id    = $row['campaign_id'];
                $copy_writer_id = $row['copy_writer_id'];
                $editor_id = $row['editor_id'];
                $due_dates = RequestExtension::getDueDates($campaign_id, $copy_writer_id, $editor_id, $condition);
                $result[$k]['due'] = implode(" <br /> ", $due_dates);
            }
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);

    }

    public static function getDueDates($campaign_id, $copy_writer_id, $editor_id = null, $qw )
    {
        global $conn;
        $sql = 'SELECT DISTINCT ck.date_end ';
        $sql .= 'FROM campaign_keyword AS ck ';
        $sql .= 'LEFT JOIN articles AS ar ON ar.keyword_id = ck.keyword_id ';
        $sql .= "WHERE ck.campaign_id='{$campaign_id}' ";
        $sql .= "AND ck.copy_writer_id = '{$copy_writer_id}' ";
        $sql .= "AND " . $qw;
        if ($editor_id > 0) {
            $sql .= "AND ck.editor_id = '{$editor_id}' ";
        }
        return $conn->GetCol($sql);
    }

	public static function getInfoByCopyWriterAndCampaignID( $copy_writer_id, $campaign_id, $extension_id)
	{
        global $conn;
		$campaign_id = mysql_escape_string(htmlspecialchars(trim($campaign_id)));
		$copy_writer_id = mysql_escape_string(htmlspecialchars(trim($copy_writer_id)));
		$extension_id = mysql_escape_string(htmlspecialchars(trim($extension_id)));
		$sql = "select re.*, u.user_name , cc.campaign_name  from `request_extension` as re, users as u,  client_campaigns as cc where cc.campaign_id=re.campaign_id and u.user_id=re.copy_writer_id and re.campaign_id=$campaign_id and re.copy_writer_id='{$copy_writer_id}'";
		if( $extension_id > 0)
		{
			$qu = " and re.extension_id=$extension_id";
		}
		$sql = $sql.$qu;
		$rs = &$conn->Execute($sql);
        $result = $rs->fields;
        if ($rs) {
            $rs->Close();
        }
		return $result;
	}

    public static function getArticles($p, $is_total = false)
    {
        global $conn;
        $copy_writer_id = addslashes(trim($p['copy_writer_id']));
        $campaign_id = addslashes(trim($p['campaign_id']));
        $editor_id = addslashes(trim($p['editor_id']));
        if (!$is_total) {
            $sql = "SELECT ck.keyword, ck.date_start, ck.date_end, at.type_name AS article_type_name, at.parent_id AS at_parent_id, ar.article_number , ar.total_words,  u.user_name as ue_name, cc.campaign_name ";
        } else {
            $sql = "SELECT count(ar.article_id) ";
        }
        $sql .= "FROM campaign_keyword AS ck ";
        $sql .= "LEFT JOIN articles AS ar ON ck.keyword_id = ar.keyword_id ";
        $sql .= "LEFT JOIN users AS u ON ck.editor_id = u.user_id ";
        $sql .= "LEFT JOIN client_campaigns AS cc ON ck.campaign_id = cc.campaign_id ";
        $sql .= "LEFT JOIN article_type AS at ON at.type_id = ck.article_type ";
        $sql .= "WHERE ck.copy_writer_id='{$copy_writer_id}' ";
        $sql .= "AND ck.campaign_id='{$campaign_id}' ";
        $sql .= "AND ar.article_status REGEXP '^(0|1gd|2)$' ";
        $sql .= "AND ck.keyword_status != 0 ";
        if (User::getRole() == 'editor') {
            $editor_id = User::getID();
        }
        if ($editor_id > 0) $sql .= " AND ck.editor_id = " . $editor_id;
        if ($is_total) $result = $conn->GetOne($sql);
        else $result = $conn->GetAll($sql);
        return $result;
    }

	public static function getInfoByExtensionID( $extension_id )
	{
		global $conn, $feedback;
		$extension_id = mysql_escape_string(htmlspecialchars(trim($extension_id)));
		$sql = "SELECT re.* FROM `request_extension` AS re ";
		if( $extension_id > 0)
		{
			$qu = " WHERE  re.extension_id=$extension_id";
		}
		else 
		{
			$feedback = "Parameter Invalid\nPlease to check";
			return false;
		}
		$sql = $sql . $qu;
        $result = $conn->GetRow($sql);
		return $result;
	}

    public static function grant($p)
    {
        global $conn;
        $editor_id = $p['editor_id'];
        $ck_editor_id = $p['ck_editor_id'];
        $extension_id = $p['extension_id'];
        if ($editor_id == 0) {
            self::storeOtherEditors($extension_id, $ck_editor_id);
            $p['editor_id'] = $ck_editor_id ;
        }
        $p['status'] = 2;
        self::store($p);
        $days_asked = addslashes(trim($p['days_asked']));
        $campaign_id = addslashes(trim($p['campaign_id']));
        $copy_writer_id = addslashes(trim($p['copy_writer_id']));
        $sql = 'UPDATE articles AS ar, campaign_keyword AS ck ';
        $sql .= 'SET ck.date_end =DATE_ADD(ck.date_end, INTERVAL ' . $days_asked . ' DAY) ';
        $sql .= 'WHERE ar.keyword_id=ck.keyword_id ';
        $sql .= " AND ck.campaign_id= '" . $campaign_id . "' ";
        $sql .= " AND ck.copy_writer_id= '" . $copy_writer_id . "' ";
        $sql .= " AND ar.article_status REGEXP '^(0|1gd|2)$' ";
        if (User::getRole() == 'editor') {
            $sql .= " AND ck.editor_id=" . User::getID() . " ";
        }
        $conn->Execute($sql);
        $info = User::getInfo($copy_writer_id);
        $rs = Campaign::getInfoFields($campaign_id, array('campaign_name'));
        $info['campaign_name'] = $rs['campaign_name'];
        $info['ask_days'] = $days_asked;
        self::sendAnnouceMail(21,$info['email'], $info);
        return true;
    }

    function sendAnnouceMail($event_id, $to, $p = array(), $subject =null, $cc=array(), $bcc=array())
    {
        Email::sendAnnouceMail($event_id, $to, $p, $subject, $cc, $bcc);
    }

    public static function storeOtherEditors($extension_id, $ck_editor_id)
    {
        $info = RequestExtension::getInfoByExtensionID($extension_id);
        $info['extension_id'] = null;
        $p = array('copy_writer_id' => $info['copy_writer_id'], 'campaign_id' => $info['campaign_id']);
        $result = Campaign::searchByCpAndEditor($p, false);
        foreach ($result as $row) {
            $editor_id  = $row['editor_id'];
            if ($ck_editor_id != $editor_id) {
                $p['editor_id'] = $editor_id;
                if (RequestExtension::getCountByParam($p) == 0) {
                    $info = array_merge($info, $row);
                    self::store($info);
                }
            }
        }
    }

    public static function reject($p)
    {
        $editor_id = $p['editor_id'];
        $ck_editor_id = $p['ck_editor_id'];
        $extension_id = $p['extension_id'];
        if ($editor_id == 0) {
            self::storeOtherEditors($extension_id, $ck_editor_id);
            $p['editor_id'] = $ck_editor_id ;
        }
        $p['status'] = 1;
        self::store($p);
        $campaign_id = addslashes(trim($p['campaign_id']));
        $copy_writer_id = addslashes(trim($p['copy_writer_id']));
        $info = User::getInfo($copy_writer_id);
        $rs = Campaign::getInfoFields($campaign_id, array('campaign_name'));
        $info['campaign_name'] = $rs['campaign_name'];
        self::sendAnnouceMail(20,$info['email'], $info);
    }

    public static function backup($p)
    {
        global $conn;
        $conditions = array();
        if (!empty($p)) {
            foreach ($p as $k => $v) {
                $v = addslashes(htmlspecialchars(trim($v)));
                $conditions[] = "{$k}='{$v}'";
            }
            $conditions[] = "is_bk = 0";
            $conditions[] = "`status` > 0";
            $sql = "UPDATE  `request_extension`  SET is_bk = 1  WHERE  ";
            if (!empty($conditions)) {
                $sql .= implode(" AND ", $conditions);
            }
            $conn->Execute($sql);
            return true;
        }
        return false;
    }

	public static function store( $hash )
	{
		global $conn, $feedback;	
		$campaign_id = mysql_escape_string(htmlspecialchars(trim($hash['campaign_id'])));
		$copy_writer_id = mysql_escape_string(htmlspecialchars(trim($hash['copy_writer_id'])));
		$editor_id = mysql_escape_string(htmlspecialchars(trim($hash['editor_id'])));
		$extension_id = mysql_escape_string(htmlspecialchars(trim($hash['extension_id'])));
		$subject = mysql_escape_string(htmlspecialchars(trim($hash['subject'])));
		$reason = mysql_escape_string(htmlspecialchars(trim($hash['reason'])));
		$days_asked = mysql_escape_string(htmlspecialchars(trim($hash['days_asked'])));
		$total = mysql_escape_string(htmlspecialchars(trim($hash['total'])));
        if (strlen($total) == 0) $total = 0;
		$status = mysql_escape_string(htmlspecialchars(trim($hash['status'])));
		if(strlen( $reason )==0)
		{
			$feedback = 'Please enter reason';
			return false;
		}
		if(strlen( $days_asked )==0)
		{
			$feedback = 'Please enter days asked';
			return false;
		} else if (!is_numeric($days_asked)) {
            $feedback = "Please enter number to days asked";
            return false;
        }
		if( count( $hash ) )
		{
			if( $extension_id > 0 )
			{
				if( $campaign_id > 0 )
					$set_query[] = "campaign_id=$campaign_id";
				if( $copy_writer_id > 0 )
					$set_query[] = "copy_writer_id=$copy_writer_id";
				if( $editor_id > 0 )
					$set_query[] = "editor_id=$editor_id";
				if( strlen($subject) )
					$set_query[] = "subject='$subject'";
				if( strlen($reason) )
					$set_query[] = "reason='$reason'";
				if( strlen($days_asked) )
					$set_query[] = "days_asked='$days_asked'";
				if( strlen($status) )
					$set_query[] = "status='$status'";
			    $set_query[] = "total=$total";
				$set_sub_query = implode(', ', $set_query);
				$sql = "UPDATE  `request_extension`  SET $set_sub_query WHERE  extension_id = $extension_id";
			}
			else
			{
				$sql = "INSERT INTO `request_extension` ( `copy_writer_id`, `editor_id`, `campaign_id`, `subject`, `reason`, `days_asked`, `total`) VALUES ($copy_writer_id, $editor_id, $campaign_id, '$subject', '$reason', '$days_asked', $total) ";
			}
			$conn->Execute($sql);
			 $feedback = 'Success';
			return true;
		}
		 $feedback = 'Failure, Please try again';
		return false;
	}
}
?>