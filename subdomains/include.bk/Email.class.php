<?php
/**
* mail class
*
* @global  string $conn
* @global  string $feadback
* @access  public
*/
class Email {

    function add($p = array())
    {
        global $conn, $feedback;
        global $g_tag;

        /*
        $foldername = addslashes(htmlspecialchars(trim($p['foldername'])));
        if ($foldername == '') {
            $feedback = 'Please provide the folder name';
            return false;
        }
        */
        $foldername = 'Public';

        $event_id = addslashes(htmlspecialchars(trim($p['event_id'])));
        if ($event_id == '') {
            $feedback = 'Please provide the template name';
            return false;
        }
        $subject = addslashes(htmlspecialchars(trim($p['subject'])));
        if ($subject == '') {
            $feedback = 'Please provide subject';
            return false;
        }
        $body = addslashes(htmlspecialchars(trim($p['body'])));
        if ($body == '') {
            $feedback = 'Please enter mail body';
            return false;
        }
        $description = addslashes(htmlspecialchars(trim($p['description'])));

        $q = "SELECT COUNT(*) AS count FROM email_templates ".
             "WHERE event_id = '".$event_id."'";
        $rs = $conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = 'The template\'s name already registered, please choose another one';
            return false;
        }

        $conn->StartTrans();
        $template_id = $conn->GenID('seq_email_templates_template_id');
        $q = "INSERT INTO email_templates (template_id, event_id, foldername, templatename, subject, `description`, body, deleted) ".
             "VALUES ('".$template_id."', ".
                     "'".$event_id."', ".
                     "'".$foldername."', ".
                     "'".$g_tag['email_event'][$event_id]."', ".
                     "'".$subject."', ".
                     "'".$description."', ".
                     "'".$body."', ".
                     "'0')";

        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end add()

    function setInfo($p = array())
    {
        global $conn, $feedback;
        global $g_tag;

        $template_id = addslashes(htmlspecialchars(trim($p['template_id'])));
        if ($template_id == '') {
            $feedback = 'Please choose a mail template';
            return false;
        }
        /*
        $foldername = addslashes(htmlspecialchars(trim($p['foldername'])));
        if ($foldername == '') {
            $feedback = 'Please provide the folder name';
            return false;
        }
        */
        $foldername = 'Public';
        $event_id = addslashes(htmlspecialchars(trim($p['event_id'])));
        if ($event_id == '') {
            $feedback = 'Please provide the template name';
            return false;
        }
        $subject = addslashes(htmlspecialchars(trim($p['subject'])));
        if ($subject == '') {
            $feedback = 'Please provide subject';
            return false;
        }
        $body = addslashes(htmlspecialchars(trim($p['body'])));
        if ($body == '') {
            $feedback = 'Please enter mail body';
            return false;
        }
        $description = addslashes(htmlspecialchars(trim($p['description'])));

        $q = "SELECT COUNT(*) AS count FROM email_templates ".
             "WHERE event_id = '".$event_id."' AND template_id != '".$template_id."'";
        $rs = $conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = 'The template\'s name already registered, please choose another one';
            return false;
        }

        $conn->Execute("UPDATE email_templates ".
                       "SET foldername = '".$foldername."', ".
                           "event_id = '".$event_id."', ".
                           "templatename = '".$g_tag['email_event'][$event_id]."', ".
                           "subject = '".$subject."', ".
                           "description = '".$description."', ".
                           "body = '".$body."' ".
                       "WHERE template_id = '".$template_id."'");
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end setInfo()

    function del($template_id)
    {
        global $conn, $feedback;

        $template_id = addslashes(htmlspecialchars(trim($template_id)));
        if ($template_id == '') {
            $feedback = 'Please choose a mail template';
            return false;
        }

        $conn->Execute("DELETE FROM email_templates WHERE template_id = '".$template_id."'");
        //$conn->Execute("UPDATE email_templates SET deleted = '1' WHERE template_id = '".$template_id."'");
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end del()

    function getAll($mode = 'all_templates')
    {
        global $conn, $feedback;

        if ($mode = 'all_templates') {
            $q = "SELECT * FROM email_templates ORDER BY template_id ASC";
        } else {
            $q = "SELECT * FROM email_templates WHERE deleted = '0' ORDER BY template_id ASC";
        }
        $rs = $conn->Execute($q);

        if ($rs) {
            $arr = array();
            while (!$rs->EOF) {
                if ($id_name_only == true) {
                    $arr[$rs->fields['template_id']] = $rs->fields['templatename'];
                } else {
                    $arr[] = $rs->fields;
                }
                $rs->MoveNext();
            }
            $rs->Close();
            return $arr;
        }

        return null;
    }//end getAll()

    function getInfo($template_id)
    {
        global $conn, $feedback;

        $template_id = addslashes(htmlspecialchars(trim($template_id)));
        if ($template_id == '') {
            $feedback = 'Please choose a mail template';
            return false;
        }

        $q = "SELECT * FROM email_templates WHERE template_id = '".$template_id."'";
        return $conn->GetRow($q);
    }//end getInfo()

    function getInfoByEventId($event_id)
    {
        global $conn, $feedback;

        $event_id = addslashes(htmlspecialchars(trim($event_id)));
        if ($event_id == '') {
            $feedback = 'Please choose a mail template';
            return false;
        }

        $q = "SELECT * FROM email_templates WHERE event_id = '".$event_id."'";
        return $conn->GetRow($q);
    }//end getInfo()

    function sendNewKeywordMail($info, $tos = array())
    {
        if (empty($tos)) $tos = User::getAllUsers('username_email_only', 'admin');
        $hash = self::getInfoByEventId(24);
        $subject = email_replace_placeholders($hash['subject'], $info);
        $body = email_replace_placeholders($hash['body'], $info);
        $body = nl2br($body);
        if (is_array($tos)) {
            foreach ($tos as $k => $to) {
                if (!empty($to)) {
                    send_smtp_mail($to, $subject, $body);
                }
            }
        } else {
            send_smtp_mail($tos, $subject, $body);
        }
        return true;
    }

    function sendAnnouceMail($event_id, $to, $p = array(), $subject =null, $cc=array(), $bcc=array())
    {
        global $conn, $g_placeholders, $mailer_param, $feedback;
        $tpl = Email::getInfoByEventId($event_id);
        if (empty($subject)) {
            $subject = $tpl['subject'];
        }
        $body = $tpl['body'];
        if (!empty($p)) {
            $body = email_replace_placeholders($body, $p);
            $subject = email_replace_placeholders($subject, $p);
        }
        $body = nl2br($body);
        if (!empty($cc)) {
            $mailer_param['cc'] = $cc;
        } 
        if (!empty($bcc)) {
            $mailer_param['bcc'] = $bcc;
        }

        if (is_array($to)) {
            foreach ($to as $k => $email) {
                if (!empty($email)) {
                    send_smtp_mail($email, $subject, $body, $mailer_param);
                }
            }
        } else {
            send_smtp_mail($to, $subject, $body, $mailer_param);
        }
        return true;
    }

}//end class Email
?>