<?php
require_once CMS_INC_ROOT.'/Article.class.php';
require_once CMS_INC_ROOT.'/custom_field.class.php';
require_once CMS_INC_ROOT.'/UserCalendar.class.php';

/*
* @global  string $conn
* @global  string $feadback
* @author  Leo.Liu  <leo.liuxl@gmail.com>
* @copyright Copyright &copy; 2006
* @access  public
*/
class Campaign {

    function addByClient($p)
    {
        global $conn, $feedback;
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (!client_is_loggedin()) { 
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        $p['client_id'] = Client::getID();
        $p['category_id'] = 0 ;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        if ($campaign_name == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }
        if ($source == '') {
            $feedback = "Please specify the domain for this campaign";
            return false;
        }
        $p['date_created'] = date("Y-m-d H:i:s");
        $p['creation_user_id']  = Client::getID();
        $p['creation_role']  = 'client';
        $p['status']  = -1; // means pending campaigns
        $conn->StartTrans();
        $campaign_id = $conn->GenID('seq_client_campaigns_campaign_id');
        $p['campaign_id'] = $campaign_id;
        $fields = array_keys($p);
        $q = "INSERT INTO client_campaigns ( `" . implode("`, `", $fields) . "`) VALUES ";
        $q .= "('" . implode("', '", $p) . "')";
        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $campaign_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
         
    }

    function getLatestQuestionsByParam($p)
    {
        global $conn, $feedback;
        $conditions = array();
        if (isset($p['source']) && $p['source'] > 0) {
            $conditions[] = 'cc.source=' . $p['source'];
        }
        if (isset($p['article_type'])) {
            $conditions[] = 'cc.article_type=' . $p['article_type'];
        }
        if (!empty($conditions)) {
            $sql = "SELECT questions FROM client_campaigns AS cc WHERE " . implode(" AND ", $conditions) . ' AND questions LIKE \'%"source"%\' ORDER BY campaign_id DESC ';
            $data = $conn->GetOne($sql);
            if (!empty($data)) {
                $result = unserialize($data);
                return $result;
            }
        }
        return false;
    }

    function saveQuestions($p)
    {
        global $conn, $feedback;
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (!client_is_loggedin()) { 
            $feedback = "Have not the permission edit one campaign";
            return false;
        }
        if (empty($p['campaign_id'])) {
            $feedback = "Please specify the campaign";
            return false;
        } else {
            $campaign_id = $p['campaign_id'];
        }
        $questions = $p['questions'];
        foreach ($questions as $k => $v) {
            foreach ($v as $subk => $subv) {
                if (empty($subv)) unset($questions[$k][$subk]);
            }
        }
        if (!empty($questions)) {
            $questions = addslashes(serialize($questions));
            $sql = "UPDATE client_campaigns SET questions='" . $questions. "' WHERE campaign_id = " . $campaign_id ;
            $conn->Execute($sql);
            $feedback = 'Success';
            return $campaign_id;
        }  else {
            $feedback = 'Please specify the description for the domain or article type.';
        }
        return false;
    }

    /**
     * Add an client's campaign information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function add($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;
        unset($p['order_campaign_id']);
        // added by snug xu 2006-11-24 15:30 - START
        // let agency user add new client compaign
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (User::getPermission() < 4 && User::getPermission() != 2 && !client_is_loggedin()) { // 3=>4
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        // added by snug xu 2006-11-24 15:30 - END
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);

        if ($client_id == '') {
            $feedback = "Please Choose a client";
            return false;
        }

        // added by nancy xu 2010-08-05 11:34
        if ($ordered_by == '') {
            $feedback = 'Please Specify the ordered by';
            return false;
        }
        // end
        // Added by nancy xu 2012-03-20 15:57
        if ($max_word == '') {
            $feedback = 'Please Specify the No. of Words';
            return false;
        }        
        // End

        // added by snug xu 2008-01-10 10:56 - STARTED
        // check category 
        if ($category_id == 0) {
            $feedback = "Please Choose a category";
            return false;
        }
        // added by snug xu 2008-01-10 10:56 - END

        if ($campaign_name == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }

        if ($campaign_type == '') {
            $feedback = "Please specify the campaign type";
            return false;
        }

        if ($total_budget == '') {
            $total_budget = 0;
            $p['total_budget'] = $total_budget;
            //$feedback = "Please provide total budget of the campaign"; //请填写first name.
            //return false;
        }
        if (!is_numeric($total_budget)) {
            $feedback = "Total budget of the campaign must be a integer"; //请填写first name.
            return false;
        }
        if ($cost_per_article == '') {
            $cost_per_article = 0;
            $p['cost_per_article'] = $cost_per_article;
        }
        if (!is_numeric($cost_per_article)) {
            $feedback = "Cost per article must be a integer"; //请填写first name.
            return false;
        }
        if ($editor_cost == '') {
            $editor_cost = 0;
            $p['editor_cost'] = $editor_cost;
        }
        if (!is_numeric($editor_cost)) {
            $feedback = "Editor cost per word per article must be a integer";
            return false;
        }
        if (strlen($max_word) == 0) {
            $feedback = "Please specify No. of Words";
            return false;
        } else if (!is_numeric($max_word) || ($max_word < 0) && $max_word <> 0) {
             $feedback = "Please input number more than 0. if it's no limit, please input 0";
            return false;
        }

        /*
        $campaign_site_url = addslashes(htmlspecialchars(trim($p['campaign_site_url'])));
        if ($campaign_site_url == '') {
            $feedback = "Please enter the campaign site url";
            return false;
        }
        */

        if ($date_start == '') {
            $feedback = "Please provide the start date of the campaign";
            return false;
        }

        if ($date_end == '') {
            $feedback = "Please provide the Due Date of the campaign";
            return false;
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }
        
        if ($monthly_recurrent == '') {
            $monthly_recurrent = 0;
        } else {
            $monthly_recurrent = 1;
        }
        $p['monthly_recurrent'] = $monthly_recurrent;

        if ($content_level == '') {
            $content_level = 0;
            $p['content_level'] = $content_level;
        }
        
        /*
        $q = "SELECT COUNT(*) AS count FROM client_campaigns WHERE campaign_name = '".$campaign_name."'";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "The client's campaign name already registered, please type another name.";//用户名重复
            return false;
        }
        */
        $conn->StartTrans();
        $campaign_id = $conn->GenID('seq_client_campaigns_campaign_id');
        $p['campaign_id'] = $campaign_id;
        if (user_is_loggedin()) {
            $p['date_created'] = date("Y-m-d H:i:s");
            $p['creation_user_id']  = User::getID();
            $p['creation_role'] = User::getRole();
        } else {
            $p['date_created'] = date("Y-m-d H:i:s");
            $p['creation_user_id']  = Client::getID();
            $p['creation_role']  = 'client';
        }
        $fields = array_keys($p);
        $q = "INSERT INTO client_campaigns ( `" . implode("`, `", $fields) . "`) VALUES ";
        $q .= "('" . implode("', '", $p) . "')";
        $conn->Execute($q);

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            self::sendAnnouceMail($p);
            if (client_is_loggedin()) {
                $p['client_name'] = Client::getName();
                $p['total'] = $p['total_keyword'] ;
                Email::sendAnnouceMail(37, 'neworder@copypress.com', $p );
            }
            if (empty($feedback)) $feedback = 'Success';
            return $campaign_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end add()


    function saveKeywordField($p)
    {
        global $conn, $feedback, $g_questions, $g_tag, $mailer_param;
        $campaign_id = $p['campaign_id'];
        $filename = $p['filename'];
        $sql = "UPDATE client_campaigns set keyword_file='" .  addslashes($filename). "' where campaign_id = " . $campaign_id;
        $conn->Execute($sql);
        Campaign::sendClientAddCampaignEmail($campaign_id);
        return true;
    }

    function sendClientAddCampaignEmail($campaign_id)
    {
        global $conn, $feedback, $g_questions, $g_tag, $mailer_param;
        if (client_is_loggedin()) {
            $p = Campaign::getInfo($campaign_id);
            self::sendAnnouceMail($p);
            $p['client_name'] = Client::getName();
            $p['total'] = $p['total_keyword'];
            $article_types = $g_tag['leaf_article_type'];
            $p['article_type'] = $article_types[$p['article_type']];
            if (!empty($p['questions'])) {
                $questions = unserialize($p['questions']);
            } else {
                $questions = array();
            }
            $str = '';
            if (!empty($p['keyword_file'])) {
                $str .= "\nAttached, you will find the users keyword spreadsheet.\n";
                $arr = explode(DS, $p['keyword_file']);
                $mailer_param['attachment'] = array(
                    'filename'=> $arr[count($arr)-1],
                    'file'=> $p['keyword_file'] ,
                ); 
            }
            foreach ($g_questions as $k => $v) {
                switch($k) {
                case 'source':
                    $str .= "\nDomain Questionnaire\n";
                    break;
                case 'article_type':
                    $str .= "\nArticle Type Questionnaire(THIS ONE IS SHAREBAIT, IT WILL BE DIFFERENT FOR EACH ARTICLE TYPE)\n";
                    break;
                }
                $tmp = isset($questions[$k]) ? $questions[$k] : array();
                if ($k == 'article_type') {
                    foreach ($tmp as $subk=>$subv) {
                        $str .= "{$subv['q']}\n";
                        $str .= ((isset($subv['v']) && !empty($subv['v'])) ? $subv['v'] : 'n/a') . "\n";
                    }
                } else if ($k == 'source') {
                    foreach ($v as $subk=>$subv) {
                        $str .= "{$subv}\n";
                        $str .= ((isset($tmp[$subk]) && !empty($tmp[$subk])) ? $tmp[$subk]: 'n/a') . "\n";
                    }
                }
            }
            $p['datastring'] = $str;
            Email::sendAnnouceMail(37, 'neworder@copypress.com', $p );
            //Email::sendAnnouceMail(37, 'nancy@infinitenine.com', $p );
        }
        return true;
    }

    function autoAdd($p)
    {
        global $conn, $feedback;
        $campaign_id = $conn->GenID('seq_client_campaigns_campaign_id');
        $p['campaign_id'] = $campaign_id;
        $fields = array_keys($p);
        $q = "INSERT INTO client_campaigns ( `" . implode("`, `", $fields) . "`) VALUES ";
        $q .= "('" . implode("', '", $p) . "')";
        $conn->Execute($q);

        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            //self::sendAnnouceMail($p);
            return $campaign_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }

    function autoAddCampaignAndKeywords($row, $oLog, $oFile)
    {
        global $conn, $feedback;
        $conn->StartTrans();
        foreach ($row as $k => $v) {
            if (is_string($v)) $row[$k] = html_entity_decode($v);
        }
        
        $campaign_id = $row['campaign_id'];
        $campaign_log_id = $row['campaign_log_id'];
        $data = array('campaign_log_id' => $campaign_log_id);
        // create campaign information
        $article_type = $row['article_type'];
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
        $keyword_instructions = $row['keyword_instructions'];
        $creation_user_id = $row['creation_user_id'];
        $date_created = $row['date_created'];
        $creation_role = $row['creation_role'];
        $campaign_file_id = $row['campaign_file_id'];
        $client_id = $row['client_id'];
        
        if ($campaign_id == 0) {
            $campaign_id = $oLog->getCampaignIdByID($campaign_log_id, 1);
            
            if (empty($campaign_id)) {
                $campaign = array(
                    'client_id' => $client_id,
                    'campaign_name' => $row['campaign_name'],
                    'category_id' => $row['category_id'],
                    'article_type' => $article_type,
                    'source' => 0,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'campaign_requirement' => $row['campaign_requirement'],
                    'sample_content' => $row['sample_content'],
                    'keyword_instructions' => $keyword_instructions,
                    'special_instructions' => $row['special_instructions'],
                    'meta_param' => $row['meta_param'],
                    'title_param' => $row['title_param'],
                    'creation_user_id' => $creation_user_id,
                    'date_created' => $date_created,
                );
                if ($row['max_word'] > 0) $campaign["max_word"] = $row['max_word'];
                if ($row['template'] > 0) $campaign["template"] = $row['template'];
                if (!empty($row['style_guide_url'])) $campaign["style_guide_url"] = $row['style_guide_url'];
                if (!empty($row['ordered_by'])) $campaign["ordered_by"] = $row['ordered_by'];

                $source = ClientUser::getIDByParam(array('client_id' => $client_id, 'domain' => $row['domain']));
                if ($source > 0) $campaign['source']= $source;
                $campaign_id = Campaign::autoAdd($campaign);
                if ($campaign_id > 0) {
                    $data['campaign_id'] = $campaign_id;
                } else {
                    return false;
                }
            }
        } else {
            $campaign = array(
                'date_end' => $date_end,
                'campaign_requirement' => $row['campaign_requirement'],
                'sample_content' => $row['sample_content'],
                'keyword_instructions' => $keyword_instructions,
                'special_instructions' => $row['special_instructions'],
            );
            if ($row['max_word'] > 0) $campaign["max_word"] = $row['max_word'];
            if ($row['template'] > 0) $campaign["template"] = $row['template'];
            if (!empty($row['style_guide_url'])) $campaign["style_guide_url"] = $row['style_guide_url'];
            if (!empty($row['ordered_by'])) $campaign["ordered_by"] = $row['ordered_by'];

            Campaign::updateFieldsByCampaignId($campaign_id, $campaign);
            
        }
        $total_repeat = $row['repeat_time'];
        //$keyword = $mapping_id = $optional1 = $optional2= $optional3 = $optional4 = array();
        $p = array();
        for ($i=0;$i <$total_repeat;$i++ ) {
            foreach ($row as $krow => $vrow) {
                if ($krow == 'keyword' || $krow =='mapping_id' || substr($krow,0,8) == 'optional') {
                    if (!isset($hash[$krow])) $hash[$krow] = array();
                    $p[$krow][] = $vrow;
                }
            }
            /*$keyword[] = $row['keyword'];
            $mapping_id[] = $row['mapping_id'];
            $optional1[] = $row['optional1'];
            $optional2[] = $row['optional2'];
            $optional3[] = $row['optional3'];
            $optional4[] = $row['optional4'];*/
        }
        $p += compact('campaign_id', 'article_type', 'date_start' ,'date_end', 'keyword_instructions', 'creation_user_id', 'creation_role');
       
        if (Campaign::addKeywordByCronjob($p, false)) {
            $data['is_parsed'] = 1;
        }
        $oLog->update($data);
        $conn->CompleteTrans();
    }

    function getInfoFields($campaign_id, $fields = array())
    {
        global $conn;
        $sql = "SELECT ";
        if (!empty($fields)) {
            $sql .= implode(", ", $fields);
        } else {
            $sql .= " * ";
        }
        $sql .= " FROM client_campaigns  ";
        $sql .=" WHERE campaign_id ='{$campaign_id}' ";
        return $conn->GetRow($sql);
    }

    function sendAnnouceMail($campaign_info, $client = null, $tos = array(), $event_id = 34)
    {
        global $conn, $mailer_param, $g_to_email, $feedback;
        $cat_res = Category::getInfo($campaign_info['category_id']);
        
        $campaign_cat = $cat_res['category'];
        $pm_infos = User::getInfo(User::getID(), "u.status != 'D'");
        if (!empty($pm_infos)) {
            $mailer_param['from'] = $pm_infos['email'];
            $mailer_param['reply_to'] = $pm_infos['email'];
        }

        //$campaign_info = Campaign::getInfo($_GET['campaign_id']);
     
        // $mail_to_arr = array('Content' => 'cptech@copypress.com');
        // $mail_to_arr = array();
        $mail_to_arr = array();
        if (!empty($tos)) {
            $mail_to_arr = array_merge($mail_to_arr, $tos);
        }

        // added by nancy xu 2011-02-02 10:54
        // cc on notification emails to create campaign
        $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_info['campaign_id']));
        
        if (!empty($client_pm)) {
            $mail_to_arr[$client_pm['first_name']] = $client_pm['email'];
        }
        
        $mailer_param['cc'] = $g_to_email;
        // end
        $arr = Email::getInfoByEventId($event_id);
        $subject = $arr['subject'];
        $content = $arr['body'];

        $datastring = "<table class=oTable>";
        $datastring .= "<tr><td class=oDataLabel nowrap>Campaign Name</td><td class=oDataField>".$campaign_info['campaign_name']."</td></tr>".
                 "<tr><td class=oDataLabel nowrap>Campaign Category</td><td class=oDataField>".$campaign_cat."</td></tr>".
                 "<tr><td class=oDataLabel nowrap>Campaign Site Url</td><td class=oDataField>".$campaign_info['campaign_site_url']."</td></tr>".
                 "<tr><td class=oDataLabel nowrap>Start Date</td><td class=oDataField>".$campaign_info['date_start']."</td></tr>".
                 "<tr><td class=oDataLabel nowrap>Due Date</td><td class=oDataField>".$campaign_info['date_end']."</td></tr>".
                 "<tr><td class=oDataLabel nowrap>Campaign Requirement</td><td class=oDataField>".html_entity_decode($campaign_info['campaign_requirement'])."</td></tr>";

        if (!empty($pm_infos)) {
            $datastring .= "<tr><td class=oDataLabel nowrap>Creator</td><td class=oDataField>" . $pm_infos['first_name'] . "</td></tr>";
        } else if (!empty($client)) {
            $datastring .= "<tr><td class=oDataLabel nowrap>Client</td><td class=oDataField>" . $client . "</td></tr>";
        }

        $datastring .= "</table><br>";

        $datastring .= "<br><br>Best Regards,<br>";
        $datastring .= "<br><br>CopyPress<br>";
        $datastring .= "<br>&copy;Copyright " . date("Y"). " CopyPress. All Rights Reserved. ";

        $info = array('datastring' => $datastring, 'campaign_name' => $campaign_info['campaign_name']);
        foreach ($mail_to_arr AS $ku => $vu) {
            $info['first_name'] =  $ku;
            $body = nl2br(email_replace_placeholders($content, $info));
            $address = $vu;
            global $feedback;
            
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        }

