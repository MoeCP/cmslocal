<?php
//由于我母语为英语的人对于feedback的习惯是什么，所以先用中文代替，后面写上了相应该的英语表达。
//tony这一部分可能要你酌情修改一下
function valid_user_name($name)
{
    global $feedback;
    $len = strlen($name);
    if ($len < 3 || $len > 16) {
        $feedback = "Please input 3-16 characters in user name text";//请填写3至16位的用户名
        return false;
    }

    //if (strspn($name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
        //$feedback = "User name must at least contain one alphabet character";
        //return false;
    //}

    if (strspn($name, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_") != strlen($name)) {
        $feedback = "User name must use alphaber character,number,english letter or - and _";//请用英文字母、阿拉伯数字、“-”和“_”填写用户名
        return false;
    }

    return true;
}


function valid_pw($pw)
{
    global $feedback;

    $len = strlen($pw);
    if ($len < 6 || $len > 16) {
        $feedback = "Please input 6-16 characters in password text";//请填写6至16位的密码
        return false;
    }

	if (ereg(' ', $pw)) {
		$feedback = 'Forbid space in password';//密码不能含有空格，请重新填写
		return false; //不能含有空格
	}

	if (ereg("[^\x80-\xF7 [:alnum:]@_.-]", $pw)) {
		$feedback = 'Nonlicet characters,Please try again';//非法字符，请重新填写
		false; //Nonlicet characters
	}

	if (preg_match('/[\x{80}-\x{A0}'.          // Non-printable ISO-8859-1 + NBSP
					 '\x{AD}'.                 // Soft-hyphen
                     '\x{2000}-\x{200F}'.      // Various space characters
                     '\x{2028}-\x{202F}'.      // Bidirectional text overrides
                     '\x{205F}-\x{206F}'.      // Various text hinting characters
                     '\x{FEFF}'.               // Byte order mark
                     '\x{FF01}-\x{FF60}'.      // Full-width latin
                     '\x{FFF9}-\x{FFFD}]/u',   // Replacement characters
                     $pw)) {
            $feedback = 'Did NOT input some special characters in password,Please try again';//非法字符，请重新填写
            return false;
	}

    return true;
}


function valid_email($email)
{
    global $feedback;

    if (preg_match("|^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{\|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{\|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{\|}~]+$|", $email)) {
        return true;
    } else {
        $feedback = 'Invalid Email Address,Please type again';//Email地址填写有误，请重新填写
        return false;
    }
}

function valid_url($url)
{
    return preg_match('|^(http|https|ftp|ftps)://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function generateValidateUrl($url)
{
    if (!valid_url($url)) {
        $url = str_replace('http:://', 'http://', $url);
        $url = str_replace('http://www.http://', 'http://', $url);
        $arr = parse_url($url); 
        if (!isset($arr['scheme'])) {
            $arr  = parse_url('http://' . $url);
            $host = $arr['host'];
            $arr = explode('.', $host);
            if (count($arr) == 2) {
                $url = 'http://www.' . $url;
            } else {
                $url = 'http://' . $url;
            }
        }
    }
    return $url;
}

function gmail_sent($address, $subject, $body, $mailer_param = array())
{
    global $feedback;
    if (empty($body) && empty($subject)) {
        return false;
    }
    if (empty($mailer_param)) {
        global $mailer_param;
    }
    require_once MAILER_INC_ROOT.'/class.phpmailer.php';
    $mail = new PHPMailer();
    $mail->CharSet = "iso-8859-1";
    $mail->IsSMTP();
    $mail->Host     = $mailer_param['smtp_host'];
    $mail->SMTPAuth = $mailer_param['smtp_auth'];
    $mail->Username = $mailer_param['smtp_username'];
    $mail->Password = $mailer_param['smtp_password'];
    $mail->From     = $mailer_param['from'];
    $mail->Sender     = $mailer_param['sender'];
    $mail->FromName = $mailer_param['from_name'];
    $mail->AddReplyTo($mailer_param['reply_to']);
    $mail->SMTPSecure = $mailer_param['smtp_secure'];
    $mail->Port  = $mailer_param['smtp_port'];
    //$mail->SMTPDebug = 2;
    $mail->IsHTML(true);
    // modified by snug xu 2007-05-04 11:43 - STARTED
    if (is_string($address))
    {
        $mail->AddAddress($address);
    }
    else if (is_array($address))
    {
        foreach($address as $k => $val)
        {
            if (!empty($val)) {
                $mail->AddAddress($val);
            }
        }
    }
    // modified by snug xu 2007-05-04 11:43 - FINISHED
	// modified by snug xu 2007-05-10 22:53 - STARTED
    if (is_string($mailer_param['cc']) && strlen($mailer_param['cc']))
    {
        $mail->AddCC($mailer_param['cc']);
    }
    else if (is_array($mailer_param['cc']))
    {
        foreach($mailer_param['cc'] as $k => $val)
        {
            $mail->AddCC($val);
        }
    }
    if (is_string($mailer_param['bcc']) && strlen($mailer_param['bcc']))
    {
        $mail->AddBCC($mailer_param['bcc']);
    }
    else if (is_array($mailer_param['bcc']))
    {
        foreach($mailer_param['bcc'] as $k => $val)
        {
            $mail->AddBCC($val);
        }
    }
    // $mail->AddBCC("xusnug14@gmail.com");
    if (isset($mailer_param['attachment']) && !empty($mailer_param['attachment'])) {
        $attachs = $mailer_param['attachment'];
        if (isset($attachs['filename'])) {
            $mail->AddAttachment($attachs['file'], $attachs['filename']);
        } else {
            foreach ($attachs as $row) {
                $mail->AddAttachment($row['file'], $row['filename']);
            }
        }
    }
	// modified by snug xu 2007-05-10 22:53 - FINISHED
    $mail->Subject = $subject;
    $mail->Body = $body;
    $result = $mail->Send();
    global $feedback;
    $feedback  = $mail->ErrorInfo;
    return  $result;

}

function set_eAddress($address, $obj, $method = 'addTo')
{
    if (is_string($address)) {
        $addresses = array($address);
    } else if (is_array($address)) {
        $addresses = $address;
    }
    if (!empty($addresses)) {
        foreach ($addresses as $k => $v) {
            $obj->$method($v);
        }
    }
    return $obj;
}
function ses_sent($address, $subject, $body, $mailer_param = array())
{
    global $feedback;
    if (empty($body) && empty($subject)) {
        return false;
    }
    if (empty($mailer_param)) {
        global $mailer_param;
    }
    
    require_once 'sdk.class.php';
    
    require_once 'Mail/mime.php';
    
    $ses = new AmazonSES();
    $m = new Mail_mime("\n");
    $m->setParam('html_charset', 'UTF-8');
    $m->setParam('text_charset', 'UTF-8');
    $m->setParam('head_charset', 'UTF-8');
    $m->setHTMLBody($body);
    $m->setSubject(html_entity_decode($subject));
    $sender = $mailer_param['smtp_username'];
    $m->setFrom($mailer_param['from_name'] . '<' . $sender . '>');
    $m = set_eAddress($address, $m);
    $cc = $mailer_param['cc'];
    $m = set_eAddress( $cc, $m, 'addCc');
    $bcc = $mailer_param['bcc'];
    $m = set_eAddress($bcc, $m, 'addBcc');
    if (isset($mailer_param['attachment']) && !empty($mailer_param['attachment'])) {
        $attachs = $mailer_param['attachment'];
        require_once 'MIME/Type.php';
        if (isset($attachs['filename'])) {
            $attachs = array($attachs);
        }
        foreach ($attachs as $row) {
            $type = MIME_Type::autoDetect($row['file']);
            $m->addAttachment($row['file'], $type, $row['filename']);
        }
    }
    if ($mailer_param['reply_to'] != $mailer_param['from']) {
        $mailer_param['reply_to'] = $mailer_param['from'];
    }
    $headers = $m->txtHeaders(array('Reply-To' => $mailer_param['reply_to']));
    $body = $m->get();
    $message = $headers . "\r\n" . $body;

    if (!is_array($address)) $address = array($address);
    if (!empty($cc)) {
        if (!is_array($cc)) {
            $cc  = array($cc);
        } 
        $address = array_merge($address, $cc);
    }
    if (!empty($bcc)) {
        if (!is_array($bcc)) {
            $bcc  = array($bcc);
        } 
        $address = array_merge($address, $bcc);
    }
    $opt = array(
        'Destinations' => $address,
        'Source' => $sender,
    );

    $result = $ses->send_raw_email(array('Data' => base64_encode($message)), $opt);
    if ($result->status == '200') {
        $result = true;
    } else {
        //pr($result);
        $error = (array) $result->body->Error;
        $feedback = $error['Message'];
        $result = false;
    }
    return $result;
}


function send_smtp_mail($address, $subject, $body, $mailer_param = array())
{
    global $g_mailer_server, $feedback;
    $feedback = '';
    if (isset($mailer_param["reply_to"]) && $mailer_param["reply_to"] == 'no-reply@copypress.com') {
        $body = $body . '\n\n\n\n* Note: This email address is not monitored.  If you need to get in touch with someone at CopyPress, please email <a href="mailto:community@copypress.com">community@copypress.com<a>.';
    }
    if ($g_mailer_server == 'gmail') {
        $result = gmail_sent($address, $subject, $body, $mailer_param);
    } else { // amazon  simple email service
        $result = ses_sent($address, $subject, $body, $mailer_param);
    }
    require_once CMS_INC_ROOT  . '/track_email.class.php';
    TrackEmail::storeEmailFromSystem($address, $subject, $body, $mailer_param, $mail);
    return empty($feedback) ?  true : false;
}//end send_smtp_mail()

function debug($info, $stop = false){
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    if ($stop) die;
}
function pr($info, $stop = false){
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    if ($stop) die;
}

//added by leo 12/18/2014
function genPayMonthList($start_time=null, $nof_months=5) {
    global $g_pay_per_month;
    $mlst = array();
    $months = array(1,2,3,4,5,6,7,8,9,10,11,12);

    if (empty($start_time)) $start_time=time();
    $curryear = date("Y");
    $currmonth = date("n");

    if (is_numeric($start_time) && abs($start_time) > 12 && strlen($start_time) < 10) {
        $start_time = time();
    }
    if (is_numeric($start_time) && abs($start_time) <= 12) {
        $targetmonth = $start_time;
        if ($targetmonth >= 0) {
            $targetyear = $curryear + (($targetmonth+$currmonth>12) ? 1 : 0);
        } else {
            $targetyear = $curryear + (($targetmonth+$currmonth<=0) ? -1 : 0);
        }

        $modmonth = ($currmonth + $targetmonth-1) % 12;
        $mp1 = array_slice($months, 0, $modmonth);
        $mp2 = array_slice($months, $modmonth);
    } else {
        $targetyear = date("Y", $start_time);
        $targetmonth = date("n", $start_time);

        if (empty($start_time)) $start_time=time();
        $daydiff = ($start_time - time()) / 86400;
        $daydiff = round($daydiff);
        $monthdiffplus = round($daydiff/31);
        $monthdiff = $targetmonth - $currmonth;//the same as date("m");
        $yeardiff = $targetyear - $curryear;

        if ($monthdiffplus!=$monthdiff) {
            if (($monthdiffplus < 0 && $monthdiff < 0 && $monthdiffplus<$monthdiff)
                || ($daydiff > 0 && $yeardiff==0) 
                || ($daydiff<0 && $yeardiff<0 && $monthdiffplus+12 != $monthdiff) ) {
                $start_time = strtotime("-6 days", $start_time);
            }
        }

        $targetyear = date("Y", $start_time);
        $targetmonth = date("n", $start_time);
        $mp1 = array_slice($months, 0, $targetmonth-1);
        $mp2 = array_slice($months, $targetmonth-1);
    }

    $rstmonth = array_merge($mp2,$mp1);
    $_y = $targetyear;
    //print_r($rstmonth);

    for($i=0; $i<$nof_months; $i++) {
        $_m = $rstmonth[$i];
        (string)$_m = ($_m>=10) ? $_m : "0".$_m;
        //$_m = str_pad($_m, 2, "0", STR_PAD_LEFT);
        $mlt = $_y.$_m;
        $mltlab = $_y."-".$_m;
        if ($g_pay_per_month > 1) {
            for ($j=1; $j<=$g_pay_per_month; $j++) {
                $mlst[$mlt."$j"] = $mltlab."({$j})";
            }
        } else {
            $mlst[$mlt] = $mltlab;
        }
        
        if ($_m == 12) {
            $_y++;
        }
    }

    return $mlst;
}


/**
 * Created time: 2006-10-17 14:42
 * @author snug xu <xuxianuan@gmail.com>
 * @param string $current_month:200608
 * @return timestamp
 */
 function changeTimeFormatToTimestamp($current_month)
 {
     if (empty($current_month)) {
         return time();
     }
	 $year		= substr($current_month, 0, 4);
	 $month	= substr($current_month, 4, 2);
     $time = substr($current_month, -1);
     $pay_per_month = getPayPerMonth($current_month);
     $interval_date = floor(31/$pay_per_month);
     if ($time > 0) {
         $day = ($time-1) * $interval_date + 1;
         if (strlen($day) == 1) $day = '0' . $day;
     } else {
         $day = '01';
     }
	 $now = strtotime("$year-$month-$day");
	 return $now;
 }

 function changeTimeToPayMonthFormat($time = null)
 {
     if (empty($time)) $time = time();
    $date = date("j", $time);
    $ym = date("Ym", $time) ;
    $pay_per_month = getPayPerMonth($ym. '1');
    $interval = 31/$pay_per_month;
    $current_month = $ym . ceil($date/$interval);
    return $current_month;
 }

 function getForecastDates($current_month)
 {
	 $year		= substr($current_month, 0, 4);
	 $month	= substr($current_month, 4, 2);
     $time = substr($current_month, -1);
     $pay_per_month = getPayPerMonth($current_month);
     $interval_date = floor(31/$pay_per_month);
     if ($time > 0) {
         $day = ($time-1) * $interval_date + 1;
         if (strlen($day) == 1) $day = '0' . $day;
         $end_day = $time * $interval_date + 1;
     } else {
         $day = '01';
         $end_day = '31';
     }
     $start_date = $year . '-' . $month . '-' . $day;
     if ($end_day >= '30') {
         $end_day = '01';
         $month++;
         if ($month > 12) {
             $year++;
             $month = $month%12;
             if (strlen($month) == 1) $month = '0' . $day;
         }
     }
     $end_date = $year . '-' . $month . '-' . $end_day;
	 return compact('start_date', 'end_date');
 }


 function getCostAndPayType($fields, $prefix = '')
 {
    if (isset($fields['ach_' . $prefix .'type_cost']) && $fields['ach_' . $prefix .'type_cost'] > 0) {
        $cost_per_unit = $fields['ach_' . $prefix . 'type_cost'];
        $checked = $fields['ach_' . $prefix .'checked'];
    } else {
        if (strlen($fields['ac_checked']) == 0 ) {
            $checked = $fields['at_checked'];
            $cost_per_unit = ($checked == 1) ? $fields['at_' . $prefix .'article_cost'] : $fields['at_' . $prefix .'word_cost'];
        } else {
            $checked = $fields['at_checked'];
            $cost_per_unit = ($checked == 1) ? $fields['ac_' . $prefix .'article_cost'] : $fields['ac_' . $prefix .'word_cost'];
        }
    }

    return compact('checked', 'cost_per_unit');
}

function getCostFields()
{
    $permission = User::getPermission();
    
    if ($permission == 1) {
        $cost_field = 'ac.cp_article_cost AS ac_article_cost, at.cp_article_cost AS at_article_cost, ac.cp_cost AS ac_word_cost, at.cp_cost AS at_word_cost,';
    } else if ($permission == 3) {
        $cost_field = 'ac.editor_article_cost AS ac_article_cost, at.editor_article_cost AS at_article_cost, ac.editor_cost AS ac_word_cost, at.editor_cost AS at_word_cost,';
    } else {
        $cost_field = "IF(apl.role = 'editor', ac.editor_article_cost, ac.cp_article_cost ) AS ac_article_cost, IF(apl.role = 'editor', ac.editor_cost , ac.cp_cost) AS ac_word_cost, \n";
        $cost_field .= "IF(apl.role = 'editor', at.editor_article_cost, at.cp_article_cost) AS at_article_cost, IF(apl.role = 'editor', at.editor_cost , at.cp_cost) AS at_word_cost, \n ";
    }
    return $cost_field;
}

 function showMonth($month)
 {
    $pay_per_month = getPayPerMonth($month);
    if ($pay_per_month > 1) {
        $month = preg_replace("/(\d{4})(\d{2})(\d{1})/i", "\$1-\$2($3)",  $month);
    } else {
        $month = preg_replace("/(\d{4})(\d{2})(\d{1})/i", "\$1-\$2",  $month);
    }
    return $month;
 }

 function lastPayMonth($target_month, $target_time, $pay_per_month = null)
 {
     if (empty($pay_per_month)) $pay_per_month = getPayPerMonth($target_month);
     if ($pay_per_month > 1) {
         $c_time = substr($target_month, -1);
        if ($c_time <= $pay_per_month && $c_time > 1) {
            $last_target_month = substr($target_month, 0, 6) . ($c_time - 1);
        } else {
            $last_target_month =  date("Ym", strtotime( "-1 month", $target_time)) . '1';
        }
     } else {
         $last_target_month   = changeTimeToPayMonthFormat(strtotime( "-1 month", $target_time));
     }
     return $last_target_month;
 }

 function nextPayMonth($target_month, $target_time = null, $pay_per_month = null)
 {
     if (empty($target_time)) $target_time = changeTimeFormatToTimestamp($target_month);
     if (empty($pay_per_month)) $pay_per_month = getPayPerMonth($target_month);
     if ($pay_per_month > 1) {
        $c_time = substr($target_month, -1);
        if ($c_time > 0 && $c_time < $pay_per_month) {
            $next_target_month = substr($target_month, 0, 6) . ($c_time + 1);
        } else {
            $next_target_month =  date("Ym", strtotime( "+1 month", $target_time)) . '1';
        }
     } else {
        $next_target_month = changeTimeToPayMonthFormat(strtotime( "+1 month", $target_time));
     }
     return $next_target_month;
 }

 function nextPayTime($target_month, $target_time = null, $pay_per_month = null)
 {
     $next_target_month = nextPayMonth($target_month, $target_time, $pay_per_month);
     return changeTimeFormatToTimestamp($next_target_month);
 }

 function getDelayTime()
 {
     global $g_delay_days;
     return time() - $g_delay_days *86400;
 }

/**
 * Created time: 2006-10-26 13:48
 * @author snug xu <xuxianuan@gmail.com>
 * @param timestamp
 * @return array: 
 *     array('month_end' => $month_end, 'month_start' => $month_start)
 */
 function getMonthEndAndMonthStart($time)
 {
	 $time_array['month_start'] = date("Y-m-d", $time) . " 00:00:00";
	 $time_array['month_end'] = date("Y-m-d", strtotime( "+1 month", $time)) . " 00:00:00";
	 return $time_array;
 }

/**
 * added by Snug 15:39 2006-09-03
 * add first month and last month to current monthes
 * @param array: $monthes
 * @return array: $frist_month + $monthes + $last_month
 **/
function addFirstMonthAndLastMonthToCurrentMonthes( $monthes)
{
    global $g_pay_per_month, $g_payment_settings;
    if (empty($g_payment_settings)) {
        require_once CMS_INC_ROOT . '/PaymentSetting.class.php';
        $g_payment_settings = PaymentSetting::getAll();
    }
	$strlen = count( $monthes );
	if ($strlen > 0)  {	
        $keys = array_keys($monthes);
        $latest_month = $keys[$strlen-1];
        $year = substr($latest_month, 0, 4);
        $month = substr($latest_month, 4, 2);
        $timestamp = strtotime($year . '-' . $month . '-01');
	} else {
        $month = date("m");
        $year = date("Y");
        $timestamp = mktime(0, 0, 0, $month-2, 1 , $year);
        $month = date("m", $timestamp);
        $year = date("Y", $timestamp);
	}
   $this_month = date("Ym01");
   $date = $year . $month . '01';
   while($date <= $this_month) {
        $index = $year . $month . '1';
        $monthes += getMonthLabels($timestamp);
        $timestamp = mktime(0, 0, 0, $month+1, 1 , $year);
        $month = date("m", $timestamp);
        $year = date("Y", $timestamp);
        $date = $year . $month . '01';
   }
    // $monthes += getMonthLabels(getDelayTime());
     asort($monthes);
	return $monthes;
}//End addFirstMonthAndLastMonthToCurrentMonthes

function getMonthLabels($timestamp)
{
    $monthes = array();
    $month = date("m", $timestamp);
    $year = date("Y", $timestamp);
    $index = $year . $month . '1';
    $pay_per_month = getPayPerMonth($index);
    for ($i=1;$i<=$pay_per_month;$i++) {
        if ($pay_per_month > 1) {
            $monthes[$year . $month . $i] = $year .'-' . $month . '(' . $i . ')';
        } else {
            $monthes[$year . $month . '1'] = $year .'-' . $month;
        }
    }
    return $monthes;
}

function getPayPerMonth($month)
{
    global $g_pay_per_month, $g_payment_settings;

    $pay_per_month = $g_pay_per_month;
    foreach ($g_payment_settings as $row) {
        if ($row['start_month'] < $month && $row['end_month'] >= $month) {
            $pay_per_month = $row['pay_per_month'];
            break;
        }
    }
    return $pay_per_month;
}

if ( !function_exists('htmlspecialchars_decode') )
{
   function htmlspecialchars_decode($text, $option='')
   {
       return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $option)));
   }
}


