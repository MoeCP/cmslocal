<?php
    $g_current_path = "preference";
    require_once('../pre.php');
    require_once('../cms_menu.php');
    if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
            header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
                exit;
    }    
   // Edit upload location here
   $destination_path = $g_article_storage;
   unlink($destination_path . $_POST['filename']);
   sleep(1);
?>