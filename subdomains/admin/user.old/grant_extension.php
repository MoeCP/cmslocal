<?php
require_once('../pre.php');//加载配置信息
//$conn->debug = true;
require_once('../cms_menu.php');

if (!user_is_loggedin()) 
{
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/request_extension.class.php';
require_once CMS_INC_ROOT.'/User.class.php';

$extension_id = $_GET['eid'];
$ck_editor_id = $_GET['ckeid'];

if( $extension_id > 0 )
{
	$info = RequestExtension::getInfoByExtensionID($extension_id);
}

$smarty->assign('info', $info);
$smarty->assign('ck_editor_id', $ck_editor_id);
if ( count( $_POST ) )
{
    $hash = array_merge($info, $_POST);
	if ( RequestExtension::grant($hash))
	{
        echo '<script language="javascript">
        window.opener.opener = null;
        window.opener.location.replace(window.opener.location.href);
        window.close();
        </script>';
	}
}
$smarty->assign('feedback', $feedback);
$smarty->assign('campaign_name', $campaign_name);
$smarty->assign('copy_writer_name', User::getName());

$smarty->display('user/grant_extension.html');
?>