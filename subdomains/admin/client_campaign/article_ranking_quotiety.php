<?php
$g_current_path = "preference";
global $feedback;
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

// let users who role is agency access this page
//##if (!user_is_loggedin() && (User::getPermission() < 5)) { // 2=>3
if (!user_is_loggedin() || (User::getPermission() < 5) 
    || (User::getRole() == 'admin' && User::getUserType() == -1)) { // 2=>3
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/Pref.class.php';
require_once CMS_INC_ROOT.'/article_ranking.class.php';

if (count($_POST))
{
    $opt = $_POST['operation'];
    if ($opt == 'save')
    {  
        if (ArticleRanking::storeAllRankingQuotiety($_POST)) {
            $feedback = "Succeed";
            echo "<script>window.location.herf='/client_campaign/article_ranking_quotiety.php';</script>";
        } else {
            $feedback = "Failed!";
        }
    }
}

$fields = ArticleRanking::getQuotietyAllInfo();
foreach ($fields as $row) {
    $smarty->assign($row['pref_field'], $row);
}

$smarty->assign('feedback', $feedback);
$smarty->display('client_campaign/article_ranking_quotiety.html');
?>