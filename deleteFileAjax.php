<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $id = trim($_POST['id']);
        $result = $con->query("select workspace_id, prog_id, file from signoff_files_log where id = '$id'")->fetch_assoc();
        $wid = $result['workspace_id'];
        $prog_id = $result['prog_id'];
        $response = 0;

        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];

        if($con->query("delete from signoff_files_log where id = '$id'") === TRUE)
        {
            $fileName = $result['file'];
            unlink($_SESSION['upload_file_location'].$result['file']);
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$fileName File has been deleted for program:- $pname ')");
            $response = 1;
            $commentResult = $con->query("select count(id) total from signoff_comments_log where workspace_id = '$wid' and prog_id = '$prog_id'")->fetch_assoc()['total'];
            $fileResult = $con->query("select count(id) total from signoff_files_log where workspace_id = '$wid' and prog_id = '$prog_id'")->fetch_assoc()['total'];
            if($commentResult == 0 && $fileResult == 0){
                $con->query("update workspace_log set status = 0 where workspace_id = $wid and program_id = $prog_id");
                $con->query("delete from signoff_prepare_log where workspace_id = $wid and prog_id = $prog_id");
                $con->query("delete from signoff_review_log where workspace_id = $wid and prog_id = $prog_id");
                $con->query("delete from assertion where workspace_id = $wid and program_id = $prog_id");
            }
        }
        echo $response;
    }
?>