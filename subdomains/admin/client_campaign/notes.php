<?php

$g_current_path = "client_campaign";
require_once('../pre.php');//load config
require_once('../cms_menu.php');

$keyword_id = $_REQUEST['keyword_id'];
$campaign_id = $_REQUEST['campaign_id'];
$copy_writer_id = $_REQUEST['copy_writer_id'];
$editor_id = $_REQUEST['editor_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notes = $_POST['notes'];
    if (!empty($notes)) {
       $sql = "INSERT INTO editor_notes(notes, keyword_id, campaign_id, copy_writer_id, editor_id)
                VALUES('$notes', $keyword_id, $campaign_id, $copy_writer_id, $editor_id)";
        $conn->Execute($sql);
        $feedback = 'Add Notes Success!';
    } else {
        $feedback = 'Please fill Notes';
        $smarty->assign('notes', $notes);
    }
} else {
    $smarty->assign('notes', '');
}

//keyword_id={$item.keyword_id}&campaign_id={$item.campaign_id}&copy_writer_id={$item.copy_writer_id}&editor_id={$item.editor_id}'
$smarty->assign('keyword_id', $_REQUEST['keyword_id']);
$smarty->assign('campaign_id', $_REQUEST['campaign_id']);
$smarty->assign('copy_writer_id', $_REQUEST['copy_writer_id']);
$smarty->assign('editor_id', $_REQUEST['editor_id']);


$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/notes.html');


?>