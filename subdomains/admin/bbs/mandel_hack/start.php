<?php /* $Id: start.php,v 1.0 2005-11-15 16:53 mandelwu Exp $ */

include_once($forumDir . '/mandel_hack/md_function.' . $phpEx);
include_once($forumDir . '/mandel_hack/forum_account_management.class.' . $phpEx);
session_start();


$oAccount = new CForumAccountManagement();

if ( isset($_GET['logout']) || isset($_POST['logout']) ) {
    $oAccount->keepOffline();

} else if ( $oAccount->hasCmsLogin() ) { //check if has logged cms, (not forum)
    if ($oAccount->hasForumLogin()) {
    } else {
        if ( $oAccount->isActived() ) {
            $oAccount->syncUserInfo();
        } else {
            $oAccount->createNewForumUser();
        }
        $oAccount->autoLogin();
    }

} else {
    if (false === strpos($_GET['redirect'], 'admin')) {        
		    } else {
				        $_SESSION['admin_login'] = true;
						    }
	    if (!$_SESSION['admin_login'])
			        $oAccount->keepOffline();
}

?>
