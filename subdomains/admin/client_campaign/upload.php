<?php
ini_set('post_max_size', '40M');
ini_set('upload_max_filesize', '20M');
require_once('../pre.php');
require_once('../cms_menu.php');
if (!user_is_loggedin() || User::getPermission() < 3) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/feed_url.class.php';
$allowedexts = array('xml');

$campaign_id = $_POST['campaign_id'];
$client_id = $_POST['client_id'];
$destination_path = WEB_PATH . DS . 'openfiles' . DS . 'gfeed' . DS . $client_id ;
if (!file_exists($destination_path)) {
    mkdir($destination_path, 0777);
}
$destination_path .= DS . $campaign_id;
 if (!file_exists($destination_path)) {
    mkdir($destination_path, 0777);
}
$destination_path .= DS;
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
    $full_file = $prefix . $filename;
    $target_path = $destination_path . $full_file;
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
    $data = array(
        'feed_url' => $g_base_url . '/openfiles/gfeed/' . $client_id . '/' . $campaign_id . '/' . $full_file,
        'campaign_id' => $campaign_id,
    );
    $url_id = FeedUrl::save($data);
} else {
    $result = -1;
}
sleep(1);
?>

<script language="javascript" type="text/javascript">window.location.href='/client_campaign/uploadfeed.php?url_id=<?php echo $url_id; ?>&campaign_id=<?php echo $campaign_id;?>'</script>   
