<?php
    include 'dbconnection.php';
    session_start();
    $flag = 0;
    if($_POST){
        $status = trim($_POST['status']);
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $uid = $_SESSION['id'];
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];
        if(!$status){
            // $status = 0 means prepare signoff
            if($con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
            {
                $con->query("update workspace_log set status = 1 where program_id = $prog_id and workspace_id = $wid");
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Prepare Sign Off done for program:- $pname ')");
                $flag = 1;
            }
        }
        else{
            if($con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
            {
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Review Sign Off done for program:- $pname ')");
                $flag = 1;
            }
        }
    }
    echo $flag;
?>