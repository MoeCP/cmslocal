<?php

/* $Id: img_chs.php,v 1.01 2006-4-28 15:35:24 Leo.Liu Exp $ */

// Generate a random checksum image in png format
$_SESSION['sc_img'] = "";
session_start();

// no cache
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");

@header("Content-type: image/jpeg");

function gen_checksum($digits)
{
    $c = "";
    for ($i = 1; $i <= $digits; $i ++) {
        $r = mt_rand(48, 57); // ASCII 0 to 9, // See http://www.asciitable.com/
        //$r = mt_rand(65, 90); // ASCII A to Z, // See http://www.asciitable.com/
        //$r = mt_rand(97, 122); // ASCII a to z, // See http://www.asciitable.com/
        $c .= chr($r);
    }
    return $c;
}

function gd_img($content)
{
    $image_x = 70;
    $image_y = 20;

    $im = imagecreate($image_x, $image_y);

    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    $grey  = imagecolorallocate($im, 200, 200, 200 );

    $no_x_lines = ($image_x - 1) / 5;

    for ($i = 0; $i <= $no_x_lines; $i ++) {
        // X lines
        imageline($im, $i * $no_x_lines, 0, $i * $no_x_lines, $image_y, $grey);
        // Diag lines
        imageline($im, $i * $no_x_lines, 0, ($i * $no_x_lines) + $no_x_lines, $image_y, $grey);
    }

    $no_y_lines = ($image_y - 1) / 5;

    for ($i = 0; $i <= $no_y_lines; $i ++) {
        imageline($im, 0, $i * $no_y_lines, $image_x, $i * $no_y_lines, $grey);
    }

    $font = '/home/sites/com.cpcms/admin/hurryup.ttf';
    //$font = 'F:/Htdocs/cms/66.240/CMS/www/hurryup.ttf';

    $text_bbox = imagettfbbox(22, 0, $font, $content);

    $sx = ($image_x - ($text_bbox[2] - $text_bbox[0])) / 2; 
    $sy = ($image_y - ($text_bbox[1] - $text_bbox[7])) / 2; 
    $sy -= $text_bbox[7];

    imagettftext($im, 22, 0, $sx, $sy, $black, $font, $content);

    imagejpeg($im);
    imagedestroy($im);
}

if (!isset($_SESSION['user_id'])) { // We don't use User:getID() because this script not include '../pre.php'

    $_SESSION['sc_img'] = gen_checksum(4);
/*
    $string = $_SESSION['img_chs'];
    $im     = imagecreatefrompng("./sc_img.png");
    $color  = imagecolorallocate($im, 255, 0, 0);
    imagestring($im, 5, 4, 1, $string, $color);
    imagepng($im);
    imagedestroy($im);
*/
    gd_img($_SESSION['sc_img']);
}

?>