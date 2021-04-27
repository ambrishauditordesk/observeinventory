<?php
include 'dbconnection.php';
$wid = trim($_POST['id']);
$freeze = trim($_POST['freeze']);
$con->query("update workspace set freeze = $freeze where id= $wid");
$date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$email = $_SESSION['email'];
$con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Workspace Freezed.')");
echo 1;
?>