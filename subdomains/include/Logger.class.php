<?php
/**
* Logger Class（登陆操作类）
*
* 本类是实现用户登陆后的session值的写入。
* Insert user's or client's login info.
*
* @global  string $conn
* @author  Leo.liuxl@gmail.com
* @copyright Copyright &copy; CMS 2006
*/
class Logger {

    // execute ONLY ONCE after login success, called in User.class.php
     /**
     * Insert user's or client's login info.
     *
     * No return,But record
     *
     * @param int $user_id user's or client's ID
     * @param string $session_id session ID value
     */
    function logSession($user_id, $session_id)
    {
        global $conn;

        $rs = &$conn->Execute("SELECT * FROM session WHERE user_id = '".$user_id."'");
        if ($rs) {
            if ($rs->fields['user_id'] > 0) {
                $rs->Close();
                // update
                $conn->Execute("UPDATE session ".
                               "SET session = '".$session_id."', ".
                                   "time = '".time()."', ".
                                   "warning_counter = '0', ".
                                   "ip_addr = '".$_SERVER['REMOTE_ADDR']."' ".
                               "WHERE user_id = '".$user_id."'");
            } else {
                $rs->Close();
                $conn->Execute("INSERT INTO session (user_id, session, time, ip_addr) ".
                               "VALUES ('".$user_id."', '".$session_id."', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
            }
        } else {
            // insert
            $conn->Execute("INSERT INTO session (user_id, session, time, ip_addr) ".
                           "VALUES ('".$user_id."', '".$session_id."', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
        }
    }

    function getUserLastLogin($user_id)
    {
        global $conn, $feedback;
        $sql = 'SELECT time FROM session WHERE user_id = ' . $user_id;
        return $conn->GetOne($sql);
    }

    function getClientLastLogin($client_id)
    {
        global $conn, $feedback;
        $sql = 'SELECT time FROM client_session WHERE client_id = ' . $client_id;
        return $conn->GetOne($sql);
    }

    // execute ONLY ONCE after login success, called in User.class.php
     /**
     * Insert client's login info.
     *
     * No return,But record
     *
     * @param int $client_id client's ID
     * @param string $session_id session ID value
     */
    function logClientSession($client_id, $session_id)
    {
        global $conn;

        $rs = &$conn->Execute("SELECT * FROM client_session WHERE client_id = '".$client_id."'");
        if ($rs) {
            if ($rs->fields['client_id'] > 0) {
                $rs->Close();
                // update
                $conn->Execute("UPDATE client_session ".
                               "SET session = '".$session_id."', ".
                                   "time = '".time()."', ".
                                   "ip_addr = '".$_SERVER['REMOTE_ADDR']."' ".
                               "WHERE client_id = '".$client_id."'");
            } else {
                $rs->Close();
                $conn->Execute("INSERT INTO client_session (client_id, session, time, ip_addr) ".
                               "VALUES ('".$client_id."', '".$session_id."', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
            }
        } else {
            // insert
            $conn->Execute("INSERT INTO session (client_id, session, time, ip_addr) ".
                           "VALUES ('".$client_id."', '".$session_id."', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
        }
    }//end logClientSession()

    /*
     * Param: $action ENUM('insert', 'delete', 'update')
     */
     /**
     * 记录用户登陆后的行为信息
	 * Insert user's or client's login action info.
     *
     * This function wasn't return any value.
     *
     * @param int $pk_id 外键ID
     * @param int $table 对数据库中的哪个表进行了操作
     * @param string $action 用户行为
     */
    function logAction($action, $table, $pk_id)
    {
        global $conn;

        $q = "INSERT INTO log_action (`action`, `table`, `pk_id`, `user_id`, `date`) ".
             "VALUES ('".$action."', '".$table."', '".$pk_id."', '".User::getID()."', '".time()."')";
        $conn->Execute($q);
    }

}//end class Logger

?>