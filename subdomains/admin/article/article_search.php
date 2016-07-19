<?php
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once CMS_INC_ROOT.'/Client.class.php';
if (client_is_loggedin()) {
    require_once('../client/cms_client_menu.php');
} else {
    require_once('../cms_menu.php');
}
if ((!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission() <> 2)) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/article_search.class.php';
require_once 'HTTP' . DS . 'Client.php';


if (!empty($_GET)) {
    $_GET['perPage'] = isset($_GET['perPage']) ?  $_GET['perPage'] : 25;
    $p = $_GET;
     $kso = trim($p['kso']);
    if (!empty($p['fst'])) {
        if ($kso <= 3) {
            $result = search_private_index($p);
            $total = $result['total'];
            if ($total > 0) {
                $p['article_id'] = explode(",", $result['aids']);//##8/9/2014
                //###if (empty($p["date_start"])) $p['article_id'] = explode(",", $result['aids']);
                $wordcount = $result['wordcount'];
                $smarty->assign('wordcount', $wordcount);
            }
        } else {
            $keyword = trim($p['fst']);
            $total = -1;
            switch ($kso) {
            case 4:
                $article_ids = ArticleSearch::getArticleIdsByTagName($keyword);
                if (!empty($article_ids)) {
                    $p['article_id'] = $article_ids;
                } else {
                    $total = 0;
                }
                break;
            case 5:
                $p['article_id'] = $keyword;
                break;
            }
        }
    } else {
        $total = -1;
        $p['article_status'] = array(5,6);
    }
} else {
    $total = 0;
    $p['article_status'] = array(5,6);
}

//pr($p, true);

$search = ArticleSearch::searchKeyword($p, $total);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
    $smarty->assign('show_cb', $search['show_cb']);
}

$smarty->assign('campaign_id', $campaign_id);
if (user_is_loggedin()) {
    $smarty->assign('login_role', User::getRole());
    $smarty->assign('login_op_id', User::getID());
} else {
    $smarty->assign('login_role', 'client');
    $smarty->assign('login_op_id', Client::getID());
}

$smarty->assign('article_statuses', $g_tag['article_status']);
unset($_GET['sort']);
$smarty->assign('query_string', http_build_query($_GET));
$smarty->assign('login_permission', User::getPermission());
$smarty->assign('feedback', $feedback);
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$all_writer = User::getAllUsers($mode='id_name_only', $user_type = 'copy writer');
asort($all_writer);
$clients = Client::getAllClients('id_name_only', false);
asort($clients);
$smarty->assign('all_editor', $all_editor);
$smarty->assign('all_writer', $all_writer);
$smarty->assign('all_clients', $clients);
$search_options = array(
    1=>'Title', 2=> 'Content', 3=> 'Title and Content',  4=> 'Tags',  5=> 'Article ID'
);
$smarty->assign('search_options', $search_options);
$smarty->assign('article_type', $g_tag['leaf_article_type']);
$smarty->display('article/article_search.html');
?>