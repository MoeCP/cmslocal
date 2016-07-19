<?php
error_reporting(E_ALL);
require_once('../pre.php');//加载配置信息

if (!user_is_loggedin() || User::getPermission() < 5) { // 4=>5
    header("Location: http://".$_SERVER['HTTP_HOST'].$logout_folder."/logout.php");
    exit;
}


require_once CMS_INC_ROOT.'/otherutils.php';
if (!empty($_GET["article_id"]) && !empty($_GET["invoice_date"])) {
    $_GET['invoice_date'] = date("Y-m-d", strtotime($_GET['invoice_date']));
    $_GET["article_id"] = (int)$_GET["article_id"];
    $data = array("invoice_date"=>$_GET["invoice_date"]);
    $p = array("article_id='".$_GET['article_id']."'");
    $uabp = OtherUtilsForArticle::updateArticleByParam($data, $p);
    if ($uabp) {
        echo json_encode(array("success"=>true, "msg"=>"Update Invoice Date Successfully."));
    } else {
        echo json_encode(array("success"=>false, "msg"=>"Failure, Please try it again."));
    }
}
exit;
?>