function change_special_quot($richtext)
{
    $richtext = preg_replace("/&((amp|#0?38);)((gt|lt);)/i", "&amp;$1$3", $richtext);
    $search = array ("'&((amp|#0?38);)?(ldquo|rdquo|laquo|raquo);'i",
                 "'&((amp|#0?38);)?(ndash);'i",
                 "'&((amp|#0?38);)?(reg);'i",
                 "'&((amp|#0?38);)?(hellip);'i",
                 "'&((amp|#0?38);)?(nbsp);'i",
                 "'&((amp|#0?38);)?(rsquo|lsquo|#0?39);'i");  
    $replace = array('"',
                  "-",
                  "®",
                  "...",
                  " ",
                  "'");
    $richtext = preg_replace($search, $replace, $richtext);
    return $richtext;
}

function change_richtxt_to_paintxt($richtext, $option=ENT_QUOTES)
{
    global $conn;
    $richtext = change2EQuote($richtext);
    $richtext = change_special_quot($richtext);
    // added function that dispose latin1 letter 2012-08-22 12:23
    $richtext = process_latin($richtext);
    // end
    //pr($richtext . "\n");
    //$temp_text = html_entity_decode($richtext, $option, 'UTF-8');
    $temp_text = html_entity_decode($richtext, $option, 'UTF-8');
    //pr($temp_text . "\n");
    $nl = "\r\n";
    $temp_text = str_ireplace(array("<br>", "<br/>", "<br />", "</p>", "&nbsp;", "</span>", "</div>"), array($nl . "<br>", $nl . "<br/>", $nl . "<br />", "</p>" . $nl . $nl, " ", "</span>" . $nl, "</div>" . $nl), $temp_text);
    
    $filter = new InputFilter;
    /*
    $paintext = $filter->process($temp_text);
    $paintext = $filter->safeSQL($paintext, $conn);
    */
    $paintext = $filter->process($temp_text);

    $paintext = preg_replace("/&(amp|#0?38);(gt|lt);/i", "&$2;", $paintext);
    $search = array ("&(gt);'i",
                 "'&(lt);'i");  
    $replace = array('<',
                  ">");
    $paintext = preg_replace("/&(amp|#0?38);(gt|lt);/i", "&$2;", $paintext);
    $paintext = stripslashes($paintext);
    if (!get_magic_quotes_gpc()) {
        $paintext = addslashes($paintext);
    }
    //$paintext = mb_convert_encoding($paintext, "UTF-8", 'ISO-8859-1');
    //pr($paintext . "\n");
    return $paintext;
}

