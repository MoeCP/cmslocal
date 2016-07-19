<?php
$g_current_path = "client_campaign";
require_once('../pre.php');//load config
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/campaign_notes.class.php';
require_once CMS_INC_ROOT.'/Campaign.class.php';

if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}

$campaign_id = $_REQUEST['campaign_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $opt = trim($_POST['operation']);
    unset($_POST['operation']);
    switch ($opt)
    {
    case 'delete':
        $campaign_id = $_POST['campaign_id'];
        if ($campaign_id > 0)
        {
            if (CampaignNotes::deleteNotesByCampaignId($campaign_id))
            {
                $feedback = 'Delete Notes Success!';
            }
            else
            {
                $feedback = 'Delete Notes Failed!';
            }
        }
        else
        {
            $feeback = 'Please Specify the Campaign';
        }
        break;
    case 'append';
    case 'email';
        if(!empty($_POST['subject']) && isset($_POST['subject'])) {
            $subject = $_POST['subject'];
            if (!empty($_POST['note'])){
                $_POST['note'] .= "\n".$subject;
            }else {
                $_POST['note'] .= $subject;
            }
        }
        unset($_POST['subject']);
        unset($_POST['sub']);
        $note = $_POST['note'];
        if (!empty($note)) {
            if (CampaignNotes::store($_POST))
            {
                if ($opt  == 'email') {
                    $notes = $campaign_id > 0 ? CampaignNotes::getNotesByCampaignID($campaign_id) : '';
                    if (!empty($notes)) Campaign::sendNoteToAllCampaignEditor($campaign_id, $notes);
                    $feedback = 'Add note and email those notes';
                } else {
                    $feedback = 'Add Notes Success!';
                }
            }
        } else {
            $feedback = 'Please fill Notes';
        }
        break;
    }
}
//die(print_r($notes));
//add by liushufen 14:52 2007-11-26
$subject = CampaignNotes::getGeneralNotes(array('single_column'=>'body'));
//die(print_r($subject));
//END

// get all notes by campaign_id 
$notes = $campaign_id > 0 ? CampaignNotes::getNotesByCampaignID($campaign_id) : '';
$smarty->assign('campaign_id', $campaign_id);
$smarty->assign('creator', User::getID());
$smarty->assign('creator_role', User::getRole());
$smarty->assign('notes', $notes);
//print_r($subject);
$smarty->assign('subject',$subject);//array("getinto")
$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/campaign_notes.html');

?>