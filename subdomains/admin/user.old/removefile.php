<?php
    $g_current_path = "preference";
    require_once('../pre.php');
    require_once('../cms_menu.php');
    if (!user_is_loggedin()) { // 2=>3
            header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
                exit;
    }    
   // Edit upload location here
   $user_id = User::getID();
   $destination_path = $w9_dir . $user_id . DS;
   $filename = $_POST['filename'];
   unlink($destination_path . $filename);
   User::setByID(array('user_id' => $user_id, 'w9pdf' =>''));
   sleep(1);
?>
<script language="javascript" type="text/javascript">window.top.window.location.reload();</script>   