function process_latin($value, $type = 'decode')
{
    $html_entities = array (
        "&" =>  "&amp;",     #ampersand  
        "á" =>  "&aacute;",     #latin small letter a
        "Â" =>  "&Acirc;",     #latin capital letter A
        "â" =>  "&acirc;",     #latin small letter a
        "Æ" =>  "&AElig;",     #latin capital letter AE
        "æ" =>  "&aelig;",     #latin small letter ae
        "À" =>  "&Agrave;",     #latin capital letter A
        "à" =>  "&agrave;",     #latin small letter a
        "Å" =>  "&Aring;",     #latin capital letter A
        "å" =>  "&aring;",     #latin small letter a
        "Ã" =>  "&Atilde;",     #latin capital letter A
        "ã" =>  "&atilde;",     #latin small letter a
        "Ä" =>  "&Auml;",     #latin capital letter A
        "ä" =>  "&auml;",     #latin small letter a
        "Ç" =>  "&Ccedil;",     #latin capital letter C
        "ç" =>  "&ccedil;",     #latin small letter c
        "É" =>  "&Eacute;",     #latin capital letter E
        "é" =>  "&eacute;",     #latin small letter e
        "Ê" =>  "&Ecirc;",     #latin capital letter E
        "ê" =>  "&ecirc;",     #latin small letter e
        "È" =>  "&Egrave;",     #latin capital letter E
/*... sorry cutting because limitation of php.net ...
... but the principle is it ;) ... */
        "û" =>  "&ucirc;",     #latin small letter u
        "Ù" =>  "&Ugrave;",     #latin capital letter U
        "ù" =>  "&ugrave;",     #latin small letter u
        "Ü" =>  "&Uuml;",     #latin capital letter U
        "ü" =>  "&uuml;",     #latin small letter u
        "Ý" =>  "&Yacute;",     #latin capital letter Y
        "ý" =>  "&yacute;",     #latin small letter y
        "ÿ" =>  "&yuml;",     #latin small letter y
        "Ÿ" =>  "&Yuml;",     #latin capital letter Y
    );
    $codes = array_keys($html_entities);
    if ($type == 'decode') {
        $value =  str_replace($html_entities, $codes, $value);
        $value = str_replace('&mdash;','—', $value);
    } else {
        $value =  str_replace($codes, $html_entities, $value);
        $value = str_replace('—','&mdash;', $value);
    }
    return $value;
}