        return true;
    }// end sendAnnouceMail()

    /**
     * Get campaign's info by $campaign_id
     *
     * @param int $campaign_id
     *
     * @return boolean or an array containing all fields in tbl.client_campaigns
     */
    function getInfo($campaign_id)
    {
        global $conn, $feedback;
        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        //modify by Liu Shufen 9:40 2007-11-15
        if (is_array($campaign_id)) {
            $campaign_id = implode(",", array_values($campaign_id));
        }
        $q = "SELECT cc.*, cl.user_name, cl.company_name FROM client_campaigns AS cc ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE cc.campaign_id IN ( ". $campaign_id ." ) ";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $ret = false;
            if ($rs->fields['campaign_id'] != 0) {
                $ret = $rs->fields; // return an array
            }

            $rs->Close();
            return $ret;
        }

        return false; // return false if client does not exist
    }//end getInfo()
    
    function getCampaignFromApi($is_sent = false)
    {
        global $conn;
        $q = "SELECT cc.*, cl.user_name, cl.company_name FROM client_campaigns AS cc ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE cc.source > 0 ";
        if (!$is_sent) {
            $q .= ' AND cc.is_sent = 0 ';
        }
        return $conn->GetAll($q);
    }

    function getCampaignByParam($param = array())
    {
        global $conn;
        $q = "SELECT cc.*, cl.user_name, cl.company_name FROM client_campaigns AS cc ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ";
        $qw = array("1");
        foreach ($param as $k => $v) {
            $v = addslashes(htmlspecialchars(trim($v)));
            $qw[] = "{$k}='{$v}'";
        }
        $q .=  ' WHERE ' . implode(" AND " , $qw);
        return $conn->GetAll($q);
    }

    function updateFieldsByCampaignId($cids, $p = array())
    {
        global $conn;
        $qw = array();
        if (empty($cids)) {
            return false;
        } else {
            if (is_array($cids)) {
                $qw[] = "campaign_id IN ('" . implode ("', '", $cids) . "')";
            } else {
                $qw[] = "campaign_id=" . $cids;
            }
        }
        if (empty($p)) return false;
        $sets = array();
        $sql = "UPDATE `client_campaigns` SET ";
        foreach ($p as $k => $v) {
            $sets[] = "{$k}='{$v}'";
        }
        $sql .= implode(",", $sets);
        $sql .= ' WHERE ' . implode(" AND ", $qw);
        $conn->Execute($sql);
    }
     /**
     * Set client's campaign info
     *
     *
     * @param array $p
     *
     * @return boolean  if success return ture, else return false;
     */
    function setInfo($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;

        // added by snug xu 2006-11-24 19:51
        // let agency have right to modified her/his campaign
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (User::getPermission() < 4 && User::getPermission() != 2 && !client_is_loggedin()) { // 3=>4
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        if (isset($p['order_campaign_id'])) unset($p['order_campaign_id']);
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        if ($campaign_id == '') {
            $feedback = "Please Choose a client's campaign";
            return false;
        }
        if ($client_id == '') {
            $feedback = "Please Choose a client";
            return false;
        }
        // added by snug xu 2008-01-10 10:56 - STARTED
        // check category 
        if ($category_id == 0) {
            $feedback = "Please Choose a category";
            return false;
        }
        // added by snug xu 2008-01-10 10:56 - END
        if ($campaign_name == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }
        if (empty($total_budget)) {
            $total_budget = 0;
            $p['total_budget'] =$total_budget;
        }
        if (!is_numeric($total_budget)) {
            $feedback = "Total budget of the campaign must be a integer";//请填写first name.
            return false;
        }
        if (empty($cost_per_article)) {
            $cost_per_article = 0;
            $p['cost_per_article'] =$cost_per_article;
        }
        if (!is_numeric($cost_per_article)) {
            $feedback = "Copywriter cost per word per article must be a integer";
            return false;
        }
        if (empty($editor_cost)) {
            $editor_cost = 0;
            $p['editor_cost'] =$editor_cost;
        }
        if (!is_numeric($editor_cost)) {
            $feedback = "Editor cost per word per article must be a integer";
            return false;
        }

        if ($date_start == '') {
            $feedback = "Please provide the Start Date of the campaign";
            return false;
        }
        if ($date_end == '') {
            $feedback = "Please provide the Due Date of the campaign";
            return false;
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }
        /*
        $q = "SELECT COUNT(*) AS count FROM client_campaigns ".
             "WHERE campaign_name = '".$campaign_name."' AND client_id != '".$client_id."'";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "The client's user name already registered, please enter another name.";//用户名重复
            return false;
        }
        */
        $campaign_info = self::getInfo($campaign_id);
        if ($campaign_info['monthly_recurrent'] == 2) {
            $monthly_recurrent = 2;
        } else {
            if ($monthly_recurrent == '') {
                $monthly_recurrent = 0;
            } else {
                $monthly_recurrent = 1;
            }
        }
        $p['monthly_recurrent'] = $monthly_recurrent;
        if ($content_level == '') {
            $content_level = 0;
            $p['content_level'] = $content_level;
        }

        $sql = "UPDATE client_campaigns SET ";
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\'' . $v . '\' ';
        }
        $sql .= implode(",", $sets);
        $sql .= "WHERE campaign_id = '".$campaign_id."' ";
        $conn->Execute($sql);
        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
        
    }//end setInfo()

    /**
     * get all user and user's information
     *
     * @param array $mode
     *
     * @return array
     */
    function getAllClients($mode = 'all_infos')
    {
        global $conn;

        if ($mode == 'all_infos') {
            $q = "SELECT * FROM `client` WHERE status != 'D' ORDER BY client_id";
            $rs = &$conn->Execute($q);
            if ($rs) {
                $client = array();
                while (!$rs->EOF) {
                    $client[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
                return $client;
            }
            return null;
        } else {
            $rs = &$conn->Execute("SELECT client_id, user_name FROM `client` WHERE status != 'D' ORDER BY user_name ASC");
            if ($rs) {
                $client = array();
                while (!$rs->EOF) {
                    $client[$rs->fields['client_id']] = $rs->fields['user_name'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $client;
            }
            return null;
        }
    }// end getAllClients()

    /**
     * get all campaign and campaign's information
     *
     * @param array $mode
     *
     * @return array
     */
    function getAllCampaigns($mode = 'all_infos', $client_id = '', $archived = 0, $campaign_type = 1)
    {
        global $conn, $g_archived_month_time;

        require_once CMS_INC_ROOT.'/Client.class.php';
        $q = "WHERE 1 ";
        $archived_date = date("Y-m-d", $g_archived_month_time);
        if ($archived > -1) {
            $q .= ' AND cc.archived = ' . $archived . ' ';
        }
        if (!empty($campaign_type)) {
            $q .= ' AND cc.campaign_type = ' . $campaign_type . ' ';
        }
        if (client_is_loggedin()) {
            $q .= "AND cc.client_id = '".Client::getID()."' ";
        } else {
            $is_mutil_client = false;
            if ($client_id != '') {
                if (is_array($client_id)) {
                    $is_mutil_client = true;
                    $q .= "AND cc.client_id  IN ('".  implode("', '", $client_id) ."') ";
                } else {
                    $q .= "AND cc.client_id = '". addslashes(htmlspecialchars(trim($client_id))) ."' ";
                }
            }
            if (User::getPermission() == 4) {
                $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
            }
        }

        if ($mode == 'all_infos') {
            $query = '*';
        } else if ($is_mutil_client) {
            $query = 'cc.campaign_id, cc.campaign_name, cc.client_id';
        } else {
            $query = 'cc.campaign_id, cc.campaign_name';
        }
        $q = "SELECT {$query} FROM client_campaigns as cc " 
                . "LEFT JOIN client AS cl ON cl.client_id=cc.client_id  " 
                . $q ." ORDER BY cc.campaign_id ASC";
        if ($mode == 'all_infos') {
            $rs = &$conn->Execute($q);
            if ($rs) {
                $campaigns = array();
                while (!$rs->EOF) {
                    $campaigns[] = $rs->fields;
                    $rs->MoveNext();
                }
                $rs->Close();
                return $campaigns;
            }
            return null;
        } else if ($is_mutil_client) {
            $rs = &$conn->Execute($q);
            if ($rs) {
                $campaigns = array();
                while (!$rs->EOF) {
                    $campaigns[$rs->fields['client_id']][$rs->fields['campaign_id']] = $rs->fields['campaign_name'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $campaigns;
            }
            return null;
        } else {
            $rs = &$conn->Execute($q);
            if ($rs) {
                $campaigns = array();
                while (!$rs->EOF) {
                    $campaigns[$rs->fields['campaign_id']] = $rs->fields['campaign_name'];
                    $rs->MoveNext();
                }
                $rs->Close();
                return $campaigns;
            }
            return null;
        }
    }// end getAllCampaigns()

    function getCampaignList($client_id = '', $param = array())
    {
        global $conn;
        require_once CMS_INC_ROOT.'/Client.class.php';
        $q = "WHERE 1 ";
        if (client_is_loggedin()) {
            $q .= "AND cc.client_id = '".Client::getID()."' ";
        } elseif (user_is_loggedin()) {
            if ($client_id != '') {
                $q .= "AND cc.client_id = '".addslashes(htmlspecialchars(trim($client_id)))."' ";
            }
            $permission = User::getPermission();
            if ($permission == 3) {
                $q .= "AND ck.editor_id = '" . User::getID() . "' ";
            } else if ($permission == 1) {
                $q .= "AND ck.copy_writer_id = '" . User::getID() . "' ";
            } else if ($permission == 2) {
                $q .= "AND cl.agency_id = '" . User::getID() . "' ";
            }
        }
        if (!empty($param)) {
            if (isset($param['client_id']) && $param['client_id'] > 0) {
                $client_id = trim($param['client_id']);
                $q .= "AND cc.client_id = '". addslashes(htmlspecialchars($client_id))."' ";
            }
            if (isset($param['keyword']) && !empty($param['keyword'])) {
                $keyword = trim($param['keyword']);
                 $q .= "AND cc.campaign_name LIKE '%". addslashes(htmlspecialchars($keyword))."%' ";
            }
            if (isset($param['date_start_l']) && !empty($param['date_start_l'])) {
                $date_start = trim($param['date_start_l']);
                $q .= " AND cc.date_start>= '{$date_start}' ";
            }
            if (isset($param['date_start_r']) && !empty($param['date_start_r'])) {
                $date_start = trim($param['date_start_r']);
                $q .= " AND cc.date_start<= '{$date_start}' ";
            }
            if (isset($param['date_end_l']) && !empty($param['date_end_l'])) {
                $date_end = trim($param['date_end_l']);
                $q .= " AND cc.date_end>= '{$date_end}' ";
            }
            if (isset($param['date_end_r']) && !empty($param['date_end_r'])) {
                $date_end = trim($param['date_end_r']);
                $q .= " AND cc.date_end<= '{$date_end}' ";
            }
        }
        $sql  = "SELECT DISTINCT cc.campaign_id, cc.campaign_name  ";
        $sql .= "FROM client_campaigns AS cc ";
        if (user_is_loggedin()) {
            if ($permission == 3 || $permission == 1) {
                $sql .= "LEFT JOIN campaign_keyword AS ck ON ck.campaign_id=cc.campaign_id ";
            } else if ($permission == 2) {
                $sql .= "LEFT JOIN client AS cl ON cc.client_id=cl.client_id ";
            }
        }
        $sql .= $q;
        $rs = &$conn->Execute($sql);
        if ($rs) {
            $campaigns = array();
            while (!$rs->EOF) {
                $campaigns[$rs->fields['campaign_id']] = $rs->fields['campaign_name'];
                $rs->MoveNext();
            }
            $rs->Close();
            return $campaigns;
        }
    }

	/**
	 * Created time 14:27 2006-9-25
	 * Funtion Description: copywriter uncompleted assignment report
	 * @author Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p
	 * @return array copywriter uncompleted assignment info
	 */
	function unfinishedAssignmentReport($p=array())
	{
		global $conn, $feedback;
		global $g_pager_params;

		foreach ($p as $k => $value)
		{
			$p[$k] = addslashes(htmlspecialchars(trim($value)));
		}
		if (strlen($p['month']) == 0)
		{
			$p['month'] = time();
		}
		$start_time = date("Y-m", $p['month']) . "-01 00:00:00";
		$end_time  = date("Y-m", strtotime("+1 month", $p['month'])) . "-01 00:00:00";
		$users = User::getCopywritersFromKeywordAssignmentByAssignTime($start_time, $end_time);
		$conditions = array(
									'start_time'=>$start_time, 
									'end_time'=>$end_time
								);
		foreach ($users as $user_id => $user)
		{
			$conditions['copy_writer'] = $user_id;
			if (self::isCopyWriterStartWorkingByMonth($conditions))
			{
				unset($users[$k]);
			}
			else
			{
				self::getCampaignInfoByCopyWriterId($conditions, $users);
			}
		}
		return $users;
	}// END unfinishedAssignmentReport();

	/**
	 * Function Description: check whether copywriter started to work or not between start time and end time
	 * Created Time: 15:37 2006-9-25
	 * @authoer Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p: datetime $p['start_time'], datetime $p['end_time'], int $p['copy_writer '] copy writer id
	 * @param array $users
	 */
	function getCampaignInfoByCopyWriterId($p, &$users)
	{
		global $conn, $feedback;
		foreach ($p as $k => $value)
		{
			$p[$k] = addslashes(htmlspecialchars(trim($value)));
		}
		$sql = "SELECT count( * ) as `num`, `cc`.`campaign_id`, `ck`.`copy_writer_id`, `cc`.`campaign_name` 
		FROM `client_campaigns` AS `cc`, `campaign_keyword` AS `ck`
		WHERE `cc`.`campaign_id` = `ck`.`campaign_id`
		AND `ck`.`copy_writer_id` = '{$p['copy_writer']}' 
		AND (`ck`.`date_assigned`>='{$p['start_time']}' AND `ck`.`date_assigned`<='{$p['end_time']}') AND ck.status!='D' 
		GROUP BY `ck`.`copy_writer_id`, `cc`.`campaign_id`";
		$users[$p['copy_writer']]['total'] = 0;
		$rs = &$conn->Execute($sql);
		if ($rs)
		{
			while (!$rs->EOF)
			{
				$num = $rs->fields['num'];
				if ($num>0)
				{
					$users[$p['copy_writer']][$rs->fields['campaign_id']] = $rs->fields;
					$users[$p['copy_writer']]['total'] += $num;
				}
                $rs->MoveNext();
			}
			$rs->close();
		}
	}

	/**
	 * Function Description: check whether copywriter started to work or not between start time and end time
	 * Created Time: 15:37 2006-9-25
	 * @authoer Snug Xu <xuxiannuan@gmail.com>
	 * @param array $p: datetime $p['start_time'], datetime $p['end_time'], int $p['copy_writer '] copy writer id
	 * @return bool if copy write started to work return true, else return false
	 */
	function isCopyWriterStartWorkingByMonth($p)
	{
		global $conn, $feedback;
		
		foreach ($p as $k => $value)
		{
			$p[$k] = addslashes(htmlspecialchars(trim($value)));
		}
		$sql = "SELECT count( * ) as `num`
		FROM `articles` AS `ar`, `campaign_keyword` AS `ck`
		WHERE `ar`.`keyword_id` = `ck`.`keyword_id`
		AND `ar`.`article_status` > '0'
		AND `ck`.`copy_writer_id` = '{$p['copy_writer']}' 
		AND (`ck`.`date_assigned`>='{$p['start_time']}' AND `ck`.`date_assigned`<='{$p['end_time']}') and ck.status!='D' 
		GROUP BY `ck`.`copy_writer_id`";

		$rs = &$conn->Execute($sql);

		if ($rs)
		{
			if (!$rs->EOF)
			{
				$num = $rs->fields['num'];
				if ($num > 0)
				{
					return true;
				}
			}
			$rs->Close();
		}
		return false;
	}


    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function search($p = array() )
    {
        global $conn, $feedback;

        global $g_pager_params, $g_archived_month_time;

        $q = "WHERE 1 ";

        // added by nancy xu 2011-02-17 17:24
        $direction = $sort = '';
        if (isset($p['sort']) && !empty($p['sort'])) {
            $sort = $p['sort'];
        } else {
            $sort = 'cc.campaign_id';
        }
        if (isset($p['direction']) && !empty($p['direction'])) {
            $direction = $p['direction'];
        }
        $order_by = ' ORDER BY ' . $sort  . ' '. $direction;
        //end

        // added by snug xu 2006-11-24 15:52 - START
        // if the role of user is agency, only show user's own client campaign
        if (User::getRole() == 'agency')
        {
            $q .= " AND cl.agency_id='" . User::getID() . "'";
        }
        // added by snug xu 2006-11-24 15:52 - END
        // added by nancy xu 2010-06-04 15:10
        
        $archived_date = date("Y-m-d", $g_archived_month_time);
        if (isset($p['archived']) && $p['archived'] > -1) {
            $q .= ' AND cc.archived = '.  $p['archived']  . ' ';
        }
        // end
        // added by nancy xu 2011-02-02 0:13
        if (User::getPermission() == 4) {
            $q .= ' AND cl.project_manager_id=' . User::getID() . ' ';
        }
        //end
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
        }

        $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
        if ($client_id != '') {
            $q .= "AND cc.client_id = '".$client_id."' ";
        }

        $campaign_name = addslashes(htmlspecialchars(trim($p['campaign_name'])));
        if ($campaign_name != '') {
            $q .= "AND cc.campaign_name LIKE '%".$campaign_name."%' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "AND cc.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "AND cc.date_end <= '".$date_end."' ";
        }
        $campaign_requirement = addslashes(htmlspecialchars(trim($p['campaign_requirement'])));
        if ($campaign_requirement != '') {
            $q .= "AND cc.campaign_requirement LIKE '%".$campaign_requirement."%' ";
        }

        //$q .= "AND (cc.permission < '".User::getPermission()."' OR cc.user_id = '".User::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(`cl`.`user_name`, `cl`.`company_name`, `cl`.`company_address`, `cl`.`city`, `cl`.`state`, `cl`.`zip`, `cl`.`company_phone`, `cl`.`email`, `cc`.`campaign_name`, `cc`.`campaign_requirement`)")." ";
            }
        }
        $q .= "AND cl.client_id = cc.client_id ";

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "AND cc.client_id = '".Client::getID()."' ";
        }

        $count_q = $q;
        $user_id = User::getID();
        if (User::getPermission() == 1 || User::getPermission() == 3) {
            $count_q .= "AND cc.campaign_id=ck.campaign_id AND ck.keyword_status != '0'  AND ck.`status`!='D' ";
            if (User::getPermission() == 1) {
                $count_q .= " AND ck.copy_writer_id = '".$user_id."' ";
            } else if (User::getPermission() == 3) {
                $count_q .= " AND ck.editor_id = '".$user_id."' ";
            }
        }
        $csql = "SELECT COUNT(DISTINCT cc.campaign_id) AS count FROM client_campaigns AS cc ";
        $csql .= "LEFT JOIN `client` AS cl  ON  cl.client_id = cc.client_id ";
        $csql .= "LEFT JOIN campaign_keyword AS ck ON cc.campaign_id=ck.campaign_id " . $count_q;
        $rs = &$conn->Execute($csql);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        if (User::getPermission() == 1) {
            $q .= " AND ck.copy_writer_id = '".User::getID()."' " ;
            $q .= " AND ck.keyword_status != '0'  " ;
            $q .= " AND cc.campaign_id=ck.campaign_id  " ;
            $q .= " AND `ck`.`status`!='D'  " ;
        } else if (User::getPermission() == 3) {
            $q .= " AND ck.editor_id = '".User::getID()."' " ;
            $q .= " AND cc.campaign_id=ck.campaign_id  " ;
            $q .= " AND `ck`.`status`!='D'  " ;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems'   => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT  cl.client_id, cl.company_name,cc.parent_id, cc.campaign_id, cc.campaign_type, cc.campaign_name, cc.date_end,cc.date_start,cc.date_created,cc.total_budget, cc.cost_per_article, csg.style_id, cc.is_import_kw, ". 
             // added by snug xu 2007-05-28 13:15 - STARTED
             // get total glean clean article 
              "COUNT( ar.article_id) as total_gc, " . 
             // added by snug xu 2007-05-28 13:15 - FINISHED
             " u.user_name AS project_manager, ck.copy_writer_id, creator.user_name AS creator_user \n".  
             "FROM `client` AS cl \n" .
             "LEFT JOIN client_campaigns AS cc ON (cl.client_id = cc.client_id) \n" .
             // added by snug xu 2007-05-28 13:11 - STARTED
             // added by snug xu 2007-06-04 18:18 - STARTED
             "LEFT JOIN `campaign_style_guide` AS csg ON (csg.campaign_id=cc.campaign_id) \n" .
             // added by snug xu 2007-06-04 18:18 - FINISHED
             // get total glean clean article 
             "LEFT JOIN campaign_keyword AS ck ON (ck.campaign_id=cc.campaign_id) \n" .
             "LEFT JOIN users AS creator ON (creator.user_id=cc.creation_user_id) \n" .
             "LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id AND ar.article_status = '1gc' )  \n" .
             "LEFT JOIN `users` AS u ON (u.user_id = cl.project_manager_id) \n".
             $q . 
             // " AND ck.status!='D' "  . 
             // added by snug xu 2007-05-28 13:59 - STARTED
             // get total glean clean article 
             " GROUP BY cc.campaign_id "
              .  $order_by;
             // added by snug xu 2007-05-28 13:59 - FINISHED

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }
        /**start: modified by snug 10:56 2006-8-2***/
        if (!empty($result)) {
            foreach ($result as $kr => $vr) 
			{
                $clause_from = ' LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ';
				$clause_where= " AND  ck.campaign_id = {$vr['campaign_id']} AND ck.status!='D' ";
				if (User::getPermission() == 1)
				{
					$clause_where .= " AND ck.copy_writer_id=". User::getID() . " ";
                    $clause_where .= " AND ck.keyword_status != '0' ";
					$key_count = self::countArticleBySubWhere( 'all', 2 , $clause_from, $clause_where );
				}
				else
				{
					$key_count = self::countKeywordByCampaignID($vr['campaign_id']);
//					if(client_is_loggedin())
//					{
//						$key_count = self::countArticleBySubWhere( 'all', 1 , $clause_from, $clause_where );
//					}
				}
                if ($key_count > 0) {
					if( client_is_loggedin() )
					{
						$clause_from .= "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ";
						$clause_where .= "AND cc.client_id = '".Client::getID()."' ";
						$article_count = self::countArticleBySubWhere('4|5|6|99',  2 , $clause_from, $clause_where );
					}
					else
					{
						$article_count = self::countArticleBySubWhere('0|1gd|2', 3 , $clause_from, $clause_where );
					}
                    $result[$kr]['progress'] = ($article_count / $key_count) * 100;
                    if (empty($key_count)) $result[$kr]['progress'] = 100;
                } else {
                	$result[$kr]['progress'] = 0;
                }
            }
        }/**end**/
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);

    }//end search()

    function searchByCpAndEditor($p, $is_page = true)
    {
        global $conn, $feedback;

        global $g_pager_params;

        $q = "WHERE 1 ";

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "AND cc.campaign_id = '".$campaign_id."' ";
            // $q .= "AND ck.campaign_id = '".$campaign_id."' ";
        }

        $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
        if ($client_id != '') {
            $q .= "AND cc.client_id = '".$client_id."' ";
        }

        $campaign_name = addslashes(htmlspecialchars(trim($p['campaign_name'])));
        if ($campaign_name != '') {
            $q .= "AND cc.campaign_name LIKE '%".$campaign_name."%' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "AND cc.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "AND cc.date_end <= '".$date_end."' ";
        }
        $campaign_requirement = addslashes(htmlspecialchars(trim($p['campaign_requirement'])));
        if ($campaign_requirement != '') {
            $q .= "AND cc.campaign_requirement LIKE '%".$campaign_requirement."%' ";
        }
        if (User::getPermission() == 1) {
            $copy_writer_id = User::getID();
        } else {
            $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
            if ($copy_writer_id == '') $copy_writer_id = 0;

        }

        //$q .= "AND (cc.permission < '".User::getPermission()."' OR cc.user_id = '".User::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "AND ".$search->getLikeCondition("CONCAT(`cl`.`user_name`, `cl`.`company_name`, `cl`.`company_address`, `cl`.`city`, `cl`.`state`, `cl`.`zip`, `cl`.`company_phone`, `cl`.`email`, `cc`.`campaign_name`, `cc`.`campaign_requirement`)")." ";
            }
        }
        $q .= "AND cl.client_id = cc.client_id ";
        if ($copy_writer_id > 0 ) $q.= " AND ck.copy_writer_id = " . $copy_writer_id . " ";
        if (User::getPermission() == 1) {
            $q .= " AND ck.keyword_status != '0'  " ;
            $q .= " AND cc.campaign_id=ck.campaign_id  " ;
            $q .= " AND `ck`.`status`!='D'  " ;
        }
        $tablefrom = "FROM `campaign_keyword` AS ck \n"
                          ." LEFT JOIN `client_campaigns` AS cc  ON cc.campaign_id = ck.campaign_id \n"
                          ." LEFT JOIN `client` AS cl  ON cl.client_id = cc.client_id \n";
        if ($is_page) {
            $count_q = "SELECT COUNT(DISTINCT cc.campaign_id, ck.copy_writer_id, ck.editor_id) AS count  ";
            $count_q .= $tablefrom;
            $count_q .= $q;

            $rs = &$conn->Execute($count_q);
            if ($rs) {
                $count = $rs->fields['count'];
                $rs->Close();
            }
            if ($count == 0 || !isset($count)) {
                //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
                return false;
            }
            $perpage = 50;
            if (trim($_GET['perPage']) > 0) {
                $perpage = $_GET['perPage'];
            }

            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems'   => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            $q = "SELECT cc.campaign_name, cc.date_start, cc.date_end, re.*, cc.campaign_id,". 
                  "ck.editor_id AS ck_editor_id , ck.copy_writer_id AS ck_cp,  " . 
                  "cp.user_name as copywriter,  e.user_name as editor, " . 
                  "COUNT( ar.article_id) AS total, u.user_name AS project_manager \n".  
                 $tablefrom . 
                 "LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id )  \n" .
                 "LEFT JOIN `users` AS u ON (u.user_id = cl.project_manager_id) \n".
                 "LEFT JOIN `users` AS cp ON (cp.user_id = ck.copy_writer_id) \n".
                 "LEFT JOIN `users` AS e ON (e.user_id = ck.editor_id) \n".
                 "LEFT JOIN `request_extension` AS re ON (re.copy_writer_id = ck.copy_writer_id AND ck.campaign_id = re.campaign_id) AND (re.editor_id = 0  OR (re.editor_id > 0 AND re.editor_id = ck.editor_id)) \n".
                 $q . 
                 " GROUP BY cc.campaign_id, ck.copy_writer_id, ck.editor_id  ";
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
            if ($rs) {
                $result = array();
                $i = 0;
                while (!$rs->EOF) {
                    $result[$i] = $rs->fields;
                    $rs->MoveNext();
                    $i ++;
                }
                $rs->Close();
            }
            if (!empty($result)) {
                foreach ($result as $kr => $vr) 
                {
                    $key_count = $vr['total'];
                    $editor_id = $vr['editor_id'];
                    if (empty($editor_id)) {
                        $editor_id = $vr['ck_editor_id'];
                    }
                    if ($key_count > 0) {
                        $clause_from = ' LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ';
                        $clause_where= " AND  ck.campaign_id = {$vr['campaign_id']} AND ck.status!='D' ";
                        if (User::getPermission() == 1)
                        {
                            $clause_where .= " AND ck.copy_writer_id=". $copy_writer_id . " ";
                            $clause_where .= " AND ck.editor_id=". $editor_id . " ";
                            $clause_where .= " AND ck.keyword_status != '0' ";
                        }
                        $article_count = self::countArticleBySubWhere('0|1gd|2', 3 , $clause_from, $clause_where );
                        $result[$kr]['total_writing'] = $article_count;
                        $result[$kr]['progress'] = round(($article_count / $key_count) * 100, 2);
                        if (empty($key_count)) $result[$kr]['progress'] = 100;
                    } else {
                        $result[$kr]['progress'] = 0;
                    }
                }
            }
            return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);
        } else {
            $q .= " AND ar.article_status REGEXP '^(0|1gd|2)$' ";
            $q = "SELECT ck.campaign_id, ck.editor_id, ck.copy_writer_id,COUNT( ar.article_id) AS total \n". 
                 $tablefrom . 
                 "LEFT JOIN articles AS ar ON (ck.keyword_id=ar.keyword_id )  \n" .
                 $q . 
                 " GROUP BY ck.campaign_id, ck.copy_writer_id, ck.editor_id  ";
            $result = $conn->GetAll($q);
        }
        return $result;
    }

    /**
     * delete one campaign
     *
     * @param int    $user_id
     * @param string $status
     *
     * @return boolean
     */
    function setStatus($campaign_id, $status)
    {
        global $conn, $feedback;

        // added by snug xu 2006-11-24 18:08
        // if role of user is agency, let this user to set campaign as 'D'
        if (User::getRole() != 'admin' && User::getRole() != 'agency') {
            $feedback = "Have not the permission to delete one campaign";
            return false;
        }
        /*
        $q = "UPDATE client_campaigns ".
             "SET status = '".$status."' ".
             "WHERE campaign_id = '".$campaign_id."' ";
        */
        $campaign_id = addslashes(trim($campaign_id));
        if ($campaign_id == '') {
            $feedback = "Please choose one campaign";
            return false;
        }
        /*
        articles
        articles_version_history
        campaign_keyword
        client_campaigns
        client
        comments_on_articles
        cp_campaign_article_summary
        user_payment_history
        */

        $q = "SELECT keyword_id FROM campaign_keyword ".
             "WHERE campaign_id = '".$campaign_id."' AND `status` !='D' ";
        $rs = $conn->Execute($q);
        $keywords = array();
        if ($rs) 
        {
            while (!$rs->EOF) 
            {
                $keywords[] = $rs->fields['keyword_id'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        $conn->StartTrans();
        if (empty($keywords)) {
            //do nothing;
        } else {
            $keyword_ids_str = implode(',', array_values($keywords));
            $q = "SELECT COUNT(article_id) AS count FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.")";
            $rs = $conn->Execute($q);
            if ($rs) {
                $article_count = $rs->fields['count'];
                $rs->Close();
            }
            if ($article_count > 0) {
                $q = "DELETE FROM comments_on_articles ".
                     "WHERE article_id IN (SELECT article_id FROM articles ".
                                          "WHERE keyword_id IN (".$keyword_ids_str."))";
                $conn->Execute($q);
            }
            $q = "DELETE FROM articles_version_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM user_payment_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);
        }

        $q = "DELETE FROM campaign_keyword WHERE campaign_id = '".$campaign_id."' ";
        $conn->Execute($q);
        $q = "DELETE FROM cp_campaign_article_summary WHERE campaign_id = '".$campaign_id."' ";
        $conn->Execute($q);
        $q = "DELETE FROM client_campaigns WHERE campaign_id = '".$campaign_id."' ";
        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $user_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end setStatus()

    function deleteKeywordByKeywordIdScope($start, $end = 0)
    {
        global $feedback, $conn;
        $conditions = array();
        if ($start > $end)
        {
            return false;
        }
        if ($start > 0)
        {
            $conditions[] = "keyword_id >= {$start}";
        }
        if ($end > 0)
        {
            $conditions[] = "keyword_id <= {$end}";
        }
        if (empty($conditions))
        {
            return false;
        }

        $conn->StartTrans();
        $qw = 'WHERE ' . implode(" AND ", $conditions);
        $q = "SELECT COUNT(article_id) AS count FROM articles ".
             $qw;
//        $rs = $conn->Execute($q);
        if ($rs) {
            $article_count = $rs->fields['count'];
            $rs->Close();
        }
        if ($article_count > 0) {
            $q = "DELETE FROM comments_on_articles ".
                 "WHERE article_id IN (SELECT article_id FROM articles ".
                                      $qw;
//            $conn->Execute($q);
        }
        $q = "DELETE FROM articles_version_history ".
             $qw;
//        $conn->Execute($q);

        $q = "DELETE FROM articles ".
             $qw;
//        $conn->Execute($q);

        $q = "DELETE FROM user_payment_history ".
             $qw;
//        $conn->Execute($q);
        $q = "DELETE FROM campaign_keyword " . $qw;
//        $conn->Execute($q);
        $ok = $conn->CompleteTrans();
        return true;
    }

    function deleteKeywordByCampaignID($campaign_id)
    {
        global $conn, $feedback;

        $campaign_id = addslashes(trim($campaign_id));
        if ($campaign_id == '') {
            $feedback = "Please choose one campaign";
            return false;
        }

        $q = "SELECT keyword_id FROM campaign_keyword ".
             "WHERE campaign_id = '".$campaign_id."' AND `status`!='D' ";
        $rs = $conn->Execute($q);
        $keywords = array();
        if ($rs) 
        {
            while (!$rs->EOF) 
            {
                $keywords[] = $rs->fields['keyword_id'];
                $rs->MoveNext();
            }
            $rs->Close();
        }

        $conn->StartTrans();
        if (empty($keywords)) {
            //do nothing;
        } else {
            $keyword_ids_str = implode(',', array_values($keywords));
            $q = "SELECT COUNT(article_id) AS count FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.")";
            $rs = $conn->Execute($q);
            if ($rs) {
                $article_count = $rs->fields['count'];
                $rs->Close();
            }
            if ($article_count > 0) {
                $q = "DELETE FROM comments_on_articles ".
                     "WHERE article_id IN (SELECT article_id FROM articles ".
                                          "WHERE keyword_id IN (".$keyword_ids_str."))";
                $conn->Execute($q);
            }
            $q = "DELETE FROM articles_version_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM articles ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);

            $q = "DELETE FROM user_payment_history ".
                 "WHERE keyword_id IN (".$keyword_ids_str.") ";
            $conn->Execute($q);
        }

        $q = "DELETE FROM campaign_keyword WHERE campaign_id = '".$campaign_id."' ";
        $conn->Execute($q);
//        $q = "DELETE FROM cp_campaign_article_summary WHERE campaign_id = '".$campaign_id."' ";
//        $conn->Execute($q);
//        $q = "DELETE FROM client_campaigns WHERE campaign_id = '".$campaign_id."' ";
//        $conn->Execute($q);
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $user_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }

    function setKeywordStatusByConditions($status, $campaign_id, $conditions)
    {
        global $conn, $feedback;
        if (User::getPermission() < 4) { // 3=>4
            $feedback = "Have not the permission to delete one keyword";
            return false;
        }
        if (empty($conditions)) {
            $feedback = 'You can\'t delete entire keywords';
            return false;
        }
        if (empty($campaign_id)) {
            $feedback = 'Please specify the camapign';
            return false;
        } else {
            $conditions[] = 'ck.campaign_id=\'' . $campaign_id . '\'';
        }
        $conditions[] = "ck.keyword_id=ar.keyword_id";
        $sql = "UPDATE campaign_keyword AS ck, articles AS ar SET ck.status='{$status}' WHERE " . implode(" AND ", $conditions) ;
        $conn->Execute($sql);
    }

    /**
     * Delete a keyword and correlative infomation
     *
     * @param keyword_id    $keyword_id
     * @param char    $status
     *
     * @return boolean
     */
    function setKeywordStatus($keyword_id, $status)
    {
        global $conn, $feedback;

        if (User::getPermission() < 4) { // 3=>4
            $feedback = "Have not the permission to delete one keyword";
            return false;
        }
        $keyword_id = addslashes(trim($keyword_id));
        if ($keyword_id == '') {
            $feedback = "Please choose one article";
            return false;
        }
        $q = "SELECT article_id FROM articles WHERE keyword_id = '".$keyword_id."'";
        $rs = $conn->Execute($q);
        $article_id = 0;
        if ($rs) {
            $article_id = $rs->fields['article_id'];
            $rs->Close();
        }

        $conn->StartTrans();
        if ($article_id > 0) {
            $q = "DELETE FROM comments_on_articles ".
                 "WHERE article_id = '".$article_id."' ";
            $conn->Execute($q);
        }
        $q = "DELETE FROM articles_version_history WHERE keyword_id = '".$keyword_id."' ";
        $conn->Execute($q);
        $q = "DELETE FROM articles WHERE keyword_id = '".$keyword_id."' ";
        $conn->Execute($q);
        $q = "DELETE FROM campaign_keyword WHERE keyword_id = '".$keyword_id."' ";
        $conn->Execute($q);
        //$ok = $conn->CompleteTrans();
        /*
        $q = "UPDATE campaign_keyword ".
             "SET status = '".$status."' ".
             "WHERE keyword_id = '".$keyword_id."' ";
        */

        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return $user_id;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }//end setStatus()

    /**
     * Add an client's campaign keyword information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function addKeyword($p = array())
    {
        global $conn, $feedback;
		//Start:Added By Snug 10:50 2006-8-25
		/****如果当前的keyword已经存在，将这个keyword放在这个数组中****/
		$duplicated_keywords = array();
		//Ended Added
        //global $g_tag;

        // added by snug xu 2006-11-24 15:30 - START
        // let agency user add new client compaign
        if (User::getPermission() < 4 && User::getPermission() != 2) { // 3=>4
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        // added by snug xu 2006-11-24 15:30 - END

        $new_keywords = array(
            'keywords' => array(),
            /*'optional1' => array(),
            'optional2' => array(),
            'optional3' => array(),
            'optional4' => array(),
            'optional5' => array(),
            'optional6' => array(),
            'optional7' => array(),*/
        );

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        $keyword = trim($p['keyword']);
        if ($keyword == '') {
            $feedback = "Please specify the keyword";
            return false;
        }
        //$keywords = explode("\n", $keyword);
        $keywords = splitFieldByChar($keyword);

        //$mapping_id = htmlspecialchars(trim($p['mapping_id']));
        $mapping_id = trim($p['mapping_id']);
        if ($mapping_id != '') {
            //$mappings = explode("\n", $mapping_id);
            $mappings = splitFieldByChar($mapping_id);
            /***************************************
             *added by nancy xu 2010-02-23 13:25
            $is_not_macth  = false;
            $klen = count($keywords);
            $mlen = count($mappings);
            if ($klen != $mlen) {
                $is_not_macth = true;
            } else {
                foreach ($keywords as $k => $v) {
                    if (!empty($v) && empty($mappings[$k])) {
                        $is_not_macth = true;
                    }
                }
            }
            if ($is_not_macth) {
                $feedback = 'Each keyword must have one mapping ID';
                return false;
            }
            *end 
            *****************************/
        } else {
            $mappings = array();
        }
        //modified by nancy xu 2012-04-20 15:31
        $options = array();
        foreach ($p as $k => $item) {
            if (substr($k, 0, 8) == 'optional') {
                $options[$k] = splitFieldByChar($item);
            }
        }
        // end
        
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type == '') {
            $feedback = "Please provide article type";
            return false;
        }
        if (isset($p['keyword_status']))
        {
            $keyword_status = addslashes(htmlspecialchars(trim($p['keyword_status'])));
        }
        else
        {
            $keyword_status = -1;
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $qaer_id = addslashes(htmlspecialchars(trim($p['qaer_id'])));
        if ($qaer_id == '') $qaer_id = 0;
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        $subcids = splitFieldByChar($p['subcid']);
        //$subcid = addslashes(htmlspecialchars(trim($p['subcid'])));
        if (empty($subcid)) $subcid = 0;
        if ($copy_writer_id != '' || $editor_id != '') {
            if ($copy_writer_id == '') {
                $copy_writer_id = 0;
            } else {
                if ($copy_writer_id > 0 ) {
                    // added by nancy xu 2010-03-19 13:51
                    require_once CMS_INC_ROOT . "/request_extension.class.php";
                    RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $campaign_id));
                    // end
                }
            }
            if ($editor_id == '') {
                $feedback = 'Please provide an editor';
                return false;
            }
        } else {
            $copy_writer_id = 0;
            $editor_id = 0;
        }

        $pref_table = 'campaign_keyword';
        $pref_field = 'keyword_category';
        $pref_value = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        $q = "SELECT pref_id FROM preference ".
             "WHERE pref_table = '".$pref_table."' AND pref_field = '".$pref_field."' AND pref_value = '".$pref_value."'";
        $rs = $conn->Execute($q);
        $pref_id = 0;
        if ($rs) {
            $pref_id = $rs->fields['pref_id'];
            $rs->Close();
        }

        //generate article number
        $campaign_info = self::getInfo($campaign_id);
        if (empty($campaign_info)) {
            $feedback = "Invalid Campaign, please try again";
            return false;
        }
        $company_name = strtoupper($campaign_info['company_name']);
        $numbers = explode(" ", $company_name);//we can use preg_split()
        $article_number = "";
        foreach ($numbers as $k => $v) {
            $article_number .= substr($v, 0, 1);
        }
        // added by nancy xu 2009-12-16 11:18
        if (isset($p['date_start']) && !empty($p['date_start'])) {
            $date_start = $p['date_start'];
        } else {
            $date_start = $campaign_info['date_start'];
        }
        if (isset($p['date_end']) && !empty($p['date_end'])) {
            $date_end = $p['date_end'];
        } else {
            $date_end = $campaign_info['date_end'];
        }
        // end
        $date_created = date('Y-m-d H:i:s', time());
        if ($copy_writer_id > 0) $date_assigned = $date_created;
        else $date_assigned = '0000-00-00 00:00:00';
        $conn->StartTrans();
        if ($pref_id > 0) {
            //do nothing;
        } else {
            $pref_id = $conn->GenID('seq_preference_pref_id');
            $q = "INSERT INTO preference (pref_id, pref_table, pref_field, pref_value) ".
                 "VALUES ('".$pref_id."', '".$pref_table."', '".$pref_field."', '".$pref_value."')";
            $conn->Execute($q);
        }
        $keyword_ids = array();
        foreach ($keywords as $k => $v) {
            if ($v != '') {
                $q = "SELECT COUNT(*) AS count FROM campaign_keyword ".
                     "WHERE keyword = '".$v."' AND campaign_id = '".$campaign_id."' AND article_type = '".$article_type."' AND `status`!='D' AND `keyword_category` = ".$pref_id;
                $rs = $conn->Execute($q);
                $count = 0;
                if ($rs) {
                    $count = $rs->fields['count'];
                    $rs->Close();
                }
                if ($count > 0) {
					$duplicated_keywords[] = $v;
                    // modified by nancy xu 2009-12-03 15:49
                    // when keyword are duplicated, the keyword still store to our system 
                    // continue;
                    // end
                }

                $keyword_id = $conn->GenID('seq_campaign_keyword_keyword_id');
                $keyword_ids[] = $keyword_id;
                $new_keywords['keywords'][$keyword_id] = stripslashes($v);
                // modified by nancy xu 2012-04-20 15:31
                foreach ($options as $ok => $item) {// optinal1-7
                    if (!isset($new_keywords[$ok])) $new_keywords[$ok] = array();
                    $new_keywords[$ok][$keyword_id] = $item[$k];
                }// end
                if (!empty($mappings)) {
                    $mapping_id = trim($mappings[$k]);
                } else {
                    $mapping_id = '';
                }
                $hash = array('keyword_id' => $keyword_id);
                $hash['campaign_id'] = $campaign_id;
                $hash['copy_writer_id'] = $copy_writer_id;
                $hash['qaer_id'] = $qaer_id;
                $hash['editor_id'] = $editor_id;
                if ($editor_id > 0) {
                    $hash['editor_status'] = -1; 
                }
                $hash['date_assigned'] = $date_assigned;
                $hash['keyword'] = $v;
                $hash['article_type'] = $article_type;
                $hash['keyword_description'] = addslashes(htmlspecialchars(trim($p['keyword_description'])));
                $hash['date_start'] = $date_start;
                $hash['date_end'] = $date_end;
                $hash['creation_user_id'] = User::getID();
                $hash['creation_role'] = User::getRole();
                $hash['date_created'] = $date_created;
                $hash['keyword_status'] = $keyword_status;
                $hash['mapping_id'] = $mapping_id;
                $hash['keyword_category'] = $pref_id;
                $hash['subcid'] = strlen($subcids[$k]) ? $subcids[$k] : 0;
                foreach ($options as $ok => $item) {
                    $hash[$ok] = $item[$k];
                }
                $q = "INSERT INTO campaign_keyword (`" . implode('`,`', array_keys($hash)). "`) VALUES ('" . implode("','", $hash). "')";

                 /*$q = "INSERT INTO campaign_keyword  (`keyword_id`, `campaign_id`, copy_writer_id, editor_id, ".
                "`date_assigned`, `keyword`, `article_type`, `keyword_description`, `date_start`, `date_end`, ".
                "`creation_user_id`, `creation_role`, `date_created`, `keyword_status`, `mapping_id`, `optional1`,`optional2`,`optional3`,`optional4`, `keyword_category`) ".
                     "VALUES ('".$keyword_id."', ".
                             "'".$campaign_id."', ".
                             "'".$copy_writer_id."', ".
                             "'".$editor_id."', ".
							 "'".$date_assigned."', ".
                             "'".$v."', ".
                             "'".$article_type."', ".
                             "'".addslashes(htmlspecialchars(trim($p['keyword_description'])))."', ".
                             "'".$date_start."', ".
                             "'".$date_end."', ".
                             "'".User::getID()."', ".
                             "'".User::getRole()."', ".
                             "'".$date_created."', ".
                             "'".$keyword_status."', ".
                             "'". $mapping_id ."', ".
                             "'". (isset($optional1[$k]) && !empty($optional1[$k]) ? $optional1[$k] : '') ."', ".
                             "'". (isset($optional2[$k]) && !empty($optional2[$k]) ? $optional2[$k] : '') ."', ".
                             "'". (isset($optional3[$k]) && !empty($optional3[$k]) ? $optional3[$k] : '') ."', ".
                             "'". (isset($optional4[$k]) && !empty($optional4[$k]) ? $optional4[$k] : '') ."', ".
                             "'". $pref_id . "')";*/
                $conn->Execute($q);

                $article_id = $conn->GenID('seq_articles_article_id');
                $q = "INSERT INTO articles (`article_id`, `article_number`, `keyword_id`, `creation_user_id`, `creation_role`, ".
                                           "`creation_date`, `language`, `title`, `body`, ".
                                           "`article_status`, `current_version_number`) ".
                     "VALUES ('".$article_id."', ".
                             "'".$article_number."-".($article_type+1)."-".$campaign_id."-".$keyword_id."', ".
                             "'".$keyword_id."', ".
                             "'".User::getID()."', ".
                             "'".User::getRole()."', ".
                             "'".date('Y-m-d H:i:s', time())."', ".
                             "'', ".
                             "'', ".
                             "'', ".
                             "'0', ".
                             "'1.0')";
                $conn->Execute($q);

            }
        }
        $ok = $conn->CompleteTrans();

        if ($ok) {
			if (count($duplicated_keywords))
			{
				$feedback = 'Duplicated keywords:\n  ' . implode('\n  ' , $duplicated_keywords);
				$feedback = preg_replace("/[\f\n\r\v]/", " ", $feedback);
			}
			else
			{
				$feedback = 'Success';
			}
             
            // added by nancy xu  2011-02-02 15:12
            global $mailer_param;
            $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_info['campaign_id']));
            //end
  
            if (!empty($keyword_ids)) {
                if ($editor_id > 0) {
                    if (!empty($client_pm)) {
                        $mailer_param['cc'] = $client_pm['email'];
                    }
                    if (!empty($copy_writer_id)) $copy_writer_keywords = $new_keywords;
                    $editor_keywords = $new_keywords;
                    //self::sendAssignKeywordMail($copy_writer_id, $editor_id, $campaign_info['date_end'], $copy_writer_keywords, $editor_keywords);
                    //self::sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $copy_writer_keywords, true);
                    if ($copy_writer_id > 0)self::sendAssignMail($copy_writer_id, $copy_writer_id, $campaign_info['date_end'], $copy_writer_keywords);
                    if ($editor_id > 0)self::sendAssignMail($editor_id, $copy_writer_id, $campaign_info['date_end'], $editor_keywords, false);
                    if (empty($feedback)) $feedback ='Success';
                } else {
                     $link = 'http://' . $_SERVER['HTTP_HOST']. '/client_campaign/keyword_list.php?campaign_id=' . $campaign_info['campaign_id']; 
                     $info = array(
                         'campaign_name' => $campaign_info['campaign_name'],
                         'client_name' => $campaign_info['user_name'],
                         'login_link' => $link,
                     );
                     unset($mailer_param['cc']);
                     Email::sendNewKeywordMail($info, $client_pm['email']);
                }
            }
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end addKeyword()


    function addKeywordByCronjob($p, $is_email = true)
    {
        global $conn, $feedback;
		//Start:Added By Snug 10:50 2006-8-25
		/****如果当前的keyword已经存在，将这个keyword放在这个数组中****/
		$duplicated_keywords = array();
		//Ended Added
        //global $g_tag;

        $new_keywords = array(
            'keywords' => array(),
            /*'optional1' => array(),
            'optional2' => array(),
            'optional3' => array(),
            'optional4' => array(),*/
        );
        
        foreach ($p as $k => $v ) {
            if (!is_array($v)) {
                $p[$k] = addslashes(htmlspecialchars(trim($v)));
            }
        }
        //added by nancy xu 2012-08-01 20:37
        $options = array();
        foreach ($p as $k => $item) {
            if (substr($k, 0, 8) == 'optional') {
                if (is_array($item)) {
                    foreach ($item as $ik => $iv) {
                        $item[$ik]= stripslashes($iv);
                    }
                } else {
                    $item = stripslashes($item);
                }
                $options[$k] = splitFieldByChar($item);
            }
        }
        // end
        extract($p);
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }

        $keywords = $keyword;
        if (empty($keywords)) {
            $feedback = "Please enter keywords";
            return false;
        }

        if ($article_type == '') {
            $feedback = "Please provide article type";
            return false;
        }
        if (isset($p['keyword_status'])) {
            $keyword_status = addslashes(htmlspecialchars(trim($p['keyword_status'])));
        }else{
            $keyword_status = -1;
        }
        if ($copy_writer_id != '' || $editor_id != '') {
            if ($copy_writer_id == '') {
                $copy_writer_id = 0;
            } else {
                if ($copy_writer_id > 0 ) {
                    // added by nancy xu 2010-03-19 13:51
                    require_once CMS_INC_ROOT . "/request_extension.class.php";
                    RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $campaign_id));
                    // end
                }
            }
            
            if ($editor_id == '') {
                $feedback = 'Please provide an editor';
                return false;
            }
        } else {
            $copy_writer_id = 0;
            $editor_id = 0;
        }

        $pref_table = 'campaign_keyword';
        $pref_field = 'keyword_category';
        $pref_value = $keyword_category;
        $q = "SELECT pref_id FROM preference ".
             "WHERE pref_table = '".$pref_table."' AND pref_field = '".$pref_field."' AND pref_value = '".$pref_value."'";
        $rs = $conn->Execute($q);
        $pref_id = 0;
        if ($rs) {
            $pref_id = $rs->fields['pref_id'];
            $rs->Close();
        }

        //generate article number
        $campaign_info = self::getInfo($campaign_id);
        if (empty($campaign_info)) {
            $feedback = "Invalid Campaign, please try again";
            return false;
        }
        $company_name = strtoupper($campaign_info['company_name']);
        $numbers = explode(" ", $company_name);//we can use preg_split()
        $article_number = "";
        foreach ($numbers as $k => $v) {
            $article_number .= substr($v, 0, 1);
        }
        // added by nancy xu 2009-12-16 11:18
        if (isset($p['date_start']) && !empty($date_start)) {
            // $date_start = $p['date_start'];
        } else {
            $date_start = $campaign_info['date_start'];
        }
        if (isset($p['date_end']) && !empty($date_end)) {
            // $date_end = $p['date_end'];
        } else {
            $date_end = $campaign_info['date_end'];
        }
        // end
        $date_created = date('Y-m-d H:i:s', time());
        if ($copy_writer_id > 0) $date_assigned = $date_created;
        else $date_assigned = '0000-00-00 00:00:00';
        $conn->StartTrans();
        if ($pref_id > 0) {
            //do nothing;
        } else {
            $pref_id = $conn->GenID('seq_preference_pref_id');
            $q = "INSERT INTO preference (pref_id, pref_table, pref_field, pref_value) ".
                 "VALUES ('".$pref_id."', '".$pref_table."', '".$pref_field."', '".$pref_value."')";
            $conn->Execute($q);
        }
        $keyword_ids = array();
        $creation_user_id = isset($p['creation_user_id']) ? $p['creation_user_id'] : User::getID();
        $creation_role = isset($p['creation_role']) ? $p['creation_role'] : User::getRole();
        $mappings = $mapping_id;
        
        foreach ($keywords as $k => $v) {
            if ($v != '') {
                $q = "SELECT COUNT(*) AS count FROM campaign_keyword ".
                     "WHERE keyword = '".$v."' AND campaign_id = '".$campaign_id."' AND article_type = '".$article_type."' AND `status`!='D' AND `keyword_category` = ".$pref_id;
                $rs = $conn->Execute($q);
                $count = 0;
                if ($rs) {
                    $count = $rs->fields['count'];
                    $rs->Close();
                }
                if ($count > 0) {
					$duplicated_keywords[] = $v;
                }
                $hash = array();
                $keyword_id = $conn->GenID('seq_campaign_keyword_keyword_id');
                $hash['keyword_id'] = $keyword_id;
                echo $keyword_id . ' ' . $k . "\n";
                $keyword_ids[] = $keyword_id;
                $new_keywords['keywords'][$keyword_id] = stripslashes($v);
                $hash['keyword'] = $v;
                if (!empty($mappings)) {
                    $mapping_id = addslashes(htmlspecialchars(trim($mappings[$k])));
                } else {
                    $mapping_id = '';
                }
                $hash['mapping_id'] = $mapping_id;
                foreach ($options as $ok => $item) {// optinal1-10
                    if (!isset($new_keywords[$ok])) $new_keywords[$ok] = array();
                    $item_value = $item[$k];
                    $new_keywords[$ok][$keyword_id] = $item_value;
                    $hash[$ok] = $item_value;
                }
                $hash['campaign_id'] = $campaign_id;
                $hash['copy_writer_id'] = $copy_writer_id;
                $hash['editor_id'] = $editor_id;
                $hash['date_assigned'] = $date_assigned;
                $hash['article_type'] = $article_type;
                $hash['keyword_description'] = $keyword_description;
                $hash['date_start'] = $date_start;
                $hash['date_end'] = $date_end;
                $hash['creation_user_id'] = $creation_user_id;
                $hash['creation_role'] = $creation_role;
                $hash['date_created'] = $date_created;
                $hash['keyword_status'] = $keyword_status;
                $hash['keyword_category'] = $pref_id;
                $q = "INSERT INTO campaign_keyword  (`" . implode("`,`", array_keys($hash))."`) ".
                     "VALUES ('". implode("','", $hash) . "')";
                $conn->Execute($q);
                $hash = array(
                    'article_id' => $article_id,
                    'keyword_id' => $keyword_id,
                    'creation_user_id' => $creation_user_id,
                    'creation_role' => $creation_role,
                    'creation_date' => $date_created,
                    'language' => '',
                    'title' => '',
                    'body' => '',
                    'article_status' => '0',
                    'current_version_number' => '1.0',
                    'article_number' => $article_number."-".($article_type+1)."-".$campaign_id."-".$keyword_id,
                 );
                $article_id = $conn->GenID('seq_articles_article_id');
                $q = "INSERT INTO articles (`article_id`, `article_number`, `keyword_id`, `creation_user_id`, `creation_role`, ".
                                           "`creation_date`, `language`, `title`, `body`, ".
                                           "`article_status`, `current_version_number`) ".
                     "VALUES ('".$article_id."', ".
                             "'".$article_number."-".($article_type+1)."-".$campaign_id."-".$keyword_id."', ".
                             "'".$keyword_id."', ".
                             "'". $creation_user_id ."', ".
                             "'". $creation_role ."', ".
                             "'". $date_created ."', ".
                             "'', ".
                             "'', ".
                             "'', ".
                             "'0', ".
                             "'1.0')";
                $conn->Execute($q);

            }
        }
        $ok = $conn->CompleteTrans();

        if ($ok) {
			if (count($duplicated_keywords))
			{
				$feedback = "Duplicated keywords:\n " . implode("\n  " , $duplicated_keywords);
				$feedback = preg_replace("/[\f\n\r\v]/", " ", $feedback);
			}
			else
			{
				$feedback = 'Success';
			}
             if (!empty($keyword_ids) && (!empty($copy_writer_id) &&  !empty($editor_id))) {
                if (!empty($copy_writer_id)) $copy_writer_keywords = $new_keywords;
                $editor_keywords = $new_keywords;
                if ($is_email) {
                    //self::sendAssignKeywordMail($copy_writer_id, $editor_id, $campaign_info['date_end'], $copy_writer_keywords, $editor_keywords);
                    self::sendAssignMail($copy_writer_id, $copy_writer_id, $campaign_info['date_end'], $copy_writer_keywords);
                }
             } else if (!empty($keyword_ids) && empty($copy_writer_id) &&  empty($editor_id)) {
                 $info = array(
                     'campaign_name' => $campaign_info['campaign_name'],
                     'client_name' => $campaign_info['user_name'],
                 );
                 if ($is_email) Email::sendNewKeywordMail($info);
             }
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
    }

    function saveKeyword($data, $article, $is_trans = true)
    {
        global $conn;
        if ($is_trans) $conn->StartTrans();
        $keys = array_keys($data);
        foreach ($data as $k => $v) {
            $data[$k] = addslashes($v);
        }

        if (empty($data['keyword_id'])) {
            $keyword_id = $conn->GenID('seq_campaign_keyword_keyword_id');
            $data['keyword_id'] = $keyword_id;
            $keys[] = 'keyword_id';
            $sql = "INSERT INTO campaign_keyword  (`" . implode("`,`", $keys) ."`) VALUES ";
            $sql .= "('" . implode("',\n'", $data) . "')";
        } else {
            $keyword_id = $data['keyword_id'];
            $sql = "UPDATE campaign_keyword ";
            $sets = array();
            foreach ($data as $k => $v) {
                $sets[] = "`{$k}`='{$v}'";
            }
            $sql .= implode(",\n", $sets);
            $sql .= ' WHERE keyword_id=' . $keyword_id;
        }
        $conn->Execute($sql);
        $article['keyword_id'] = $keyword_id;
        if (!empty($article['richtext_body'])) {
            $article['richtext_body'] = htmlspecialchars($article['richtext_body']);
        }
        if (!isset($article['body']) || empty($article['body'])) {
            if (!empty($article['richtext_body'])) $article['body'] = change_richtxt_to_paintxt($article['richtext_body']);
        } else {
            if (!isset($article['richtext_body']) || empty($article['richtext_body']))
                $article['richtext_body'] = htmlspecialchars(nl2br($article['body']));
        }

        foreach ($article as $k => $v) {
            $article[$k] = addslashes($v);
        }
        $article['article_number'] .='-' . ($data['article_type']+1) . '-' . $data['campaign_id'] . '-' . $keyword_id;
        $keys = array_keys($article);
        if ($article['article_id'] > 0) {
            $article_id = $article['article_id'];
            $sql = "UPDATE articles ";
            $sets = array();
            foreach ($data as $k => $v) {
                $sets[] = "`{$k}`='{$v}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE article_id=' . $article_id;
        } else {
            $article_id = $conn->GenID('seq_articles_article_id');
            $article['article_id'] = $keyword_id;
            $keys[] = 'article_id';
            $sql = "INSERT INTO articles  (`" . implode("`,`", $keys) ."`) VALUES ";
            $sql .= "('" . implode("',\n'", $article) . "')";
        }
        $conn->Execute($sql);
        if ($is_trans)  {
            $ok = $conn->CompleteTrans();
            return $ok ? $article_id : false;
        }
        return $article_id;
    }

    function updateCustomFieldsByKeywordId($p = array())
    {
        global $conn, $feedback, $g_custom_fields;
        if (empty($g_custom_fields)) {
            require_once CMS_INC_ROOT.'/custom_field.class.php';
            $g_custom_fields = CustomField::getFieldLabelsFromDB(array('client_id' => $p['client_id']), 'custom_field');
        }
        if (!empty($g_custom_fields)) {           
            $keyword_id = $p['keyword_id'];
            if (empty($keyword_id)) {
                $feedback = 'Invalid keyword, please to check';
                return false;
            }
            $sets = array();
            foreach ($g_custom_fields as $k => $v) {
                if (isset($p[$k])) {
                    $sets[] = $k .'=\'' . addslashes(htmlspecialchars($p[$k])) . '\'';
                }
            }
            if (!empty($sets)) {
                $sql = 'UPDATE campaign_keyword SET ' . implode(',', $sets) . ' WHERE keyword_id = ' . $keyword_id ;
                $conn->Execute($sql);
            }
        }
        return true;
    }


    /**
     * Set an client's campaign keyword information
     *
     * @param array $p the value was submited by form
     *
     * @return boolean or an int
     */
    function setKeyword($p = array())
    {
        global $conn, $feedback;
        //global $g_tag;

        if (User::getPermission() < 4) { // 3=>4
            $feedback = "Have not the permission add one campaign";
            return false;
        }
        /*
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        */
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        $keyword = addslashes(htmlspecialchars(trim($p['keyword'])));
        if ($keyword == '') {
            $feedback = "Please enter the name of the campaign";
            return false;
        }

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type == '') {
            $feedback = "Please provide keyword article type";
            return false;
        }

        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start == '') {
            $feedback = "Please provide the start date  of the campaign keyword";
            return false;
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end == '') {
            $feedback = "Please provide the Due Date of the campaign keyword";
            return false;
        }

        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }
        $qu = '';
        $sets = array();
        foreach ($p as $k => $v) {
            if ($k == 'keyword_description' || $k == 'mapping_id' || substr($k,0,8) == 'optional' || $k == 'subcid') {                
                if ($k == 'subcid' && empty($v)) $v == 0; 
                $v = addslashes(htmlspecialchars(trim($v)));
                $sets[] = $k .'=\'' . $v.  '\'';
            }
        }
        if (!empty($sets)) $qu .= implode(',', $sets) . ',';
        /*if (isset($p['keyword_description'])) {
            $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
            $qu .= "keyword_description = '".$keyword_description."', ";
        }

        if (isset($p['mapping_id'])) {
            $mapping_id = addslashes(htmlspecialchars(trim($p['mapping_id'])));
            $qu .= "mapping_id = '".$mapping_id."', ";
        }

        if (isset($p['optional1'])) {
            $optional1 = addslashes(htmlspecialchars(trim($p['optional1'])));
            $qu .= "optional1 = '".$optional1."', ";
        }

        if (isset($p['optional2'])) {
            $optional2 = addslashes(htmlspecialchars(trim($p['optional2'])));
            $qu .= "optional2 = '".$optional2."', ";
        }

        if (isset($p['optional3'])) {
            $optional3 = addslashes(htmlspecialchars(trim($p['optional3'])));
            $qu .= "optional3 = '".$optional3."', ";
        }

        if (isset($p['optional4'])) {
            $optional4 = addslashes(htmlspecialchars(trim($p['optional4'])));
            $qu .= "optional4 = '".$optional4."', ";
        }
        if (isset($p['optional5'])) {
            $optional5 = addslashes(htmlspecialchars(trim($p['optional5'])));
            $qu .= "optional5 = '".$optional5."', ";
        }
        if (isset($p['optional6'])) {
            $optional6 = addslashes(htmlspecialchars(trim($p['optional6'])));
            $qu .= "optional6 = '".$optional6."', ";
        }
        if (isset($p['optional7'])) {
            $optional7 = addslashes(htmlspecialchars(trim($p['optional7'])));
            $qu .= "optional7 = '".$optional7."', ";
        }*/

        /*
        $q = "SELECT COUNT(*) AS count FROM client_campaigns WHERE campaign_name = '".$campaign_name."'";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = "The client's campaign name already registered, please type another name.";//用户名重复
            return false;
        }
        */
        $pref_table  = 'campaign_keyword';
        $pref_field   = 'keyword_category';
        $pref_value = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        $q = "SELECT pref_id FROM preference ".
             "WHERE pref_table = '".$pref_table."' AND pref_field = '".$pref_field."' AND pref_value = '".$pref_value."'";
        $rs = $conn->Execute($q);
        $pref_id = 0;
        if ($rs) {
            $pref_id = $rs->fields['pref_id'];
            $rs->Close();
        }

        /*********strip duplicated check added by nancy xu 2010-01-13 11:00****************
        $q = "SELECT COUNT(*) AS count FROM campaign_keyword ".
             "WHERE keyword_id != '".$keyword_id."' AND keyword = '".$keyword."' AND article_type = '".$article_type."' ";
        $rs = $conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
        if ($count > 0) {
            $feedback = 'Have the same keyword in this campagin,Please try again';
            return false;
        }*************************************************/

        $q = "SELECT article_number FROM articles ".
             "WHERE keyword_id = '".$keyword_id."'";
        $rs = $conn->Execute($q);
        if ($rs) {
            $article_number = $rs->fields['article_number'];
            $rs->Close();
        }

        $article_number_arr = explode('-', $article_number);
        //print_r($article_number_arr);
        if ($article_number_arr[1] != ($article_type + 1)) {
            $article_number_arr[1] = $article_type + 1;
            $article_number = implode('-', $article_number_arr);
            $set_article_number = true;
            unset($article_number_arr);
            //echo $article_number;
        }

        $conn->StartTrans();
        if ($pref_id > 0) {
            //do nothing;
        } else {
            $pref_id = $conn->GenID('seq_preference_pref_id');
            $q = "INSERT INTO preference (pref_id, pref_table, pref_field, pref_value) ".
                 "VALUES ('".$pref_id."', '".$pref_table."', '".$pref_field."', '".$pref_value."')";
            $conn->Execute($q);
        }
        $conn->Execute("UPDATE campaign_keyword ".
                       "SET keyword = '".$keyword."', ".
                           "article_type = '".$article_type."', ".
                           "date_start = '".$date_start."', ".
                           "date_end = '".$date_end."', ". $qu . 
                           "keyword_category = '".$pref_id."' ".
                       "WHERE keyword_id = '".$keyword_id."' ");

        if ($set_article_number == true) {
            $conn->Execute("UPDATE articles ".
                           "SET article_number = '".$article_number."' ".
                           "WHERE keyword_id = '".$keyword_id."' ");
        }
        $ok = $conn->CompleteTrans();

        if ($ok) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }

    }//end setKeyword()

    //added by nancy xu 2012-05-07 9:32
    function denyKeyword($p = array(), $conditions = array() )
    {
        global $conn, $feedback, $g_decline_reason;
        extract($p);
        if (empty($keyword_id)) {
            $feedback = 'Invalid Keyword, please specify the keyword';
            return false;
        }
        if (empty($decline_reason)) {
            $feedback = 'Pease specify the decline reason';
            return false;
        }
        $reason_str = $g_decline_reason[$decline_reason];
        $sql = "SELECT ar.article_id, ar.keyword_id, aaf.id FROM articles AS ar ";
        $sql .= 'LEFT JOIN article_additional_fields AS aaf ON (aaf.article_id = ar.article_id) ';
        if (is_array($keyword_id)) {
            foreach ($keyword_id as $k => $v) {
                $keyword_id[$k] = addslashes(htmlspecialchars(trim($v)));
            }
            $sql .= ' WHERE ar.keyword_id IN ( ' . implode(',', $keyword_id) . ')';
        } else {
            $sql .= ' WHERE ar.keyword_id = ' . addslashes(htmlspecialchars(trim($keyword_id)));
        }
        $data = $conn->GetAll($sql);
        require_once CMS_INC_ROOT . '/article_additional_field.class.php';
        if (User::getRole() == 'copy writer') {
            $p['cp_status'] = 0;
            $pre_field = 'deny_';
        } else {
            $p['editor_status'] = 0;
            $pre_field = 'e_deny_';
        }
        foreach ($data as $row) {
            $data = array(
                $pre_field. 'option' => $decline_reason,
                $pre_field  . 'memo' => addslashes(htmlspecialchars(trim($reason_str))),
                'article_id' => $row['article_id'],
            );
            ArticleAdditionalField::store($data, $row['id']);
        }
        unset($p['decline_reason']);

        if (is_array($keyword_id)) {
            return self::batchUpdateKeyword($p);
        } else {
            return self::updateKeyword($p);
        }
    }

    function updateKeyword($p = array(), $conditions = array())
    {
        global $conn, $feedback;
        extract($p);
        if (empty($keyword_id)) {
            $feedback = 'Invalid Keyword, please specify the keyword';
            return false;
        }
        $sets = array();
        unset($p['keyword_id']);
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\'' .  addslashes($v) . '\'';
        }
        $where = ' WHERE keyword_id = \'' . $keyword_id . '\'';
        if (!empty($conditions)) {
            $where .= ' AND ' . implode( ' AND ', $conditions);
        }
        // modified by nancy xu 2012-08-05 18:23
        $result = array();
        /*if ((isset($p['cp_status']) && $p['cp_status'] <> -1) ||  (isset($p['editor_status']) && $p['editor_status'] <>-1)) {
            $result = self::generateDataToSendEmail($where, $p);
        } else {
            
        }*/
        // end
        $sql = 'UPDATE campaign_keyword SET ' . implode(',', $sets) . $where;
        $conn->Execute($sql);
        // added by nancy xu 2012-07-26 14:27
        // generate assigned denied notification
        if (isset($p['editor_status']) || isset($p['cp_status'])) {
            $p['keyword_id'] = $keyword_id;
            Campaign::generateNoteForDeny($p);
        }
        // end
       /*if (!empty($result)) {
            extract($result);
            if (!empty($editor_ids) && !empty($data)) {
                // send email to editor
                foreach ($data as $rows) {
                    self::sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $rows, false);
                }
            }
       }*/
       $feedback = 'Success!';
        return true;
    }

    function batchUpdateKeyword($p = array()) 
    {
        global $feedback, $conn;
        if (!isset($p['keyword_id']) || empty($p['keyword_id'])) {
            $feedback = 'Please choose keywords';
            return false;
        }
        if (is_array($p['keyword_id'])) {
            $keyword_ids = $p['keyword_id'];
        } else {
            $keyword_ids = array($p['keyword_id']);
        }
        unset($p['keyword_id']);
        $sets = array();
        foreach ($p as $k => $v) {
            $sets[] = $k . '=\''. addslashes($v) . '\'';
        }
        $where = ' WHERE keyword_id IN( \'' . implode("','", $keyword_ids) . '\')';
        // we didn't send email to editor/copy writer again.
        // $result = self::generateDataToSendEmail($where, $p);
        $sql = 'UPDATE campaign_keyword SET ' . implode(',', $sets) . $where;
        $conn->Execute($sql);
        // added by nancy xu 2012-07-26 14:27
        // generate assigned denied notification
        if (isset($p['editor_status']) || isset($p['cp_status'])) {
            $p['keyword_id'] = $keyword_ids;
            Campaign::generateNoteForDeny($p);
        }
        // end
        /*extract($result);
        if (!empty($result) && !empty($editor_ids) && !empty($data)) {
            // send email to editor
            foreach ($data as $rows) {
                self::sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $rows, false);
            }
        }*/
        $feedback = 'Success!';
        return true;
    }
    // added by nancy xu 2012-07-26 
    /*
     * when editor/writer denied one keyword, notification will be generate and alert to project manager of the campaign
     */
    function generateNoteForDeny($p)
    {
        global $conn, $g_note_fields;
        $fields = $g_note_fields;

        $user_status = isset($p['editor_status']) ? $p['editor_status'] : (isset($p['cp_status']) ? $p['cp_status']:-1);
        
        if ($user_status == 0) {
            $role = isset($p['editor_status']) ? 'editor':(isset($p['cp_status']) ? 'copy writer':'');            
            if ($role) { 
                if (isset($p['keyword_id']) && !empty($p['keyword_id'])) {
                    require_once CMS_INC_ROOT . DS . 'Notification.class.php';
                    $keyword_id = $p['keyword_id'];
                    if (is_array($keyword_id)) {
                        foreach ($keyword_id as $k => $v) {
                            $keyword_id[$k] = addslashes($v);
                        }
                        $where  = " WHERE ck.keyword_id  IN ('" . implode("','", $keyword_id). '\')';
                    } else {
                        $where  = ' WHERE ck.keyword_id  = \'' . addslashes($keyword_id). '\'';
                    }
                    $permission = User::getPermission();
                    if ($permission == 1  || $permission == 3) {
                        $user_id = User::getID();
                        $role = User::getRole();
                        $user_name = User::getName();
                        $join_part = $select_part = '';

                    } else {
                        $join_part = 'LEFT JOIN users AS  u ON (' . ($role == 'editor' ? 'ck.editor_id' : 'ck.copy_writer_id') . '=u.user_id)';
                        $select_part = ', u.user_name ';
                    }
                    $sql = "SELECT ck.keyword, ck.keyword_id, cc.campaign_name, cc.campaign_id, pm.user_id as pm_id, pm.role AS pm_role " . $select_part;
                    $sql .= "FROM campaign_keyword AS ck ";
                    $sql .= "LEFT JOIN client_campaigns AS cc ON ck.campaign_id=cc.campaign_id ";
                    $sql .= "LEFT JOIN client AS cl ON cl.client_id=cc.client_id ";
                    $sql .= "LEFT JOIN users AS pm ON pm.user_id=cl.project_manager_id ";
                    $sql .= $join_part . $where;
                    $result = $conn->GetAll($sql);
                    $date = date('Y-m-d H:i:s');
                    foreach ($result as $k => $row) {
                        if ($permission <> 1  && $permission <> 3) {
                            $user_name = $row['user_name'];
                        }
                        $campaign_id = $row['campaign_id'];
                        $keyword_id = $row['keyword_id'];
                        $keyword_link = '<a href="/client_campaign/assign_keyword.php?keyword_id=' . $keyword_id . '&frm=acceptance" target="_blank">' . $row['keyword'] . '</a>';
                        $campaign_link = '<a href="/article/acceptance.php?campaign_id=' . $campaign_id . '" target="_blank">' .  $row['campaign_name'] . '</a>';
                        $hash = array(
                            'keyword_id' => $keyword_id,
                            'campaign_id' => $campaign_id,
                            'campaign_name' => $row['campaign_name'],
                            'user_id' => $row['pm_id'],
                            'role' => $row['pm_role'],
                            'total' => 1,
                            'generate_date' => $date,
                            'field_name' => 'assigned_denied',
                            'notes' =>  sprintf($fields['assigned_denied'], $keyword_link, $campaign_link,$user_name),
                        );
                        Notification::save($hash);
                        require_once CMS_INC_ROOT.'/Email.class.php';
                        if (!isset($row['user_name'])) $row['user_name'] = $user_name;
//                        Email::sendAnnouceMail(40, 'nxu@copypress.com', $row);
                        Email::sendAnnouceMail(40, 'mliriano@copypress.com', $row);
                        //Email::sendAnnouceMail(40, 'kzipp@copypress.com', $row);
                    }
                }
            }
        }
    }// added

    function generateDataToSendEmail($where, $p)
    {
        global $conn;
        if ((!user_is_loggedin() || User::getPermission() ==1) && $p['cp_status'] == 1) {
            $sql = "SELECT keyword_id, keyword, " . (!empty($opt_fields) ? (implode(',', $opt_fields) .','):'') . "date_assigned,  editor_id, campaign_id, copy_writer_id , date_end FROM campaign_keyword  " . $where  ;
            $result = $conn->GetAll($sql);
            $campaign_ids = $editor_ids = $cp_ids = $data = array();
            foreach ($result as $k => $row) {
                extract($row);
                if (!isset($data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id])) {
                    $sql = 'SELECT COUNT(keyword_id) FROM campaign_keyword '
                    . 'WHERE campaign_id= ' . $campaign_id 
                    . ' AND editor_id = ' . $editor_id
                    . ' AND cp_status = 1 '
                    . ' AND date_assigned=\''. $date_assigned. '\'';
                    $count = $conn->GetOne($sql);
                    if ($count == 0) {
                        if (!isset($data[$date_assigned])) $data[$date_assigned] = array();
                        if (!isset($data[$date_assigned][$campaign_id])) $data[$date_assigned][$campaign_id] = array();
                        if (!isset($data[$date_assigned][$campaign_id][$editor_id])) $data[$date_assigned][$campaign_id][$editor_id] = array();
                        if (!isset($data[$date_assigned][$campaign_id][$editor_id][$date_end])) $data[$date_assigned][$campaign_id][$editor_id][$date_end] = array();
                        if (!isset($data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id])) $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id] = array();
                        $editor_ids[] = $editor_id;
                        $campaign_ids[$campaign_id][$date_end] = $date_end;
                        $cp_ids[] = $copy_writer_id;
                    }
                }
                if (isset($data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id])) {
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['keywords'][$keyword_id] = $keyword;
                    // added by nancy xu 2012-08-02 18:20
                    foreach ($row as $kk => $vv) {
                        if (substr($kk, 0,8) == 'optional') {
                            $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id][$kk][$keyword_id]=$vv;
                        }
                    } //end
                    /*$data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional1'][$keyword_id] = $optional1;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional2'][$keyword_id] = $optional2;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional3'][$keyword_id] = $optional3;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional4'][$keyword_id] = $optional4;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional5'][$keyword_id] = $optional5;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional6'][$keyword_id] = $optional6;
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional7'][$keyword_id] = $optional7;*/
                    $data[$date_assigned][$campaign_id][$editor_id][$date_end][$copy_writer_id]['dateend'][$keyword_id] = $date_end;
                }
            }
            return compact('data', 'editor_ids', 'cp_ids', 'campaign_ids');
        }
    }
    // end

    //added by nancy xu 2012-05-02 17:15
    function getAssignedKeywords($p = array())
    {
        global $g_pager_params, $conn, $g_assign_interval;
        if (user_is_loggedin()) {
            $role = User::getRole();
            $user_id = User::getID();
        } else {
            $role = 'client';
        }
        $conditions = array('ck.copy_writer_id > 0 ', 'ck.editor_id > 0');
        if ($role == 'editor' || $role == 'copy writer') {
            //$conditions[] = '(ck.editor_status = -1 OR ck.cp_status = -1)';
        }
        if ($role == 'copy writer') {
            $conditions[] = 'ar.article_status=0 AND ck.cp_status <> 0';
        } else {
            $conditions[] = "ar.article_status IN ('0','1','1gc', '1gd', '2', '3') ";
        }
        $now = time() ;
        $over_date = date("Y-m-d H:i:s", ($now - $g_assign_interval * 3600));
        if (isset($p['editor_status']) && strlen($p['editor_status'])) {
            if ($p['editor_status'] <> -2) $conditions[] = 'ck.editor_status=' . $p['editor_status'];
            else $conditions[] = '(ck.editor_status=-1 AND ck.cp_accept_time > 0 AND ck.cp_accept_time <=\'' .$over_date. '\')';
            //else 
        }
        if (isset($p['cp_status']) && strlen($p['cp_status'])) {
            if ($p['cp_status'] <> -2)  $conditions[] = 'ck.cp_status=' . $p['cp_status'];
            else $conditions[] = "(ck.cp_status=-1 AND ck.date_assigned<= '" . $over_date. "')";
        }
        if ($role == 'copy writer') {
            $conditions[] = "(ck.cp_status=-1 AND ck.date_assigned >  '" . $over_date. "' || ck.cp_status<>-1)";
        }
        if (isset($p['copy_writer_id']) && strlen($p['copy_writer_id'])) {
            $conditions[] = 'ck.copy_writer_id=' . $p['copy_writer_id'];
        }

        if (isset($p['campaign_id']) && strlen($p['campaign_id'])) {
            $conditions[] = 'ck.campaign_id=' . $p['campaign_id'];
        }

        if (isset($p['editor_id']) && strlen($p['editor_id'])) {
            $conditions[] = 'ck.editor_id=' . $p['editor_id'];
        }
        if ($role == 'editor') {
            $conditions[] = 'ck.editor_id=' . $user_id;
            //$conditions[] = 'ck.cp_status=1';
        }else if ($role == 'copy writer') {
            $conditions[] = 'ck.copy_writer_id=' . $user_id;
        }
        if (isset($p['article_status']) && strlen($p['article_status'])) {
            $conditions[] = 'ar.article_status=' . $p['article_status'];
        }
        $where = !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $from_q = ' FROM campaign_keyword AS ck ';
        $from_q .= 'LEFT JOIN articles AS ar ON ar.keyword_id = ck.keyword_id ' ;
        
        $count_q = 'SELECT COUNT(ck.keyword_id) ' . $from_q .  $where;
        $count = $conn->GetOne($count_q);
        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }
        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        list($from, $to) = $pager->getOffsetByPageId();
        $q =" SELECT `cc`.`campaign_name`, ck.editor_status, ck.cp_status, ck.editor_id, ck.copy_writer_id, ck.keyword, ar.article_number, ar.article_status, ck.article_type,ck.keyword_id, ck.campaign_id, ar.article_id, ck.date_start, ck.date_end, ck.date_assigned, ck.cp_accept_time , aaf.deny_memo, aaf.e_deny_memo, CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name, ue.user_name AS ue_name,  cp.user_name AS cp_name, CONCAT(cp.first_name, ' ', cp.last_name) AS cp_name " 
            . $from_q
            . 'LEFT JOIN  article_additional_fields AS aaf ON (aaf.article_id = ar.article_id) '
            . 'LEFT JOIN users AS ue ON (ue.user_id = ck.editor_id) '
            . 'LEFT JOIN users AS cp ON (cp.user_id = ck.copy_writer_id) '
            . 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) '
            . $where . ' ORDER BY `ck`.date_assigned DESC '
            ;
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        $result  = array();
        
        $show_cb = false;
        $keyword_ids = array();
        if ($rs) {
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $assigned_time = strtotime($fields['date_assigned']);
                if ($fields['cp_status'] == -1) { 
                    $cp_interval = ($now - $assigned_time)/3600;
                    $old_cp_status = $fields['cp_status'];
                    if ($cp_interval >= $g_assign_interval) {
                        $fields['cp_status'] = -2;
                        if ($role == 'copy writer') {
                            $rs->MoveNext();
                            continue;
                        }
                    }
                }
                if ($fields['editor_status'] == -1) { 
                    $cp_accept_time = $fields['cp_accept_time'];
                    $interval = $cp_accept_time > 0 ?  (($now - strtotime($cp_accept_time))/3600) :($old_cp_status == -1 &&$fields['cp_status'] == 0 ? $cp_interval:0);
                   // if (empty($interval) && $fields['cp_status'] == 0) $interval = $cp_interval;
                    if ($interval >= $g_assign_interval) {
                        $fields['editor_status'] = -2;
                    }
                }
                if (($role == 'admin' || $role == 'project manager') && ($fields['cp_status']== 0 || $fields['editor_status'] == 0 || $fields['editor_status'] == -2 || $fields['cp_status'] == -2)|| $role == 'editor'  && $fields['editor_status'] == -1   || $role == 'copy writer' && $fields['cp_status'] == -1 ){ // && $fields['cp_status'] == 1
                    if (!$show_cb ) $show_cb = true;
                    $fields['show_cb'] = true;
                }
                $keyword_ids[] = $fields['keyword_id'];
                $result[] = $fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        $result = self::getNotesByKeywords($result, $keyword_ids);
        return array('pager'  => $pager->links,
             'total'  => $pager->numPages(),
             'result' => $result,
             'count' => $count,
             'show_cb' => $show_cb,
        );
    }
    // end

    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function searchKeyword($p = array(), $show_kd_groupby_topic = false)
    {
        global $conn, $feedback, $g_archived_month_time;

        global $g_pager_params, $g_assign_interval;
         
        $show_cb = false;

        $q = "";
        // added by snug xu 2006-11-24 20:34
        // if login user is agency, it is not allowed that he/she see the other keyword
        if (User::getRole() == 'agency' )
        {
            $q .= "\n AND cl.agency_id = '" . User::getID() . "'";
        }
        // added by nancy xu 2010-06-04 13:43
        /*$archived = isset($p['archived']) ? $p['archived'] : 0;
        $approval_date = date('Y-m-d H:i:s', $g_archived_month_time);
        if ($archived == 1) {
            $q .= "\n AND " . '(ar.article_status = 5 || ar.article_status = 6) && ar.client_approval_date < \'' . $approval_date. '\'';
        } else {
            $q .= "\n AND " . ' ((ar.article_status != 5 &&  ar.article_status != 6) || ((ar.article_status = 5 || ar.article_status = 6) && ar.client_approval_date >=  \'' . $approval_date. '\')) ';
        }*/
        // end
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            if (is_numeric($campaign_id)) {
                $q .= "\nAND ck.campaign_id =  '".$campaign_id."' ";
            } else {
                $q .= "\nAND ck.campaign_id IN ( ".$campaign_id." ) ";
            }
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "\nAND ck.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        $qaer_id = addslashes(htmlspecialchars(trim($p['qaer_id'])));
        if ($qaer_id != '') {
            $q .= "\nAND ck.qaer_id = '".$qaer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "\nAND ck.creation_user_id = '".$creation_user_id."' ";
        }
        /*
        $keyword = addslashes(htmlspecialchars(trim($p['keyword'])));
        if ($keyword != '') {
            $q .= "AND ck.keyword LIKE '%".$keyword."%' ";
        }
        */

        $subcid = addslashes(htmlspecialchars(trim($p['subcid'])));
        if ($subcid != '') {
            $q .= "AND ck.subcid LIKE '%".$subcid."%' ";
        }

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "\nAND ck.article_type = '".$article_type."' ";
            //$q .= "\nAND at.parent_id = '".$article_type."' ";
        }
        $keyword_category = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        if ($keyword_category != '') {
            $q .= "\nAND ck.keyword_category = '".$keyword_category."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "\nAND ck.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "\nAND ck.date_end <= '".$date_end."' ";
        }

        if (isset($p['submit_date_start']) && !empty($p['submit_date_start'])) {
            $submit_date_start = $p['submit_date_start'];
            $q .= "\nAND ar.cp_updated >= '".$submit_date_start."' ";
        }

        if (isset($p['submit_date_end']) && !empty($p['submit_date_end'])) {
            $submit_date_end = $p['submit_date_end'];
            $q .= "\nAND ar.cp_updated <= '".$submit_date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "\nAND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }
        
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else
        {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }

        $is_client_ready = addslashes(htmlspecialchars(trim($p['is_client_ready'])));
        if ($is_client_ready != '') {
            $q .= "AND ar.is_client_ready = '".$is_client_ready."' ";
        }

        $noflow_status = addslashes(htmlspecialchars(trim($p['noflow_status'])));
        if ($noflow_status != '') {
            $q .= "AND ar.noflow_status = '".$noflow_status."' ";
        }

		//START: ADDED By Snug 16:02 2006-8-17
		$is_today = addslashes(htmlspecialchars(trim($p['is_today'])));
		if ($is_today ==1 ) 
		{
			$q .= "\n AND DATEDIFF( ar.approval_date, CURDATE( ) ) =0";
        }
		//END ADDED
        
		//Start: Added By Snug 22:41 2006-08-13
		$is_pay_adjust = addslashes(htmlspecialchars(trim($p['is_pay_adjust'])));
        
        $sql_left = "\nLEFT JOIN article_action AS aa ON (aa.article_id = ar.article_id  AND aa.status=1 AND aa.new_status='1gc' AND aa.curr_flag=1)";
        $sql_left .= "\nLEFT JOIN article_payment_log AS apl ON (apl.article_id = ar.article_id AND ck.copy_writer_id = apl.user_id) ";
        $sql_left .= "\nLEFT JOIN article_type AS at ON at.type_id = ck.article_type ";
        $sql_left .= "\nLEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type)  ";
        $sql_left .= "\nLEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)  ";
        $sqls = array(
            'where' => '', 
            'left' => $sql_left
         );
        if ($is_pay_adjust == 1) 
		{
			$current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
            // modified by snug 2007-05-13 10:35
			if( strlen($current_month) == 0 )
			{
				$now		   = time();
				$current_month = changeTimeToPayMonthFormat($now);
			}
			else
			{
				$now = changeTimeFormatToTimestamp($current_month);
			}
            $param['now']     = $now;
            $param['user_id'] = $p['copy_writer_id'];
            $param['forced_adjust'] = isset($p['forced_adjust']) ? $p['forced_adjust'] : 0;
            $forced_adjust =  $param['forced_adjust'];
            $param['include_google_clean'] = isset($p['include_google_clean']) ? $p['include_google_clean'] : 0;
            $include_google_clean =  $param['include_google_clean'];
            $param['include_editor_approval'] = isset($p['include_editor_approval']) ? $p['include_editor_approval'] : 0;
            $include_editor_approval =  $param['include_editor_approval'];
            $param['show_current_month'] = $p['show_current_month'];
            $param['type']     = 'keyword-adjust';
            $sqls = User::getCPAccountingConditionOrSql($param);
            if (!empty($sqls['where'])) $sqls['where'] = ' AND  ' . $sqls['where'];

            $next_month = nextPayMonth($current_month);
            $show_current_month = mysql_escape_string(htmlspecialchars(trim($p['show_current_month'])));
		}
		//End Added

        //$q .= "AND (cc.permission < '".User::getPermission()."' OR cc.user_id = '".User::getID()."') ";
        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                //$q .= "AND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description, uc.user_name, ue.user_name, cu.user_name)")." ";
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description, ar.article_number, ar.title)")." ";
            }
        }
        //$q .= "AND cl.client_id = cc.client_id ";
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "\nAND cc.client_id = '".Client::getID()."' ";
        } else {
            $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
            if ($client_id > 0) {
                $q .= "\nAND cc.client_id = '". $client_id ."' ";
            }
        }
        
        $where = "\nWHERE 1 {$sqls['where']} {$q} AND ck.status!='D' ";
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ".
                "\nFROM campaign_keyword AS ck ".
                "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                $sqls['left'] . 
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
                $where;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        if ($show_kd_groupby_topic == true) {

        }

        $cp_users = User::getCpPaymentHistory(array(), false);
        $cost_field = 'ac.cp_article_cost AS ac_article_cost, at.cp_article_cost AS at_article_cost, ac.cp_cost AS ac_word_cost, at.cp_cost AS at_word_cost,';
        $e_cost_field = 'ac.editor_article_cost AS ac_e_article_cost, at.editor_article_cost AS at_e_article_cost, ac.editor_cost AS ac_e_word_cost, at.editor_cost AS at_e_word_cost,';

        $q = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `cc`.`campaign_name`, `ck`.`copy_writer_id`, \n". 
            "`ck`.`editor_id`, `ck`.`keyword`, `ck`.`article_type`,  `ck`.`credential_id`,`ck`.`keyword_description`, `ck`.`date_assigned`, \n" . 
            "`ck`.`date_start`, `ck`.`date_end`, `ck`.`creation_user_id`, `ck`.`creation_role`, `ck`.`cp_status`,`ck`.`cp_accept_time`, `ck`.`editor_status`,ck.subcid,  ar.delivered_date, \n" .
            "`ck`.`keyword_category`, `ck`.`status`, `ck`.`cost_per_article`,ck.qaer_id, at.parent_id ,`ck`.`is_sent`,`ar`.`cp_updated`, aei.qa_complete,\n" . 
            "ar.article_id, ar.article_number, ar.approval_date,ar.client_approval_date,  MAX(aa.created_time) AS google_approved_time , \n" . 
            "ar.target_pay_month, ar.is_canceled, apl.log_id, apl.month as apl_month, apl.pay_month, apl.paid_time, ar.curr_dl_time, \n" .
            "ar.article_status, ar.checking_url,  cl.user_name, cl.company_name, cc.campaign_name, ar.total_words AS word_count , \n" . 
            "at.pay_by_article AS at_checked, ac.pay_by_article AS ac_checked, ach.pay_by_article AS ach_checked, \n" . 
            "{$cost_field} ach.cost_per_article AS ach_type_cost, \n" . 
            "{$e_cost_field}ach_e.pay_by_article AS ach_e_checked, ach_e.cost_per_article AS ach_e_type_cost, \n" . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name, uc.pay_level, CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name , CONCAT(uq.first_name, ' ', uq.last_name) AS uq_name, cu.user_name AS cu_name, ar.body, ar.richtext_body, ar.is_client_ready, ar.noflow_status  \n" . 
             "FROM campaign_keyword AS ck \n".
             "LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
            "\nLEFT JOIN `article_extra_info` AS aei ON (aei.article_id = ar.article_id) " . 
             $sqls['left'] .
             "\nLEFT JOIN article_payment_log AS apl_e ON (apl_e.article_id = ar.article_id AND ck.editor_id = apl_e.user_id) \n".
             "LEFT JOIN `article_cost_history` AS ach_e ON (ach_e.campaign_id = ck.campaign_id AND ach_e.article_type=ck.article_type AND ach_e.user_id=apl_e.user_id AND ach_e.month=apl_e.pay_month)   \n".
             "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) \n".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) \n".
             "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) \n".
             "LEFT JOIN users AS uq ON (ck.qaer_id = uq.user_id) \n".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) \n".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id)".
             $where  ;
        $q .= "\nGROUP BY ar.article_id";
        $q .= "\nORDER BY ck.keyword_id DESC, google_approved_time  ";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        $keyword_ids = array();
        $permission = User::getPermission();
        $now_time = time();
        $role = User::getRole();
        if ($rs) {
            $result = array();
            $kb = array();
            $i = 0;
            $show_assign_cb = $show_deliver = false;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                // added by nancy xu 2011-05-30 13:40
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                // end
                if (empty($fields['qa_complete'])) $fields['qa_complete'] = 0;
                $word_count = $fields['word_count'] ;
                $fields['cost_per_article'] = $cost_per_unit;
                $fields['cost_for_article'] = ($checked == 0) ? $word_count* $cost_per_unit : $cost_per_unit;
                if ($permission == 5) {
                    $tmp = getCostAndPayType($fields, 'e_');
                    extract($tmp);
                    $editor_cost = ($checked == 0) ? $word_count* $cost_per_unit : $cost_per_unit;
                    $fields['cost_per_article'] += $cost_per_unit;
                    $fields['cost_for_article'] += $editor_cost;
                }

                // added by nancy xu 2012-05-08 13:25
                $fields['show_assign_cb'] = ($fields['cp_status'] == 1 && $fields['editor_status']== 1) ? false : true;
                if ($fields['article_status'] == 0 && ($fields['cp_status'] == -1 || $fields['editor_status'] ==-1)&& $fields['editor_id'] > 0 && $fields['copy_writer_id'] > 0 && $fields['show_assign_cb']) {
                    $cp_accept_time = $fields['cp_accept_time'];
                    $interval = ($now_time - strtotime($fields['date_assigned']))/3600;
                    if ($interval <= $g_assign_interval) {
                        $fields['show_assign_cb'] = false;
                    } else if ($cp_accept_time) {
                        $interval = ($now_time - strtotime($cp_accept_time))/3600;
                        if ($interval <= $g_assign_interval) {
                            $fields['show_assign_cb'] = false;
                        }
                    }
                }              
                if (!$show_assign_cb && $fields['show_assign_cb']) $show_assign_cb = true;
                // end

                $result[$i] = $fields;

                // added by snug xu 2007-03-14 9:09  - STARTED
                if (strcasecmp($rs->fields['article_status'], '1gc') == 0 || $rs->fields['article_status'] == '3') {
                    $show_cb = true;
                }
				if ($show_kd_groupby_topic == true) {
                    if ($rs->fields['keyword_description'] != '') {
                        $kb[$rs->fields['keyword_id']] = $rs->fields['keyword_description'];
                    }
                }
                if (empty($rs->fields['delivered_date']) && !$show_deliver) {
                    $show_deliver = true;
                }
                $keyword_ids[] = $rs->fields['keyword_id'];
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
		//Start:Added By Snug 23:02 2006-08-15
		/***get notes by keyword_id***/
        $result = self::getNotesByKeywords($result, $keyword_ids);
		//END ADDED

        if ($show_kd_groupby_topic == true) {
            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'kb'     => $kb,
                         'count'     => $count,
                         'show_deliver'     => $show_deliver,
                         'show_assign_cb'     => $show_assign_cb,
                         'result' => $result);
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     'show_deliver' => $show_deliver,
                     'show_assign_cb' => $show_assign_cb, 
                     'show_cb' => $show_cb
                     );

    }//end searchKeyword()

    function getNotesByKeywords($result, $keyword_ids)
    {
        if (!empty($keyword_ids)) {
            sort($keyword_ids);
            require_once CMS_INC_ROOT.'/Notes.class.php';
            $notes = Notes::getNotesInfoByKeywordID($keyword_ids);
            foreach($result as $k => $v)
            {
                $keyword_id = $v['keyword_id'];
                if ($keyword_id > 0) {
                    $note = $notes[$keyword_id];
                    $result[$k]['note_id']=$note['note_id'];
                    $result[$k]['notes']=$note['notes'];        	
                }
            }
        }
        return $result;
    }

    function searchUnusedKeyword($p = array())
    {
        global $conn, $feedback;
        global $g_pager_params;
        $conditions = array('((ar.article_status=0 AND ck.copy_writer_id=0 AND ck.status=\'A\') OR ck.status=\'D\')' . "\n");
        foreach ($p as $k => $v) {
            $p[$k] = trim($v);
        }
        if (isset($p['campaign_id'])) {
            $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
            $conditions[] = "ck.campaign_id='" . $campaign_id . "'\n";
        }

        if ($p['keyword'] != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $conditions[] = $search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)\n")." ";
            }
        }

        if (isset($p['article_status']) && !empty($p['article_status'])) {
            $conditions[] = "ar.article_status='" . $p['article_status'] . "'";
        }

        if (isset($p['article_type']) && strlen($p['article_type'])) {
            $conditions[] = "ck.article_type='" . $p['article_type'] . "'";
        }

        $from_table = "\nFROM campaign_keyword AS ck ";
        $left_join  = "\nLEFT JOIN articles AS ar ON ar.keyword_id=ck.keyword_id ";
        $left_join .= "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ";
        $left_join .= "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ";
        $left_join .= "\nLEFT JOIN client_campaigns AS cc ON cc.campaign_id=ck.campaign_id ";
        $left_join .= "\nLEFT JOIN `client` AS cl ON cl.client_id=cc.client_id ";
        if (!empty($conditions)) {
            $where = "\n". 'WHERE ' . implode(" AND ", $conditions);
        } else {
            $where = '';
        }
        $sql = "SELECT COUNT(ck.keyword_id)  " . $from_table . $left_join . $where;
        $count = $conn->GetOne($sql);
        if (empty($count)) return false;
        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }
        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
	    list($from, $to) = $pager->getOffsetByPageId();
        $q = "SELECT ck.keyword, ck.keyword_id, ck.status, ck.date_start, ck.date_end, ck.article_type, ar.article_status, ar.total_words AS word_count, \nCONCAT(uc.first_name, ' ', uc.last_name) AS uc_name, CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name, cc.campaign_name, cc.campaign_id, cl.company_name, cl.client_id " . $from_table . $left_join . $where;
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        $result = array();
        if ($rs) {
            while(!$rs->EOF) {
                $result[] = $rs->fields;
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return array('pager'  => $pager->links,
         'total'  => $pager->numPages(),
         'result' => $result,
         'count' => $count,
         );
    }


    function keywordInfoByKeywordID($keyword_id, $fields = null)
    {
        global $conn;
        if (empty($fields)) $fields = array('ck.*', 'cc.campaign_name');
        $q = "SELECT  " . implode(",", $fields) .
             " FROM campaign_keyword AS ck ".
             "LEFT JOIN  client_campaigns AS cc ON (ck.campaign_id=cc.campaign_id) ".
             "WHERE ck.keyword_id = '".$keyword_id."'";
        return $conn->GetRow($q);
    }
	

    /**
     * Get client's info by $client_id
     *
     * @param int $client_id
     *
     * @return boolean or an array containing all fields in tbl.client
     */
    function getKeywordInfo($keyword_id)
    {
        global $conn, $feedback;
        $keyword_id = addslashes(htmlspecialchars(trim($keyword_id)));
        if ($keyword_id == '') {
            $feedback = "Please Choose a campaign keyword";
            return false;
        }

        $q = "SELECT ck.*, cc.campaign_name, cc.show_cp_bio, cc.style_guide_url, cc.meta_param, cc.title_param, cc.max_word, cc.pay_type, cl.client_id,  cl.user_name, cl.company_name, csg.style_id, uc.user_name AS uc_name, ue.user_name AS ue_name, cu.user_name AS pm_name ".
             "FROM campaign_keyword AS ck ".
             "LEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
             "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) ".
             "LEFT JOIN campaign_style_guide AS csg ON (csg.campaign_id = cc.campaign_id) ".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
             "WHERE ck.keyword_id = '".$keyword_id."' AND ck.status!='D' ";
        $rs = &$conn->Execute($q);

        if ($rs) {
            $ret = false;
            if ($rs->fields['keyword_id'] != 0) {
                $ret = $rs->fields; // return an array
            }
            $rs->Close();
            return $ret;
        }

        return false; // return false if client does not exist
    }//end getKeywordInfo()

    function assignKeyword($p = array())
    {
        global $conn, $feedback;
        $editor_keywords = $copy_writer_keywords = array();
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id == '')  {
            $feedback = "Please Choose a campaign keyword";
            return false;
        } else {
			$clause_where = " AND ar.keyword_id = {$keyword_id} ";
			$count = self::countArticleBySubWhere( '5|6|99', 2, '', $clause_where );
			if($count>0) {
				$feedback = "This  is a compeleted article. You can't reassign to other person";
				return false;
			}
		}
        $is_forced     = addslashes(htmlspecialchars(trim($p['is_forced'])));
        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id == '') {
            $feedback = "Please provide a copywriter";
            return false;
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id == '') {
            $feedback = "Please provide an editor";
            return false;
        }

        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start == '') {
            $feedback = "Please provide the start date of the campaign keyword";
            return false;
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end == '') {
            $feedback = "Please provide the Due Date of the campaign keyword";
            return false;
        }
        if (strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date, Please try again';
            return false;
        }
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type == '') {
            $feedback = "Please provide keyword article type";
            return false;
        }
		
        // added by snug xu 2007-03-06 14:13 - STARTED
        // get keyword and its article information by keyword_id
        // set keyword status as writing after reassignment
        $old_info = Article::getInfoByKeywordID($keyword_id);
        if ($old_info['article_status'] == '5') {
            $feedback = 'The article has completed!';
        	return false;
        }
        $is_no = ($copy_writer_id == 0 || $editor_id == 0) ? true : false;
        if ($copy_writer_id != $old_info['copy_writer_id'] || $editor_id != $old_info['editor_id'] ||isset($p['qaer_id']) && $p['qaer_id'] > 0 ) {
            if ($is_no && $old_info['article_status'] != '0') {
                $feedback = 'Article status is not writing, you can\'t reassign it to No Editor or No Copy Writer';
                return false;
            }
            // added by nancy xu 2010-07-14 13:47 - started
            // set start status
            if ($old_info['copy_writer_id'] == 0 && $copy_writer_id > 0) {
                Campaign::updateArticleStatus($old_info['article_id'], 'started');
            } else if ($copy_writer_id == 0 && $old_info['copy_writer_id']  > 0) {
                Campaign::updateArticleStatus($old_info['article_id'], 'started', 0);
            }
            // end
            // added by snug xu 2007-12-26 20:19 - started
            if (!$is_no && (!isset($p['is_forced_not_free']) || empty($p['is_forced_not_free']))) {
                $param = array(
                    'user_id' => $copy_writer_id,
                    'is_free' => 0,
                    '>=' => array('c_date' => $date_start),
                    '<=' => array('c_date' => $date_end),
                );
                $dates = UserCalendar::getListByParam($param);
                if (!empty($dates)) {
                    $feedback = 'The copywriter is not free in ' . implode(', ', $dates) . ".\\n please try again";
                    return false;
                }
                // added by nancy xu 2010-01-18 14:06
                if ($editor_id > 0) {
                    $param['user_id'] = $editor_id;
                    $dates = UserCalendar::getListByParam($param);
                    if (!empty($dates)) {
                        $feedback = 'The editor is not free in ' . implode(', ', $dates) . ".\\n please try again";
                        return false;
                    }
                }
                // end
                // added by nancy xu 2010-03-19 13:56
                if ($copy_writer_id != $old_info['copy_writer_id'] && $copy_writer_id > 0) {
                    require_once CMS_INC_ROOT . "/request_extension.class.php";
                    RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $old_info['campaign_id']));
                }
                // end
            }
            // end

            $is_reassigned = true;
            // modified by snug xu 2007-03-13 14:13 - STARTED
            // get keywords that articles.is_sent = 0 and 
            // cp_payment_history.payment_flow_status is 'cpc' or 'paid'
            if (!$is_no) {
                $allow_status = array('1gc', '3', '4');
                if (in_array($old_info['article_status'], $allow_status)) {
                    $month = changeTimeToPayMonthFormat(strtotime($old_info['google_approved_time']));
                    // get all cp payment report for check each keyword payment status
                    $email_keywords = array();
                    $pay_report = User::getCpPaymentHistory(array('user_id'=>$old_info['copy_writer_id'], 'month'=>$month), false); 
                    // get payment flow status
                    $payment_status = $pay_report[$old_info['copy_writer_id']][$month]['payment_flow_status'];
                    if ($payment_status == 'cpc' || $payment_status == 'paid') {
                       // modified by snug xu 2007-05-11 - STARTED
                       // if $is_foreced = true and new copy wirter is not equal to raw copy writer, 
                       // collect the abnormal reassignment keywords 
                       if ($is_forced || $copy_writer_id == $old_info['copy_writer_id']) {
                          if ($copy_writer_id != $old_info['copy_writer_id'] && $old_info['is_sent'] == 0)
                            $email_keywords[$old_info['editor_id']][$old_info['copy_writer_id']][] = $old_info;
                       } else {
                           $is_reassigned = false;
                           // added by snug xu 2007-05-11 13:59 - FINISHED
                           if ($payment_status == 'cpc') {
                                $feedback = "One article has been confirmed by copy writer.\\n";
                                $feedback .= "You can reassign those articles forcedly.(please click forced assign checkbox).";
                           } else if ($payment_status == 'paid') {
                                $feedback = "One article has been paid by Infinitenine.\\n";
                                $feedback .= "You can reassign those articles forcedly.(please click forced assign checkbox).";
                           }
                           // added by snug xu 2007-05-11 13:59 - started
                       }
                    }
                }
            }
            
            // added by snug xu 2007-03-13 13:05 - FINISHED
            if ($is_reassigned) {
                if ($old_info['article_id'] > 0 && !$is_no) {
                    // added nancy xu 2008-01-27 16:11 - STARTED
                    $p['article_id'] = $old_info['article_id'];
                    self::eRaseArticleInfo($p);
                    // added nancy xu 2008-01-27 16:11 - END
                    foreach ($old_info as $kk => $vv) {
                        $vv = trim($vv);
                        if ($kk == 'keyword' || substr($kk, 0,8) == 'optional') {
                            if ($kk == 'keyword') $kk = 'keywords';
                            if ($old_info['copy_writer_id'] != $copy_writer_id || $old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id) {
                                if ($old_info['copy_writer_id'] != $copy_writer_id) {
                                    $copy_writer_keywords[$kk][$keyword_id] = $vv;
                                }
                                $editor_keywords[$kk][$keyword_id] = $vv;
                            }
                        }
                    }
                    $keyword = $old_info['keyword'];
                    /*$optional1 = $old_info['optional1'];
                    $optional2 = $old_info['optional2'];
                    $optional3 = $old_info['optional3'];
                    $optional4 = $old_info['optional4'];
                    $optional5 = $old_info['optional5'];
                    $optional6 = $old_info['optional6'];
                    $optional7 = $old_info['optional7'];*/
                    if ($old_info['copy_writer_id'] != $copy_writer_id) { //modified by leo.
                        $ret = Article::setArticleStatus($old_info['article_id'], 0, $old_info['article_status'], $copy_writer_id);
                        
                        /*$copy_writer_keywords['keywords'][$keyword_id] = $keyword;
                        $copy_writer_keywords['optional1'][$keyword_id] = $optional1;
                        $copy_writer_keywords['optional2'][$keyword_id] = $optional2;
                        $copy_writer_keywords['optional3'][$keyword_id] = $optional3;
                        $copy_writer_keywords['optional4'][$keyword_id] = $optional4;
                        $copy_writer_keywords['optional5'][$keyword_id] = $optional5;
                        $copy_writer_keywords['optional6'][$keyword_id] = $optional6;
                        $copy_writer_keywords['optional7'][$keyword_id] = $optional7;
                        $editor_keywords['keywords'][$keyword_id] = $keyword;
                        $editor_keywords['optional1'][$keyword_id] = $optional1;
                        $editor_keywords['optional2'][$keyword_id] = $optional2;
                        $editor_keywords['optional3'][$keyword_id] = $optional3;
                        $editor_keywords['optional4'][$keyword_id] = $optional4;
                        $editor_keywords['optional5'][$keyword_id] = $optional5;
                        $editor_keywords['optional6'][$keyword_id] = $optional6;
                        $editor_keywords['optional7'][$keyword_id] = $optional7;*/
                        if ($old_info['is_sent'] == 1)
                            $qu .= " is_sent=0, ";
                    } else if ($old_info['is_sent'] == 0 && count($email_keywords)) {
                        // set is_sent as 1
                        $qu .= " is_sent=1, ";
                    }
                     /*if ($old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id) { //modified by nancy.
                        $editor_keywords['keywords'][$keyword_id] = $keyword;
                        $editor_keywords['optional1'][$keyword_id] = $optional1;
                        $editor_keywords['optional2'][$keyword_id] = $optional2;
                        $editor_keywords['optional3'][$keyword_id] = $optional3;
                        $editor_keywords['optional4'][$keyword_id] = $optional4;
                        $editor_keywords['optional5'][$keyword_id] = $optional5;
                        $editor_keywords['optional6'][$keyword_id] = $optional6;
                        $editor_keywords['optional7'][$keyword_id] = $optional7;
                     }*/
                }
                if ($keyword_id > 0) {
                    // modified by nancy xu 2012-05-09 15:02
                    $date_assigned = date("Y-m-d H:i:s");
                    $qset = '';
                    if (in_array($old_info['article_status'], array('0','1','1gd','1gc','2','3'))) {
                        if ($old_info['copy_writer_id'] != $copy_writer_id) {
                            $qset .= ", `cp_accept_time` = null, `cp_status`=-1 ";
                        } else if ($old_info['cp_status']) {
                            $qset .= ", `cp_accept_time` = '{$date_assigned}' ";
                        }
                        if ($editor_id != $old_info['editor_id']) {
                            $qset .= " , `editor_status`=-1 " ;
                        }
                        //$qset .= " , `editor_status`=1 " ;
                    }
                    if (!$is_no) $qset .= ", `date_assigned` = '{$date_assigned}' ";
                    // added by nancy xu 2014-05-06 0:06
                    if (isset($p['qaer_id']) && $p['qaer_id'] > 0) {
                        $qaer_id = addslashes(htmlspecialchars(trim($p['qaer_id'])));
                        $qset .= ", `qaer_id` = '{$qaer_id}' ";
                    }
                    // end
                    // end 
                    $conn->Execute("UPDATE campaign_keyword ".
                    "SET copy_writer_id = '".$copy_writer_id."', ". $qu . 
                       "editor_id = '".$editor_id."', ".
                       "article_type = '".$article_type."', ".
                       "date_start = '".$date_start."', ".
                       "date_end = '".$date_end. "'" . 
                        $qset .
                       " WHERE keyword_id = '".$keyword_id."' ");
                }
            }
            $affected_rows = $conn->Affected_Rows();
                
            if ($affected_rows == 1) {
                if (!$is_no) {
                    if (count($copy_writer_keywords) || count($editor_keywords)) {
                        // sent email to cp and editor
                        //self::sendAssignKeywordMail($copy_writer_id, $editor_id, $date_end, $copy_writer_keywords, $editor_keywords);
                        if ($old_info['copy_writer_id'] != $copy_writer_id)  self::sendAssignMail($copy_writer_id, $copy_writer_id, $date_end, $copy_writer_keywords);
                        if ($old_info['editor_id'] != $editor_id)  self::sendAssignMail($editor_id, $copy_writer_id, $date_end, $editor_keywords, false);
                    }
                    if (count($email_keywords)) {
                        // send email to tara and tony
                        self::sendAccountingChangeEmail($email_keywords, $copy_writer_id, $editor_id);
                    }
                }
                $feedback = 'Success';
                return true;
            } else {
                if ($is_reassigned)
            	    $feedback = 'Failure, Please try again';
                return false;
            }
        }
        $feedback = 'Success';
        return true;
        // modified by snug xu 2007-03-13 14:13 - FINISHED 
    }//end assignKeyword()
    
    // added by nancy xu 2008-01-27 14:28 - STARTED    
    function eRaseArticleInfo($p = array())
    {
        global $conn, $feedback, $handle;
        
        $action_info = array();
        //global $g_tag;
        // if togle $p['is_reserve_content'], reserve the article content, else erase the article conetent
        $is_reserve_content = isset($p['is_reserve_content']) ? $p['is_reserve_content'] : 0;

        $article_id = addslashes(htmlspecialchars(trim($p['article_id'])));
        if ($article_id == '') {
            $feedback = "Please Choose a article";
            return false;
        }

        if (empty($is_reserve_content))
        {
            $ar_qu = "language = '', ";
            $ar_qu .= "title = '', ";
            $ar_qu .= "html_title = '', ";
            $ar_qu .= "body = '', ";
            $ar_qu .= "richtext_body = '' ";
            $sql = "UPDATE articles SET ";
            $sql .= $ar_qu;
            $sql .= "WHERE article_id = '{$article_id}'";
            $conn->Execute($sql);
        }
        return true;
    }
    // added by nancy xu 2008-01-27 14:28 - END

    /**
    * Batch assign keyword to editor and copywriters
    *
    * We can change article type,editor,copywriter that they are opposite the keyword 
    *
    * @param array $p 
    * @return boolean if success return true，else return false
    */
    function batchAssignKeyword($p = array()) 
    {
        global $conn, $feedback;
        require_once CMS_INC_ROOT . "/request_extension.class.php";
        if (empty($p['keyword_id'])) {
            $feedback = 'Please provide at least one keyword';
            return false;
        }
        $is_forced = trim($p['is_forced']);
        if (empty($p['keyword_id']))  {
            $feedback = "Please choose one keyword.";
            return false;
        } else {
			$keywords = implode("','", $p['keyword_id'] );
			$keywords = trim($keywords, "','" );
			$clause_where = " AND ar.keyword_id IN ('". $keywords. "')";
			$count = self::countArticleBySubWhere( '5|6|99', 2, '', $clause_where );
			if ($count >0 ) {
				$feedback = "You choose the completed articles. Please to check.";
				return false;
			}
		}
        $qu = "";
        
        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $no_date_end = $no_date_start = $no_cp = $no_editor = false;
        if ($copy_writer_id != '') {
            $qu .= "copy_writer_id = '".$copy_writer_id."', ";
        } else {
            $no_cp = true;
        }
        if (isset($p['qaer_id'])) {
            $no_qaer=false;
            $qaer_id = addslashes(htmlspecialchars(trim($p['qaer_id'])));
            if ($qaer_id != '') {
                $qu .= "qaer_id = '".$qaer_id."', ";
            } else {
                $no_qaer = true;
            }
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $qu .= "editor_id = '".$editor_id."', ";
        } else {
            $no_editor = true;
        }

        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));

        if ($editor_id == '' && $copy_writer_id == '' && $date_start == '' && $date_end == '') {
            $feedback = 'Please choose a copywriter or a editor for keyword';
            return false;
        }

        // added by nancy xu 2011-7-29 15:15
        // check if is no editor or no copy writer
        $is_no = (strlen($editor_id) && $editor_id == 0 || strlen($copy_writer_id) && $copy_writer_id == 0) ? true : false;
        // end
        
        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $qu .= "article_type = '".$article_type."', ";
        }
        
        if (!empty($date_end) && !empty($date_start) && strtotime($date_end) < strtotime($date_start)) {
            $feedback = 'Incorrect date,Please try again';
            return false;
        }

        if (!empty($date_end)) {
            $qu .= "date_end = '".$date_end."', ";
        } else {
           $no_date_end = true;
        }
        if (!empty($date_start)) {
            $qu .= "date_start = '".$date_start."', ";
        } else {
            $no_date_start = true;
        }

		$keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
		if ($keyword_description != '') {
			if (trim($p['new_or_append']) == 'Append') {
				$qu .= "keyword_description = CONCAT(`keyword_description`, '".$keyword_description."'), ";
			} elseif (trim($p['new_or_append']) == 'New') {
				$qu .= "keyword_description = '".$keyword_description."', ";
			} else {
				//do nothing;
			}
		}

		$notes = mysql_escape_string(htmlspecialchars(trim($p['notes'])));
		if( strlen($notes) ) {
			if (trim($p['new_or_append']) == 'Append')  {
				$en .= "notes = CONCAT_WS('\n', `notes`, '".$notes."'), ";
			} elseif (trim($p['new_or_append']) == 'New')  {
				$en .= "notes = '".$notes."', ";
			}  else {
				//do nothing;
			}
		}
		$campaign_id = mysql_escape_string(htmlspecialchars(trim($p['campaign_id'])));
		if( $campaign_id>0 ) {
			$en .= "campaign_id={$campaign_id}, ";
		} else {
			$feedback = 'There is no campaign';
			return false;
		}

        		    
        if (!isset($p['is_forced_not_free']) || empty($p['is_forced_not_free'])) {
            $param = array(
                'user_id' => $copy_writer_id,
                'is_free' => 0,
                '>=' => array('c_date' => $date_start),
                '<=' => array('c_date' => $date_end),
            );
            $dates = UserCalendar::getListByParam($param);
            if (!empty($dates)) {
                $feedback = 'The copywriter is not free in ' . implode(', ', $dates) . ".\\n please try again";
                return false;
            }
            // added by nancy xu 2010-01-18 13:53
            // check whether editor is free or not
            if ($editor_id > 0) {
                $param['user_id'] = $editor_id;
                $dates = UserCalendar::getListByParam($param);
                if (!empty($dates)) {
                    $feedback = 'The editor is not free in ' . implode(', ', $dates) . ".\\n please try again";
                    return false;
                }
            }
            // end
        }

        // get all cp payment report for check each keyword payment status
        $email_keywords = array();
        $pay_report = User::getCpPaymentHistory(array(), false); 

        $date_assignment = date('Y-m-d H:i:s', time());
        $copy_writer_keywords = array();
        $all_cp_ids = $all_editor_ids = $editor_ids = $cp_ids = $campaign_ids = $keyword_ids = $editor_keywords = array();
        foreach ($p['keyword_id'] AS $k => $v) {
			$keyword_id = mysql_escape_string(htmlspecialchars(trim($p['keyword_id'][$k])));
            $is_reassigned = true;
            // get keyword and its article information by keyword_id
            // set keyword status as writing after reassignment
            $old_info = Article::getInfoByKeywordID($keyword_id);
            $campaign_id = $old_info['campaign_id'];
            $old_status = $old_info['article_status'];
            if ($no_cp) $copy_writer_id = $old_info['copy_writer_id'];
            if ($no_editor) $editor_id = $old_info['editor_id'];
            if ($no_date_end) $date_end = $old_info['date_end'];
            if ($no_date_start) $date_start = $old_info['date_start'];
            if ($no_qaer) $qaer_id = $old_info['qaer_id'];
            if ($copy_writer_id != '' && $copy_writer_id != $old_info['copy_writer_id']  
                ||  $editor_id != ''  && $editor_id != $old_info['editor_id'] || !$no_date_end || !$no_date_start) {
                if ($is_no && $old_status != '0') {
                    $feedback = 'Article status is not writing, you can\'t reassign it to No Editor or No Copy Writer';
                    return false;
                }

                // added by nancy xu 2010-07-14 13:47 - started
                // set start status
                if ($old_info['copy_writer_id'] == 0 && $copy_writer_id > 0) {
                    Campaign::updateArticleStatus($old_info['article_id'], 'started');
                } else if ($copy_writer_id == 0 && $old_info['copy_writer_id'] > 0) {
                    Campaign::updateArticleStatus($old_info['article_id'], 'started', 0);
                }
                // end
                
                if (!$is_no || $copy_writer_id != '' || $editor_id != '') {
                    if (!isset($date_ends[$date_end])) $date_ends[$date_end] = array();
                    if (!isset($campaign_ids[$campaign_id])) $campaign_ids[$campaign_id] = array();
                    // get keywords that articles.is_sent = 0 
                    // and cp_payment_history.payment_flow_status is 'cpc' or 'paid'
                   $allow_status = array('1gc', '3', '4');
                    if (in_array($old_status, $allow_status)) {
                        $month = changeTimeToPayMonthFormat(strtotime($old_info['google_approved_time']));
                        $payment_status = $pay_report[$old_info['copy_writer_id']][$month]['payment_flow_status'];
                        if ($payment_status == 'cpc' || $payment_status == 'paid') {
                           // if $is_foreced = true and new copy wirter is not equal to raw copy writer, 
                           // collect the abnormal reassignment keywords 
                           if ($is_forced || $copy_writer_id == $old_info['copy_writer_id']) {
                              if ($copy_writer_id != $old_info['copy_writer_id'] && $old_info['is_sent'] == 0)
                                  $email_keywords[$old_info['editor_id']][$old_info['copy_writer_id']][] = $old_info;
                           } else {
                               $is_reassigned = false;
                               unset($p['keyword_id'][$k]);
                               $article_id = $old_info['article_id'];
                               if ($payment_status == 'cpc') {
                                   $cpc_articles[$article_id] = $old_info['keyword'];
                               } else if ($payment_status == 'paid') {
                                   $paid_articles[$article_id] = $old_info['keyword'];
                               }
                           }
                        }
                    }
                } 

                if ($is_reassigned) {
                    if ($old_info['article_id'] && !$is_no) {
                        $p['article_id'] = $old_info['article_id'];
                        self::eRaseArticleInfo($p);
                        // added by nancy xu 2012-08-02 18:34
                        foreach ($old_info as $kk => $vv) {
                            if ($old_info['copy_writer_id'] != $copy_writer_id || $old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id)  {
                                if ($kk == 'keyword' || $kk== 'date_end'|| substr($kk,0,8) == 'optional') {
                                    if ($kk == 'keyword' ) {
                                        $kk = 'keywords';
                                    } else if ($kk == 'date_end') {
                                        $kk = 'dateend';
                                    }
                                    if ($old_info['copy_writer_id'] != $copy_writer_id) {
                                        $copy_writer_keywords[$campaign_id][$date_end][$kk][$keyword_id] = $vv;
                                    }
                                    $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id][$kk][$keyword_id] = $vv;
                                }
                            }
                        } // end
                        /*$keyword = $old_info['keyword'];
                        $optional1 = $old_info['optional1'];
                        $optional2 = $old_info['optional2'];
                        $optional3 = $old_info['optional3'];
                        $optional4 = $old_info['optional4'];
                        $optional5 = $old_info['optional5'];
                        $optional6 = $old_info['optional6'];
                        $optional7 = $old_info['optional7'];
                        $duedate = $old_info['date_end'];*/
                        if ($old_info['copy_writer_id'] != $copy_writer_id) { //modified by leo.
                            /*$copy_writer_keywords[$campaign_id][$date_end]['keywords'][$keyword_id] = $keyword;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional1'][$keyword_id] = $optional1;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional2'][$keyword_id] = $optional2;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional3'][$keyword_id] = $optional3;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional4'][$keyword_id] = $optional4;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional5'][$keyword_id] = $optional5;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional6'][$keyword_id] = $optional6;
                            $copy_writer_keywords[$campaign_id][$date_end]['optional7'][$keyword_id] = $optional7;
                            $copy_writer_keywords[$campaign_id][$date_end]['duedate'][$keyword_id] = $duedate;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['keywords'][$keyword_id] = $keyword;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional1'][$keyword_id] = $optional1;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional2'][$keyword_id] = $optional2;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional3'][$keyword_id] = $optional3;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional4'][$keyword_id] = $optional4;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional5'][$keyword_id] = $optional5;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional6'][$keyword_id] = $optional6;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional7'][$keyword_id] = $optional7;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['duedate'][$keyword_id] = $duedate;*/
                            if ($old_info['editor_id'] != $editor_id) $editor_ids[] = $editor_id;
                            $cp_ids[]      = $copy_writer_id;
                            $all_editor_ids[] = $editor_id;
                            $all_cp_ids[] = $copy_writer_id;
                            $campaign_ids[$campaign_id][$date_end] = $date_end;
                            $ret = Article::setArticleStatus($old_info['article_id'], 0, $old_info['article_status'], $copy_writer_id);
                            if ($old_info['is_sent'] == 1)
                                $qu .= " is_sent=0, ";
                            RequestExtension::backup(array('copy_writer_id' => $copy_writer_id, 'campaign_id' => $old_info['campaign_id']));
                        } else if ($old_info['is_sent'] == 0 && count($email_keywords)) {
                            $qu .= " is_sent=1, ";
                        }
                        // modified by nancy.
                        if ($old_info['copy_writer_id'] == $copy_writer_id && $old_info['editor_id'] != $editor_id) { 
                            /*$editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['keywords'][$keyword_id] = $keyword;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional1'][$keyword_id] = $optional1;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional2'][$keyword_id] = $optional2;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional3'][$keyword_id] = $optional3;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional4'][$keyword_id] = $optional4;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional5'][$keyword_id] = $optional5;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional6'][$keyword_id] = $optional6;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['optional7'][$keyword_id] = $optional7;
                            $editor_keywords[$campaign_id][$editor_id][$date_end][$copy_writer_id]['duedate'][$keyword_id] = $duedate;*/
                            $campaign_ids[$campaign_id][$date_end] = $date_end;
                            $editor_ids[] = $editor_id;
                            $all_editor_ids[] = $editor_id;
                            $all_cp_ids[] = $copy_writer_id;
                        }
                    }
                    $qset = $qu;
                    if ( in_array($old_info['article_status'], array('0','1','1gd','1gc','2','3'))) {
                        if ($copy_writer_id != $old_info['copy_writer_id'] ) {
                            $qset .= "`cp_accept_time` = null,  `cp_status`=-1, ";
                        } else if ($old_info['cp_status']) {
                            $qset .= "`cp_accept_time` = '" . $date_assignment . "', ";
                        }
                        if ($editor_id != $old_info['editor_id'] && $old_info['editor_status']!=-1) {
                           $qset .= " `editor_status`=-1, " ;     
                        }
                        //$qset .= " `editor_status`=1, " ;
                    }
                    
                    if ($no_cp && $no_editor || $is_no ) {
                        $qset = trim($qset, ' ,');
                    } else {
                       $qset .= "`date_assigned` = '".$date_assignment."' ";
                    }
                    $conn->Execute("UPDATE campaign_keyword ".
                               // "SET copy_writer_id = '".$copy_writer_id."', ".
                                "SET " .  $qset . " WHERE keyword_id = '".$keyword_id."' ");

                    $note_id = mysql_escape_string(htmlspecialchars(trim($p['note_id'][$k])));
                    if( strlen( $notes ) ) {
                        if (empty($copy_writer_id)) $copy_writer_id = 0;
                        if (empty($editor_id)) $editor_id = 0;
                        if( $note_id > 0) {
                            $query = "UPDATE editor_notes ".
                                    "SET copy_writer_id='{$copy_writer_id}', ".
                                    $en . 
                                    "editor_id='{$editor_id}', ".
                                    "keyword_id='{$keyword_id}' ".
                                    "WHERE note_id='{$note_id}'";
                        } else {
                            $query = "INSERT INTO editor_notes (notes, keyword_id, campaign_id, copy_writer_id, editor_id)".
                            " VALUES ('{$notes}', '{$keyword_id}', '{$campaign_id}', '{$copy_writer_id}', '{$editor_id}') ";
                        }
                        $conn->Execute( $query );
                    }
                }
            } else {
            	unset($p['keyword_id'][$k]);
            }
        }
        if (!$is_no) {
            if (count($copy_writer_keywords) || count($editor_keywords)) {
                if (!$no_editor)  $editor_ids = array($editor_id);
                if (!$no_cp) $cp_ids = array($copy_writer_id);
                if (!empty($copy_writer_keywords) && !empty($cp_ids)) self::sendBatchAssignKeywordMail($all_editor_ids, $cp_ids, $campaign_ids, $copy_writer_keywords, true);
                if (!empty($editor_keywords)  && !empty($editor_ids)) self::sendBatchAssignKeywordMail($editor_ids, $all_cp_ids, $campaign_ids, $editor_keywords, false);
            }
            if (count($email_keywords)) {
                // send email to tara and tony
                self::sendAccountingChangeEmail($email_keywords, $copy_writer_id, $editor_id);
            }
        }

        $feedback = "Success";
        // get feedback
        if (!$is_no) {
            if (isset($cpc_articles) && count($cpc_articles)) {
                $feedback .= "\\n";
                $feedback .= "One or more article(s) have been confirmed by copy writer.\\n";
                $feedback .= "You can reassign those articles forcedly.(please click forced assign checkbox).";
            }
            if (isset($paid_articles) && count($paid_articles)) {
                $feedback .= "\\n";
                $feedback .= "One or more article(s) have been paid by Infinitenine.\\n";
                $feedback .= "You can reassign those articles forcedly.(please click forced assign checkbox).";
            }
        }
        return true;
    }//end batchAssignKeyword()

    /**
    * Batch assign keyword to editor and copywriters
    *
    * We can change article type,editor,copywriter that they are opposite the keyword 
    *
    * @param array $p 
    * @return boolean if success return true，else return false
    */
    function batchApprovalKeyword($p = array()) 
    {
        global $conn, $feedback;
        $is_forced = trim($p['is_forced']);
        $qu = '';
        if (empty($p['keyword_id'])) 
		{
            $feedback = "Please choose one keyword.";
            return false;
        }
		else
		{
			$keywords = implode("','", $p['keyword_id'] );
			$keywords = trim($keywords, "'," );
			$clause_where = " AND ar.keyword_id IN ('". $keywords. "')";
			$count = self::countArticleBySubWhere( '5|6|99', 2, '', $clause_where );
			if($count>0)
			{
				$feedback = "You choose the completed articles. Please to check.";
				return false;
			}
            $qu .= " AND keyword_id IN ('". $keywords. "')";
		}
        $sql = 'UPDATE `campaign_keyword` SET `keyword_status` = 1 ';
        $sql .= 'WHERE 1 ' . "\n";
        $sql .= $qu;
        $ret = $conn->Execute($sql);
        return true;
    }//end batchApprovalKeyword()

    
    // added by snug xu 2007-03-13 14:26 - STARTED
    /**
     * @param $keywords: keywords info
     * @param $cp : new copy writer id
     * @param $editor : new editor id 
     * @return boolean
     */
    function sendAccountingChangeEmail($keywords, $cp, $editor) 
    {
        global $conn, $mailer_param;

        if (trim($cp) == '') {
            return false;
        }

        if (empty($keywords)) {
            return false;
        }

        if (empty($editor)) {
            return false;
        }

        $event_id = 12;
        $hint = "Article(s) have been reassigned";
        $ret = User::sendAdjustKeywordsEmail($event_id, $hint, $keywords, $editor, $cp);
        return $ret;
    }
    // added by snug xu 2007-03-13 14:26 - FINISHED

    function sendBatchAssignKeywordMail($editor_ids, $cp_ids, $campaign_ids, $keywords, $is_writer = true)
    {
        global $conn, $mailer_param;
        $editor_infos = $cp_infos = $keyword_ids = array();
        if (empty($keywords)) {
            return false;
        }
        $cp_ids = array_unique($cp_ids);
        $copy_writer_id = $cp_ids[0];
        $editor_ids = array_unique($editor_ids);
        $editor_infos = User::getUserByIds($editor_ids, "status != 'D'");
        $cp_infos = User::getUserByIds($cp_ids, "status != 'D'");
        if ((empty($cp_infos) &&$is_writer) ||  (empty($editor_infos) && !$is_writer)) {
            return false;
        }
        // modified by snug xu 2007-05-28 14:02 - STARTED
        $q  = "SELECT DISTINCTROW cc.campaign_id, cc.campaign_name, cc.campaign_requirement, cc.client_id, cl.company_name ";
        $q .= "FROM client_campaigns AS cc  ";
        $q .= "LEFT JOIN `client` AS cl  ON (cc.client_id=cl.client_id) ";
        $q .= "WHERE cc.campaign_id IN (".implode(",", array_keys($campaign_ids)).")";
        $campaigns = &$conn->GetAll($q);
        
        $cp_str = $editor_str = '';
        if (count($campaigns)) {
            foreach ($campaigns as $k => $value) 
            {
                $campaign_id = $value['campaign_id'];
                if ($campaign_id > 0) {
                    $keyword_list_str = "<br /><br />Campaign Name: {$value['campaign_name']}<br />";
                    $keyword_list_str .= "Client Name: {$value['company_name']}<br />";
                    // added by nancy xu 2011-02-02 14:54
                    $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_id));
                    if (!empty($client_pm)) {
                        $mailer_param['cc'][] = $client_pm['email'];
                        //$keyword_list_str .= "Project Manager: {$client_pm['first_name']} {$client_pm['last_name']}<br />";
                        //$keyword_list_str .= "Project Manager Email: {$client_pm['email']}<br />";
                    }
                    if (!empty($value['campaign_requirement'])) {
                        //$keyword_list_str .= "Assignment Details: " . html_entity_decode($value['campaign_requirement']) . "<br />";
						$keyword_list_str .=  html_entity_decode($value['campaign_requirement']) . "<br />";
                    }
                    // end
                    $cp_str .= $keyword_list_str;
                    foreach ($campaign_ids[$campaign_id] as $date_end) {
                        //$keyword_str = '<br />Deadline: ' . $date_end . '<br />';
                        if (!$is_writer) {
                            $editor_str .= $keyword_str;
                            foreach ($keywords[$campaign_id] as $editor_id => $rows) {
                                if (!isset($editor_infos[$editor_id]['editor_str'])) {
                                    $editor_infos[$editor_id]['editor_str'] = $keyword_list_str;
                                }
                                if (isset($rows[$date_end])) {
                                    $e_tmp_keywords  = $rows[$date_end];
                                    foreach ($e_tmp_keywords as $cp_id => $items) {
                                        $cp_info = $cp_infos[$cp_id];
                                       // $editor_str .= 'Copywriter: '. $cp_info['first_name'] . '<br />';
                                        $editor_str .= self::generateEmailKeywordInfo($items, $editor_id);
                                        //$editor_str .= 'Keyword List:<br />';
                                        //$editor_str .= implode("<br />", $items['keywors']) . '<br />';
                                        $editor_infos[$editor_id]['editor_str'] .= $editor_str;
                                        $editor_str = '';
                                    }
                                }
                            }
                        } else {
                            //$cp_str .= $keyword_str;
                            if (isset($keywords[$campaign_id][$date_end])) {
                                $cp_tmp_keywords = $keywords[$campaign_id][$date_end];
                                // $cp_str .= 'Keyword List:<br />';
                                // $cp_str .= implode("<br />", $cp_tmp_keywords);
                                $cp_str .= self::generateEmailKeywordInfo($cp_tmp_keywords, $copy_writer_id,1);
                            }
                        }
                    }
                }
            }
        }
        $mailer_param['cc'] = array_unique($mailer_param['cc']);
        $cp_mail = Email::getInfoByEventId(0);
        if ($is_writer) {
            $subject = $cp_mail['subject'];
            $main_body = nl2br($cp_mail['body']);
            $cp_info = $cp_infos[$copy_writer_id];
            $body = email_replace_placeholders($main_body, $cp_info);
            $body .= $cp_str;
            $address = $cp_info['email'];
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        } else {
            $editor_mail = Email::getInfoByEventId(11);
            $editor_body = nl2br($editor_mail['body']);
            $subject = $editor_mail['subject'];
            foreach ($editor_infos as $editor_info) {
                $body = email_replace_placeholders($editor_body, $editor_info);
                $body .= $editor_info['editor_str'];
                $address = $editor_info['email'];
                if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                    //return false;
                    //do nothing;
                } else {
                    //return true;
                    //do nothing;
                }
            }
        }
    }

    function getClientByKeywordIds($kids)
    {
        global $conn;
        $sql = ' SELECT DISTINCT  cc.client_id ' 
                  . 'FROM campaign_keyword AS ck '
                  . 'LEFT JOIN client_campaigns AS cc ON ck.campaign_id=cc.campaign_id '
                  . ' WHERE ck.keyword_id IN ( ' . implode(',', $kids) . ')';
         $result = $conn->GetAll($sql);
         $cids = array();
         if ($result) {
            foreach ($result as $item) {
                $cids[] = $item['client_id'];
            }
         }
         return $cids;
    }

    function generateEmailKeywordInfo($info, $user_id, $type= 3)
    {
        global $g_base_url;
        //$str = '<table cellspacing="1" cellpadding="1" border="1"><tr><th>Keyword List</th><th>Optional Keyword 1</th><th>Optional Keyword 2</th><th>Optional Keyword 3</th><th>Optional Keyword 4</th><th>Due date</th><td></td></tr>%s</table>';
        extract($info);
        // added by nancy xu 2012-04-21 23:48
        $kids = array_keys($keywords);
        $cids = Campaign::getClientByKeywordIds($kids);
        if (count($cids)== 1) {
            $client_id = $cids[0];
        }  else {
            $client_id = 0;
        }
        $fields = CustomField::getFieldLabels($client_id, 'optional');
        // end
        $str = '<table cellspacing="1" cellpadding="1" border="1"><tr><th>Keyword List</th>';
        foreach ($fields as $item) {
            $str .= '<th>' . $item['label']. '</th>';
        }
        if ($type == 1) {
            $str .= '<th>' . htmlentities('Accept/Decline') . '</th>';
            $link = $g_base_url . '/article/dokeywordaction.php?p=';
        }
        $str .= '</tr>%s</table>';
        
        $rows = array();
        $td =  '<td>%s</td>';
        
        foreach ($keywords as $k=> $v) {
            $v = htmlentities($v);
            // $dd = htmlentities($duedate[$k]);
            //$rows[] = '<tr>' . sprintf($td, $v) . sprintf($td, $p1) . sprintf($td, $p2) . sprintf($td, $p2) . sprintf($td, $p4) . sprintf($td, $dd) .  '</tr>';
            //$rows[] = '<tr>' . sprintf($td, $v) . sprintf($td, $p1) . sprintf($td, $p2) . sprintf($td, $p3) . sprintf($td, $p4) . '</tr>';
            $rowstr = '<tr>' . sprintf($td, $v) ;
            foreach($fields as $field=>$item) {
                $value = htmlentities($info[$field][$k]);
                $rowstr .= sprintf($td, $value);
            }
            if ($type == 1) {
                $accept_p = passport_encrypt($type . '.' . $user_id. '.' . $k . '.1');
                $decline_p = passport_encrypt($type . '.' . $user_id. '.' . $k . '.0');
                $rowstr .= '<td><a href="' . $link. $accept_p . '" target="_blank">&#x2714;</a> or <a href="' . $link. $decline_p . '" target="_blank">x</a></td>';
             }
            /*$accept_p = passport_encrypt($type . '.' . $user_id. '.' . $k . '.1');
            $decline_p = passport_encrypt($type . '.' . $user_id. '.' . $k . '.0');
            $rowstr .= '<td><a href="' . $link. $accept_p . '" target="_blank">&#x2714;</a> or <a href="' . $link. $decline_p . '" target="_blank">x</a></td>';*/
            $rowstr .= '</tr>';
            $rows[] = $rowstr;
        }
        $tr = implode("\n", $rows);
        $str = sprintf($str, $tr);
        /*foreach ($info as $ikey=>$ivalues) {
            if ($ikey == 'keywords') {
                $str .= '<strong>Keyword List:</strong><br />';
            } else if($ikey == 'optional1') {
                $str .= '<strong>Optional Keyword 1:</strong><br />';
            } else if($ikey == 'optional2') {
                $str .= '<strong>Optional Keyword 2:</strong><br />';
            } else if($ikey == 'optional3') {
                $str .= '<strong>Optional Keyword 3:</strong><br />';
            } else if($ikey == 'optional4') {
                $str .= '<strong>Optional Keyword 4:</strong><br />';
            }
            $str .= implode("<br />", $ivalues) . '<br />';
        }*/
        return $str;
    }

    function sendAssignMail($user_id, $cp_id, $date_end, $keywords = array(), $is_writer = true)
    {
        global $conn, $mailer_param;
        if (empty($keywords)) {
            return false;
        }
        $user_info = User::getInfo($user_id, "status != 'D'");
         if (empty($user_info)) return true;
         $keyword_ids = array_keys($keywords['keywords']);
        $q  = "SELECT DISTINCTROW ck.campaign_id, cc.campaign_name, cc.campaign_requirement, cc.client_id, cl.company_name ";
        $q .= "FROM campaign_keyword as ck ";
        $q .= "LEFT JOIN client_campaigns AS cc  ON (cc.campaign_id=ck.campaign_id) ";
        $q .= "LEFT JOIN `client` AS cl  ON (cc.client_id=cl.client_id) ";
        $q .= "WHERE keyword_id IN (".implode(",", $keyword_ids).") AND ck.status!='D' ";
        $mailer_param['cc'] = array();
        $campaigns = &$conn->GetAll($q);
        if (count($campaigns)) {
            foreach ($campaigns as $k => $value) 
            {
                $campaign_id = $value['campaign_id'];
                if ($campaign_id > 0) {
                    $keyword_list_str .= "<br /><br />Campaign Name: {$value['campaign_name']}<br />";
                    $keyword_list_str .= "Client Name: {$value['company_name']}<br />";
                    // $keyword_list_str .= "Keyword List:<br>";
                }
                // added by nancy xu 2011-02-02 14:54
                $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_id));
                if (!empty($client_pm)) {
                    $mailer_param['cc'][] = $client_pm['email'];
                    //$keyword_list_str .= "Project Manager: {$client_pm['first_name']} {$client_pm['last_name']}<br />";
                    //$keyword_list_str .= "Project Manager Email: {$client_pm['email']}<br />";
                }
                // end
                if (!empty($value['campaign_requirement'])) {
                    //$keyword_list_str .= "Assignment Details: " . html_entity_decode($value['campaign_requirement']) . "<br />";
					$keyword_list_str .= html_entity_decode($value['campaign_requirement']) . "<br />";
                }
            }
        }

        $address = $user_info['email'];
        if ($is_writer) {
            $cp_mail = Email::getInfoByEventId(0);
            $subject = $cp_mail['subject'];
            $main_body = nl2br($cp_mail['body']);
            $main_body = email_replace_placeholders($main_body, $user_info);
            $body = $main_body . "<br />";
            //$body .= "<br />Deadline: {$date_end}";

            $body .= $keyword_list_str . self::generateEmailKeywordInfo($keywords, $user_info['user_id'], 1);
            
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        } else {
            $editor_mail = Email::getInfoByEventId(11);
            $cp_info = User::getInfo($cp_id, "status != 'D'");
            $subject = $editor_mail['subject'];
            $body = nl2br($editor_mail['body']);
            $body = email_replace_placeholders($body, $user_info);
            //$body .= "<br /><br />Deadline: {$date_end}";
            //$body .= "<br /><br />Copywriter: {$cp_info['first_name']}";
            $body .= $keyword_list_str . self::generateEmailKeywordInfo($keywords, $user_info['user_id'], 3);
            
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        }
    }

    function sendAssignKeywordMail($copy_writer_id, $editor_id, $date_end, $copy_writer_keywords = array(), $editor_keywords = array())
    {
        global $conn, $mailer_param;
        $copy_writers = $keyword_ids = array();
        if (!empty($copy_writer_keywords) || !empty($editor_keywords)) {
            if (!empty($editor_keywords['keywords'])) $keyword_ids += array_keys($editor_keywords['keywords']);
        } else {
            return false;
        }
        if (trim($copy_writer_id) == '') {
            return false;
        }

        $cp_info = User::getInfo($copy_writer_id, "status != 'D'");
        //$ed_info = User::getInfo($editor_id);
        $editor_info = User::getInfo($editor_id, "status != 'D'");
        if (empty($cp_info) && empty($editor_info)) return true;
        // modified by snug xu 2007-05-28 14:02 - STARTED
        $q  = "SELECT DISTINCTROW ck.campaign_id, cc.campaign_name, cc.campaign_requirement, cc.client_id, cl.company_name ";
        $q .= "FROM campaign_keyword as ck ";
        $q .= "LEFT JOIN client_campaigns AS cc  ON (cc.campaign_id=ck.campaign_id) ";
        $q .= "LEFT JOIN `client` AS cl  ON (cc.client_id=cl.client_id) ";
        $q .= "WHERE keyword_id IN (".implode(",", $keyword_ids).") AND ck.status!='D' ";
        $mailer_param['cc'] = array();
        $campaigns = &$conn->GetAll($q);
        if (count($campaigns)) {
            foreach ($campaigns as $k => $value) 
            {
                $campaign_id = $value['campaign_id'];
                if ($campaign_id > 0) {
                    $keyword_list_str .= "<br /><br />Campaign Name: {$value['campaign_name']}<br />";
                    $keyword_list_str .= "Client Name: {$value['company_name']}<br />";
                    // $keyword_list_str .= "Keyword List:<br>";
                }
                // added by nancy xu 2011-02-02 14:54
                $client_pm = Client::getPMInfo(array('campaign_id' => $campaign_id));
                if (!empty($client_pm)) {
                    $mailer_param['cc'][] = $client_pm['email'];
                    //$keyword_list_str .= "Project Manager: {$client_pm['first_name']} {$client_pm['last_name']}<br />";
                    //$keyword_list_str .= "Project Manager Email: {$client_pm['email']}<br />";
                }
                if (!empty($value['campaign_requirement'])) {
                    //$keyword_list_str .= "Assignment Details: " . html_entity_decode($value['campaign_requirement']). "<br />";
					$keyword_list_str .= html_entity_decode($value['campaign_requirement']). "<br />";
                }
                // end
            }
        }
        // modified by snug xu 2007-05-28 14:02 - FINISHED
        if (!empty($mailer_param['cc'])) {
            $mailer_param['cc'] = array_unique($mailer_param['cc']);
        }
        if (!empty($copy_writer_keywords) && !empty($cp_info)) {
            $cp_mail = Email::getInfoByEventId(0);
            $subject = $cp_mail['subject'];
            $main_body = nl2br($cp_mail['body']);
            $main_body = email_replace_placeholders($main_body, $cp_info);
            $body = $main_body . "<br />";
            //$body .= "<br />Deadline: {$date_end}";
             
            // $body .= $keyword_list_str . implode("<br />", $copy_writer_keywords);
            $body .= $keyword_list_str . self::generateEmailKeywordInfo($copy_writer_keywords, $cp_info['user_id'], 1);
            $address = $cp_info['email'];
            
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        }
        
        if (!empty($editor_keywords) && !empty($editor_info)) {
            $editor_mail = Email::getInfoByEventId(11);
            $subject = $editor_mail['subject'];
            $body = nl2br($editor_mail['body']);
            $body = email_replace_placeholders($body, $editor_info);
            //$body .= "<br /><br />Deadline: {$date_end}";
            //$body .= "<br /><br />Copywriter: {$cp_info['first_name']}";
            // $body .= $keyword_list_str . implode("<br />", $editor_keywords);
            $body .= $keyword_list_str . self::generateEmailKeywordInfo($editor_keywords, $editor_info['user_id'], 3);
            $address = $editor_info['email'];
            if (!send_smtp_mail($address, $subject, $body, $mailer_param)) {
                //return false;
                //do nothing;
            } else {
                //return true;
                //do nothing;
            }
        }

        return true;
    }// end sendBatchAssignKeywordMail()

    function sendNoteToAllCampaignEditor($campaign_id, $notes)
    {
        global $conn, $feedback;
        if ($campaign_id) {
            $sql = 'SELECT campaign_name FROM client_campaigns WHERE campaign_id=' . $campaign_id;
            $arr = &$conn->GetRow($sql);
            $campaign_name = $arr['campaign_name'];
            $subject = 'Editor Notes For \'' . $campaign_name . '\' Campaign';
            $sql = ' SELECT DISTINCT u.first_name, u.last_name, u.email, ck.editor_id  FROM campaign_keyword AS ck LEFT JOIN users AS u ON (ck.editor_id = u.user_id) WHERE ck.campaign_id=' . $campaign_id .  ' AND u.status!=\'D\' ' . " AND ck.status!='D' "  ;
            $rs = &$conn->Execute($sql);
            while (!$rs->EOF) {
                $email = $rs->fields['email'];
                $first_name = $rs->fields['first_name'];
                $body = 'Dear ' . $first_name . ", <br /> " . $notes;
                send_smtp_mail($email, $subject, $body);
                $rs->MoveNext();
            }
        } else {
            $feedback = 'Invailed Campaign';
            return false;
        }
    }


    function countKeywordByCampaignID($campaign_id)
    {
        global $conn, $feedback;

        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }

        $q = "SELECT COUNT(*) AS count FROM `campaign_keyword` AS ck WHERE campaign_id = '".$campaign_id."' AND ck.status!='D' ";
        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
            return $count;
        }

        return $count;
    }//end countKeywordByID()

	/*add by snug 14:44 2006-07-30
	 *@param $article_status:'all' means no status restriction;$article_status:'4|5' means mutil status
	 *@param $is_single_status:0 means neq status; 1 means single status ; 2 means multil status
	 *@param  $clause_from: decided by user
	 *@param  $clause_where: decided by user
	*/
	function countArticleBySubWhere( $article_status = 'all', $is_single_status=1, $clause_from , $clause_where )
	{
		global $conn, $feedback;
		$where = "WHERE 1=1 ";
		if ($article_status != 'all')
		{
			switch( $is_single_status )
			{
				case 0:
					$where .=" AND ar.article_status != '".$article_status."' ";
					break;
				case 1:
					$where .= "AND ar.article_status = '".$article_status."' ";
					break;
				case 2:
					$where .=" AND ar.article_status REGEXP '^(".$article_status.")$' ";
					break;
				case 3:
					$where .=" AND ar.article_status NOT REGEXP '^(".$article_status.")$' ";
					break;
			}
		}
		$where = $where . $clause_where;
		$query = "SELECT COUNT( ar.article_id ) AS count FROM articles AS ar $clause_from $where";
		$rs = &$conn->Execute($query);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }
		return $count;
	}//end
    function countArticleByCampaignID($campaign_id, $article_status = 'all')
    {
        global $conn, $feedback;

        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            $feedback = "Please Choose a campaign";
            return false;
        }
        if ($article_status != 'all') {
            $q = "AND ar.article_status = '".$article_status."' ";
        }

        $q = "SELECT COUNT(ar.article_id) AS count FROM `articles` AS ar ".
             "LEFT JOIN campaign_keyword AS ck ON (ar.keyword_id = ck.keyword_id) ".
             "WHERE ck.campaign_id = '".$campaign_id."' AND ck.status!='D'  ".$q;

        $rs = &$conn->Execute($q);
        $count = 0;
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
            return $count;
        }

        return $count;
    }//end countArticleByCampaignID()


    //Total Articles in Queue:  //this means still working on
    //Total Articles delivered: // this means completed to this point 
    //Articles Pending review:  // this means client needs to login and approve
    function reportCampaignByRole($is_archived = -1)
    {
        global $conn/*, $feedback*/;

        $total_keyword = 0;
        $total_article_in_queue = 0;
        $total_article_deliverd = 0;
        $total_aritcle_pending = 0;

        $qw = "WHERE 1 AND ck.status!='D'  ";

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $qw .= " AND cc.client_id = '".Client::getID()."' GROUP BY ck.campaign_id ";

            //total keyword
            $q = "SELECT COUNT(ck.keyword_id) AS count, cc.campaign_id, cc.campaign_name, cc.date_start, cc.date_end  FROM campaign_keyword AS ck ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ". $qw ;
            $rs = &$conn->Execute($q);
			
            if ($rs) {
				    $result = array();
					$total_keyword=0;
                    $i = 0;
                    while (!$rs->EOF) {
                        $result[$rs->fields['campaign_id']] = $rs->fields;
						$total_keyword += $rs->fields['count'];
                        $rs->MoveNext();
                        $i ++;
                    }
                   $rs->Close();
            }
            $qw_all = $qw;
            if ($is_archived > -1) {
                $qw .= " AND cc.archived=" . $is_archived . ' ';
            }
            // total client approval articles this month
            $month_start = date("Y-m-01 00:00:00");
            $month_end = date("Y-m-d H:i:s");
            $q = "SELECT COUNT(ar.article_id) AS count, COUNT(ar.total_words) as total_word ".
                 "FROM articles AS ar ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                 "AND (ar.article_status = '5' OR ar.article_status = '6'  OR ar.article_status = '99') " .
                "AND (ar.client_approval_date>='{$month_start}' AND ar.client_approval_date<='{$month_end}' ) ".
                "AND cc.client_id = '".Client::getID() . "' AND ck.status!='D'  " ;
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
                $total_month = 0;
                if (!$rs->EOF) {
                    $total_month = $rs->fields['count'];
                    $total_word_month = $rs->fields['total_word'];
                }
                $rs->Close();
            }
            // tatal article which article status = 5 and artile status = 6
            $q = "SELECT COUNT(ar.article_id) AS count, cc.campaign_id ".
                 "FROM articles AS ar ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                 "AND (ar.article_status = '5' OR ar.article_status = '6' OR ar.article_status = '99' ) ".$qw;
            $rs = &$conn->Execute($q);
            if ($rs) 
			{
				  $total_article_deliverd=0;
                    $i = 0;
					while (!$rs->EOF) {
                        $campaign_id = $rs->fields['campaign_id'];
                        $count = $rs->fields['count'];
                        $result[$campaign_id]['total_article_download'] = $count;
                        $result[$campaign_id]['total_finished'] = $count;
                        $result[$campaign_id]['percent'] = ($count/$result[$campaign_id]['count'])*100;
						$total_article_deliverd += $rs->fields['count'];
                        $rs->MoveNext();
                        $i ++;
                    }
                   $rs->Close();
            }

            //total article which article status = 4;
            $q = "SELECT COUNT(ar.article_id) AS count, cc.campaign_id ".
                 "FROM articles AS ar ".
                 "LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id = ar.keyword_id) ".
                 "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                 "AND ar.article_status = '4' ".$qw;
            $rs = &$conn->Execute($q);
            if ($rs) {
					$total_aritcle_pending=0;
                    $i = 0;
					while (!$rs->EOF) {
                        $campaign_id = $rs->fields['campaign_id'];
                        $count = $rs->fields['count'];
                        $result[$campaign_id]['total_article_download'] += $count;
                        $result[$campaign_id]['total_article_approved'] = $count;
                        $result[$campaign_id]['percent'] = ($result[$campaign_id]['total_article_download']/$result[$campaign_id]['count'])*100;
						$total_aritcle_pending += $count;
                        $rs->MoveNext();
                        $i ++;
                    }
                $rs->Close();
            }
            if ($total_keyword > 0) {
			    $percent = ( ($total_aritcle_pending+$total_article_deliverd)*1.0/$total_keyword )*100;
            } else {
            	$percent = 0;
            }

            $total_article_in_queue = $total_keyword - $total_article_deliverd-$total_aritcle_pending;
            return array('total_keyword'          => $total_keyword,
                         'total_month' => $total_month,
                         'total_article_deliverd' => $total_article_deliverd,
                         'total_article_in_queue' => $total_article_in_queue,
                         'percent' => $percent,
                         'total_aritcle_pending'  => $total_aritcle_pending,
						 'report' => $result );
        } elseif (user_is_loggedin()) {

            $role = User::getRole();
            // added by nancy xu 2010-01-15 11:25
            $result = Notification::getNoticesByUserID(User::getID());
            $rs_stats = array();
            //do nothing;
            $now = time();

            $user_id = User::getID();
            if ($role == 'copy writer') {
                $qw .= "AND ck.copy_writer_id = '". $user_id ."' ";
                $qw .= "AND ck.keyword_status != 0 ";
            } else {
                $qw .= "AND ck.editor_id = '". $user_id ."' ";
            }

            $qw_all = $qw;
            if ($is_archived > -1) {
                $qw .= " AND cc.archived=" . $is_archived . ' ';
            }

            global $g_archived_month_time;
            $sql = 'SELECT cc.campaign_id, count(ar.article_id) AS total '. "\n";
            $sql .= 'FROM client_campaigns AS cc'. "\n";
            $sql .= 'LEFT JOIN  campaign_keyword AS ck ON (ck.campaign_id=cc.campaign_id) ' . "\n";
            $sql .= 'LEFT JOIN articles AS ar ON ck.keyword_id=ar.keyword_id '. "\n";
            $sql .= $qw . ' and  cc.date_end < \'' . date("Y-m-d H:i:s", $g_archived_month_time). '\' '. "\n";
            $sql .= ' AND (ar.article_status = \'5\' OR ar.article_status =\'6\' OR ar.article_status = \'99\') '. "\n";
            $sql .= 'GROUP BY ck.campaign_id ';
            $rs = &$conn->Execute($sql);
            $oldCampaigns = array();
            if ($rs) {
                while (!$rs->EOF) {
                    $oldCampaigns[$rs->fields['campaign_id']] = $rs->fields['total'];
                    $rs->MoveNext();
                    $i++;
                }
                $rs->Close();
            }
            // end
            if ($role == 'copy writer') {
                $q = "SELECT cc.campaign_id, cc.date_end, u.email as editor, cc.campaign_name, COUNT(ck.keyword_id) AS count FROM campaign_keyword AS ck ".
                     "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                     "LEFT JOIN users AS u ON (u.user_id = ck.editor_id) ".
                     $qw.
                     "GROUP BY cc.campaign_id ORDER BY cc.date_end DESC";
            } else {
                 $q = "SELECT cc.campaign_id, cc.campaign_name, cc.date_end, u.email AS project_manager , COUNT(ck.keyword_id) AS count " 
                    ." FROM campaign_keyword AS ck ".
                     "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                     "LEFT JOIN client AS c ON (c.client_id = cc.client_id) ".
                     "LEFT JOIN users AS u ON (u.user_id = c.project_manager_id) ".
                    $qw.
                     "GROUP BY cc.campaign_id ORDER BY cc.date_end DESC";
            }
            $rs = &$conn->Execute($q);
            $campaign_ids = array();
            if ($rs) {
                $i = 0;
                while (!$rs->EOF) {
                    $fields =  $rs->fields;
                    $count =  $fields['count'];
                    $campaign_id = $fields['campaign_id'];
                    $campaign_ids[] = $campaign_id;
                    $rs_stats[] = array('total' => $count, 'campaign_id' => $campaign_id);
                    $rs->MoveNext();
                    $i++;
                    // this archived information
                    //if ($count == $oldCampaigns[$campaign_id]) continue;
                    $result['report'][$campaign_id] = $count;
                    $result['campaign'][$campaign_id] = $fields['campaign_name'];
                    $result['campaign']['date_end'][$campaign_id] = $fields['date_end'];
                    if (isset($fields['editor'])) 
                        $result['campaign']['editor'][$campaign_id] = $fields['editor'];
                    if (isset($fields['project_manager'])) 
                        $result['campaign']['pm'][$campaign_id] = $fields['project_manager'];
                }
                $rs->Close();
                if (is_array($result['report'])) {
                    $result['total_articles'] = array_sum(array_values($result['report']));
                } else {
                    $result['total_articles'] = 0;
                }
            }

            $group_field = 'campaign_id';
            Client::getCountGroupByClients($rs_stats, 'total_client_approval', $campaign_ids, array( "ar.article_status REGEXP  '^(5|6|99)$'"), $qw, $group_field, false);
            Client::getCountGroupByClients($rs_stats, 'total_started', $campaign_ids, array( 'ck.copy_writer_id > 0', "ar.article_status REGEXP  '^(1|1gc|1gd|2|3|4|5|6|99)$'"), $qw, $group_field, false);
            if ($role == 'copy writer') { 
                Client::getCountGroupByClients($rs_stats, 'text_report', $campaign_ids, array( 'ck.copy_writer_id > 0', "ar.article_status REGEXP  '^(1|1gc|3|4|5|6|99)$'"), $qw, $group_field, false);
                Client::getCountGroupByClients($rs_stats, 'total_assign', $campaign_ids, array( 'ck.copy_writer_id > 0'), $qw, $group_field, false);
                Client::getCountGroupByClients($rs_stats, 'working_on', $campaign_ids, array( "ar.article_status REGEXP  '^(0|2|1gd)$'"), $qw, $group_field, false);
                Client::getCountGroupByClients($rs_stats, 'total_rejected', $campaign_ids, array( "ar.article_status = 2"), $qw, $group_field, false);
            } else {
                Client::getCountGroupByClients($rs_stats, 'completed_report', $campaign_ids, array( "ar.article_status REGEXP  '^(4|5|6|99)$'"), $qw, $group_field, false);
                Client::getCountGroupByClients($rs_stats, 'pending_report', $campaign_ids, array( "ar.article_status REGEXP  '^(3|1gc)$'"), $qw, $group_field, false);
            }
            // added by nancy xu 2012-06-25 10:40
            if (!empty($campaign_ids)) {
                $q = "SELECT style_id, campaign_id FROM campaign_style_guide WHERE campaign_id IN (" . implode(",", $campaign_ids). ")";
                $styles = $conn->GetAll($q);
                $style_ids = array();
                foreach ($styles as $row) {
                    $style_ids[$row['campaign_id']] = $row['style_id'];
                }
            }
			// end 
            // added report that waiting to accept
            if ($role == 'copy writer' || $role == 'editor') {
                $new_stats = $new_campaign_ids =  array();
                foreach ($rs_stats as $k => $row) {
                    $started_articles = isset($row['total_started']) && $row['total_started'] ? $row['total_started'] : 0;
                    $rs_total = $row['total'];
                    $campaign_id = $row['campaign_id'];
                    $new_total = $started_articles > 0 ? ($rs_total - $started_articles):$rs_total;
                    if ($new_total > 0) {
                        $style_id =isset($style_ids[$k]) ? $style_ids[$k] : '';
                        $new_stats[] = array('total' => $new_total, 'campaign_id'=> $campaign_id, 'style_id' => $style_id);
                        $new_campaign_ids[] = $campaign_id;
                    }
                }
                if (!empty($new_campaign_ids) && !empty($new_stats)) {
                    $new_field = ($role == 'copy writer' ? 'ck.cp_status' : ' ck.editor_status');
                    $new_qw =  $qw . ' AND ' . $new_field . '=1';
                    Client::getCountGroupByClients($new_stats, 'total_assigned', $new_campaign_ids, array( "ar.article_status = '0'"), $new_qw, $group_field, false);
                    $now_time = time()-86400;
                    $assigned_limit = date("Y-m-d H:i:s", $now_time);
                    $new_qw =  $qw . ' AND ' . $new_field . '=-1 and ck.date_assigned >= \'' . $assigned_limit . "'";
                    Client::getCountGroupByClients($new_stats, 'working_on', $new_campaign_ids, array( "ar.article_status = '0'"), $new_qw, $group_field, false);
                    $new_qw =  $qw . ' AND (' . $new_field . '=-1 and ck.date_assigned < \'' . $assigned_limit . '\' OR ' . $new_field . '<>-1)';
                    Client::getCountGroupByClients($new_stats, 'total_finished', $new_campaign_ids, array( "ar.article_status = '0'"), $new_qw, $group_field, false);
                    if (!empty($new_stats)) {
                        foreach ($new_stats as $k => $sitem) {
                            if ($sitem['total_finished'] == $sitem['total']) {
                                unset($new_stats[$k]);
                            }
                        }
                    }
                }
            }
            $result['new_report'] = $new_stats;
            foreach ($rs_stats as $row) {
                $k = $row['campaign_id'];
                $v = $row['total'];
                /*$q = "SELECT style_id FROM campaign_style_guide WHERE campaign_id={$k}";
                $result['img_report'][$k]['style_id'] = $conn->GetOne($q);*/
                $result['img_report'][$k]['style_id'] = isset($style_ids[$k]) ? $style_ids[$k] : '';
                $result['img_report'][$k]['campaign_id'] = $k;
                $result['total_client_approval'][$k] = $row['total_client_approval'];
                if ($role == 'copy writer') {
                    $result['text_report'][$k] = $row['text_report'];
                    $result['img_report'][$k]['percent'] =$row['pct_text_report'];
                    $result['img_report'][$k]['working_on'] = $row['working_on'];
                    $result['img_report'][$k]['total_rejected'] = $row['total_rejected'];
                    $result['total_assign'][$k] = $row['total_assign'];
                } else {
                    $pending_report = $row['pending_report'];
                    if (empty($pending_report)) $pending_report = 0;
                    $result['pending_report'][$k] = $pending_report;
                    $result['completed_report'][$k] = $row['completed_report'];
                    $result['img_report'][$k]['pending'] = $row['pct_pending_report'];
                    $result['img_report'][$k]['working_on'] = $pending_report; 
                    $result['img_report'][$k]['percent'] = $row['pct_completed_report']; 
                }
            }
            $conditons = array();
            if ($role == 'editor') {
                $conditons[] = 'ck.editor_id > 0';
            } else {
                $conditons[] = 'ck.copy_writer_id > 0';
            }
            $article_type_report =  Client::getCount('total', $conditons, $qw_all, 'ar.article_status');
            if (is_array($result['text_report'])) {
                // $result['total_completed_so_far'] = array_sum(array_values($result['text_report']));
                $total_completed_so_far = array_sum(array_values($result['text_report']));
                $result['total_completed_so_far'] = Campaign::sumFieldReport($article_type_report, 'article_status', array('1','1gc','3','4','5','6','99'));
                if (is_array($result['total_assign'])) {
                    // $result['total_assigned_so_far'] = array_sum(array_values($result['total_assign']));
                    $result['total_assigned_so_far'] = Campaign::sumFieldReport($article_type_report, 'article_status');
                }
            } else if (is_array($result['pending_report'])) {
                // $result['total_pending'] = array_sum(array_values($result['pending_report']));
                $result['total_pending'] = Campaign::sumFieldReport($article_type_report, 'article_status', array('1gc','3'));                
            } else {
                $result['total_completed_so_far'] = 0;
                $total_completed_so_far = 0;
                $result['total_pending'] = 0;
            }
            if (is_array($result['total_client_approval'])) {
                // $result['total_client_approved_so_far'] = array_sum(array_values($result['total_client_approval']));
                $result['total_client_approved_so_far'] = Campaign::sumFieldReport($article_type_report, 'article_status', array('5', '6', '99')); 
            }
            if (is_array($result['completed_report'])) {
                // $result['total_completed'] = array_sum(array_values($result['completed_report']));
                $result['total_completed'] = Campaign::sumFieldReport($article_type_report, 'article_status', array('4', '5', '6', '99'));
            } else {
                $result['total_completed'] = 0;
            }
            
            $result['total_assigned'] = Campaign::sumFieldReport($article_type_report, 'article_status', array('0', '1gd','2')); ;

            
            if ($role == 'copy writer' || $role == 'editor') {
               $rs = Article::getAllClientApprovedArticle($user_id, $role, changeTimeToPayMonthFormat(getDelayTime()), false, true, false, true);
               $result['1gc_this_month'] = $rs['count'];
               $result['total_word_client_approved_so_far'] = User::sumTotalWordsForUsers($user_id);
               $result['total_word_this_month'] = $rs['total_word'];
               if (strlen($result['1gc_this_month']) == 0) $result['1gc_this_month'] = 0;
               return $result;
            }
        }

        return null;
    }//end reportCampaignByRole()

    function sumFieldReport($report, $search_field, $allowed_values = null, $field = 'total')
    {
        $total = 0;
        foreach ($report as $row) {
            if (is_array($allowed_values) && !empty($allowed_values)) {
                if (in_array($row[$search_field], $allowed_values)) {
                    $total += $row[$field];
                }
            } else {
                $total += $row[$field];
            }
        }
        return $total;
    }


    function getLastestKeywordGroupByCampaigns($field = 'total_submit', $qw = '', $group_by='ck.campaign_id', $order_by = null)
    {
        global $conn;
        $query  = "SELECT COUNT( ar.article_id )  as " .$field.", ar.title,  u.user_id, " . $group_by . ", cc.campaign_name \n";
        $query .= "FROM users AS u \n";
        $query .= "LEFT JOIN campaign_keyword AS ck on (ck.copy_writer_id = u.user_id) \n";
        $query .= "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) \n";
        $query .= ' WHERE  1 ' . " AND ck.status!='D'  " ;
        $query .= $qw . "\n";
        $query .= "GROUP BY  ck.copy_writer_id, ".  $group_by ;
        if (!empty($order_by)) $query .= 'ORDER BY ' . $order_by;
        return $$conn->GetAll($query);
    }

    /**
     * @param $result  array
     * @param $field string
     * @param $user_field_name string
     * @param $qw string
     * @param $group_by string
     */
    function getCountGroupByCampaigns($field = 'total_submit', $qw = '', $group_by='ck.campaign_id')
    {
        global $conn;
        $query  = "SELECT COUNT( ar.article_id )  as " .$field.", u.user_id, " . $group_by . ", cc.campaign_name \n";
        $query .= "FROM campaign_keyword AS ck \n";
        $query .= "LEFT JOIN users AS u on (ck.copy_writer_id = u.user_id) \n";
        $query .= "LEFT JOIN articles AS ar ON (ck.keyword_id = ar.keyword_id) \n";
        $query .= "LEFT JOIN client_campaigns AS cc ON (ck.campaign_id = cc.campaign_id) \n";
        $query .= ' WHERE  1 '. " AND ck.status!='D'  ";
        $query .= $qw . "\n";
        $query .= "GROUP BY  ck.copy_writer_id, ".  $group_by ;
        $result = $conn->GetAll($query);
        return $result;
    } // end getCountGroupByCampaigns

    function getTotalGroupByCampaignID($campaign_ids)
    {
        global $conn;
        if (empty($campaign_ids)) return false;
        if (is_array($campaign_ids)) {
            $where = ' WHERE ck.campaign_id IN (' . implode(",", $campaign_ids). ')';
        } else {
            $where = ' WHERE ck.campaign_id = ' .  $campaign_ids;
        }
        $sql  = "SELECT COUNT(ck.keyword_id) AS total, ck.campaign_id ";
        $sql .= "FROM campaign_keyword AS ck ";
        $sql .= $where;
        $sql .= ' GROUP BY ck.campaign_id ';
        $result = $conn->GetAll($sql);
        $report =  array();
        foreach ($result as $row) {
            $report[$row['campaign_id']] = $row['total'];
        }
        return $report;
    }

    function getPrefByCampaignID($campaign_id)
    {
        global $conn, $feedback;

        $campaign_id = addslashes(htmlspecialchars(trim($campaign_id)));
        if ($campaign_id == '') {
            return null;
        }
        
        $q = "SELECT DISTINCT p.* FROM preference as p, campaign_keyword as ck WHERE pref_table = 'campaign_keyword' AND pref_field = 'keyword_category' and p.pref_id=ck.keyword_category and ck.campaign_id = '{$campaign_id}' AND ck.`status` != 'D' ORDER BY pref_id ASC";

        $rs = $conn->Execute($q);
        
        if ($rs) {
            $arr = array();
            while (!$rs->EOF) {
                //$arr[] = $rs->fields['pref_value'];
                //$arr[] = $rs->fields;
                $arr[$rs->fields['pref_id']] = $rs->fields['pref_value'];
                $rs->MoveNext();
            }
            $rs->Close();
            if (!$arr) {
                return null;
            }
            return $arr;
        }
        return null;

    }//end function getPrefByCampaignID()

	function getCampaignIDByKeywordID( $keyword_id )
	{
		global $conn, $feedback;
		$sql = "SELECT `campaign_id` FROM `campaign_keyword` WHERE keyword_id='$keyword_id' AND   `status`!='D' ";
		$rs = $conn->Execute( $sql );
		if ($rs) 
		{
            $arr = array();
            if (!$rs->EOF) 
			{
                $campaign_id = $rs->fields['campaign_id'];
            }
            $rs->Close();
            if ( strlen( $campaign_id )===0 )
			{
               $campaign_id = 0;
            }
        }
		return $campaign_id;
	}

    /**
     * confirm self pending articles
     *
     *
     *
     * @return boolean  if success return ture, else return false;
     */
    function confirmPending()
    {
        global $conn, $feedback;
        //global $g_tag;

        $month = date('Ym', time());
        $conn->Execute("UPDATE cp_campaign_article_summary ".
                       "SET is_paid = '1' ".
                       "WHERE copy_writer_id = '".User::getID()."' AND month = '".$month."'");

        if ($conn->Affected_Rows() == 1) {
            $feedback = 'Success';
            return true;
        } else {
            $feedback = 'Failure, Please try again';
            return false;
        }
        
    }//end confirmPending()

    function setCampaignFieldsById($fields, $campaignId)
    {
        global $feedback, $conn;
        if (empty($campaignId) || !is_numeric($campaignId)) {
            $feedback = 'Invalid Campaign';
            return false;
        }
        $sql = 'UPDATE client_campaigns SET ';
        $sets = array();
        foreach ($fields as $k => $v) {
            $sets[] = $k . '=\'' . $v . '\'';
        }
        $sql .= implode(',', $sets);
        $sql .= 'WHERE campaign_id = ' . $campaignId;
        $conn->Execute( $sql );
        return ( $conn->Affected_Rows()>0||$conn->Affected_Rows()===0)? true : false;
    }

	/*
	 *Added By Snug 14:31 2006-8-25
	 *Function Description: set status of a set of campaigns or a campaign
	 *@param $campaign_id array or string
	 *@param $status int: 0 means  uncompleted campaign; 1 means completed campaign
	 *@param $is_in: 
	 *@return boolean  if success return ture, else return false;
	 */
	function setCampaignStatus( $status, $campaign_id, $is_in )
	{
		global $conn;		
		if( is_array( $campaign_id ) )//check whether the campaign_id is array or not
			$campaign_ids = implode("','", $campaign_id );
		else
			$campaign_ids = $campaign_id;
		if( $is_in==1 )
			$qw = 'IN';
		else
			$qw = 'NOT IN';
		$sql = "UPDATE `client_campaigns` SET `status`='$status' WHERE campaign_id $qw ( ' $campaign_ids ' )";
		$conn->Execute( $sql );
		if( $conn->Affected_Rows()>0||$conn->Affected_Rows()===0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}//end setCampaignStatus

	/*
	 *Added By Snug 2007-06-04 19:45
	 *Function Description: set editor notes of a a campaign
	 *@param $campaign_id int
	 *@param $key string: client_campaign field name
	 *@param $value string: value of last field name
	 *@return boolean  if success return ture, else return false;
	 */
    function setCampaignFieldByID( $key, $value, $campaign_id)
	{
		global $conn;		
        if ($campaign_id > 0)
        {
		    $sql = "UPDATE `client_campaigns` SET `{$key}`='{$value}' WHERE campaign_id ={$campaign_id}";
        }
		$conn->Execute( $sql );
		if( $conn->Affected_Rows()>0 || $conn->Affected_Rows()===0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}//end setCampaignFieldByID

   // added by snug xu 2008-01-11 23:32 - STARTED
    function __getConditions($p, $opt = '=')
    {
        $conditions = array();
        foreach ($p as $k => $rows)
        {
            $k = strtolower($k);
            switch ($k)
            {
            case 'like':
                $conditions = array_merge($conditions, self::__getConditions($rows, $k));
                break;
            default:
                if (is_array($rows))
                    $conditions[] = "`{$k}` IN ('" .  implode( "', '", $rows) . "') ";
                else
                    $conditions[] = "`{$k}` {$opt} '{$rows}' ";
                break;
            }
        }
        return $conditions;
    }
   // added by snug xu 2008-01-11 23:32 - END
    
    /**
     * get campaign ids by params
     * @param array $p
     * @return array 
     */
    function getCampaignsByParam($p, $fields)
    {
        global $conn;
        $conditions = self::__getConditions($p);
        $conditions[] = '1=1';
        // $conditions[] = 'cc.campaign_id != 34';

        if (is_array($fields)) $query = implode(', ', $fields);
        else if (is_string($fields)) $query = $fields;
        $sql  = "SELECT {$query} ";
        $sql .= "FROM client_campaigns AS cc  ";
        $sql .= "WHERE " . implode(" AND ", $conditions);
		$rs = &$conn->Execute($sql);
        if ($rs) {
            $result = array();
            while(!$rs->EOF) {
                if (is_array($fields)) {
                    $result[] = $rs->fields;
                } else {
                    $result[] = $rs->fields['campaign_id'];
                }
                $rs->MoveNext();
            }
            $rs->Close();
        }
        return $result;
    }

	/**
	 *Added By Snug 14:31 2006-8-25
	 *Function Description: set status of a set of campaigns or a campaign
	 *@param $p array:
		$p['is_single_status']:1 means single article status; 0 means multi article status
	 *@return array: campaign id set
	 */
	function getCampaignIdsByArticleStatus( $p )
	{
		global $conn;
		$is_single_status = addslashes(htmlspecialchars(trim($p['is_single_status'])));
		$ar_status = addslashes(htmlspecialchars(trim($p['ar_status'])));//article status
		$cc_status = addslashes(htmlspecialchars(trim($p['cc_status'])));//client campaign status
		$qw = ' 1 ';
		if( $is_single_status == 1 )
		{
			$qw .= "AND ar.article_status='$ar_status' ";
		}
		else
		{
			$qw .= "AND ar.article_status  REGEXP '^(" . $ar_status .  ")$' ";
		}
		$query = "SELECT campaign_id FROM client_campaigns WHERE campaign_id NOT IN ( SELECT DISTINCT ck.campaign_id FROM campaign_keyword AS ck left join articles AS ar ON (ar.keyword_id=ck.keyword_id) WHERE $qw AND ck.status!='D')";
		$rs = &$conn->Execute( $query );
		while( !$rs->EOF )
		{
			$result[] = $rs->fields['campaign_id'];
			$rs->MoveNext();
		}
		$rs->Close();
		return $result;
	}//end getCampaignIdsByArticleStatus();

    /**
	 *Added By Liu ShuFen  2007-11-4
	 *Function Description: get the data of copywriter and campiagns
	 *@param $p array: variable $_GET array
	 *@return array: copywriter and campaign data, pager links and total pages
	 */ 
    function getAllCampaign($p) {
        global $conn;
        global $g_pager_params;
        global $g_tag;
        $g_article_types = $g_tag['article_type'];

        $qw = ' WHERE 1 ';
        $current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
		if (strlen($current_month)) {
			$now = changeTimeFormatToTimestamp($current_month);
		} else {
			$now = time();
            $current_month = date("Ym", $now);
		}
        $next_month  = strtotime('+1 month', $now);
        $last_month  = strtotime('-1 month', $now);
        $month_end   = date('Y-m', $next_month) . "-01 00:00:00";
        $month_start = date('Y-m', $now)."-01 00:00:00";
        $qw .= "\n AND aa.created_time >='".$month_start."'";//
        $qw .= "\n AND aa.created_time <='" . $month_end. "'";
        $qw .= "\n AND aa.status =4 AND aa.new_status =5 ";
        $qw .= "\n AND aa.curr_flag=1";
        $qw .= "\n AND us.user_id > 0";
        
        $user_status = isset($p['status']) ? trim($p['status']) : 'All';        
        $qw .= ($user_status == 'All') ? '' : " AND us.status ='".$user_status. "'";
        $campaign_id = isset($p['campaign_id']) ? $p['campaign_id'] : '';
        $qw .= ($campaign_id == '') ? '' : ' AND cc.campaign_id ='.$p['campaign_id'];        
        $qw .= "\n AND ( ar.article_status =5 OR ar.article_status =6 ) AND  ck.status!='D' ";
        $sql_by = "\n ORDER BY ck.copy_writer_id ";
        
        $page_sql = " SELECT count(DISTINCT ck.campaign_id, us.user_id) AS num ".                
                    "\n FROM campaign_keyword AS ck ".
                    "\n LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) ".
                    "\n LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) ".
                    "\n LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) ".
                    "\n LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id)";
        $page_sql .= $qw;
        $page_sql .= $sql_by;
        $rs_all = $conn->getAll($page_sql);
        $count = $rs_all[0]['num'];
        if ($count != 0) {
            if (trim($p['perPage']) > 0) {
                $perpage = $p['perPage'];
            } else {
                $perpage= 50;
            }

            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $page_sql = " SELECT DISTINCT ck.campaign_id, us.user_id, us.user_name,".
                        "\n us.first_name, us.last_name, us.status, us.email ".
                        "\n FROM campaign_keyword AS ck ".
                        "\n LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) ".
                        "\n LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) ".
                        "\n LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) ".
                        "\n LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id)";
            $page_sql .= $qw;
            $page_sql .= $sql_by;
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($page_sql, $perpage, ($from - 1));
            $g_article_type = $g_tag['article_type'];
            if ($rs) {
                $users = array();
                while (!$rs->EOF) {
                    $uids[$rs->fields['user_id']]     = $rs->fields['user_id'];
                    $key = $rs->fields['user_id'] . $rs->fields['campaign_id'];
                    $users[$key] = $rs->fields;
                    foreach ($g_article_type as $tk => $v) {
                        $users[$key][$tk] = 0;
                    }
                    $users[$key]['total'] = 0;
                    $rs->MoveNext();               
                }
                $rs->Close();
            } else {
                return null;
            }
            $user_ids = implode(",", $uids);
            $user_ids = trim($user_ids, ",");
            $q = "SELECT cc.campaign_id, cc.campaign_name, ck.article_type, at.parent_id, us.user_id, ".
                 "\n COUNT( DISTINCT ck.keyword_id ) AS num ".
                 "\n FROM campaign_keyword AS ck " .
                 "\n LEFT JOIN articles AS ar ON ( ar.keyword_id = ck.keyword_id ) ".
                 "\n LEFT JOIN client_campaigns AS cc ON ( cc.campaign_id = ck.campaign_id ) ".
                 "\n LEFT JOIN users as us ON (us.user_id = ck.copy_writer_id)".  
                 "\n LEFT JOIN article_type as at ON (at.type_id = ck.article_type)".  
                 "\n LEFT JOIN article_action AS aa ON ( aa.article_id = ar.article_id ) ";
            
            $qw .= " AND ck.copy_writer_id IN (" . $user_ids .")";
            
            $q_by = "\n GROUP BY ck.campaign_id, ck.article_type, us.user_id ";
            $q_by .= "\n  ORDER BY ck.copy_writer_id";

            $q .= $qw . $q_by;
            $arr = $conn->getAll($q);
            if (!empty($arr)) {
                foreach($arr as $rs) {
                    $user_id = $rs['user_id'];
                    $campaign_id = $rs['campaign_id'];
                    $j = intval($user_id.$campaign_id);
                   
                    //use this judgment to use initialized array store data
                    //this set is useful to prevent other uninitialized array
                    if (isset($users[$j])) {
                        $users[$j]['campaign_name']= $rs['campaign_name'];
                        $parent_id = $rs['parent_id'];
                        $users[$j][$parent_id] += $rs['num'];
                        $users[$j]['total'] += $rs['num'];
                    }
                }
            }
            return array('pager'  =>$pager->links,
                         'total'  => $pager->numPages(),
                         'count'  => $count,
                         'result' => $users
                         );
        }
        else {return null;}
    }//END getAllCampaign()

    //add by liu shu fen 12:01 2007-12-24
    function getCampaignByCampaignId($fields, $p) {
        global $conn;
        $qw[] = " WHERE 1 ";
        if (!empty($fields) || count($fields)) {
            if (is_array($fields)) {
                $select = implode(",", $fields);
            } else $select = trim($fields);
        }
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            if (is_array($p['campaign_id'])) {
                $qw[] = " campaign_id IN (" . implode(",", $p['campaign_id']) . ") ";
            } else $qw[] = " campaign_id=" . $p['campaign_id'] . " ";
        }
        if (isset($p['category_id']) && !empty($p['category_id'])) {
            if (is_array($p['category_id'])) {
                $qw[] = " category_id IN (" . implode(",", $p['category_id']) .")";
            } else {
                $qw[] = " category_id=" . $p['category_id'];
            }
            
        }
        $sql = "SELECT " . $select . " FROM client_campaigns ";
        if (!empty($qw))
            $sql .= implode(" AND ", $qw);
        $res = $conn->getAll($sql);
        if (!empty($res)) {
            return $res;
        } else return null;
    }//end

    //add by liu shu fen 12:02 2007-12-24
    function getUnassignedKeyword($p) {
        //TODO
        global $conn;
        $qw[] = " WHERE 1 ";
        if (isset($p['fields']) && !empty($p['fields'])) {
            if (is_array($p['fields'])) {
                $fields = implode(",", $p['fields']);
            } else {
                $fields = trim($p['fields']);
            }
        } else {
            $fields = "ck.keyword_id, ck.campaign_id, ck.keyword, ck.article_type, ck.keyword_description, ck.date_start, ck.date_end, at.parent_id AS at_parent_id";
        }
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            if (is_array($p['campaign_id'])) {
                $qw[] = " ck.campaign_id IN (" . implode(",", $p['campaign_id']) . ")";
            } else {
                $qw[] = " ck.campaign_id=" . $p['campaign_id'];
            }
        }
        $qw[] = " ck.copy_writer_id=0 ";
        $qw[] = " ck.`status`!='D' ";
        $sql = "SELECT " . $fields . " FROM campaign_keyword as ck ";
        $sql .= "LEFT JOIN article_type AS at ON at.type_id = ck.article_type ";
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $order = " ORDER BY ck.date_created DESC";
        $limit = " LIMIT 0, 50 ";

        $sql .= $order;
        $sql .= $limit;
        $res = $conn->getAll($sql);
        if ($res) {
            return $res;
        } else {
            return null;
        }
    }//END

    //add by liu shu fen 7:26 2007-12-25
    function assignKeywordByCp($p) {
        //TODO
        global $conn, $feedback;
        $feedback = '';
        $assign_date = date("Y-m-d H:i:s");
        $set[] = " date_assigned='" . $assign_date . "' ";
        if (isset($p['copywriter_id']) && !empty($p['copywriter_id'])) {
            $set[] = " copy_writer_id=" . trim($p['copywriter_id']);
        } else {
            $feedback = "Please Login First!";
            return false;
        }
        if (isset($p['editor_id']) && !empty($p['editor_id'])) {
            $editors = $p['editor_id'];
        }
        if (isset($p['keyword_id']) && !empty($p['keyword_id'])) {
            if (is_array($p['keyword_id'])) {
                if (!isset($p['is_forced_not_free']))
                {
                    // added by snug xu 2007-12-27 10:02 - STARTED
                    $sql = "SELECT MAX(date_end) AS end_date, MIN(date_start) AS start_data \n";
                    $sql .= "FROM campaign_keyword AS ck ";
                    $sql .= "WHERE 1  AND ck.keyword_id IN (" . implode(',', $p['keyword_id']) . ") \n";
                    $sql .= "AND ck.date_start != '0000-00-00' AND ck.status!='D'";
                    $rs = &$conn->Execute($sql);
                    if ($rs)
                    {
                        $date_end  = $rs->fields['end_date'];
                        $date_start = $rs->fields['start_data'];
                        $param = array(
                            'user_id' => $copy_writer_id,
                            'is_free' => 0,
                            '>=' => array('c_date' => $date_start),
                            '<=' => array('c_date' => $date_end),
                        );
                        $rs->Close();
                        $dates = UserCalendar::getListByParam($param);
                        if (!empty($dates))
                        {
                            $feedback = 'You are not free in ' . implode(', ', $dates) . ".\n please try again";
                            return false;
                        }
                    }
                }
                // end 
               
                foreach ($p['keyword_id'] as $num => $k_id) {
                    if ($k_id > 0) {
                        if (!empty($editors)) {
                            if (!empty($editors[$num])) {
                                $set_editor_id = " editor_id=" . $editors[$num];
                            }
                        }
                        unset($qw);
                        $qw[] = " WHERE 1 ";
                        $qw[] = " keyword_id=" . trim($k_id);
                        $sql = "UPDATE campaign_keyword SET ";
                        if (!empty($set)) {
                            $sql .= implode(",", $set);
                        }
                        if (!empty($set_editor_id)) {
                            $sql .= "," . $set_editor_id;
                        }
                        if (!empty($qw)) {
                            $sql .= implode(" AND ", $qw);
                        }
                        $res = $conn->Execute($sql);
                        if (!$res) {
                            $feedback .= "Keyword ID:" . $k_id . " Assign Failed! ";
                            continue;
                        }
                    }
                }
            }
        } else {
            $feedback = "Please Select Keywords First!";
            return false;
        }
        if ($feedback == '') {
            return true;
        } else {
            return false;
        }
    }//END

    //add by liu shu fen 16:57 2007-12-29
    function getAllKeywordsByCp($p, $fields = array()) {
        global $conn;
        $qw[] = " WHERE 1 ";
        if (isset($p['copywriter_id']) && !empty($p['copywriter_id'])) {
            $cp_id = trim($p['copywriter_id']);
            $qw[] = " copy_writer_id=" . $cp_id;
        }
        if (isset($p['campaign_id']) && !empty($p['campaign_id'])) {
            $c_id = trim($p['campaign_id']);
            $qw[] = " campaign_id=" . $c_id;
        }
        $qw[] = " status!='D' ";
        // modified by snug xu 2008-01-14 14:51 - STARTED
        if (empty($fields)) $fields[] = 'keyword_id';
        $sql = "SELECT " . implode(', ', $fields). " FROM campaign_keyword ";
        // modified by snug xu 2008-01-14 14:51 - END
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $ret = $conn->getAll($sql);
        if ($ret) {
            return $ret;
        } else {
            return null;
        }
    }//end

    /**
     * Search Keywords that need to search
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function searchAdjustKeyword($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;
         
        $show_cb = false;

        $q = "";

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "\nAND ck.campaign_id IN ( ".$campaign_id." ) ";
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "\nAND ck.keyword_id = '".$keyword_id."' ";
        }
        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        $user_id = $p['user_id'];
        $role = addslashes(htmlspecialchars(trim($p['role'])));
        if ($role == 'copy writer') {
            $user_field = 'copy_writer_id';
            $copy_writer_id = $user_id;
            $cost_field = 'cp_cost';
            $cost_article = 'cp_article_cost';
        } else {
            $user_field = 'editor_id';
            $editor_id = $user_id;
            $cost_field = 'editor_cost';
            $cost_article = 'editor_article_cost';
        }
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "\nAND ck.creation_user_id = '".$creation_user_id."' ";
        }

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "\nAND ck.article_type = '".$article_type."' ";
        }
        $keyword_category = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        if ($keyword_category != '') {
            $q .= "\nAND ck.keyword_category = '".$keyword_category."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "\nAND ck.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "\nAND ck.date_end <= '".$date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "\nAND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }
        
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else
        {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }
        
        $current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
        // modified by snug 2007-05-13 10:35
        if( strlen($current_month) == 0 ) {
            $now = time();
            $current_month = changeTimeToPayMonthFormat($now);
        } else {
            $now = changeTimeFormatToTimestamp($current_month);
        }
        $role = $p['role'];
        $param['now']     = $now;
        $param['user_id'] = $p['user_id'];
        $param['role'] = $role;
        $param['forced_adjust'] = isset($p['forced_adjust']) ? $p['forced_adjust'] : 0;
        $forced_adjust =  $param['forced_adjust'];
        $param['include_google_clean'] = isset($p['include_google_clean']) ? $p['include_google_clean'] : 0;
        $include_gc =  $param['include_google_clean'];
        $param['include_editor_approval'] = isset($p['include_editor_approval']) ? $p['include_editor_approval'] : 0;
        $include_ea =  $param['include_editor_approval'];
        $param['show_current_month'] = $p['show_current_month'];
        $param['is_paid'] = $p['is_paid'];
        $param['type']     = 'keyword-adjust';
        $sqls = User::getAccountingConditionOrSql($param, $role);
        if (!empty($sqls['where'])) $sqls['where'] = ' AND  ' . $sqls['where'];
        $next_month = nextPayMonth($current_month, $now);
        $show_current_month = mysql_escape_string(htmlspecialchars(trim($p['show_current_month'])));

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)")." ";
            }
        }
        $where = "\nWHERE 1 {$sqls['where']} {$q}";
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ".
                $sqls['left'] . 
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
                $where;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            $feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $users = User::getCpPaymentHistory(array('role' => $role), false);

        $q = "SELECT `ck`.`keyword_id`, `ck`.`campaign_id`, `ck`.`copy_writer_id`, `ck`.`editor_id`, `ck`.`keyword`, \n `ck`.`article_type`, `ck`.`keyword_description`, `ck`.`date_start`, `ck`.`date_end`,  `ck`.`status`, `ck`.`is_sent`, \n `ar`.`cp_updated`, ar.article_id, ar.article_number, ar.google_approved_time, ar.approval_date, ar.client_approval_date, \n IF (apl.log_id > 0 , apl.is_canceled, ar.is_canceled) as is_canceled , apl.log_id, apl.pay_month , apl.paid_time,  ar.article_status, \n cl.client_id, cl.user_name, cl.company_name, cc.campaign_name, ar.total_words as word_count ,  CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , \n CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name,  ach.pay_by_article AS ach_checked, at.pay_by_article AS at_checked, at.{$cost_field} AS at_word_cost ,at.{$cost_article} AS at_article_cost, \n ac.pay_by_article AS ac_checked, ac.{$cost_field} AS ac_word_cost ,ac.{$cost_article} AS ac_article_cost, \n ach.cost_per_article as ach_type_cost,IF(ach.article_type_name != '' && ach.article_type_name IS NOT NULL, ach.article_type_name, at.type_name) AS article_type_name \n" . 
         $sqls['left'] .
         "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) \n".
         "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
         "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) \n".
         "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) \n".
         "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) \n".
         "LEFT JOIN `article_type` AS at ON (at.type_id = ck.article_type) \n ".
         "LEFT JOIN `article_cost` AS ac ON (ac.campaign_id = ck.campaign_id and ac.article_type=ck.article_type) \n ".
         "LEFT JOIN `article_cost_history` AS ach ON (ach.campaign_id = ck.campaign_id AND ach.article_type=ck.article_type AND ach.user_id=apl.user_id AND ach.month=apl.pay_month)  \n ".
         $where;
        $q .= "\nGROUP BY ar.article_id";
        $q .= "\nORDER BY ar.client_approval_date";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $result[$i] = $fields;
				/****决定是否显示move to next pay period按纽*****/
                $google_approved_time = $rs->fields['google_approved_time'];
                $article_status = $rs->fields['article_status'];
                if (strlen($google_approved_time) == 0) 
                {
                    $google_approved_time = $fields['approval_date'];
                }
                $pay_month = $fields['pay_month'];
                $paid_time = $fields['paid_time'];
                $user_id = $fields[$user_field];
