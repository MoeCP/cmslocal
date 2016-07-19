<?php
require_once 'pre_cron.php';//parameter settings
require_once CMS_INC_ROOT . '/Inputfilter.class.php';
/*$str = '<p>Perfection is rare -- even when sought by a camera lens. Pictures will fail; images will disappoint; and photographers (whether professional or amateur) may find themselves staring at red-eye tragedies. Mistakes are all too common.<br /><br />They can also, however, be corrected.<br /><br />Even the <a href="http://accessories.us.dell.com/sna/category.aspx?c=us&amp;l=en&amp;s=dhs&amp;cs=19&amp;category_id=4005" target="_blank">best digital cameras</a> require support. Editing software is therefore needed to reconfigure pictures and straighten unwanted angles. Choose then to invest in quality wares to receive quality results. Consider these programs:<br /><br />Photoshop: Offered first in 2003, Adobe Photoshop has become the premier creative suite program. It allows users to experiment with 3D file formatting, refining technology and image stitching (which allows separate photos to be combined seamlessly). <br /><br />Picasa: Since 2004 Picasa has been offering editing techniques for the inexperienced. It provides color enhancement technology, angle adjustments, cropping and red eye removal -- all of which can be done by novices.<br /><br />iPhoto: Released in 2002, the iPhoto program is intended for convenience. Its streamlined <a href="http://en.wikipedia.org/wiki/Image_editing" target="_blank">editing tools</a> (such as contrast filters, resizing, brightness adjustments and more) allow it to be understood by all. <br /><br />Paint Shop Pro: Supporting Microsoft users (as well as the best digital cameras) Paint Shop Pro began in 1990. Over time it has developed into a juggernaut of editing capabilities. It offers raster graphics -- which enables compositions blends, format conversion and typography insertion. It also can replace blurred pixels with ease.<br /><br />Adobe Fireworks: Established as a part of Adobe&rsquo;s ever-growing brand in 2005, Fireworks allows users to alter their photos quickly. Nine scale slicing, universal symbol usage (which offers editing for multiple files at once) and object layering makes each pixel perfect.<br /><br /><a href="http://graphicssoft.about.com/od/pixelbased/a/bybphotoeditor.htm" target="_blank">Use software</a> to redefine photographs and gain a professional appearance.</p>';
$str = change_richtxt_to_paintxt($str, ENT_QUOTES);
$words = preg_split("/[\s]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
foreach ($words as $k => $v) {
    echo $k . ' ' . $v . '<br />';
}
exit();*/
/*
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<script type="text/javascript" src="http://i9cms/js/prototype.js"></script>
<div id="message" class=""></div>
<script>
var str = '<p>Perfection is rare -- even when sought by a camera lens. Pictures will fail; images will disappoint; and photographers (whether professional or amateur) may find themselves staring at red-eye tragedies. Mistakes are all too common.<br /><br />They can also, however, be corrected.<br /><br />Even the <a href="http://accessories.us.dell.com/sna/category.aspx?c=us&amp;l=en&amp;s=dhs&amp;cs=19&amp;category_id=4005" target="_blank">best digital cameras</a> require support. Editing software is therefore needed to reconfigure pictures and straighten unwanted angles. Choose then to invest in quality wares to receive quality results. Consider these programs:<br /><br />Photoshop: Offered first in 2003, Adobe Photoshop has become the premier creative suite program. It allows users to experiment with 3D file formatting, refining technology and image stitching (which allows separate photos to be combined seamlessly). <br /><br />Picasa: Since 2004 Picasa has been offering editing techniques for the inexperienced. It provides color enhancement technology, angle adjustments, cropping and red eye removal -- all of which can be done by novices.<br /><br />iPhoto: Released in 2002, the iPhoto program is intended for convenience. Its streamlined <a href="http://en.wikipedia.org/wiki/Image_editing" target="_blank">editing tools</a> (such as contrast filters, resizing, brightness adjustments and more) allow it to be understood by all. <br /><br />Paint Shop Pro: Supporting Microsoft users (as well as the best digital cameras) Paint Shop Pro began in 1990. Over time it has developed into a juggernaut of editing capabilities. It offers raster graphics -- which enables compositions blends, format conversion and typography insertion. It also can replace blurred pixels with ease.<br /><br />Adobe Fireworks: Established as a part of Adobe&rsquo;s ever-growing brand in 2005, Fireworks allows users to alter their photos quickly. Nine scale slicing, universal symbol usage (which offers editing for multiple files at once) and object layering makes each pixel perfect.<br /><br /><a href="http://graphicssoft.about.com/od/pixelbased/a/bybphotoeditor.htm" target="_blank">Use software</a> to redefine photographs and gain a professional appearance.</p>';
str = str.unescapeHTML().replace(/<\/?[^>]+>/gi,"").replace(/&nbsp;|&#160;|\.|:/gi, ' ').replace(/\s+/g," ");

var arr = str.split(' ');
str = '';
for (var i=0; i < arr.length; i++)
{
    str+= i + ' ' + arr[i] +  "<br />";
}
$('message').update(str);
</script>
</body>
</html>
<?php
*/
$body = "<div>
    <strong>Possible duplicated article:</strong><br />
    paydayone.com <br />
    http://content.copypress.com/article/article_comment_list.php?article_id=114811<br /><br />
<br /><a href='http://www.google.com/search?hl=en&newwindow=1&q=%22+Payday+loans+should+only+be+used+for+emergency+situations%22' >Google Search Link</a><br />
    <br />please to re-submit <a href='http://content.copypress.com/article/article_set.php?article_id=114811&keyword_id=114811' >here</a><br /><br />
    <strong>Writer's Contact Info</strong><br />
    Name:&nbsp;Cynthia&nbsp;Hannikman<br />
    Email:&nbsp;angelicbaby1313@gmail.com<br />
    Phone:&nbsp;216-571-3507<br />
</div>";
//$ccs = array('cptech@copypress.com', 'tony@infinitenine.com');
//$ccs[] = 'jillenabean@gmail.com';
//$ccs = array_unique($ccs);
//$mailer_param['cc'] = $ccs;
//$address = 'angelicbaby1313@gmail.com';
$address = 'xusnug14@gmail.com';
$subject = 'Possible Duplicated Article';
$mailer_param =  array(
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port' => 465,
    'smtp_username' => 'xusnug1@gmail.com',
    'smtp_password' => 'xxn1027',
    'sender'          => 'xusnug1@gmail.com',
    'from'          => 'xusnug1@gmail.com',
    'from_name' => "Test",
    'reply_to'     => 'xusnug1@gmail.com',
    'smtp_secure'     => 'ssl',
     'smtp_auth'     => true
);
send_smtp_mail( $address, $subject, $body, $mailer_param );
//echo md5(8);



?>
