<?php
require_once "pre.php";
require_once CMS_INC_ROOT.DS."UserCalendar.class.php";
if (!user_is_loggedin()) {
    header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    exit;
}
$table = new UserCalendar();
$y = $_POST['y'];
$m = $_POST['m'];
$d_max = $_POST['d_max'];
$user_id = empty($_POST['user_id']) ? User::getID() : $_POST['user_id'];
$from = "$y-$m-1";
$to = "$y-$m-$d_max";
$re = $table->getUnFreeDate($from, $to, $user_id);
echo php2js($re);
function php2js($a)
{
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a)) {
        $a = addslashes($a);
        $a = str_replace("\n", '\n', $a);
        $a = str_replace("\r", '\r', $a);
        $a = preg_replace('{(</)(script)}i', "$1'+'$2", $a);
        return "'$a'";
    }
    $isList = true;
    for ($i=0, reset($a); $i<count($a); $i++, next($a))
        if (key($a) !== $i) { $isList = false; break; }
    $result = array();
    if ($isList) {
        foreach ($a as $v) $result[] = php2js($v);
        return '[ ' . join(', ', $result) . ' ]';
    } else {
        foreach ($a as $k=>$v)
            $result[] = php2js($k) . ': ' . php2js($v);
        return '{ ' . join(', ', $result) . ' }';
    }
}
?>