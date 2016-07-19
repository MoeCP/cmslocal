<?php /* $Id: forum_account_management.class.php, v 1.0 2006-06-09 17:13 mandelwu Exp $ */
class CForumAccountManagement 
{

    function __construct() {
        $this->cms_user_id = intval($_SESSION['user_id']) > 0 ? intval($_SESSION['user_id']) : 0;
    }

    /*
    * to check if current user has logged in forum
    */
    public function hasForumLogin() {
        return ($_SESSION['forum_logged_in'] === true) ? true : false;
    }
    
    /*
    * to check if the current user has logged in CMS
    */
    public function hasCmsLogin() {
        global $phpbb_user_ip;
        return ($this->cms_user_id > 0 && chkSessionIP($phpbb_user_ip)) ? true : false;
    }

    /*
    * create a new user of forum according to cms_user_id
    */
    public function createNewForumUser() {
        if ($this->cms_user_id == 0) 
            return false;
        global $db;
        
        $sql = "SELECT MAX(user_id)+1 AS next_user_id FROM " . USERS_TABLE;
        if ( !($result = $db->sql_query($sql)) ) {
            message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
        }

        if ( !($row = $db->sql_fetchrow($result)) ) {
            message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
        }
        $next_user_id = $row['next_user_id'];
        $cms_user_info = $this->getCmsUserInfo();

        if ($cms_user_info['role'] == 'admin') { // for admin
            //$user_password = $cms_user_info['user_pw'];
            $user_password = md5($cms_user_info['user_pw']);
            $user_level = 1;
        } else { //common user
            $user_password = 'USER_PASSWORD';
            $user_level = 0;
        }

        $sql = "INSERT INTO " . USERS_TABLE . " (user_level, user_id, cms_user_id, username, user_password, user_regdate, user_style, user_lang,  user_viewemail, user_attachsig, user_allowhtml, user_email ) VALUES ";
        $sql .= "($user_level, '$next_user_id', " . $this->cms_user_id . ", '" . mysql_escape_string($cms_user_info['user_name']) . "', '".$user_password."', UNIX_TIMESTAMP(NOW()), 1, 'english', 0, 1, 0, '" . mysql_escape_string($cms_user_info['email']) . "')";

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Error in inserting userdata', '', __LINE__, __FILE__, $sql);
            return false;
		}
        return true;
    }

    /*
    * sync user's info between cms and forum when user login to forum
    */
    public function syncUserInfo() {
        if ($this->cms_user_id == 0) 
            return false;
        global $db;

        $cms_user_info = $this->getCmsUserInfo();

        if ($cms_user_info['role'] == 'admin') { // for admin
            //$user_password = $cms_user_info['user_pw'];
            $user_password = md5($cms_user_info['user_pw']);
            $user_level = 1;
        } else { //common user
            $user_password = 'USER_PASSWORD';
            $user_level = 0;
        }
        $sql = "UPDATE " . USERS_TABLE . " SET user_level=$user_level, user_password='" . $user_password . "', username='" . mysql_escape_string($cms_user_info['user_name']) . "' , user_email='" . mysql_escape_string($cms_user_info['email']) . "' ";
        $sql .= " WHERE cms_user_id=" . $this->cms_user_id;

		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Error in sync userdata', '', __LINE__, __FILE__, $sql);
            return false;
		}        
        return true;
    }

    /*
    * get user's cms info by cms_user_id
    */
    private function getCmsUserInfo() {
        if ($this->cms_user_id == 0) 
            return false;
        global $db;

        $sql = "SELECT user_name, email, user_pw, `role` FROM users WHERE user_id = " . $this->cms_user_id;
		if ( !($result = $db->sql_query($sql)) ) {
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}
        return $row = $db->sql_fetchrow($result);
    }

    /*
    * login current user to forum automatically
    */
    public function autoLogin() {

        global $db, $board_config, $phpbb_user_ip, $phpEx;

        $sql = "SELECT user_id, username, user_password, user_active, user_level"
			    . "\nFROM " . USERS_TABLE
			    . "\nWHERE cms_user_id = '" . str_replace("\\'", "''", $this->cms_user_id) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}
		if( $row = $db->sql_fetchrow($result) )
		{
			if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
			{
				redirect(append_sid("index.$phpEx", true));
			}
			else
			{
				if( $row['user_active'] )
				{
					$autologin = 1;
					$session_id = session_begin($row['user_id'], $phpbb_user_ip, PAGE_INDEX, FALSE, $autologin);

					if( $session_id )
					{
                        $_SESSION['forum_logged_in'] = true;
						$url = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : "index.$phpEx";
						redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				else
				{
					$redirect = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : '';
					$redirect = str_replace('?', '&', $redirect);

					if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
					{
						message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
					}

					$template->assign_vars(array(
						'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
					);

					$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}
		}
		else
		{
			$redirect = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);

			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
			{
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}

			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}


    }

    /*
    * to check if the account is actived(existed) in forum db
    */
    function isActived() {
        global $db;
        $sql = "SELECT COUNT(*) AS user_cnt "
                . "\nFROM " . USERS_TABLE
                . "\nWHERE cms_user_id = " . $this->cms_user_id; // AND user_active != 0
        $result = $db->sql_fetchrow( $db->sql_query($sql) );

        if (intval($result['user_cnt']) === 1)
            return true;
        return false;
    }

    /*
    * keep the whole system offline
    */
    function keepOffline() {
        global $phpbb_user_ip, $phpEx;

        $userdata = session_pagestart($phpbb_user_ip, PAGE_INDEX);
        if( $userdata['session_logged_in'] || $this->hasForumLogin() )
		{
            if( $userdata['session_logged_in'] )
			    session_end($userdata['session_id'], $userdata['user_id']);

            if ( $this->hasForumLogin() ) {
                $_SESSION = array();
                session_destroy();
            }

            if (!empty($_POST['redirect']) || !empty($_GET['redirect']))
            {
                $url = (!empty($_POST['redirect'])) ? htmlspecialchars($_POST['redirect']) : htmlspecialchars($_GET['redirect']);
                $url = str_replace('&amp;', '&', $url);
                redirect(append_sid($url, true));
            }
            else
            {
                redirect(append_sid("index.$phpEx", true));
            }
        }

    }

    /********** Class Variables **********/
    public $cms_user_id;
    
}
?>