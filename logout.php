<?php
include 'dbconnection.php';
    session_start();
    if(isset($_SESSION["email"])) {
        $userId = $_SESSION['id'];
        $dateTime = date_format(date_create("now",new DateTimeZone('America/Chicago')), "Y-m-d H:i:s");
        $con->query("update session_log set logout_datetime	= '$dateTime' where user_id = '$userId' and logout_datetime = ''");
        
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        // $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','User Logged Out successfully.')");

        session_unset();
        session_destroy();
        header("location:./");
    }
    else
    {
        header("location:./");
    }