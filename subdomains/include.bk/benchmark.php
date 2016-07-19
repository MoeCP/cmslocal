<?php

/* $Id: benchmark.php,v 1.0 2006-4-27 17:45:54 Leo Exp $ */

function benchmark_start()
{
    global $benchmark_start;

    $benchmark_start = getmicrotime();
}


function benchmark_end()
{
    global $benchmark_start;

    return (getmicrotime() - $benchmark_start);
}


function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>