function getArticleDoc($article_info)
{
    $doc = '<html xmlns:v="urn:schemas-microsoft-com:vml" ' . "\n";
    $doc .= 'xmlns:o="urn:schemas-microsoft-com:office:office" ' . "\n";
    $doc .= 'xmlns:w="urn:schemas-microsoft-com:office:word" ' . "\n";
    $doc .= 'xmlns:st1="urn:schemas-microsoft-com:office:smarttags" ' . "\n";
    $doc .= 'xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
    $doc .= '<head>';
    $doc .= '<meta http-equiv=Content-Type content="text/html; charset=utf-8">';
    $doc .= '<meta name=ProgId content=Word.Document>';
    $doc .= '<meta name=Generator content="Microsoft Word 11">';
    $doc .= '<meta name=Originator content="Microsoft Word 11">';
    $doc .= '</head>';

    $doc .= "<body><div><span><strong>Article Title:</strong> </span><span>" . $article_info['title'] . "</span></div><br />";
    $doc .= "<div><span><strong>Keyword: </strong></span><span>" . $article_info['keyword'] . "</span></div><br />";
    $article_status = $article_info['article_status'];
    $cp_updated = ($article_status == '0' || $article_status== '') ? 'n/a' : date("m-d-Y", strtotime($article_info['cp_updated']));
    $doc .= "<div><span><strong>Submit Date: </strong></span><span>" . $cp_updated . "</span></div><br />";
    if (!empty($article_info['mapping_id'])) {
        $doc .= "<div><span><strong>Mapping-ID: </strong></span><span>" . $article_info['mapping_id'] . "</span></div><br />";
    }
    // added by nancy xu 2012-08-02 18:48
    require_once CMS_INC_ROOT.'/custom_field.class.php';
    $opt_fields =  CustomField::getFieldLabels($article_info['client_id'], 'optional');
    foreach ($opt_fields as $kk => $vv) {
        if (!empty($article_info[$kk])) {
            $doc .= "<div><span><strong>" .$vv['label']. ": </strong></span><span>" . $article_info[$kk] . "</span></div><br />";
        }
    } // end
    /*if (!empty($article_info['optional1'])) {
        $doc .= "<div><span><strong>Optional Field 1: </strong></span><span>" . $article_info['optional1'] . "</span></div><br />";
    }
    if (!empty($article_info['optional2'])) {
        $doc .= "<div><span><strong>Optional Field 2: </strong></span><span>" . $article_info['optional2'] . "</span></div><br />";
    }
    if (!empty($article_info['optional3'])) {
        $doc .= "<div><span><strong>Optional Field 3: </strong></span><span>" . $article_info['optional3'] . "</span></div><br />";
    }
    if (!empty($article_info['optional4'])) {
        $doc .= "<div><span><strong>Optional Field 4: </strong></span><span>" . $article_info['optional4'] . "</span></div><br />";
    }*/
//    if (!empty($article_info['author'])) {
//        $doc .= "<div><span><strong>Copywriter: </strong></span><span>" . $article_info['author'] . "</span></div><br />";
//    }
    $doc .= "<div><span><strong>Article Content: </strong></span><br /><span>". $article_info['richtext_body'] . "</span></div></body>\n</html>";
   return $doc;
}

function getXMLDATA($p = array(), &$filename = null, &$campaign_info = null, $is_remote = false) 
{  
    $article_id   = $p['article_id'];
    $campaign_id  = $p['cid'];
    $timestamp = $p['timestamp'];
    $article_ids  = $p['article_ids'];
    $title        = $p['title'];
    $ht           = $p['ht'];
    $mk           = $p['mk'];
    $md           = $p['md'];
    $body         = $p['body'];
    $author       = $p['author'];    
    $is_rich      = $p['is_rich'];
    $text_body = $p['text_body'];
    $rich_body = $p['rich_body'];
    $username     = trim($p['u']);
    $password     = trim($p['p']);
    $url_part     = trim($p['url_part']);
    $topic        = trim($p['topic']);
    $info =  $p['result'];
    $mid = $p['mid'];
    $optional1 = $p['optional1'];
    $optional2 = $p['optional2'];
    $optional3 = $p['optional3'];
    $optional4 = $p['optional4'];
    $optional5 = $p['optional5'];
    $optional6 = $p['optional6'];
    $optional7 = $p['optional7'];
    $optional8 = $p['optional8'];
    $optional9 = $p['optional9'];
    $optional10 = $p['optional10'];
    if (is_array($campaign_id)) {
        $campaign_id = implode(",", $campaign_id);
    }
    if (strlen($username) && strlen($password)) {
       switch($url_part) {
       	case 'user':
            $sess = User::getLogin($username, $password);
            if ($sess) {
            	User::setLogin($sess);
            }
            break;
        case 'client':
            $sess = Client::getLogin($username, $password);
            if ($sess) {
            	Client::setLogin($sess);
            }
        	break;
       }
    	if ($sess === false) {
            echo "<script>alert('Incorrect username password combination!');window.close();</script>";
            return false;
        } else if ($campaign_id > 0 && $title == '' && $mk == '' && $mk == '' && $body == '') {
            echo "<script>alert('Incorrect URL format!');window.close();</script>";
        	return false;
        }
    }
	require_once CMS_INC_ROOT.'/Client.class.php';
    require_once CMS_INC_ROOT.'/Article.class.php';
    require_once CMS_INC_ROOT.'/Campaign.class.php';
    //$info = array();
    // get campaign information by keyword id
    $all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
    if (!$is_remote) {
        if (strlen($article_ids) || $article_id >= 0) {
            if ($article_id) {
                $article_info = Article::getInfo($article_id, false);
                $info[$article_id] = $article_info;
                $filename = $article_info['title'];
            } else if (strlen($article_ids) && empty($info)) {
                $article_ids = trim($article_ids, ';');
                $aids = explode(";", $article_ids);
                // $article_info = Article::getInfo($aids[0], false);
                $p['article_id'] = $aids;
                $info = Article::getCheckedArticle($p);
            }
            if ($campaign_id <= 0) {
                $campaign_id = $article_info['campaign_id'];
            }
        }
        if ($campaign_id >= 0 && count($info) == 0) {
            if (client_is_loggedin()) {
                global $client_downloaded_statuses;
                $p['article_status'] = $client_downloaded_statuses;
            }
            $info = Article::downloadArticleByCampaignID($campaign_id, $p);
        }
    }


   
    $campaign_info = Campaign::getInfo($campaign_id);
    
    if ($url_part == 'client' ) {
        
        if ($campaign_info['client_id'] != Client::getID()) {
            echo "<script>alert('You have no privilege to download this campaign article');window.close();</script>";
            return false;
        }
        if ($is_remote) {
            if (empty($timestamp)) {
                $timestamp = $campaign_info['timestamp'];
            } else {
                $timestamp = date("Y-m-d 00:00:00", strtotime($timestamp));
            }
            //$timestamp = '2011-06-20 00:00:00';
            $info = Article::downloadClientArticleByCampaignID($campaign_id, $timestamp);
            $campaign_info['newTimestamp'] = date("Y-m-d H:i:s");
            Campaign::setCampaignFieldByID('timestamp', $campaign_info['newTimestamp'], $campaign_id);
        }
    }
    if (count($info) == 0 && count($campaign_info) == 0) {
        echo "<script>alert('There is no article');window.close();</script>";
        return false;
    }
    // generate xml info
    if (count($info) && count($campaign_info)) {
        if (!$is_remote) {
            $xml  = '<?xml version="1.0" encoding="utf-8"?>';
        } else {
            $xml = '<numArticles>' . count($info) . '</numArticles>';
        }
        $xml .= '<campaign>';
        foreach($info as $key => $val) {
            
            $ar_arr['article_id'][$val['article_id']] = $val['article_id'];
            $xml .= '<article>';
            if ($title == 1) {
                $xml .= "<title><![CDATA[{$val['title']}]]></title>";
            }
            $article_status = $val['article_status'];
            $cp_updated = ($article_status == '0' || $article_status== '') ? 'n/a' : date("m-d-Y", strtotime($val['cp_updated']));
            $xml .= "<submitDate>" . $cp_updated . "</submitDate>";
            $mapping_id = $val['mapping_id'] ;
            if ($mid == 1 && !empty($mapping_id)) {              
                $xml .= "<mappingID><![CDATA[{$mapping_id}]]></mappingID>";
            }
            // addded by nancy xu 2012-08-02
            foreach ($val as $kk => $vv) {
                if (${$kk} == 1 && substr($kk, 0,8) == 'optional') {
                    $num = str_replace( 'optional', '', $kk);
                    $xml .= "<optionalField" . $num ."><![CDATA[{$vv}]]></optionalField" .$num . ">";
                }
            }// end
            /*$optional_field = $val['optional1'];
            if ($optional1 == 1) {              
                $xml .= "<optionalField1><![CDATA[{$optional_field}]]></optionalField1>";
            }

            $optional_field = $val['optional2'];
            if ($optional2 == 1) {              
                $xml .= "<optionalField2><![CDATA[{$optional_field}]]></optionalField2>";
            }

            $optional_field = $val['optional3'];
            if ($optional3 == 1 ) {              
                $xml .= "<optionalField3><![CDATA[{$optional_field}]]></optionalField3>";
            }
            $optional_field = $val['optional4'];
            if ($optional4 == 1) {              
                $xml .= "<optionalField4><![CDATA[{$optional_field}]]></optionalField4>";
            }*/
            if ($ht == 1) {
                $html_title = empty($val['html_title']) ? $val['keyword'] : $val['html_title'] ;
                $xml .= "<htmlTitle><![CDATA[{$html_title}]]></htmlTitle>";
            }
            if ($topic == 1) {
                $topic = empty($val['topic']) ? $val['title'] : $val['topic'] ;
                $xml .= "<topic><![CDATA[{$topic}]]></topic>";
            }
            if ($author == 1) {
                if (empty($val['author'])) {
                    $val['author'] = $all_copy_writer[$val['copy_writer_id']];
                }
                $xml .= "<author><![CDATA[{$val['author']}]]></author>";
            }
            if ($mk == 1) {
                $keyword_meta = '';
                if ($campaign_info['meta_param'] == 1)
                {
                    if (!empty($val['keyword_meta']))
                    {
                        $keyword_meta = $val['keyword_meta'];
                    }
                }

                if (empty($keyword_meta))
                {
                    $keyword_meta = $val['keyword'];
                }
                $keyword_meta = htmlspecialchars_decode($keyword_meta);
                $xml .= "<metaKeyword><![CDATA[{$keyword_meta}]]></metaKeyword>";

            }
            if ($md == 1) {
                $description_meta = '';
                if ($campaign_info['meta_param'] == 1)
                {
                    if (!empty($val['description_meta']))
                    {
                        $description_meta = $val['description_meta'];
                    }
                }

                if (empty($description_meta))
                {
                    $body_arr = explode(".", $val['body']);
                    $i = 0;
                    while($i < count($body_arr)) {
                        if ($body_arr[$i] != '') {
                            $body_arr[0] = $body_arr[$i];
                            break;
                        } else {
                            $i++;
                        }
                    }
                    $description_meta = $body_arr[0];
                }
                $description_meta = htmlspecialchars_decode($description_meta);
                $xml .= "<metaDescription><![CDATA[{$description_meta}]]></metaDescription>";
            }
            if ($body == 1 || $text_body == 1 || $rich_body == 1) {
                if(empty($is_rich))
                    $is_rich = 0;
                if($is_rich == 0 || $is_rich == 2) {
                    $content = $val['body'];
                    if (empty($content)) {
                        $content = stripslashes(change_richtxt_to_paintxt($val['richtext_body'], ENT_QUOTES));
                    }
                    if (empty($rich_body) && $text_body || $body == 1) {
                        $xml .= "<textBody><![CDATA[" . $content . "]]></textBody>";
                    } else if ($rich_body && $text_body) {
                        $xml .= "<textBody><![CDATA[" . $content . "]]></textBody>";
                    }
                }
                if($is_rich == 1 || $is_rich == 2 || $rich_body == 1) {
                    $rich_content = htmlspecialchars_decode($val['richtext_body']);//
                    // added by snug xu 2007-04-27 17:55 - STARTED
                    // get frist paragraph from rich content as intro
                    $tmp = preg_split("|\r\n|ims", $val['body'], -1, PREG_SPLIT_NO_EMPTY);
                    $intro = $tmp[0];
                    // added by snug xu 2007-04-27 17:55 - FINISHED
                    if (empty($text_body) && $rich_text) {
                        $xml .= "<richBody><![CDATA[" . $rich_content . "]]></richBody>";
                    } else {
                        if ($body == 1) $xml .= "<intro><![CDATA[" . $intro . "]]></intro>";
                        $xml .= "<richBody><![CDATA[" . $rich_content . "]]></richBody>";
                    }
                }
            }
            if (isset($val['tag_id']) && !empty($val['tag_id'])) {
                $xml .= '<tags>';
                foreach ($val['tag_id'] as $tag_id) {
                    $xml .= '<tagId>' . $tag_id . '</tagId>';
                }
                $xml .= '</tags>';
            }
            $xml .= '</article>';
        }
        $xml .= '</campaign>';
        return $xml;
    }
}

