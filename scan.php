<?php

function scanFile($filename) {

    $file = $filename;

    $result = shell_exec(escapeshellcmd("clamscan ${file}"));
    $resultArray = explode(PHP_EOL, $result);


    $scannerFiles = $resultArray[6];
    $infectedFiles = $resultArray[7];
    $isInfected = 1;

    if((explode(': ', $scannerFiles)[1]) > 0) {
        $isInfected = explode(': ', $infectedFiles)[1] > 0 ? 1 : 0;
    }
    return $isInfected;
}

?>