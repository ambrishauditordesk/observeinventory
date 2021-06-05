<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $id = trim($_POST['id']);
        $type = trim($_POST['type']);
        if($type == 1){
            $result = $con->query("select workspace_id, prog_id, file from signoff_files_log where id = '$id'")->fetch_assoc();
            $wid = $result['workspace_id'];
            $prog_id = $result['prog_id'];
            $response = 0;

            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
            $email = $_SESSION['email'];
            $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];

            if($con->query("update signoff_files_log set status = '0', deletedDate = '' where id = '$id'") === TRUE)
            {
                $fileName = $result['file'];
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$fileName File has been recovered for program:- $pname ')");
                $response = 1;
            }
        }
        elseif($type == 2){
            $result = $con->query("select workspace_id, file_name from accounting_estimates_files where id = '$id'")->fetch_assoc();
            $wid = $result['workspace_id'];
            $response = 0;

            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
            $email = $_SESSION['email'];
            $pname = 'Accounting Estimates';

            if($con->query("update accounting_estimates_files set status = '0', deletedDate = '' where id = '$id'") === TRUE)
            {
                $fileName = $result['file_name'];
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$fileName File has been recovered for program:- $pname ')");
                $response = 1;
            }
        }
        elseif($type == 3){
            $result = $con->query("select * from insignificant_files where id = '$id'")->fetch_assoc();
            $wid = $result['workspace_id'];
            $prog_id = $result['pid'];
            $response = 0;

            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
            $email = $_SESSION['email'];
            $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];;

            if($con->query("update insignificant_files set status = '0', deletedDate = '' where id = '$id'") === TRUE)
            {
                $fileName = $result['fname'];
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$fileName File has been recovered for program:- $pname ')");
                $response = 1;
            }
        }
        elseif($type == 4){
            $tableName = 'signoff_files_log';
        }
        
        echo $response;
    }
?>