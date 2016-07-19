<?php
function debug($info, $stop=false) {
    echo "<pre>"; print_r($info); echo "</pre><br />";
    if ($stop) die;
}

function chkSessionIP($curr_ip) {
    $curr_ip = decode_ip($curr_ip);
    $sess_ip = $_SESSION['user_ip'];

    $e_sess_ip = explode(".", $sess_ip);
    $e_curr_ip = explode(".", $curr_ip);
    // require same class B subnet
    if ($e_sess_ip[0] == $e_curr_ip[0] && $e_sess_ip[1] == $e_curr_ip[1]) {
        return true;
    } else {
        //$feedback = 'Current IP address and inital IP address mismatch,Please try again';
        return false;
    }
}
?>