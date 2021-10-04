<?php
include 'dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: ../login");
}
$flag = 0;
if($_POST){
    $id = trim($_POST['id']);
    $wid = trim($_POST['wid']);
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $email = $_SESSION['email'];
    if($con->query("DELETE FROM accounting_estimates WHERE id = $id and workspace_id=$wid") == TRUE)
    {
        $flag = 1;
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Accounting Estimates deleted')");
    }
}
echo $flag;
?>