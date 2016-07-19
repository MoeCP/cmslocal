<?php
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Campaign.class.php';
$str = passport_decrypt($_GET['p']);
$param = explode('.', $str);
//pr($param);
if (count($param) ==4) {
    $keyword_id =$param[2]; 
    $user_id = $param[1];
    $data['keyword_id'] = $keyword_id;
     if ($param[0]==1) {
         $data['cp_status'] = $param[3];
         $data['cp_accept_time'] = date("Y-m-d H:i:s");
         $conditions[]  = 'cp_status=-1';
         $conditions[]  = 'copy_writer_id=' . $user_id;
         if ($param[3] == 0) {
             if (user_is_loggedin() &&User::getID() <> $user_id) {
                 exit('You are currently logged in as ' . User::getName(). '.  Please login to the account that this task was assigned to.');
             }
             header('location:/article/decline_article.php?keyword_id=' . $keyword_id);
             exit();
         }
     } else if ($param[0] == 3) {
         $data['editor_status'] = $param[3];
         $conditions[]  = 'editor_status=-1';
         $conditions[]  = 'editor_id=' . $user_id;
     }
     Campaign::updateKeyword($data, $conditions);
    echo 'Success. Click <a href="' . $g_base_url . '/article/acceptance.php">here</a> and go to Assignment Acceptance Page';
} else {
    echo 'Invalid parameter, Please to check.';
}
exit();
?>