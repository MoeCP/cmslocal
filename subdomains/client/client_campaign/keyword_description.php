<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//load config
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Article.class.php';

if (!user_is_loggedin() && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
$campaign_id = $_REQUEST['campaign_id'];
$pm_name     = $_REQUEST['pm_name'];

// get all notes by campaign_id 
$info = $campaign_id > 0 ?  Article::getInfoByKeywordID($campaign_id): array();
if (count($info) && strlen($info['keyword_description']))
{
    $notes = "<b>Note From {$pm_name}:</b><br />" . nl2br($info['keyword_description']);
} 
else
{
    $notes = '';
}
$smarty->assign('notes', $notes);
$smarty->display('client_campaign/keyword_description.html');
?>