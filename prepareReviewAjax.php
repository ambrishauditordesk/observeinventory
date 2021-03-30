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
        if(!$status){
            // $status = 0 means prepare signoff
            if($con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
            {
                $con->query("update workspace_log set status = 1 where program_id = $prog_id and workspace_id = $wid");
                $flag = 1;
            }
        }
        else{
            if($con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
            {
                $flag = 1;
            }
        }
    }
    echo $flag;
?>