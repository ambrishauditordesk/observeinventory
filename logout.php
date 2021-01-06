<?php
include 'dbconnection.php';
    session_start();
    if(isset($_SESSION["email"])) {
        $userId = $_SESSION['id'];
        $dateTime = date_format(date_create("now",new DateTimeZone('America/Chicago')), "Y-m-d H:i:s");
        $con->query("update session_log set logout_datetime	= '$dateTime' where user_id = '$userId' and logout_datetime = ''");
        session_unset();
        session_destroy();
        header("location:./");
    }
    else
    {
        header("location:./");
    }