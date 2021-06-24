<?php

include 'dbconnection.php';
$date = date("l jS \of F Y h:i:s A");

$con->query("insert into cron_test(date) values('$date')");
?>