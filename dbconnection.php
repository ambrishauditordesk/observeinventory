<?php
    $servername = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname= "auditorsdesk";
// Create connection
    $con = new mysqli($servername, $dbuser, $dbpass , $dbname);
    $con->query("SET NAMES 'utf8'");
// Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
?>
