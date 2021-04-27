<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $wid = trim($_POST['wid']);
        $accid = trim($_POST['account_id']);
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $pname = $con->query("select account from accounts where id = $accid")->fetch_assoc()['account'];
        
        if($con->query("insert into accounts_log(workspace_id,accounts_id) values ('$wid','$accid')") === TRUE){
            echo 1;
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Account Request added $pname')");
        }
        else{
            echo 0;
        }
    }
?>