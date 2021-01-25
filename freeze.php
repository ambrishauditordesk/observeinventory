<?php
include 'dbconnection.php';
$wid = trim($_POST['id']);
$freeze = trim($_POST['freeze']);
$con->query("update workspace set freeze = $freeze where id= $wid");
echo 1;
?>