function download_file($file_add, $path = '', $type = 'application/octet-stream', $rename='')
{
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header("Content-Type:" . $type); 
    if (!empty($rename)) {
        $file_name = $rename;
    } else {
        $arr = explode(".", $file_add);
        $file_name = date("Ymd") . '.' . $arr[count($arr) - 1];
    }
    header("Content-Disposition:attatchment;filename=" . $file_name); 
    ob_clean();
    flush();
    if (!empty($path)) $file_add = $path. $file_add;
    readfile($file_add);
    exit(0);
}

function downloadZipFiles($files, $path, $archive_file_name = '')
{
    global $g_article_storage;
    if (empty($archive_file_name)) $archive_file_name = $g_article_storage . time(). '.zip';
    $zip = new ZipArchive();
    if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }
    if (is_array($path)) {
        foreach ($path as $k => $value) {
            $subFiles = $files[$k];
            foreach($subFiles as $subk => $row) {
                $zip->addFile($value.$row['filename'],  $k . '/' .  $row['name']);
            }
        }
    } else {
        foreach($files as $k => $row) {
            $zip->addFile($path.$row['filename'], $row['name']);
        }
    }
    $zip->close();
    download_file($archive_file_name, '', 'application/zip');
    
}

function uploadedFiles($field, $cid, $dir='candidate_samples')
{
    global $g_article_storage;
    $allowedexts = array('doc','docx','pdf');
    $fileField = array();

    $destination_path = $g_article_storage .  $dir . DS . $cid . DS;
    if (!file_exists($destination_path)) {
        mkdir($destination_path, 0777, true);
    }

    $names = $_FILES[$field]['name']['fileField'];
    $types = $_FILES[$field]['type']['fileField'];
    $files = $_FILES[$field]['tmp_name']['fileField'];
    $error = $_FILES[$field]['error']['fileField'];
    foreach ($names as $k => $v) {
        if ($error[$k] == 0) {
            $arr = explode('.', $v);
            $ext = $arr[count($arr)-1];
            if (in_array($ext, $allowedexts)) {
                $j = $k + 1;
                $filename = $j  . '.' . $ext;
                if(move_uploaded_file($files[$k], $destination_path . $filename)) {
                    $fileField[$k] = array(
                        'name' => $v,
                        'filename' => $filename,
                        'type' => $types[$k],
                    );
                }
            } else {
                return $cid;
            }
        } else {
            $fileField[$k] = array();
        }
    }
    return $fileField;
}

function getClientXML($p = array()) {
    $username     = trim($p['user']);
    $password     = trim($p['pass']);
    $campaign_id = $p['cid'];
    $timestamp = $p['timestamp'];
    $p['u'] = $username;
    $p['p'] = $password;
    //$p['cid'] = $campaign_id;
    $p['title'] = 1;
    $p['ht'] = 1;
    $p['mk'] = 1;
    $p['md'] = 1;
    $p['text_body'] = 1;
    $p['rich_body'] = 1;
    $p['optional1'] = 1;
    $p['optional2'] = 1;
    $p['optional3'] = 1;
    $p['optional4'] = 1;
    $xml = getXMLDATA($p, $filename, $campaign_info, true);
    if (!$xml) {
        echo "<script>alert('There is no articles');window.close();</script>";
        exit;
    }
    ob_start();
    header("Content-type: text/xml; charset=utf-8");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    
    header("Pragma: no-cache"); 
    // get all keyword info
   
    $suffix = '.xml';

    $filename = 'CopyPress-' . time() . '-' . $campaign_id;  
    
    //windows valid file name,
    if (empty($timestamp)) $timestamp = $campaign_info['timestamp'];
    $xml_part = '<?xml version="1.0" encoding="utf-8"?>';
    $xml_part .= '<APIDownload>';
    $xml_part .= '<ClientName>' . Client::getName(). '</ClientName>';
    $xml_part .= '<CampaignName>' . $campaign_info['campaign_name']. '</CampaignName>';
    $xml_part .= '<cID>' . $campaign_id. '</cID>';
    $xml_part .= '<oldTimestamp>' . $timestamp . '</oldTimestamp>';
    $xml_part .= '<newTimestamp>' . $campaign_info['newTimestamp'] . '</newTimestamp>';
    $xml_part .= $xml;
    $xml_part .= '</APIDownload>';
    $xml = $xml_part;

    $reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/', '/\s/');
    $filename = preg_replace( $reg_str, '_', $filename ) . $suffix;
    header('Content-Disposition: attachment; filename='. $filename  );
    if(!$xml) {
    	exit;
    } else {
        //output xml info
        echo $xml;
        ob_end_flush();    	
    }
    if ($url_part == 'client' && client_is_loggedin()) {
         Article::setDownLoadTime(array('article_id' => $article_ids));
    }

    if (strlen($username) && strlen($password)) {
        session_start();
        $_SESSION = array();
        session_destroy();
    }
}

function getXML($p = array()) {
    $url_part = $p['url_part'];
    $article_ids = $p['article_ids'];
    $username     = trim($p['u']);
    $password     = trim($p['p']);

    ob_start();
    header("Content-type: text/xml; charset=utf-8");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    
    header("Pragma: no-cache"); 
    // get all keyword info
   
    $suffix = '.xml';

    // get xml file name
    $filename = preg_replace( '#\s+#', '_', trim($campaign_info['campaign_name']) );
    $cid = ($p['cid']); 
    if ( is_array($cid) ) {
        $cid = implode("-", $cid);       
    } 
    $filename = 'CopyPress-' . time() . '-' . $cid;  
    
    //windows valid file name,

    $xml = getXMLDATA($p, $filename, $campaign_info);
    $reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/', '/\s/');
    $filename = preg_replace( $reg_str, '_', $filename ) . $suffix;
    header('Content-Disposition: attachment; filename='. $filename  );
    if(!$xml) {
    	exit;
    } else {
        //output xml info
        echo $xml;
        ob_end_flush();    	
    }
//    if ($url_part == 'client' && client_is_loggedin()) {
//         Article::setDownLoadTime(array('article_id' => $article_ids));
//    }
    Article::setDownLoadTime(array('article_id' => $article_ids));

    if (strlen($username) && strlen($password)) {
        session_start();
        $_SESSION = array();
        session_destroy();
    }
}

function my_preg_match_all($start, $end, $string)
{
    $res = array();
    while(strpos($string, $start) !== FALSE && strpos($string, $end) !== FALSE)
    {
        $first  = strpos($string, $start);
        $string = substr($string, $first);
        $last   = strpos($string, $end);
        $res[]  = substr($string, 0, $last + strlen($end));
        $length = $last;
        $string = substr($string, $length);
    }
    return $res;
}

