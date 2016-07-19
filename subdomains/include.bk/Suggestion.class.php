<?php
class Suggestion {

    function save($arr) 
    {
        global $conn, $feedback;

        $arr['created'] = date("Y-m-d H:i:s");
        if (client_is_loggedin()) {
            $arr['role'] = 'client';
            $arr['from'] = Client::getEmail();
            $arr['sender'] = Client::getName();
            $arr['created_by'] = Client::getID();
        } else if (user_is_loggedin()) {
            $arr['role'] = User::getRole();
            $arr['from'] = User::getEmail();
            $arr['sender'] = User::getName();
            $arr['created_by'] = User::getID();
        }
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes(htmlspecialchars(trim($value)));
        }
        extract($arr);
        if ($subject == '') {
            $feedback = "Please Specify the subject";
            return false;
        }
        if ($content == '') {
            $feedback = "Please Specify the content";
            return false;
        }
 
        $conn->StartTrans();
        if (empty($suggestion_id)) {
            $keys = array_keys($arr);
            $q = "INSERT INTO `suggestions` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        } else {
            unset($arr['suggestion_id']);
            $q = "UPDATE `suggestions` SET ";
            $sets = array();
            foreach ($arr as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $q .= implode(", ", $sets);
            $q .= "WHERE suggestion_id='{$suggestion_id}'";
        }
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        self::sent($arr);
        if ($ok) {
            $feedback = 'Successful!';
            return true;
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function sent($arr)
    {
        global $g_to_email;
        extract($arr);
        $mailbody  = "Sender:  ". $sender."\n";
        $mailbody .= "Role:  ". $role."\n";
        $mailbody .= "Date :  ". $created."\n";
        $mailbody .= "Email :  ". $from."\n";
        if (isset($campaign_id)) {
            $campaigns = Campaign::getAllCampaigns('id_name_only');
            $mailbody .= 'Campaign:' . $campaigns[$campaign_id] . "\n";
        }
        $mailbody .= "Subject :  ". $subject ."\n";
        $mailbody .= "Content :  ". $content ."\n";
        $mailbody = nl2br($mailbody);
        $subject =  'Contact Form Email: ' . $subject;
        $cc_email = array();
        if (client_is_loggedin()) {
            $client_id = Client::getID();
            $pm = Client::getPMInfo(array('client_id' => $client_id));
            $cc_email[] = $pm['email'];
            $agency_id = Client::getAgencyId();
            if ($agency_id > 0) {
                $agency = User::getInfo($agency_id);
                $cc_email[] = $agency['email'];
            }
            $cc_email[] = 'cptech@blueglass.com';
        }
        return sendMail($g_to_email, $mailbody, $subject, $from, $cc_email);
    }
}
?>
