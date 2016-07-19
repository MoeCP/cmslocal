<?php
//ini_set('max_excute_time', 0);
//$g_current_path = "client_campaign";
$g_current_path = "article";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

require_once CMS_INC_ROOT.'/Client.class.php';
if ((!user_is_loggedin() || (User::getPermission() < 3 && User::getPermission() <> 2)) && !client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
require_once CMS_INC_ROOT.'/article_search.class.php';
require_once 'HTTP' . DS . 'Client.php';
require_once "File/CSV.php";


if (!empty($_GET)) {
    //##$_GET['perPage'] = isset($_GET['perPage']) ?  $_GET['perPage'] : 25;
    //Export ALL results, but should set it less than 10000, prevent the export burns out the memory.
    $_GET['perPage'] = 9999;
    $p = $_GET;

    $kso = trim($p['kso']);
    if (!empty($p['fst'])) {
        if ($kso <= 3) {
            $result = search_private_index($p);
            $total = $result['total'];
            if ($total > 0) {
                $p['article_id'] = explode(",", $result['aids']);
                $wordcount = $result['wordcount'];
                //###$smarty->assign('wordcount', $wordcount);
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
    $sresult = $search['result'];
    $spager = $search['pager'];
    $stotal = $search['total'];
    $scount = $search['count'];
}

unset($_GET['sort']);
$all_editor = User::getAllUsers($mode='id_name_only', $user_type = 'all_editor');
asort($all_editor);
$all_writer = User::getAllUsers($mode='id_name_only', $user_type = 'copy writer');
asort($all_writer);
$all_clients = Client::getAllClients('id_name_only', false);
asort($all_clients);

$article_type = $g_tag['leaf_article_type'];



//==========================
$filename = 'Article-Search-list-' . time() . '.csv';
$file = $g_article_storage . $filename;
$article_statuses = $g_tag['article_status'];
if (!empty($sresult)) {
    $conf = array(
        //#'fields' => count($fields),
        'fields' => 13,
        'sep' => ',',
        'quote' => '"',
        'crlf' => "\n",
    );

    $data = array("No.", "Article Number","Article Title","Keyword","Client","Campaign Name","Editor","Copywriter",
                  "Article Type","Number of Words","Keyword Match in Content","Submit Date","Cost");
    File_CSV::write($file, $data, $conf);
    $i = 1;
    foreach ($sresult as $row) {
        //##if(isset($row['status'])) $row['status'] = $statuses[$row['status']];
        $data = array();
        $data["no"]             = $i;
        $data["article_number"] = $row["article_number"];
        $data["title"]          = $row["title"];
        $data["keyword"]        = $row["keyword"];
        $data["client_id"]      = $all_clients[$row["client_id"]];
        $data["campaign_name"]  = $row["campaign_name"];
        $data["editor_id"]      = str_replace('&nbsp;', ' ', $all_editor[$row["editor_id"]]);
        $data["copy_writer_id"] = str_replace('&nbsp;', ' ', $all_writer[$row["copy_writer_id"]]);
        $data["article_type"]   = $article_type[$row["article_type"]];
        $data["word_count"]     = $row["word_count"];
        $data["keymatch"]       = empty($wordcount) ? 0 : $wordcount[$row["article_id"]];
        $data["cp_updated"]     = ($row["article_status"]=='0' || $row["article_status"]=="") ? "n/a" : $row["cp_updated"];
        $data["cost_for_article"] = $row["cost_for_article"];
        $data = array_values($data);
        File_CSV::write($file, $data, $conf);
        $i++;
    }
}

//==========================

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=$filename");
header("Content-Transfer-Encoding: binary ");
if (file_exists($file)) {
    echo file_get_contents($file);
}
exit();
?>