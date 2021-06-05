<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $name = trim($_POST['name']);
        $type = trim($_POST['type']);
        if($con->query("insert into program(parent_id,program_name,hasChild,def_prog) values ('$prog_id','$name','$type','0')") === TRUE)
        {
            $new_id = $con->insert_id;
            if($con->query("insert into workspace_log(workspace_id,program_id) values ('$wid','$new_id')") === TRUE)
            {
                $typeName = $type == 1 ? 'Programme' : 'Step';
                $email = $_SESSION['email'];
                $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$typeName added:- $name')");
                echo 1;
            }
            else
            {
                $con->query("delete from program where id='$new_id'");
                echo 0;
            }
        }
    }
?>