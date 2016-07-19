<?php

/*
* use google search API check whether articles is copied from other sites or not
*/
require_once 'pre_cron.php';//parameter settings

global $mailer_param;

// get total google clean articles in today
$p['article_status'] = '1gc';
$today          = date("Y-m-d");
$date_start   = $today . " 00:00:00";
$date_end     = $today . " 23:59:59";
$p['where']    = "ar.google_approved_time <= '{$date_end }' AND ar.google_approved_time >= '{$date_start }' ";
$p['columns']  = "count(ar.article_id) AS num";
$p['fields']      = 'num';
$ret = Article::getList($p);
if ($ret[0] > 0) {
    $p['columns'] = 'DISTINCTROW ck.editor_id';
    $p['fields']    = 'editor_id';
    $editors       = Article::getList($p);
    unset($p['fields']);
    $subject = "Today's Google Clean Articles";
    foreach ($editors as $k => $editor_id) {
        $body = '';
        $info =User::getInfo($editor_id);
        $p['columns'] = 'ar.article_id, ck.keyword_id, ck.keyword, ar.title, cc.campaign_name, u.user_name as writer, ck.date_start, ck.date_end, ck.campaign_id ';
        $p['editor_id'] = $editor_id;
        $aritcle_list = Article::getList($p);
        $body = '<table align="right" width="100%"  cellspacing="1" cellpadding="4" >';
        $body .= '<tr style="font-family: \'Arial\', \'Simsun\', \'Verdana\', \'Helvetica\';font-weight: bold;font-size: 12px;color: #ffffff;text-decoration: none;background-color: #356799;">';
        $body .= '<th align="center"  height="25">Keyword</th>';
		$body .= '<th align="center" >Campaign Name</th>';
		$body .= '<th align="center" >Copywriter</th>';
		$body .= '<th align="center" >Start Date</th>';
		$body .= '<th align="center" >Due Date</th>';
		$body .= '</tr>';
        foreach ($aritcle_list as $k => $article) 
        {
            $body .= '<tr style="background:#eeeecc;">';
            $body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;">';
            $body .= "<a href=" . $domain .
                "approve_article.php?article_id=" . $article['article_id'] . "&keyword_id=" . $article['keyword_id'] . "&campaign_id=". $article['campaign_id'] . ">" . $article['title'] . "</a>";
            $body .= "</td>";
            $body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;">';
            $body .= $article['campaign_name'];
            $body .= "</td>";
            $body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;">';
            $body .= $article['writer'];
            $body .= "</td>";
            $body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;">';
            $body .= $article['date_start'];
            $body .= "</td>";
            $body .= '<td align="left" height="25" sytle="border-bottom: 1px solid #cbcbae;">';
            $body .= $article['date_end'];
            $body .= "</td>";
            $body .= "</tr>";
        }
        $body .= "</table>";
        send_smtp_mail($info['email'], $subject, $body, $mailer_param);
        unset($body);
        sleep(20);
    }
}

?>
