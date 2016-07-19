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
    'agora_caller' ,
    'agora_callee' ,
    'type' ,
    'channel' ,
    'dynamic_key' ,
    'status' ,
    'created' ,
    'accept_time' ,
    'leave_time');
	//status[1,0,-1]  --> 1,默认请求对话。0.接受请求，-1拒绝请求

if (isset($data['agora_caller']) && !is_numeric($data['agora_caller'])) $data['agora_caller'] = (int)str_ireplace("user", "", $data['agora_caller']);
if (isset($data['agora_callee']) && !is_numeric($data['agora_callee'])) $data['agora_callee'] = (int)str_ireplace("user", "", $data['agora_callee']);
if (isset($data['uid']) && !is_numeric($data['uid'])) $data['uid'] = (int)str_ireplace("user", "", $data['uid']);
$nowts = time();
if ($action == 'update' || $action == 'add') {
    $hash = array();
    foreach ($fields as $field) {
        if (!empty($data[$field])) {
            $hash[$field] = addslashes($data[$field]);
        }
    }
	//$nowts = time();
	$now = date("Y-m-d H:i:s", $nowts);
	if ($action == 'add') {$hash['created'] = $now;}
	if ($action == 'update') {
		if (isset($data['accept_type']) && $data['accept_type'] = 'denied') {
			$hash['status'] = "-1";
			$hash['leave_time'] = $now;
		} elseif (isset($data['accept_type']) && $data['accept_type'] = 'accept') {
			$hash['status'] = "0";
			$hash['accept_time'] = $now;
		} else {
			$hash['status'] = "0";
			$hash['leave_time'] = $now;
		}
	}
}
switch($action) {
    case 'get':
        $uid = $data['uid'];
		if (!is_numeric($uid)) $uid = (int)str_ireplace("user", "", $uid);
		$rs = array();
        if ($uid > 0) {
            $sql = "SELECT acl.*, u.user_name ".
				"FROM `agora_call_log` as acl LEFT JOIN users AS u ON (u.user_id=acl.agora_caller) ".
				"WHERE acl.`status`='1' AND acl.`type` IN ('audio','video') AND acl.`agora_callee`=" . $uid;
            $rs = $conn->GetAll($sql);
        }
		$rs = array('msg' => 'Success', 'channels'=>$rs, 'success'=>true, 'current_server_time'=>$nowts);
        break;
    case 'update':
        $id = $data['id'];
        if ($id) {
            $sql = 'UPDATE  `agora_call_log`  SET ';
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
		$sql = "SELECT id FROM `agora_call_log` WHERE status=1 AND `agora_caller`='" . $data['agora_callee'] . "' AND agora_callee = '".$data['agora_caller']."'";
		$rs = $conn->GetRow($sql);
		if (!empty($rs)) {
			$sql = "UPDATE  `agora_call_log` SET status=0 WHERE id='".$rs['id']."'";
			$conn->Execute($sql);
			$rs = array('msg' => 'Success', 'success'=>true);
		} else {
			$sql = "SELECT id FROM `agora_call_log` WHERE status=1 AND `agora_caller`='" . $data['agora_caller'] . "' AND agora_callee = '".$data['agora_callee']."'";
			$rs = $conn->GetRow($sql);
			if (empty($rs)) {
				$sql = 'INSERT INTO `agora_call_log` (`' . implode('`,`', array_keys($hash)) . "`) VALUES ( '" . implode("','", $hash) .  "')";
				$conn->Execute($sql);
			}
			$rs = array('msg' => 'Success', 'success'=>true);
		}

        break;
}
echo json_encode($rs);
?>