<?php
    $g_current_path = "preference";
    require_once('../pre.php');
    require_once('../cms_menu.php');
    if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
        header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
        exit;
    }
    $allowedexts = array('doc','docx','xls','xlsx','pdf','jpg','jpeg','gif','png');
    $destination_path = $g_article_storage;
    $result = 0;
    $prefix = time() . rand(1,1000000);
    $filename = basename( $_FILES['myfile']['name']);
    $arr = explode('.', $filename);
    $count = count($arr);
    $ext = '';
    if ($count > 1) {
        $ext = strtolower($arr[$count-1]);
    }
    if (in_array($ext, $allowedexts)) {
        $target_path = $destination_path . $prefix . $filename;
        if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
            $result = 1;
        } else {
            $error = $_FILES['myfile']['error'];
            if ($error <= 2) {
                $result = -2;
            } else {
                $result = -1 * $error;
            }
        }
    } else {
        $result = -1;
    }

    sleep(1);
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>, '<?php echo $filename;?>', '<?php echo $prefix?>');</script>   