//add by liu shu fen 16:08 2007-11-14
function createXML($p, $time) {
    require_once CMS_INC_ROOT.'/Article.class.php';
    $result = $p['result'];
    foreach ($result as $rs) {
        $campaign_ids[$rs['campaign_id']] = $rs['campaign_id'];
        $article_ids[$rs['article_id']] = $rs['article_id'];
    }
    $p['cid'] = $campaign_ids;
    $p['article_ids'] = $article_ids;
    getXML($p);
    $p['time'] = $time;   
    Article::articleDownloadLog($p);
}

function calculate_percentage($total, $numerator, $precision = 0)
{
     $ret = $total ? round($total = $numerator*100/$total, $precision) : 0;
      return $ret . '%';
}

function getEmailSubjectAndBody($eventId, $ploaceholders)
{
    $info = Email::getInfoByEventId($eventId);
    $subject = html_entity_decode($info['subject']);
    $body = nl2br($info['body']);
    foreach ($ploaceholders as $k => $value) {
        $body = str_replace($k, $value, $body);
    }
    return compact('subject', 'body');
}

function calculateArticleWords($str)
{
    $words = preg_split("/[\s]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
    return count($words);
}

function generateDateTimeByMonth($month)
{
    $year = substr($month, 0, 4);
    $month = substr($month, 4, 2);
    $now = strtotime("$year-$month-01");
    return $now;
}

function splitMonth($month)
{
    return substr($month, 0, 4) . '-' . substr($month, 4, 2);
}
function getOptionalFields($value)
{
    $arr = array();
    if (is_array($value)) {
        foreach ($value as $v) {
            $arr[] = addslashes(htmlspecialchars($v));
        }
    } else {
        $value = addslashes(htmlspecialchars($value));
        $arr = explode("\n", $value);
    }
    return $arr;
}

function splitFieldByChar($value, $char="\n")
{
    $arr = array();
    if (is_array($value)) {
        foreach ($value as $v) {            
            $arr[] = addslashes(htmlspecialchars(trim($v)));
        }
    } else {
        $value = addslashes(htmlspecialchars(trim($value)));
        $arr = explode($char, $value);
    }
    return $arr;
}

function getIP()
{
    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (isset($_SERVER["HTTP_CLIENT_IP"]) && $_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}
function objtoarr($arr)
{
        if ($_SERVER['REMOTE_ADDR'] == '68.14.222.203' ){
	    print "in objtoarr\n";
		print_r($arr);
	}
    if (is_array($arr)) {
        foreach ($arr as $k => $row) {
            if (is_object($row)) {
                $arr[$k] = (array) $row;
                if (empty($arr[$k])) {
                    unset($arr[$k]);
                } else {
                    $arr = objtoarr($arr);
                }
            } else if (is_array($row))  {
                $arr[$k] = objtoarr($row);
            }
        }
    } else if (is_object($arr)) {
        $arr = (array) $arr;
        $arr = objtoarr($arr);
    }
    return $arr;
}


function email_replace_placeholders($str, $data)
{
   global $g_placeholders;
   if (!isset($g_placeholders)) {
        $g_placeholders= array(
            'campaign_name' => '%%CAMPAIGN_NAME%%',
            'campaign_id' => '%%CAMPAIGN_ID%%',
            'client_name' => '%%CLIENT_NAME%%',
            'company_name' => '%%COMPANY_NAME%%',
            'ask_days' => '%%ASK_DAYS%%',
            'first_name' => '%%FIRST_NAME%%',
            'user_pw' => '%%USER_PW%%',
            'login_link' => '%%LOGIN_LINK%%',
            'user_name' => '%%USER_NAME%%',
            'apikey' => '%%API_KEY%%',
            'token' => '%%SECRET_KEY%%',
            'domain' => '%%DOMAIN%%',
            'datastring' => '%%DATASTRING%%',
        );
   }
    foreach ($g_placeholders as $field => $value) {
        if (isset($data[$field])) {
            $str = str_replace($value, $data[$field], $str);
        }
    }
    $str = str_replace("[%%[", "<", $str);
    $str = str_replace("]%%]", ">", $str);
    $str = str_replace("/%%/", "/", $str);
    $str = str_replace("&quot;", '"', $str);
    return $str;
}

function keyword_search($keyword, $fields)
{
    global $feedback;
    $q = '';
    if ($keyword != '') {
        require_once CMS_INC_ROOT.'/Search.class.php';
        $search = new Search($keyword, "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q = $search->getLikeCondition("CONCAT(" . implode(",", $fields). ")")." ";
            }        
    }
    return $q;
}

function get_pager($perpage, $sql)
{
    global $g_pager_params, $conn;
    $count = $conn->GetOne($sql);
    if ($count == 0 || !isset($count)) {
        return false;
    }
    require_once 'Pager/Pager.php';
    $params = array(
        'perPage'    => $perpage,
        'totalItems' => $count
    );
    $pager = &Pager::factory(array_merge($g_pager_params, $params));
    return $pager;
}

function get_result_by_pager($pager, $sql, $perpage, $subTable = '' , $fk = '')
{
    global $conn;
    list($from, $to) = $pager->getOffsetByPageId();
    $rs = &$conn->SelectLimit($sql, $perpage, ($from - 1));
    $result =  $ids = array();
    if ($rs) {
        $i = 0;
        while (!$rs->EOF) {
            $fields = $rs->fields;
            if (isset($fields['docs'])) {
                $fields['docs'] = unserialize($fields['docs']);
            }
            $result[$i] = $fields;
            if (!empty($fk)) {
                $ids[] = $fields[$fk];
            }
            $rs->MoveNext();
            $i ++;
        }
        $rs->Close();
    }
    if (is_array($ids) && !empty($ids) && !empty($subTable)) {
        $sql = 'SELECT  * FROM ' . $subTable . ' WHERE ' . $fk . ' IN (' .  implode(',', $ids). ') ' ;
        $rs = $conn->Execute($sql);
        if ($rs) {
            $data = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                if (isset($fields['docs'])) {
                    $fields['docs'] = unserialize($fields['docs']);
                }
                $key = $fields[$fk];
                if (!isset($data[$key])) {
                    $data[$key] = array();
                }
                $data[$key][] = $fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }
        foreach ($result as $k=> $row) {
            $result[$k]['sub'] = $data[$row[$fk]];
            $result[$k]['total_sub'] = count($data[$row[$fk]]) + 1;
        }
    }
    return $result;
}

function download($file, $filename)
{
    $file_size = filesize($file);
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Accept-Length:{$file_size}");
    header("Content-Disposition:attachment;filename=".$filename);
    $fp=fopen($file,"r");
    $buffer_size = 1024;
    $cur_pos = 0;
    while(!feof($fp) && $file_size-$cur_pos > $buffer_size) {
       $buffer=fread($fp,$buffer_size);
       echo $buffer;
       $cur_pos+=$buffer_size;
    }
    $buffer=fread($fp,$file_size-$cur_pos);
    echo $buffer;
    fclose($fp);
}

function sendMail($mailto, $body, $subject, $from  = null, $cc_email = array())
{
    global $feedback, $conn, $mailer_param;

    $feedback = "";
    if (!empty($from))
    {
        $mailer_param['from'] = $from;
    }
 
    if (!empty($cc_email)) $mailer_param['cc'] = $cc_email;
    $all_user = explode(";", $mailto);

    if ($body == '') {
        $feedback = "Please provide mail body";
        return false;
    }


    if (!empty($all_user)) {
        foreach ($all_user AS $ku => $vu) {
            $address = $vu;
			if( strlen( $vu ) )
			{
				if (!send_smtp_mail($vu, $subject, $body, $mailer_param)) 
				{
					$feedback .= $vu."";
				}
			}
        }
    }

    if (trim($feedback) == '') {
        $feedback = "Send success";
        return true;
    } else {
        $feedback = "Failuer：".$feedback.". please try again.";
        return false;
    }
}

function update_user_info($user_info, $result)
{
    $is_form_parsed = $is_w9_parsed = false;
    foreach ($result as $item) {
        $title = $item['title'];
        $row = $item['fields'];
        if ($title == 'CopyPress Direct Deposit Form' && !$is_form_parsed) {
            $cb1 = trim($row['Custom Checkbox 1']);
            $cb2 = trim($row['Custom Checkbox 2']);
            if ((empty($cb1) || empty($cb2)) && ($cb1 == 'Yes' || $cb2 == 'Yes')) {
               if (!empty($row['Custom Field 8']))
                    $user_info['bank_name'] = $row['Custom Field 8'];
               if (!empty($row['Custom Field 10']))
                    $user_info['bank_info'] = $row['Custom Field 10'];
               if (!empty($row['Custom Field 11']))
                    $user_info['routing_number'] = $row['Custom Field 11'];
               if (!empty($row['Custom Field 7']))
                    $user_info['phone'] = $row['Custom Field 7'];
               /*if (!empty($row['Custom Field 3'])) 
                   $user_info['social_security_number'] = $row['Custom Field 3'];
               if (!empty($row['Custom Field 5']))
                   $user_info['address'] = $row['Custom Field 5'];*/
               $user_info['bank_acct_type'] = ($cb1 == 'Yes') ? 1 : ($cb2 == 'Yes' ? 1 : 0);
            }
            $is_form_parsed = true;
        } else if ($title == 'W-9 (Request for Taxpayer Identification Number)' && !$is_w9_parsed) {
           if (!empty($row['f1_04(0)']))
               $user_info['address'] = $row['f1_04(0)'];
           $str = $row['f1_05(0)']; 
           if (!empty($row['f1_08(0)']) && !empty($row['f1_11(0)']) && !empty($row['f1_13(0)'])) {
               $user_info['social_security_number'] = trim($row['f1_08(0)']) . trim($row['f1_11(0)']) . trim($row['f1_13(0)']);
           }
           if (!empty($str)) {
                // dispose address
                preg_match("|^([^,]+)[\s,]+([A-Za-z]+)[\s,]+([0-9]+)$|ims", $str, $matches);
                if (!empty($matches)) {
                    $user_info['city']= $matches[1];
                    $user_info['state']= $matches[2];
                    $user_info['zip']= $matches[3];
                } else {
                    $user_info['city']= $str;
                }
           }
            // get name
            $last = trim($row['last']);
            $first = trim($row['first']);
            if (empty($last)) $last  = $user_info['last_name'];
            if (empty($first)) $first = $user_info['first_name'];
            $arr = preg_split("/[\s]+/", $last, -1, PREG_SPLIT_NO_EMPTY);
            $user_info['first_name'] = $first;
            $total = count($arr);
            if ($total == 1) {
                $user_info['last_name'] = $last;
            } else {
                $user_info['middle_name'] = $arr[0];
                $user_info['last_name'] = $arr[1];
            }
            $user_info['fullname'] = $first . ' ' . $last;
            $is_w9_parsed = true;
        }
    }
    return $user_info;
}

function substrr($str, $start=-4)
{
    $sub_string = substr($str, $start);
    $len = strlen($str);
    $r_len = $len+$start;
    $r_string = "";
    for ($i=0;$i<$r_len;$i++) {
        $r_string .= "*";
    }
    return $r_string . $sub_string;
}

function getURI() {
    $aURL = array();

    // Try to get the request URL
    if (!empty($_SERVER['REQUEST_URI'])) {
        $aURL = parse_url($_SERVER['REQUEST_URI']);
    }

    // Fill in the empty values
    if (empty($aURL['scheme'])) {
        if (!empty($_SERVER['HTTP_SCHEME'])) {
            $aURL['scheme'] = $_SERVER['HTTP_SCHEME'];
        } else {
            $aURL['scheme'] = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ? 'https' : 'http';
        }
    }

    if (empty($aURL['host'])) {
        if (!empty($_SERVER['HTTP_HOST'])) {
            if (strpos($_SERVER['HTTP_HOST'], ':') > 0) {
                list($aURL['host'], $aURL['port']) = explode(':', $_SERVER['HTTP_HOST']);
            } else {
                $aURL['host'] = $_SERVER['HTTP_HOST'];
            }
        } else if (!empty($_SERVER['SERVER_NAME'])) {
            $aURL['host'] = $_SERVER['SERVER_NAME'];
        } else {
            print "xajax Error: xajax failed to automatically identify your Request URI.";
            print "Please set the Request URI explicitly when you instantiate the xajax object.";
            exit();
        }
    }

    if (empty($aURL['port']) && !empty($_SERVER['SERVER_PORT'])) {
        $aURL['port'] = $_SERVER['SERVER_PORT'];
    }

    if (empty($aURL['path'])) {
        if (!empty($_SERVER['PATH_INFO'])) {
            $sPath = parse_url($_SERVER['PATH_INFO']);
        } else {
            $sPath = parse_url($_SERVER['PHP_SELF']);
        }
        $aURL['path'] = $sPath['path'];
        unset($sPath);
    }

    if (!empty($aURL['query'])) {
        $aURL['query'] = '?'.$aURL['query'];
    }

    // Build the URL: Start with scheme, user and pass
    $sURL = $aURL['scheme'].'://';
    if (!empty($aURL['user'])) {
        $sURL.= $aURL['user'];
        if (!empty($aURL['pass'])) {
            $sURL.= ':'.$aURL['pass'];
        }
        $sURL.= '@';
    }

    // Add the host
    $sURL.= $aURL['host'];

    // Add the port if needed
    if (!empty($aURL['port']) && (($aURL['scheme'] == 'http' && $aURL['port'] != 80) || ($aURL['scheme'] == 'https' && $aURL['port'] != 443))) {
        $sURL.= ':'.$aURL['port'];
    }

    // Add the path and the query string
    $sURL.= $aURL['path'].@$aURL['query'];

    // Clean up
    unset($aURL);
    return $sURL;
}

function getMonthLastDate($first_date = null)
{
    if ($first_date) {
        $firstDateTime = strtotime($first_date);
    } else {
        $firstDateTime = strtotime(date("Y-m-1 00:00:00"));
    }
    $lastDateTime = strtotime("+1 month", $firstDateTime) -1;
    return date("Y-m-d",$lastDateTime);
}

function insertUnderlineBeforeCapital($fields)
{
    if (!empty($fields)) {
        $fields = preg_replace("|[A-Z]|U", "_\${0}", $fields);
    }
    return $fields;
}


// Generate a random character string
function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;
}

function generate_token() {
    $token = time();
    $token = substr($token, 0, 2).rand_str(5).
             substr($token, 2, 3).rand_str(5).
             substr($token, 5, 5).rand_str(8);

    return $token;
}

function stripUnderlineUCWord($str)
{
    $str = str_replace("_", " ", $str);
    return ucwords(strtolower($str));
}

function replaceSpaceToLine($str)
{
    $str = preg_replace("/[\s]+/ims", "-", $str);
    return strtolower($str);
}

function replace_specialChar($strParam){
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex,"",$strParam);
}


