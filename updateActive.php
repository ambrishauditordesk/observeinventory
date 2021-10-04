<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        if(!isset($_SESSION)){
       session_start();
    }
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['status'] = 0;
        $res['text'] = "Error";
        $email = $_SESSION['email'];
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        
        $query = $con->query("select * from workspace_log where program_id = '$prog_id' and workspace_id = '$wid'");
        if ($query->num_rows != 0)
        {
            $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];
            $queryrow = $query->fetch_assoc();
            if($queryrow['active'] == 1)
            {
                if($con->query("update workspace_log set active = 0, status = 1 where program_id = '$prog_id' and workspace_id = '$wid'") === TRUE)
                {
                    $res['status'] = 1;
                    $res['text'] = "Disabled";
                    $type = $con->query("select hasChild from program where id = $prog_id")->fetch_assoc()['hasChild'] == 0 ? "Step" : "Programme";
                    $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$type Name:- $pname is disabled')");
                }
            }
            else
            {
                if($con->query("update workspace_log set active = 1, status = 0 where program_id = '$prog_id' and workspace_id = '$wid'") === TRUE)
                {
                    $res['status'] = 1;
                    $res['text'] = "Enabled";
                    $type = $con->query("select hasChild from program where id = $prog_id")->fetch_assoc()['hasChild'] == 0 ? "Step" : "Programme";
                    $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$type Name:- $pname is enabled')");
                }
            }
        }
        echo json_encode($res);
        
    }
?>