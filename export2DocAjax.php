<?php

include 'dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }

$email = $_SESSION['email'];
$wid = $_POST['wid'];
$date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Draft Report exported to Docx')");

echo "1";
?>