//                if (isset($users[$user_id][$current_month])) 
//                    $pay_status = $users[$user_id][$current_month];
                
                $not_paid = ($paid_time=='' || $paid_time=='0000-00-00 00:00:00') && $pay_status != 'paid';
                $gc_ym = changeTimeToPayMonthFormat(strtotime($google_approved_time));
                if ($not_paid) {
                    if ( $gc_ym == $current_month && empty($pay_month)) {
                        $result[$i]['pay_this_month'] = ($include_gc && $article_status == '1gc' || $include_ea && $article_status == '4' );
                    } else {
                        $result[$i]['pay_this_month'] = false;
                    }
                    $result[$i]['is_show_adjust'] = ($pay_month == $current_month);
                    if ($show_current_month == 1 && ($include_gc && $article_status == '1gc' || $include_ea && $article_status == '4' || $pay_month == $next_month)) {
                        $result[$i]['add_to_this_month'] = ($gc_ym == $next_month  && !$pay_month || $pay_month == $next_month)? true :false;
                    }
                }
                // added by nancy xu 2011-05-26 16:05
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $cost_per_article = ($checked == 0) ? $fields['word_count'] * $cost_per_unit : $cost_per_unit;
                // end
                
                $result[$i]['payment_flow_status'] = $users[$user_id][$current_month]['payment_flow_status'];
                $result[$i]['user_id'] = $user_id;
                $result[$i]['article_cost'] = $cost_per_article;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result,
                     );

    }//end search()

    // added by nancy xu 2010-02-21 9:34
    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function getOverdueArticles($p = array(), $is_paged = true)
    {
        global $conn, $feedback;

        global $g_pager_params;

        $today = date("Y-m-d");
        $today_time = strtotime($today);
        $q = " AND `ck`.`date_end`<='{$today}' ";
        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "\nAND ck.campaign_id = '".$campaign_id . "' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        } else {
            $q .= "\nAND ck.copy_writer_id > 0 ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }

        $article_status = addslashes(htmlspecialchars(trim($p['article_status'])));
        if ($article_status != '') {
            $q .= "\nAND ar.article_status = '".$article_status."' ";
        }

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "\nAND cc.client_id = '".Client::getID()."' ";
        } else {
            $client_id = addslashes(htmlspecialchars(trim($p['client_id'])));
            if ($client_id > 0) {
                $q .= "\nAND cc.client_id = '". $client_id . "' ";
            }
        }
        
        $left = "\nFROM campaign_keyword AS ck ".
                "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
               // "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ";
        $where = "\nWHERE 1 {$q} AND ck.status!='D' AND ck.keyword_status!='0' ";
        
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ". $left . $where;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }
        if ($is_paged) {
            $perpage = 50;
            if (trim($_GET['perPage']) > 0) {
                $perpage = $_GET['perPage'];
            }

            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems' => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
        }
        $q = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `ck`.`copy_writer_id`,      `ck`.`editor_id`,`ck`.`keyword`, `ck`.`article_type`,`ck`.`date_start`, `ck`.`date_end`," . 
            "ar.article_id,  ar.article_status,  cl.company_name, cc.campaign_name,  " . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name  \n" . $left . $where;
        $q .= "\nGROUP BY ar.article_id";
        if ($is_paged) {
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        } else {
            $rs = &$conn->Execute($q);
        }
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $fields['overdue'] = ($today_time - strtotime($fields['date_end']))/86400;
                $result[$i] = $fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        if ($is_paged) {
            return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     'show_cb' => $show_cb
                     );
        } else {
            return $result;
        }

    }//end getOverdueArticles()


    // added by nancy xu 2010-12-21 15:18
    /**
     * Search Client Campaign info.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function getEditRequest($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "\nAND ck.campaign_id = '".$campaign_id . "' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        } else {
            $q .= "\nAND ck.copy_writer_id > 0 ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        
        $q .= "\nAnd (ar.article_status = '2' OR ar.article_status = '3') ";

        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "\nAND cc.client_id = '".Client::getID()."' ";
        } else if (User::getRole() == 'editor') {
            $q .= "\nAND ck.editor_id = '".User::getID()."' ";
        }
        
        $left = "\nFROM campaign_keyword AS ck ".
                "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
               // "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ";
        $where = "\nWHERE 1 {$q} AND ck.status!='D' AND ck.keyword_status!='0' ";
        
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ". $left . $where;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            //$feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $q = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `ck`.`copy_writer_id`,      `ck`.`editor_id`,`ck`.`keyword`, `ck`.`article_type`,`ck`.`date_start`, `ck`.`date_end`," . 
            "ar.article_id,  ar.article_status,  cl.company_name, cc.campaign_name, ar.rejected, " . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name  \n" . $left . $where;
        $q .= "\nGROUP BY ar.article_id";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $fields['overdue'] = ($today_time - strtotime($fields['date_end']))/86400;
                $result[$i] = $fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     'show_cb' => $show_cb
                     );

    }//end getEditRequest()

    function sendReminderEmail($p = array())
    {
        global $conn;
        $keyword_id = $p['keyword_id'];
        if (empty($keyword_id)) {
            $feedback = "Please Choose article";
            return false;
        }
        $overdue = $p['overdue'];
        if (empty($overdue)) {
            $feedback = "This article  is not overdue";
            return false;
        }
        $left = "\nFROM campaign_keyword AS ck ".
                "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ";
        $q = "SELECT   `ck`.`keyword`, `ck`.`date_end`,  cc.campaign_name,  " . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , uc.email as uc_email, CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name  \n" . $left . ' WHERE ck.keyword_id = ' . $keyword_id . " AND ck.status!='D' " ;
        $info = $conn->GetRow($q);
        $body = ' Dear ' .$info['uc_name'] . ':

You article ' . $info['keyword']. ' from campaign ' . $info['campaign_name'] . ' is ' . $overdue . ' days past due.  Please finish your work or request an extension. 

Sincerely,

CopyPress
';
        $body = nl2br($body);
        $subject = 'Reminder Email';
        send_smtp_mail($info['uc_email'], $subject, $body);
    }

    /**
     * Search Keywords.,
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function keywords($p = array(), $type='update-keyword-instructions')
    {
        global $conn, $feedback;
        global $g_pager_params;

        $q = "";

        switch ($type) {
        case 'update-keyword-instructions':
            $q .= "\n AND (ar.article_status != 5 AND ar.article_status!=6) ";
            break;
        }
        
        // added by snug xu 2006-11-24 20:34
        if (User::getRole() == 'agency' )
        {
            $q .= "\n AND cl.agency_id = '" . User::getID() . "'";
        }

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            if (is_numeric($campaign_id)) {
                $q .= "\nAND ck.campaign_id =  '".$campaign_id."' ";
            } else {
                $q .= "\nAND ck.campaign_id IN ( ".$campaign_id." ) ";
            }
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "\nAND ck.keyword_id = '".$keyword_id."' ";
        }

        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "\nAND ck.creation_user_id = '".$creation_user_id."' ";
        }

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "\nAND ck.article_type = '".$article_type."' ";
        }
        $keyword_category = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        if ($keyword_category != '') {
            $q .= "\nAND ck.keyword_category = '".$keyword_category."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "\nAND ck.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "\nAND ck.date_end <= '".$date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "\nAND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }
        
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else
        {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)")." ";
            }
        }
        require_once CMS_INC_ROOT.'/Client.class.php';
        if (client_is_loggedin()) {
            $q .= "\nAND cc.client_id = '".Client::getID()."' ";
        }
        
        $where = "\nWHERE 1 {$sqls['where']} {$q} AND ck.status!='D' ";
        
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ".
                "\nFROM campaign_keyword AS ck ".
                "\nLEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
                $sqls['left'] . 
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
                $where;
        $rs = &$conn->Execute($query);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));
        $q = "SELECT DISTINCT `ck`.`keyword_id`, `ck`.`campaign_id`, `cc`.`campaign_name`, `ck`.`copy_writer_id`, ". 
            "`ck`.`editor_id`, `ck`.`keyword`, `ck`.`article_type`, `ck`.`keyword_description`, " . 
            "`ck`.`date_start`, `ck`.`date_end`, `ck`.`creation_user_id`, `ck`.`creation_role`, " .
            "`ck`.`keyword_category`, `ck`.`status`, `ck`.`cost_per_article`, `ck`.`is_sent`,`ar`.`cp_updated`, " . 
            "ar.article_id, ar.article_number, ar.approval_date,  " . 
            "ar.target_pay_month, ar.is_canceled, ar.curr_dl_time, " .
            "ar.article_status, ar.checking_url,  cl.user_name, cl.company_name, cc.campaign_name, ar.total_words as word_count , " . 
            "CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name , cu.user_name AS cu_name, ar.body, ar.richtext_body  \n" . 
             "FROM campaign_keyword AS ck \n".
             "LEFT JOIN articles AS ar ON (ar.keyword_id = ck.keyword_id) ".
             $sqls['left'] .
             "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) \n".
             "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
             "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) \n".
             "LEFT JOIN client_campaigns AS cc ON (cc.campaign_id = ck.campaign_id) \n".
             "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id)".
             $where  ;
        //$q .= "\nGROUP BY ar.article_id";
        $q .= "\nORDER BY ar.article_id ";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result,
                     'count' => $count,
                     'show_cb' => $show_cb
                     );

    }//end search()

    function batchUpdateKeywordDescription($ids, $keyword_description)
    {
        global $feedback, $conn;
        if (empty($ids)) {
            $feedback = 'You must choose one keyword at least';
            return false;
        }
        foreach  ($ids as $k => $id) {
            $ids[$k] = addslashes(htmlspecialchars(trim($id)));
        }
        $keyword_description = addslashes(htmlspecialchars(trim($keyword_description)));
        if (empty($keyword_description)) {
            $feedback = 'Please specify the keyword description';
            return false;
        }
        $sql = 'UPDATE `campaign_keyword` SET keyword_description=\'' . $keyword_description . '\' WHERE keyword_id IN (' . implode(",", $ids). ')';
        $conn->Execute($sql);
    }

    function updateArticleType($ids, $article_type)
    {
        global $feedback, $conn;
        if (empty($ids)) {
            $feedback = 'You must choose one keyword at least';
            return false;
        }
        foreach  ($ids as $k => $id) {
            $ids[$k] = addslashes(htmlspecialchars(trim($id)));
        }
        $article_type = addslashes(htmlspecialchars(trim($article_type)));
        if (strlen($article_type) == 0) {
            $feedback = 'Please specify article type';
            return false;
        }
        $sql = 'UPDATE `campaign_keyword` SET article_type=\'' . $article_type . '\' WHERE keyword_id IN (' . implode(",", $ids). ')';
        $conn->Execute($sql);
        $feedback = 'Success!';
    }

    // added by nancy xu 2012-05-21 
    function getKeywordsIDs($p = array())
    {
        $selected_items= $p['isUpdate'];
        $ids = array();
        $keywords = $p['keyword_id'];
        foreach ($selected_items as $value) {
            $ids[] = $keywords[$value];
        }
       
        return $ids;
    }

    function updateArticleTypeByParam($p = array())
    {
        global $feedback, $conn;
        $ids = self::getKeywordsIDs($p);
        $article_type = $p['article_type'];
        if (empty($ids)) {
            $feedback = 'You must choose one keyword at least';
            return false;
        }
        foreach  ($ids as $k => $id) {
            $ids[$k] = addslashes(htmlspecialchars(trim($id)));
        }
        $article_type = addslashes(htmlspecialchars(trim($article_type)));
        if (strlen($article_type) == 0) {
            $feedback = 'Please specify article type';
            return false;
        }
        if (isset($p['changed_all']) && !empty($p['changed_all'])) {
            $campaign_id = $p['campaign_id'];
            $sql = 'UPDATE `campaign_keyword` as ck,  articles AS ar SET ck.article_type=\'' . $article_type . '\' WHERE ar.keyword_id=ck.keyword_id AND ck.campaign_id = ' . $campaign_id  . ' AND ck.status!=\'D\' AND ar.article_status!=5 AND ar.article_status!=6';
            $conn->Execute($sql);
            $feedback = 'Success!';
        } else {
            Campaign::updateArticleType($ids, $article_type);
        }
        return true;
    }

    function batchUpdateKeywordDescriptionByParam($p = array())
    {
        global $feedback, $conn;
        $ids = self::getKeywordsIDs($p);        
        if (empty($ids)) {
            $feedback = 'You must choose one keyword at least';
            return false;
        }
        foreach  ($ids as $k => $id) {
            $ids[$k] = addslashes(htmlspecialchars(trim($id)));
        }
        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if (empty($keyword_description)) {
            $feedback = 'Please specify the keyword description';
            return false;
        }

        if (isset($p['changed_all']) && !empty($p['changed_all'])) {
            $campaign_id = $p['campaign_id'];
            $sql = 'UPDATE `campaign_keyword` as ck,  articles AS ar SET ck.keyword_description=\'' . $keyword_description . '\' WHERE ar.keyword_id=ck.keyword_id AND ck.campaign_id = ' . $campaign_id  . ' AND ck.status!=\'D\' AND ar.article_status!=5 AND ar.article_status!=6';
            $conn->Execute($sql);            
        } else {
            Campaign::batchUpdateKeywordDescription($ids, $keyword_description);
        }
        $feedback = 'Success!';
    }
    // end

    function updateArticleStatus($article_id, $field, $value=1)
    {
        global $conn;
        if (!empty($article_id)) {
            $sql = 'UPDATE article_status SET ' . $field . '=' . $value; 
            if (is_array($article_id)) {
                foreach ($article_id as $k => $id) {
                    $article_id[$k] = addslashes(htmlspecialchars(trim($id)));
                }
                $sql .= ' WHERE article_id IN (\'' . implode("','", $article_id) . '\')';
            } else {
                $article_id = addslashes(htmlspecialchars(trim($article_id)));
                $sql .= ' WHERE article_id=\'' . $article_id . '\'';
            }
            $conn->Execute($sql);

            if ($field == 'completed') {
                self::sendEmail2Writer($article_id, 4);
            }
        }
    }

    function sendEmail2Writer($article_id, $article_status){
        global $conn, $mailer_param;
        if (empty($article_ids) || empty($article_status)) {
            return true;
        }

        switch ($article_status) {
            case 4:
                $subject = "Editor Approved Notification";
                $bd = 'editor approved';
                break;
            default:
                $subject = "Editor Approved Notification";
                $bd = 'editor approved';
        }

        $sql = 'SELECT ar.article_id, ar.title, cc.campaign_name, ck.copy_writer_id, u.first_name, u.email, cc.campaign_id ';
        $sql .= 'FROM articles AS ar ';
        $sql .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ';
        $sql .= 'LEFT JOIN users AS u ON (u.user_id=ck.copy_writer_id) ';
        $sql .= 'LEFT JOIN client_campaigns AS cc ON (cc.campaign_id=ck.campaign_id) ';
        if (is_array($article_id)) {
            $sql .= " WHERE ar.article_status = '".$article_status."' AND ck.status!='D' " .
                    " AND ar.article_id IN (\'" . implode("','", $article_id) . "\')";
        } else {
            $article_id = addslashes(htmlspecialchars(trim($article_id)));
            $sql .= " WHERE ar.article_status = '".$article_status."' AND ck.status!='D' AND ar.article_id=\'" . $article_id . "\'";
        }
        $sql .= "ORDER BY ck.copy_writer_id ";
        $result = $conn->GetAll($sql);

        if( count( $result ) ) {
            $user_id = 0;
            $body = "";
            $prev_email = "";
            foreach( $result as $k => $user) {
                if ($user_id == 0) $body = "<div>Hey {$user['first_name']}!</div><br />";
                if ($user_id > 0 && $user_id != $user['copy_writer_id'] && !empty($subject)) {
                    send_smtp_mail($prev_email, $subject, $body, $mailer_param);
                    $body = "<div>Hey {$user['first_name']}!</div><br />";
                }
                $user_id = $user['copy_writer_id'];
                $prev_email = $user["email"];

                $body .= "<div>The article '".$user['title']."' for the campaign '".$user['campaign_name']."' has been ".$bd.".  </div><br />";

            }
            send_smtp_mail($prev_email, $subject, $body, $mailer_param);
            //echo $body;
        }
    }

    function getSynchronizationArticleId($field)
    {
        global $conn;
        $sql = 'SELECT article_id FROM article_status ';
        $sql .= ' WHERE ' . $field . '=1';
        if ($field == 'started') $sql .= ' AND completed=0';
        return $conn->GetCol($sql);
    }
    
    function getDuplicatedArticles($p = array(), $is_page = true)
    {
        global $conn, $feedback, $g_pager_params;
        foreach ($p as $k => $v) {
            $p[$k] = addslashes(htmlspecialchars(trim($v)));
        }
        extract($p);
        $qws = array();
        if (isset($cp_id) && !empty($cp_id)) {
            $qws[] = 'ck.copy_writer_id=\'' . $cp_id . '\'';
        }

        if (isset($editor_id) && !empty($editor_id)) {
            $qws[] = 'ck.editor_id=\'' . $editor_id . '\'';
        }

        if (isset($cid) && !empty($cid)) {
            $qws[] = 'ck.campaign_id=\'' . $cid . '\'';
        }

        if ($opt != '') {
            $is_page = false;
        }

        $q = 'SELECT ar.article_id, ck.keyword_id, ar.checking_url, ck.keyword,   cc.campaign_name, u.user_name,ue.user_name as editor, aa.created_time AS detected_date ';
        $f = 'FROM articles AS ar ';
        $f .= 'LEFT JOIN campaign_keyword AS ck ON (ck.keyword_id=ar.keyword_id) ' . "\n";
        $f .= 'LEFT JOIN client_campaigns AS cc ON (ck.campaign_id=cc.campaign_id) '. "\n";
        $f .= 'LEFT JOIN users AS u ON (u.user_id=ck.copy_writer_id) '. "\n";
        $f .= 'LEFT JOIN users AS ue ON (ue.user_id=ck.editor_id) '. "\n";
        $f .= 'LEFT JOIN article_action AS aa ON (aa.article_id=ar.article_id AND aa.status=\'1\' AND aa.new_status=\'1gd\' AND aa.curr_flag=1) ';
        $qws[] = 'ar.article_status=\'1gd\'';
        if (!empty($qws)) {
            $w = ' WHERE ' . implode(" AND ", $qws);
            $q .= $f . $w;
        } else {
            $w = '';
        }
        if ($is_page) {
            $sql = 'SELECT COUNT(*) ' . $f . $w;
            $count = $conn->GetOne($sql);
            $perpage = 50;
            if (trim($_GET['perPage']) > 0) {
                $perpage = $_GET['perPage'];
            }

            require_once 'Pager/Pager.php';
            $params = array(
                'perPage'    => $perpage,
                'totalItems'   => $count
            );
            $pager = &Pager::factory(array_merge($g_pager_params, $params));
            list($from, $to) = $pager->getOffsetByPageId();
            $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
            if ($rs) {
                $result = array();
                $i = 0;
                while (!$rs->EOF) {
                    $result[$i] = $rs->fields;
                    $rs->MoveNext();
                    $i ++;
                }
                $rs->Close();
            }

            return array('pager'  => $pager->links,
                         'total'  => $pager->numPages(),
                         'count'  => $count,
                         'result' => $result
           );
        } else {
            return $conn->GetAll($q);
        }
    }
    
    // added by nancy xu 2010-09-29 13:51
    /**
     * replicate keywords from the first campaign
     */
    function getBatchKeywrodInfo($campaign_id)
    {
        global $conn;
        // $conn->debug = true;
        $sql = 'SELECT keyword, article_type, optional1, optional2, optional3, optional4, mapping_id FROM campaign_keyword WHERE campaign_id= \'' . $campaign_id . '\'';
        $rs = $conn->GetAll($sql);
        $result = array();
        $article_types = array();
        foreach ($rs as $row) {
            foreach ($row as $k => $v) {
                if (!isset($result[$k])) {
                    $result[$k] = array();
                }
                $result[$k][] = $v;
            }
        }
        if (isset($result['article_type'])) {
            $article_types = $result['article_type'];
            unset($result['article_type']);
        }
        $article_types = array_unique($article_types);
        $result['article_type'] = $article_types[0];
        foreach ($result as $k => $row) {
            if (is_array($row)) $result[$k] = implode("\n", $row);
        }
        return $result;
    }
    // end

    function getKeywords($conditions = array(), $fields = array()) 
    {
        global $conn;
        $sql  = "SELECT " . (empty($fields) ? " * " : implode(", ", $fields)) . " FROM campaign_keyword AS ck ";
        $sql .= "LEFT JOIN articles AS ar ON(ar.keyword_id=ck.keyword_id) ";
        $sql .= "LEFT JOIN client_campaigns AS cc ON(cc.campaign_id=ck.campaign_id) ";
        $sql .= "LEFT JOIN users AS u ON (ck.copy_writer_id=u.user_id) ";
        $sql .= "LEFT JOIN users AS ue ON (ck.editor_id=ue.user_id) ";
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(" AND ", $conditions);
        }
        return $conn->GetAll($sql);
    }


    /**
     * Search forecast Keywords that need to search
     *
     * @param array $p  the form submited value.
     * 
     * @return array
     * @access public
     */
    function searchForecastKeyword($p = array())
    {
        global $conn, $feedback;

        global $g_pager_params;
         
        $show_cb = false;

        $q = "";

        $campaign_id = addslashes(htmlspecialchars(trim($p['campaign_id'])));
        if ($campaign_id != '') {
            $q .= "\nAND ck.campaign_id IN ( ".$campaign_id." ) ";
        }
        $keyword_id = addslashes(htmlspecialchars(trim($p['keyword_id'])));
        if ($keyword_id != '') {
            $q .= "\nAND ck.keyword_id = '".$keyword_id."' ";
        }
        $copy_writer_id = addslashes(htmlspecialchars(trim($p['copy_writer_id'])));
        $editor_id = addslashes(htmlspecialchars(trim($p['editor_id'])));
        $user_id = $p['user_id'];
        $role = addslashes(htmlspecialchars(trim($p['role'])));
        if ($role == 'copy writer') {
            $user_field = 'copy_writer_id';
            $copy_writer_id = $user_id;
            $cost_field = 'cp_cost';
            $cost_article = 'cp_article_cost';
        } else {
            $user_field = 'editor_id';
            $editor_id = $user_id;
            $cost_field = 'editor_cost';
            $cost_article = 'editor_article_cost';
        }
        if ($copy_writer_id != '') {
            $q .= "\nAND ck.copy_writer_id = '".$copy_writer_id."' ";
        }
        
        if ($editor_id != '') {
            $q .= "\nAND ck.editor_id = '".$editor_id."' ";
        }
        $creation_user_id = addslashes(htmlspecialchars(trim($p['creation_user_id'])));
        if ($creation_user_id != '') {
            $q .= "\nAND ck.creation_user_id = '".$creation_user_id."' ";
        }

        $article_type = addslashes(htmlspecialchars(trim($p['article_type'])));
        if ($article_type != '') {
            $q .= "\nAND ck.article_type = '".$article_type."' ";
        }
        $keyword_category = addslashes(htmlspecialchars(trim($p['keyword_category'])));
        if ($keyword_category != '') {
            $q .= "\nAND ck.keyword_category = '".$keyword_category."' ";
        }
        $date_start = addslashes(htmlspecialchars(trim($p['date_start'])));
        if ($date_start != '') {
            $q .= "\nAND ck.date_start >= '".$date_start."' ";
        }
        $date_end = addslashes(htmlspecialchars(trim($p['date_end'])));
        if ($date_end != '') {
            $q .= "\nAND ck.date_end <= '".$date_end."' ";
        }

        $keyword_description = addslashes(htmlspecialchars(trim($p['keyword_description'])));
        if ($keyword_description != '') {
            $q .= "\nAND cc.keyword_description LIKE '%".$keyword_description."%' ";
        }
        
        $article_status = $p['article_status'];
        if (is_array($article_status) && !empty($article_status))
        {
            $q .= "\nAND ar.article_status IN ('". implode("', '", $article_status)."') ";
        }
        else
        {
            $article_status = addslashes(htmlspecialchars(trim($article_status)));
            if ($article_status != '') {
                if ($article_status == -1) {
                    $q .= "\nAND ck.copy_writer_id = '0' ";
                } else {
                    $q .= "\nAND ar.article_status = '".$article_status."' ";
                }
            }
        }
        
        $current_month = mysql_escape_string(htmlspecialchars(trim($p['month'])));
        // modified by snug 2007-05-13 10:35
        if( strlen($current_month) == 0 ) {
            $current_month = changeTimeToPayMonthFormat($now);
        }
        $param = getForecastDates($current_month);
        $role = $p['role'];
        $param['user_id'] = $p['user_id'];
        $param['user_type'] = $role;
        $param['type']     = 'f-keyword-adjust';
        $sqls = User::forecastConditionOrSql($param, $role);
        if (!empty($sqls['where'])) $sqls['where'] = ' AND  ' . $sqls['where'];

        if (trim($p['keyword']) != '') {
            require_once CMS_INC_ROOT.'/Search.class.php';
            $search = new Search($p['keyword'], "AND"); // use AND operator
            if ($search->getError() != '') {
                //do nothing
                $feedback = $search->getError();
            } else {
                $q .= "\nAND ".$search->getLikeCondition("CONCAT(cl.user_name, cl.company_name, cl.company_address, cl.city, cl.state, cl.zip, cl.company_phone, cl.email, cc.campaign_name, cc.campaign_requirement, ck.keyword, ck.keyword_description)")." ";
            }
        }
        $where = "\nWHERE 1 {$sqls['where']} {$q}";
		$query = "\nSELECT COUNT(DISTINCT ck.keyword_id) AS count ".
                $sqls['from'] . 
                "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) ".
                "\nLEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
                "\nLEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) ".
                "\nLEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) ".
                $where;
        $count = $conn->GetOne($query);

        if ($count == 0 || !isset($count)) {
            $feedback = "Couldn\'t find any information,Please try again";//找不到相关的信息，请重新设置搜索条件
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array(
            'perPage'    => $perpage,
            'totalItems' => $count
        );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT `ck`.`keyword_id`, `ck`.`campaign_id`, `ck`.`copy_writer_id`, `ck`.`editor_id`, `ck`.`keyword`, \n `ck`.`article_type`, `ck`.`keyword_description`, `ck`.`date_start`, `ck`.`date_end`,  `ck`.`status`, `ck`.`is_sent`, \n `ar`.`cp_updated`, ar.article_id, ar.article_number, ar.google_approved_time, ar.approval_date, ar.client_approval_date, ar.article_status,  cl.client_id, cl.user_name, cl.company_name, cc.campaign_name, IF (ar.total_words>0 , ar.total_words, if (cc.max_word >0, cc.max_word, 0)) AS word_count ,  CONCAT(uc.first_name, ' ', uc.last_name) AS uc_name , \n CONCAT(ue.first_name, ' ', ue.last_name) AS ue_name, at.pay_by_article AS at_checked, at.{$cost_field} AS at_word_cost ,at.{$cost_article} AS at_article_cost, \n ac.pay_by_article AS ac_checked, ac.{$cost_field} AS ac_word_cost ,ac.{$cost_article} AS ac_article_cost, \n at.type_name AS article_type_name \n" . 
         $sqls['from'] .
         "\nLEFT JOIN users AS uc ON (ck.copy_writer_id = uc.user_id) \n".
         "LEFT JOIN users AS ue ON (ck.editor_id = ue.user_id) ".
         "LEFT JOIN users AS cu ON (ck.creation_user_id = cu.user_id) \n".
         "LEFT JOIN `client` AS cl ON (cl.client_id = cc.client_id) \n".
         $where;
        $q .= "\nGROUP BY ar.article_id";
        $q .= "\nORDER BY ar.client_approval_date";
        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $fields = $rs->fields;
                $result[$i] = $fields;
				/****决定是否显示move to next pay period按纽*****/
                $user_id = $fields[$user_field];
                
                // added by nancy xu 2011-05-26 16:05
                $tmp = getCostAndPayType($fields); /* array('cost_per_unit' => 'xxx', 'checked' => 'xxx')*/
                extract($tmp);
                $cost_per_article = ($checked == 0) ? $fields['word_count'] * $cost_per_unit : $cost_per_unit;
                // end
                
                $result[$i]['payment_flow_status'] = $users[$user_id][$current_month]['payment_flow_status'];
                $result[$i]['user_id'] = $user_id;
                $result[$i]['article_cost'] = $cost_per_article;
                $rs->MoveNext();
                $i ++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result,
                     );

    }//end search()
}
?>