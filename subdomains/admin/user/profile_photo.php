<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4 && User::getPermission() > 1 ) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once 'sdk.class.php';
require_once 'MIME/Type.php';
$bucket = 'CopyPressImages';
$subbucket = 'userImages/';
$s3 = new AmazonS3();
define('MB', 1048576);

$user_id = empty($_GET) ? $_POST['user_id']:$_GET['user_id'];
$user_info = User::getInfo($user_id);
$upload_dir = $g_article_storage . 'img_profile/';
$resized_filename = !empty($user_info['big_photo']) ?  $user_info['big_photo'] : md5($user_id.'resized') . '.jpg';
$resized_img = $upload_dir . $resized_filename;
$thumb_img = $user_info['photo'];
$thumb_width = $thumb_height = 95;
$thumb_photo_exists = $large_photo_exists = "";
$ext = substr(strrchr($resized_filename, '.'), 0);
$thumb_filename = md5($user_id.'thumb') . $ext;
$keyname = $subbucket . $thumb_filename;

if (file_exists($resized_img)) {
    $type = MIME_Type::autoDetect($resized_img);
    if (!empty($thumb_img)){
        $thumb_photo_exists = "<img src=\"".$thumb_img."\" alt=\"Thumbnail Image\"/>";
    }
    $large_photo_exists = "<img src=\"/user/img_profile.php?g=".$resized_filename."\" alt=\"Large Image\"/>";
    $smarty->assign('large_img_width', getWidth($resized_img));
    $smarty->assign('large_img_height', getHeight($resized_img));
}

if (!empty($_POST)) {
    if (isset($_POST["upload"])) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
            chmod($upload_dir, 0777);
        }
        if (!empty($thumb_img)) {
            deleteImage($resized_img, $thumb_local_img, $bucket, $keyname, $user_id);
        } else if (file_exists($resized_img) || file_exists($thumb_local_img)) {
            if (file_exists($resized_img)) unlink($resized_img);
            if (file_exists($thumb_local_img)) unlink($thumb_local_img);
        }
        //Get the file information
        $userfile_name = $_FILES['image']['name'];
        $userfile_size = $_FILES['image']['size'];
        $filename = basename($_FILES['image']['name']);
        $file_ext = substr($filename, strrpos($filename, '.') + 1);
        $max_file = "1148576"; // Approx 1MB
        $max_width = 500;
        //Only process if the file is a JPG and below the allowed limit
        $feedback = '';
        $allowed_ext = array('jpg', 'png', 'gif');
        $resized_filename = md5($user_id.'resized') . '.' . $file_ext ;
        $resized_img = $upload_dir . $resized_filename;
        if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
            if (!in_array($file_ext, $allowed_ext)) {
                $feedback= "Invalid image, You only can upload jpg, png or gif";
            } else if ($userfile_size > $max_file) {
                $feedback= "ONLY images under 1MB are accepted for upload";
            }
        }else{
            $feedback= "Select image for upload";
        }
        if (strlen($feedback)==0){
            if (isset($_FILES['image']['name'])){
                // pr(move_uploaded_file($_FILES['image']['tmp_name'], $resized_img), true);
                move_uploaded_file($_FILES['image']['tmp_name'], $resized_img);
                chmod($resized_img, 0777);
                $width = getWidth($resized_img);
                $height = getHeight($resized_img);
                //Scale the image if it is greater than the width set above
                if ($width > $max_width){
                    $scale = $max_width/$width;
                    $uploaded = resizeImage($resized_img,  $width, $height, $scale, $_FILES['image']['type']);
                }else{
                    $scale = 1;
                    $uploaded = resizeImage($resized_img, $width,$height,$scale,$_FILES['image']['type']);
                }
                
                $data = array('big_photo' => basename($resized_img),  'user_id' =>  $user_id);
                User::setUserInfo($data);
            }
             header("location:". $_SERVER["REQUEST_URI"] );
        } else {
            echo "<script>alert('{$feedback}');</script>";
        }
    } else if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
        //Get the new coordinates to crop the image.
        $x1 = $_POST["x1"];
        $y1 = $_POST["y1"];
        $x2 = $_POST["x2"];
        $y2 = $_POST["y2"];
        $w = $_POST["w"];
        $h = $_POST["h"];
        //Scale the image to the thumb_width set above
        $scale = $thumb_width/$w;
        
        $thumb_local_img = $upload_dir . $thumb_filename;
        $cropped = resizeThumbnailImage($thumb_local_img, $resized_img,$w,$h,$scale ,'', $x1,$y1);
        //Reload the page again to view the thumbnail

        $response = $s3->create_mpu_object($bucket, $keyname , array(
            'fileUpload' => $thumb_local_img,
            // Optional configuration
            'partSize' => 5*MB, // Defaults to 50MB
            'acl' => AmazonS3::ACL_PUBLIC,
            'storage' => AmazonS3::STORAGE_REDUCED,
            'contentType' => 'image/jpeg', 
        ));

        if ($response->status == '200') {
            $data = array('photo' =>$s3->request_url,  'user_id' =>  $user_id);
            User::setUserInfo($data);
        } else {
            $body = (array)$response->body;
            $feedback = $body['Message'];
        }
        echo "<script>alert('{$feedback}');</script>";
        echo "<script>window.location.href='/user/user_detail.php?user_id={$user_id}';</script>";
        exit();
    }
} else {
    if (empty($_POST) && trim($_GET['user_id']) == '') {
        echo "<script>alert('Please choose an user');</script>";
        echo "<script>window.location.href='/user/list.php';</script>";
        exit;
    }
}

if ($_GET['a']=="delete"){
    deleteImage($resized_img, $thumb_local_img, $bucket, $keyname, $user_id);
	header("location:".$_SERVER["REQUEST_URI"]);
	exit(); 
}

$smarty->assign('user_info', $user_info);
$smarty->assign('user_id', $user_id);
$smarty->assign('resized_filename', $resized_filename);

$smarty->assign('thumb_width', $thumb_width);
$smarty->assign('post_url', $_SERVER["REQUEST_URI"]);
$smarty->assign('thumb_height', $thumb_height);
$smarty->assign('thumb_photo_exists', $thumb_photo_exists);
$smarty->assign('large_photo_exists', $large_photo_exists);
$smarty->display('user/profile_photo.html');
//Image functions
function deleteImage($resized_img, $thumb_local_img, $bucket, $keyname, $user_id) {
    $s3 = new AmazonS3();
	if (file_exists($resized_img)) {
		unlink($resized_img);
	}
	if (file_exists($thumb_local_img)) {
		unlink($thumb_local_img);
	}
    $s3->delete_object($bucket, $keyname);
    $data = array('photo' => '' ,  'user_id' =>  $user_id);
    User::setUserInfo($data);
}
//You do not need to alter these functions
function resizeImage($image, $width, $height, $scale, $type) {
	return resizeThumbnailImage($image, $image, $width, $height, $scale, $type);
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $scale, $type ='image/jpeg', $start_width = 0, $start_height =0){
    if (empty($type)) $type = MIME_Type::autoDetect($image);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        
    if ($type == 'image/gif') {
        $source = imagecreatefromgif($image);
    } else if ($type == 'image/png') {
         $source = imagecreatefrompng($image);
    } else {
	    $source = imagecreatefromjpeg($image);	    
    }
    
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
    if ($type == 'image/gif') {
        imagegif($newImage, $thumb_image_name);
    } else if ($type == 'image/png') {
        imagepng($newImage, $thumb_image_name);
    } else {
        imagejpeg($newImage, $thumb_image_name, 90); 
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}
?>