function checkUploadFile($files,$allowed_types)
{
    global $feedback;
    $error = $files['error'];
    if ($error > 0) {
        switch($error) {
        case 1:
        case 2:
            $feedback = 'The uploaded file is too large';
            break;
        case 3:
            $feedback = 'The uploaded file was only partially uploaded';
            break;
        case 4:
            $feedback = 'Missing a temporary folder';
            break;
        case 5:
            $feedback = 'Failed to write file to disk';
            break;
        case 6:
            $feedback = 'File upload stopped by extension';
            break;
        default:
            $feedback = 'Unknown upload error';
            break;
        }
        return false;
    }
    $arr = explode(".", $files['name']);
    $max_index = count($arr) - 1;
    if (!in_array($arr[$max_index], $allowed_types)) {
        $feedback = 'Invalid file type';
        return false;
    }
    return true;
}

function uploadFile($files, $desc)
{
    global $feedback;
    return move_uploaded_file($files['tmp_name'], $desc);
}

function getDataFromCsv($file)
{
    ini_set("auto_detect_line_endings", 1);
    $h = fopen($file,"r");
    $i = 0;
    $headers  = array();
    $rows = array();
    while (is_resource($h) && ($data = fgetcsv($h, 1000, ",")) !== FALSE) {
        $str = trim(implode("", $data));
        if (empty($str)) continue;
        if ($i == 0) {
            $headers = $data;
        } else {
            foreach ($headers as $k => $v) {
                $v = trim($v);
                if (!isset($rows[$v])) $rows[$v] = array();
                $rows[$v][] = $data[$k];
            }
        }
        $i++;
    }
    fclose($h);
    return $rows;
    
} 

// added by nancy 2011-01-12 10:34
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $arr) 
{
    global $environment, $feedback;
    $arr['METHOD'] = $methodName_;
    if("sandbox" === $environment || "beta-sandbox" === $environment) {
        $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
    } else {
        $API_Endpoint = 'https://api-3t.paypal.com/nvp';
    }
	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
    $nvpreq = http_build_query($arr);

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
        $feedback = "$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')';
        return false;
	}

	// Extract the response details.
    $httpParsedResponseAr = array();
    parse_str($httpResponse, $httpParsedResponseAr);

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
        $feedback = "Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.";
        return false;
	}

    return $httpParsedResponseAr;
}

