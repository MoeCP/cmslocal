<?php
class ImageComment{


	function __construct()
	{
	}

    function ImageComment()
    {
        $this->__construct();
    }

    function getTable()
    {
        return 'image_comments';
    }

    function getCommentsByImageID($image_id)
    {
        global $conn;
        $q = "SELECT ic.*, u.user_name AS creator, c.user_name AS ccreator  " . 
             "FROM " . self::getTable() . " AS ic ".
             "LEFT JOIN users AS u ON (u.user_id = ic.creation_user_id) ".
             "LEFT JOIN client AS c ON (c. client_id  = ic.creation_user_id) ".
             "WHERE ic.image_id = '".$image_id . "'" ;
        if (client_is_loggedin()) {
            $q .= ' AND ic.creation_role IN (\'client\', \'admin\', \'project manager\') ';
        }
        $q .= ' ORDER BY ic.version_number, ic.creation_date ';
        return $conn->GetAll($q);
    }

    function addComments($comment, $image_id, $image_info) 
    {
        global $feeback, $conn;
        $qcw = "";
        $data = array(
            'image_id' => $image_id, 'comment' => $comment, 
            'version_number' => $image_info['current_version_number'], 'language'=> '');
        if (user_is_loggedin()) {
            $data['creation_user_id'] = User::getID();
            $data['creation_role'] = User::getRole();
            $qcw .= "AND creation_user_id = '".User::getID()."' AND creation_role = '".User::getRole()."' ";
        } elseif (client_is_loggedin()) {
            $data['creation_user_id'] = Client::getID();
            $data['creation_role'] = 'client';
            $qcw .= "AND creation_user_id = '".Client::getID()."' AND creation_role = 'client' ";
        } else {
            $feedback = "Please sign in this system";
            return false;
        }
       
        $q = "SELECT COUNT(*) AS count FROM  " . self::getTable() .
             " WHERE image_id = '". $image_id ."' AND comment = '".$comment."' ".
             "AND version_number = '". $image_info['current_version_number'] ."' ".$qcw;
        $count = $conn->GetOne($q);
        if (!empty($count)) return false;
        $do_comment = true;
        //add comments
        
        if ($comment != '' && $do_comment) {//do comment
            return self::add($data);
        }
        return false;
    }

    function add($p)
    {
        global $conn;
        $q = 'INSERT INTO  ' . self::getTable() . ' (`' . implode('`, `', array_keys($p))  . '`) VALUES (\'' . implode("', '", $p). '\')';
        $conn->Execute($q);
        return (($conn->Affected_Rows() > 0) ? true : false);
    }
}
?>