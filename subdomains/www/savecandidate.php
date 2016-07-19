<?php
require_once 'pre.php';
$g_current_path = 'home';
require_once 'cms_menu.php';
require_once CMS_INC_ROOT.'/Candidate.class.php';
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/Email.class.php';
require_once CMS_INC_ROOT.'/Category.class.php';
global $feedback;
//$conn->debug = true;
print_r($_POST);
if (!empty($_POST)) {
    $p = $_POST;
    unset($p['opt_index']);
    unset($p['AcceptButton']);
    $accept = $p['accept'];
    unset($p['accept']);
    $cid = isset($_GET['cid']) && $_GET['cid'] ? $_GET['cid'] : $p['candidate_id'];
    if (empty($cid)) {
        $p['candidate_id'] = $cid;
    }
    $samples = $p['samples'];
    foreach ($samples as $key => $item) {
        if (empty($item)) unset($samples[$key]);
    }
    // added by nancy xu 2012-10-05 17:56
    if (!empty($p['address_apt'])) {
        if (!empty($p['address'])) $p['address'] .= "\n" . $p['address_apt'];
        else  $p['address'] = $p['address_apt'];
    }
    unset($p['address_apt']);
    // end
    
    $cid = Candidate::save($p);
    $fileField = array();
    if ($cid > 0 && !empty($_FILES)) {
        $data = array();
        if (isset($_FILES['samples']['name']['fileField']) && !empty($_FILES['samples']['name']['fileField'])) {
            $result = uploadedFiles('samples', $cid, $dir='candidate_samples');
            //print_r($result);
            if (is_array($result)) {
                $p['samples']['fileField'] = $result;
                $data['samples'] = $p['samples'];
            } else {
                echo $result;exit();
            }
        }
        if (!empty($_FILES['categories']['name']['fileField'])) {
            $result = uploadedFiles('categories', $cid, $dir='candidate_categories');
            if (is_array($result)) {
                $p['categories']['fileField'] = $result;
                $data['categories'] = $p['categories'];
            } else {
                echo $result;exit();
            }
        }
        if (!empty($data)) {
            $data['candidate_id'] = $cid;
            $cid = Candidate::saveInfo($data);
        }
    }

     //echo '&nbsp;<script type="text/javascript">jQuery("#div-allforms").html("<div style=\"color:red\">Thank you for your interest in opportunities with CopyPress. Your application has been submitted is currently under review. Should you be selected, we will be in touch with you.</div>")</script>';
     if (!empty($feedback) && empty($cid)) {
         echo $feedback; 
     } else  {
         //echo 'Thank you for your interest in opportunities with CopyPress. Your application has been submitted is currently under review. Should you be selected, we will be in touch with you.';
         echo 'Thank you for your interest in working with CopyPress. You will be notified once your application is reviewed and accepted.';
         //echo htmlspecialchars("<p>Thank you for submitting your application. </p><p>We\\'ll be in touch when you\\'re a fit for any CopyPress campaigns. In the meantime, you can review our <a href=\"http://community.copypress.com/guides/copypress-writers-guide/\" target=\"_blank\">Writer\\'s Guide</a>");
         //echo "<p>Thank you for submitting your application. </p><p>We\\'ll be in touch when you\\'re a fit for any CopyPress campaigns. In the meantime, you can review our <a href=\"http://community.copypress.com/guides/copypress-writers-guide/\" target=\"_blank\">Writer\\'s Guide</a>";
         //echo "<p>Thank you for submitting your application. </p><p>We\\'ll be in touch when you\\'re a fit for any CopyPress campaigns. In the meantime, you can review our <a href=\"http://community.copypress.com/guides/copypress-writers-guide/\" target=\"_blank\">Writer\\'s Guide</a>, fill out your <a href=\"http://community.copypress.com/how-to-complete-your-community-profile/\" target=\"_blank\">Community Profile</a>, and join our <a href=\"https://www.facebook.com/groups/186901618020210/\" target=\"_blank\">Facebook Group</a>. </p><p>Please note that only certified writer applications will be considered. If you are not certified, you can begin the process here: <a href=\"http://community.copypress.com/team/become-a-copypress-writer/\" target=\"_blank\">Become a CopyPress Writer</a></p><p>If you have any questions, please email <a href=\"mailto:community@copypress.com\" target=\"_blank\">community@copypress.com</a>.</p><p>Sincerely, <br />The CopyPress Community Team<br /></p>";
        $info = Candidate::getInfo($cid);
        Candidate::sentEmail($info);
     }
    exit();
}


?>