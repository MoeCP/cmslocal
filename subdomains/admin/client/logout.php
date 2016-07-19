<?php

/* $Id: logout.php,v 1.01 2006-4-28 19:58:48 Leo.Liu Exp $ */

//session_unset();
session_start();
$_SESSION = array();
session_destroy();

header('Location: /client/login.php');

?>
