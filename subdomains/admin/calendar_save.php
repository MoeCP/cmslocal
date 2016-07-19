<?php
require_once "pre.php";
require_once CMS_INC_ROOT.DS."UserCalendar.class.php";
require_once CMS_INC_ROOT.DS."User.class.php";
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}
$user_id = $_POST['user_id'];
if (empty($user_id)) $user_id = User::getID();
$info = User::getInfo($user_id);
$save_arr['user_id'] = $user_id;
$save_arr['user_name'] =$info['user_name'];
$save_arr['role'] = $info['role'];
$save_arr['c_date'] = $_POST['date'];
$save_arr['is_free'] = 0;
$table = new UserCalendar();
try {
    $table->save($save_arr);
    echo "ok";
} catch(Exception $e) {
    echo $e.message;
}
?>