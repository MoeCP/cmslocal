<?php
//$g_current_path = "preference";
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin()) { // 2=>3
	echo json_encode(array('msg' => 'Permission Denied', 'success'=>false));
    exit;
}
global $conn;
$data = $_REQUEST;
$action = strtolower($data['act']);
$fields = array(
    'user_id' ,
    'user_name' ,
    'user_avatar' ,
    'invite_ids' ,
    'invite_users' ,
    'invite_user_avatars' ,
    'sendbird_channel_url' ,
    'sendbird_channel' ,
    'agora_channel' ,
    'status' ,
    'latest_used' );
if (isset($data['user_id']) && !is_numeric($data['user_id'])) $data['user_id'] = (int)str_ireplace("user", "", $data['user_id']);
if (isset($data['invite_ids']) && !is_numeric($data['invite_ids'])) $data['invite_ids'] = (int)str_ireplace("user", "", $data['invite_ids']);
if (isset($data['uid']) && !is_numeric($data['uid'])) $data['uid'] = (int)str_ireplace("user", "", $data['uid']);

if ($action == 'update' || $action == 'add') {
    $hash = array();
    foreach ($fields as $field) {
        if (!empty($data[$field])) {
            $hash[$field] = addslashes($data[$field]);
        }
    }
	$hash["latest_used"] = date("Y-m-d H:i:s");
}

switch($action) {
    case 'get':
        $uid = $data['uid'];
		//if (!is_numeric($uid)) $uid = (int)str_ireplace("user", "", $uid);
		$rs = array();
        if ($uid > 0) {
			$sql = "SELECT ucc.*, u.user_name, u.user_name, CONCAT( u2.first_name, ' ', u2.last_name ) AS full_name, u2.chat_status  ".
				"FROM `user_chat_channels` as ucc ".
				"LEFT JOIN users AS u ON (u.user_id=ucc.user_id) ".
				"LEFT JOIN users AS u2 ON (u2.user_id=ucc.invite_ids) WHERE ucc.`user_id`=" . $uid;

            $rs = $conn->GetAll($sql);
            $sql = "SELECT ucc.*, u.user_name AS invite_users, CONCAT(u.first_name, ' ', u.last_name ) AS full_name, u2.chat_status ".
				"FROM `user_chat_channels` as ucc ".
				"LEFT JOIN users AS u ON (u.user_id=ucc.invite_ids) ".
				"LEFT JOIN users AS u2 ON (u2.user_id=ucc.user_id) WHERE ucc.`invite_ids`=" . $uid;

            $rs += $conn->GetAll($sql);
        }
		$rs = array('msg' => 'Success', 'users'=>$rs, 'success'=>true);
        break;
    case 'update':
        $id = $data['id'];
        if ($id) {
            $sql = 'UPDATE  `user_chat_channels`  SET ';
            $sets = array();
            foreach ($hash as $field => $value) {
                $sets[] = "`{$field}`='" . $value. "'";
            }
            $sql .= implode(',', $sets) . ' WHERE id=' . $id ;
            $conn->Execute($sql);
            $rs = array('msg' => 'Success', 'success'=>true);
        } else {
            $rs = array('msg' => 'Invalid ID, please to check', 'users'=>array(), 'success'=>false);
        }
        break;
    case 'add':
        $uid = $data['uid'];
		if (User::getID() != $data['user_id']) {
			//echo User::getID()."__".$data['user_id'];
			$rs = array('msg' => 'Wrong User', 'success'=>false);
		} else {
			//if (!is_numeric($uid)) $uid = (int)str_ireplace("user", "", $uid);
			$sql = "SELECT id FROM `user_chat_channels` WHERE `user_id`='" . $data['user_id'] . "' AND invite_ids = '".$data['invite_ids']."'";
			$rs = $conn->GetRow($sql);
			if (empty($rs)) {
				$sql = 'INSERT INTO `user_chat_channels` (`' . implode('`,`', array_keys($hash)) . "`) VALUES ( '" . implode("','", $hash) .  "')";
				$conn->Execute($sql);
			}
			$rs = array('msg' => 'Success', 'success'=>true);
		}

        break;

    case 'setstatus':
		$data['user_id'] = str_replace("user", "", $data['user_id']);
		if (User::getID() != $data['user_id']) {
			//echo User::getID()."__".$data['user_id'];
			$rs = array('msg' => 'Wrong User', 'success'=>false);
		} else {
			$sql = "UPDATE  `users` SET chat_status='".$data['chat_status']."' WHERE user_id='".$data['user_id']."'";
			$conn->Execute($sql);
			$rs = array('msg' => 'Success', 'success'=>true);
		}

        break;
}
echo json_encode($rs);
?>