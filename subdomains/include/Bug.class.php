<?php
class Bug {

    function save($arr) 
    {
        global $conn;
        $keys = array_keys($arr);
        foreach ($arr as $k => $value)  {
            $arr[$k] = addslashes($value);
        }
        $q = "INSERT INTO `bugs` (`" . implode('`,`', $keys)."`) VALUES ('" . implode("','", $arr). "')";
        return $conn->Execute($q);
    }

    function report($p, $report_to = "cptech@copypress.com")
    {
        global $feedback;
        $subject = $p['subject'];
        if (empty($subject)) {
            $feedback = 'Please input problem title';
            return false;
        }
        if (empty($p['happened'])) {
            $feedback = 'Please decribe what happened';
            return false;
        }
        if (empty($p['raw_happened'])) {
            $feedback = 'Please decribe what should have happened';
            return false;
        }
        if (empty($p['steps'])) {
            $feedback = 'Please decribe how can we reproduce the problem';
            return false;
        }
        if (empty($p['browser'])) {
            $feedback = 'Please specify your browser';
            return false;
        }
        if (empty($p['operating_system'])) {
            $feedback = 'Please specify your operating system';
            return false;
        }

        if (empty($p['campaign_id'])) unset($p['campaign_id']);
        if (client_is_loggedin()) {
            $p['reported_by'] = Client::getID();
            $p['user_role'] = 'client';
        } else {
            $p['reported_by'] = User::getID();
            $p['user_role'] = User::getRole();
        }
        $p['report_time'] = date("Y-m-d H:i:s");
        $report_to .= ';ekaufman@copypress.com';
        $p['report_to'] = $report_to;
        self::save($p);
        $mailbody = "<table>";
        $mailbody .= "<tr><td>Sender</td><td>" . (client_is_loggedin() ? Client::getName() : User::getName()) ."</td></tr>";
        $mailbody .= "<tr><td>Role</td><td>{$p['user_role']}</td></tr>";
        $mailbody .= "<tr><td>Date</td><td>" . date("Y-m-d"). "</td></tr>";
        $mailbody .= "<tr><td>Problem Title</td><td>{$subject}</td></tr>";
        if (!empty($p['campaign_name'])) $mailbody .= "<tr><td>Campaign</td><td>{$p['campaign_name']}</td></tr>";
        if (!empty($p['article_number'])) $mailbody .= "<tr><td>Article Number</td><td>{$p['article_number']}</td></tr>";
        $mailbody .= "<tr><td>What happened</td><td>{$p['happened']}</td></tr>";
        $mailbody .= "<tr><td>What should have happened</td><td>{$p['raw_happened']}</td></tr>";
        $mailbody .= "<tr><td>Steps to Reproduce</td><td>{$p['steps']}</td></tr>";
        $mailbody .= "<tr><td>Browser information</td><td>{$p['browser']}</td></tr>";
        $mailbody .= "<tr><td>Operating System</td><td>{$p['operating_system']}</td></tr>";
        $mailbody .= "</table>";
        $subject = "Bug Report Email: " . $subject;
        if (!empty($subject) && !empty($mailbody)) {
            $cc_email = null;
            if (client_is_loggedin()) {
                $from = Client::getEmail();
                $client_id = Client::getID();
                $pm = Client::getPMInfo(array('client_id' => $client_id));
                $cc_email = array($pm['email']);
                $agency_id = Client::getAgencyId();
                if ($agency_id > 0) {
                    $agency = User::getInfo($agency_id);
                    $cc_email[] = $agency['email'];
                }
            } else {
                $from = User::getEmail();
            }
            $mailto = $report_to;
            self::sendAnnouceMail($mailto, $mailbody, $cc_email, $from, $subject);
        }
        return true;
    }

    function sendAnnouceMail($mail_to, $mailbody, $cc_email, $from, $subject)
    {
        global $conn, $mailer_param;

        $feedback = "";
        if (!empty($from))
        {
            //$mailer_param['from']      = $from;
            $mailer_param['reply_to'] = $from;
        }
        if (!empty($cc_email)) $mailer_param['cc'] = $cc_email;
        $all_user = explode(";", $mail_to);
        if ($mailbody == '') {
            $feedback = "Please provide mail body";
            return false;
        }

        if (!empty($all_user)) {
            foreach ($all_user AS $ku => $vu) {
                $address = $vu;
                if( strlen( $vu ) )
                {
                    if (!send_smtp_mail($vu, $subject, $mailbody, $mailer_param)) 
                    {
                        $feedback .= $vu."";
                    }
                }
            }
        }

        if (trim($feedback) == '') {
            $feedback = "Send success";
        } else {
            $feedback = "Failuer£º".$feedback.". please try again.";
        }
    }
}
?>
