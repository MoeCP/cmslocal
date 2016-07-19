<?php
require_once 'pre_cron.php';//parameter settings

$param['status'] = 5;
$all_articles = Article::getWeekArticleReportByStatus($param);

$body .= '<table align="right" width="100%"  cellspacing="1" cellpadding="4" >
            <tr style="font-family: \'Arial\', \'Simsun\', \'Verdana\', \'Helvetica\';font-weight: bold;font-size: 12px;color: #ffffff;text-decoration: none;background-color: #356799;">
            <th align="center" height="25" >Article ID</th>
            <th align="center" height="25" >Article Title</th>
            <th align="center" height="25" >Campaign Name</th>
            <th align="center" height="25" >Date Approved</th>
            </tr>';
foreach($all_articles as $k => $article)
{
    $body .= '<tr style="background:#eeeecc;">';
    $body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $article['article_id'] . '</td>';
    $body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $article['title'] . '</td>';
    $body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $article['campaign_name'] . '</td>';
    $body .= '<td align="center" sytle="border-bottom: 1px solid #cbcbae;">' . $article['approval_date'] . '</td>';
    $body .= "</tr>";
}
$body .= "</table>";
$subject = 'Summary of Articles that client Approved from Last Thursday to Today';
global $mailer_param;
$mail_to = array(
    "cptech@copypress.com",
);
send_smtp_mail($mail_to, $subject, $body, $mailer_param);
?>