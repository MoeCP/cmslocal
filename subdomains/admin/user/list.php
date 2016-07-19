<?php
$g_current_path = "user";
require_once('../pre.php');//加载配置信息
require_once('../cms_menu.php');

if (!user_is_loggedin() || User::getPermission() < 4 
    || (User::getRole() == 'admin' && User::getUserType() == -1)) { // 3=>4
    header("Location: http://".$_SERVER['HTTP_HOST']."/logout.php");
    exit;
}
/*start
 *modifed by snug 14:10 2006-07-31
*/
if( count( $_POST ) )
{
	$operation = $_POST['operation'];
	switch( $operation )
	{
        case 'active':
			if (trim($_POST['user_id']) != '' && $_POST['form_refresh'] == "A") {
				if (User::setStatus($_POST['user_id'], 'A')){
					$feedback = "Delete Success";
				}
				//sql_log();
				echo "<script>window.location.href='/user/list.php';</script>";
				exit;
			}
            break;
		case 'delete':
			if (trim($_POST['user_id']) != '' && $_POST['form_refresh'] == "D") {
				if (User::setStatus($_POST['user_id'], 'D')){
					$feedback = "Delete Success";
				}
				//sql_log();
				echo "<script>window.location.href='/user/list.php';</script>";
				exit;
			}
			break;
		case 'send_account_info':
			User::sendWelcomeEmail($_POST['user_id']);
			echo "<script>window.location.href='/user/list.php';</script>";
			exit;
			break;
		//Added by Snug 16:31 2006-9-12
		/**
		 *update auditing frequency
		 */
	    case 'set_frequency':
			User::updateAuditingFrequency($_POST['frequency'], $_POST['user_id']);
			echo "<script>window.location.href='/user/list.php';</script>";
			exit;
			break;
		//End Added
	}
}//end
//$result = User::getAllUsers($mode = 'all_infos');

if ( !isset($_GET['status'])) {
    $_GET['status'] = "A";
}
$smarty->assign('total_status', array('All'=>'All') + $g_tag['status']);
$search = User::search($_GET);
$stats = User::sumTotalWordsForUsers(null);
$smarty->assign('stats', $stats);
if ($search) {
    $smarty->assign('result', $search['result']);
    $smarty->assign('pager', $search['pager']);
    $smarty->assign('total', $search['total']);
    $smarty->assign('count', $search['count']);
}

$smarty->assign('login_role', User::getRole());
$smarty->assign('pay_plugin', $g_pay_plugin);
$smarty->assign('users_status', $g_tag['users_status']);

$smarty->assign('user_permission', $g_tag['user_permission']);
$smarty->assign('user_roles', $g_tag['user_role']);
$smarty->assign('pay_levels', $g_tag['pay_levels']);
$smarty->assign('auditing_frequency', $g_tag['auditing_frequency']);

//Added by Snug 16:31 2006-9-12
$smarty->assign('current_frequency', $_SESSION['current_frequency']);
$smarty->assign('current_user_id', User::getID());
//End Added
$smarty->assign('payment_preference', $g_tag['payment_preference']);

//$smarty->assign('result', $result);
$smarty->assign('feedback', $feedback);
$smarty->assign('startNo', getStartPageNo());
$smarty->display('user/list.html');
?>