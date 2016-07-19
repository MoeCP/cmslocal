<?php
/*
// no cache
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

require_once 'pre.php';
require_once CMS_INC_ROOT.'/Client.class.php';

if (client_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
    exit;
}

/*
if (isset($_POST['sc_img']) && strtolower($_POST['sc_img']) != strtolower($_SESSION['sc_img'])) { // case insensitive
    $smarty->assign('feedback', 'Security Code is incorrect, Please go back and type it exactly as given');
    $smarty->display('client/login.html');//验证码填写有误，请重新填写
    exit;
}
*/

//nuke old session
$_SESSION = array();

$smarty->assign('company_name', $g_company_name);

if (trim($_POST['user_name']) != '' && trim($_POST['user_pw']) != '') {
    $sess = Client::getLogin($_POST['user_name'], $_POST['user_pw']);
    if ($sess) {
        Client::setLogin($sess);
        if ($_SERVER['HTTPS'] == 'on') {
            header("Location: https://".$_SERVER['HTTP_HOST']."/index.php");
        } else {
            header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
        }
		//$_SESSION['g_theme'] = $_POST['sel_style'];
		$_SESSION['g_theme'] = 'Default';
    } else {
        $smarty->assign('feedback', $feedback);
        $smarty->display('client/login.html');
    }
    exit;
} else {
    $smarty->display('client/login.html');
    exit;
}
?>