function checkPaypalResult($result)
{
    global $feedback;
     if("SUCCESS" == strtoupper($result["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($result["ACK"])) {
         return true;
     } else {
        $feedback = "SetExpressCheckout failed:". $result['L_LONGMESSAGE0'];
         return false;     
     }
}
function getPaypalURL($token)
{
    global $environment;
	$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
	}
    return $payPalURL;
}
//end
// added by nancy xu 2011-01-26 14:53
function  addCharBeforeCapitalLetterAndNumber($str, $char = ' ')
{
    $pattern = '/([^A-Z0-9]*)([A-Z0-9])([^A-Z0-9]*)/';
    $replacement = '$1 $2$3';
    return ucwords(preg_replace($pattern,$replacement, $str));
}
//end

function locationString($role, $from_page, $campaign_id, $article_status = null, $p = array())
{
    $qstring = $rd_url = '';
    $qw = array();
    if (!empty($p)) {
        $rd_url = "/article/article_comment_list.php?";
        if (isset($p['is_ajax'])) unset($p['is_ajax']);
        for ($i=0; $i<count($p); $i++)
        {
            $rd_url .= key($p)."=".$_GET[key($p)]."&";
            next($p);
        }
    } else {
        if (strlen($article_status)) {
             $qw[] = 'article_status=1';
        }
        if ($from_page == '1gc') {
            $qw[] = 'article_status=1gc';
            if ($role == 'editor') $from_page = 'article_list';
        }elseif ($campaign_id > 0) {
            $qw[] = 'campaign_id=' .$campaign_id;
        }
        if (!empty($qw)) $qstring = '?' . implode('&', $qw);
        if (!empty($from_page)) {
            if ($from_page == 'home') {
                $rd_url = "/";
                $qstring = '';
            } else {
                $rd_url = "/article/{$from_page}.php";
            }
        } else {
            if ($role == 'admin') {
                $rd_url = "/client_campaign/keyword_list.php";
            } else if ($role == 'editor') {
                $rd_url = "/article/article_list.php";
            } else if ($role == 'client') {
                $rd_url = "/article/article_list.php";
            }
        }
    }
    $str = "<script>window.location.href='{$rd_url}{$qstring}';</script>";
    return $str;
}


function locationImageString($role, $from_page, $campaign_id, $article_status = null, $p = array())
{
    $qstring = $rd_url = '';
    $qw = array();
    if (!empty($p)) {
        $rd_url = "/graphics/image_comment_list.php?";
        if (isset($p['is_ajax'])) unset($p['is_ajax']);
        for ($i=0; $i<count($p); $i++)
        {
            $rd_url .= key($p)."=".$_GET[key($p)]."&";
            next($p);
        }
    } else {
        if (strlen($article_status)) {
             $qw[] = 'image_status=1';
        }
        if ($from_page == '1') {
            $qw[] = 'article_status=1';
            if ($role == 'editor') $from_page = 'image_list';
        }elseif ($campaign_id > 0) {
            $qw[] = 'campaign_id=' .$campaign_id;
        }
        if (!empty($qw)) $qstring = '?' . implode('&', $qw);
        if (!empty($from_page)) {
            if ($from_page == 'home') {
                $rd_url = "/";
                $qstring = '';
            } else {
                $rd_url = "/graphics/{$from_page}.php";
            }
        } else {
            if ($role == 'admin') {
                $rd_url = "/client_campaign/image_keyword_list.php";
            } else if ($role == 'editor') {
                $rd_url = "/graphics/image_list.php";
            } else if ($role == 'client') {
                $rd_url = "/graphics/image_list.php";
            }
        }
    }
    $str = "<script>window.location.href='{$rd_url}{$qstring}';</script>";
    return $str;
}

function search_private_index($param)
{
    if (user_is_loggedin() ) {
        $user_id = User::getID();
        $permission = User::getPermission();
        switch($permission) {
        case 1:
            $param['uid'] = $user_id;
            break;
        case 2:
            require_once CMS_INC_ROOT.'/Client.class.php';
            $client_ids = Client::getClientIdsByAgencyId($user_id);
            if (!empty($client_ids)) {
                if (!empty($param['cid'])) $client_ids[] = $param['cid'];
                $param['cid'] = implode(';', $client_ids);
            }
            
            break;
        case 3:
            $param['eid'] = $user_id;
            break;
        }
    }
    $url = "http://index.copypress.com/index.php?";
    $p = array(
        'u'=>'secondstepsearch',
        'k'=>'93op9p0j6nx4tdpg',
        'o'=>'fuzzysearch',
        'e'=>'UTF-8',
        'f' => 'json'
    );
    $page = $param['page'];
    $limit = isset($param['perPage']) ? $param['perPage'] : 20;
    if ($page > 0) {
        $offset = ($page-1)*$limit;
        $p['offset'] = $offset;
    }
    $p['limit'] = $limit;
    $url = trim($url,"&") . http_build_query($p);
    $client = new HTTP_Client();
    $client->post($url, $param);
    $result = $client->currentResponse();
    $obj = json_decode($result['body'], true);
    return $obj;
}

function payment_plugin($user_info, $types, $now)
{
    global $g_pay_plugin;
    require_once CMS_INC_ROOT.'/' . $g_pay_plugin . '.php';
    global $g_pay_user;
    $oPay = new $g_pay_plugin($g_pay_user);
    $vendor_bill = $oPay->saveBill($user_info, $types, $now);
    return $vendor_bill;
}

function add_vendor_plugin($p) 
{
    global $g_pay_plugin;
    require_once CMS_INC_ROOT.'/' . $g_pay_plugin . '.php';
    global $g_pay_user;
    $oPay = new $g_pay_plugin($g_pay_user);
    $result = $oPay->saveVendor($p);
    return $result;
}

function changePhoneFormat($number, $split = '-')
{
    $str = preg_replace("/[^0-9]+/",'',$number);
    return substr($str, 0, 3) . $split . substr($str, 3, 3) . $split . substr($str, 6);
}

function changeSSNFormat($number, $split='-')
{
    $str = preg_replace("/[\s-]+/",'',$number);
    return substr($str, 0, 3) . $split . substr($str, 3, 2) . $split . substr($str, 5);
}

function getSerializeSearch($field, $v)
{
    $len = strlen(trim($v));
    return '"' . $field .'";s:' . $len . ':"' . $v  .'";';
}

function getAllMonthes($start, $end = null)
{
    if (empty($start)) $start = date("Ym1",strtotime("last month"));
    if (empty($end)) $end = time();
    $monthes = array();
    $year = substr($start, 0, 4);
    $month = substr($start, 4, 2);
    $end_year = date("Y", $end);
    $end_month = date("m", $end);
    $start = $year . $month . '1';
    $end_m = $end_year . $end_month . '1';
    $c_month = (int)$start;
    $end_m = (int) $end_m;
    $monthes[$c_month] = $year . '-' . $month;
    $month = (int) $month;
    while ($c_month <= $end_m) {
        $month++;
        if ($month > 12) {
            $year++;
            $month %= 12;
        }
        $tmp = str_pad($month, 2, 0, STR_PAD_LEFT);
        $c_month = (int)$year . $tmp . '1';
        $monthes[$c_month] = $year . '-' . $tmp;
    }
    return $monthes;
}

function getStartPageNo($perPage = 50)
{
    $page = ($_REQUEST['page']?$_REQUEST['page']:1)-1;
    $perPage = $_REQUEST['perPage'] ? $_REQUEST['perPage'] : $perPage;
    return $page * $perPage;
}

function getJsForCustomFields($customFields)
{
    $checkJs = $timeCheckJs = '';
    foreach ($customFields as $key => $item ) {
        $label = isset($item['description']) && $item['description'] ?  $item['description']: $item['label'];
        if ($item['edit_role'] && $item['is_required']) {
            $checkJs .= "if (f.{$key}.value.length==0){alert('Please specify the {$label}');f.{$key}.focus();return false;}\n";
        }
        $label2 = '';
        $time = ($key == 'custom_field1') ? 3:2;
        if (isset($item['description'])) {
            $label2 = trim($item['description']);
            $label2 = preg_replace("/([^0-9]+)([0-9]+)$/i", '$1', $label2);
        }
        $timeStr =($time > 2 ?  $time. ' times ' : ($time == 2 ? 'twice ' : 'Once '));
        $hint = 'Please go back to the article and make sure you mention the '. $label2 .' "#' . $item['label'].'" at least '.  ($time > 2 ?  $time. ' times ' : ($time == 2 ? 'twice ' : 'Once ')) . ' as required by the client in the Style Guide.';
        if ( $item['is_required']) $timeCheckJs .= "if (occurs(content,  Trim(f.{$key}.value)) < {$time}) {alert('$hint');return false;}\n";
    }
    return array($checkJs, $timeCheckJs);
}

function isValidURL($url)
{
    //$rs = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-_]+)*(:[0-9]+)?(/.*)?$|ims', $url, $arr);
    //return $rs;
    return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false ? false : true;

}

function showLinkForOptionalFields($fields, $data)
{
    foreach ($fields as $field => $item) {
        
        if (isset($data[$field]) && !empty($data[$field])) {
            $value = trim($data[$field]);
            if (isValidURL($value)) {
                 $data[$field] = '<a href="' . $value . '" target="_blank">' . $value . '</a>';
            }
        }
    }
    return $data;
}
// added by nancy xu 2012-05-10 16:16
function passport_encrypt($txt, $key ='cmsencode') {
    $encrypt_key = md5($key);
    $ctr = 0;
    $tmp = '';
    for($i = 0;$i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    return base64_encode(passport_key($tmp, $key));
}

function passport_decrypt($txt, $key ='cmsencode') {
    $txt = passport_key(base64_decode($txt), $key);
    $tmp = '';
    for($i = 0;$i < strlen($txt); $i++) {
        $md5 = $txt[$i];
        $tmp .= $txt[++$i] ^ $md5;
    }
    return $tmp;
}

function passport_key($txt, $encrypt_key) {
    $encrypt_key = md5($encrypt_key);
    $ctr = 0;
    $tmp = '';
    for($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}

function change2EQuote($str)
{
    $str = str_replace("&lsquo;", "'", $str);
    $str = str_replace("&rsquo;", "'", $str);
    $str = str_replace("&rdquo;", '"', $str);
    $str = str_replace("&ldquo;", '"', $str);
    return $str;
}

/**
 * Helper function for drupal_html_to_text().
 *
 * Calls helper function for HTML 4 entity decoding.
 * Per: http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function decode_entities_full($string, $quotes = ENT_COMPAT, $charset = 'ISO-8859-1') {
  return html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'convert_entity', $string), $quotes, $charset); 
}

/**
 * Helper function for decode_entities_full().
 *
 * This contains the full HTML 4 Recommendation listing of entities, so the default to discard  
 * entities not in the table is generally good. Pass false to the second argument to return 
 * the faulty entity unmodified, if you're ill or something.
 * Per: http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function convert_entity($matches, $destroy = true) {
  static $table = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;','psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;','sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;','Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;'
                       );
  if (isset($table[$matches[1]])) return $table[$matches[1]];
  // else 
  return $destroy ? '' : $matches[0];
}
// end
?>