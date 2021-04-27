<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $mat_id = trim($_POST['mat_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['status'] = 0;
        $res['text'] = "Error";
        $email = $_SESSION['email'];
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $mat = $con->query("select name from materiality where id = '$mat_id' and workspace_id = '$wid'")->fetch_assoc()['name'];
        
        if($con->query("delete from materiality where id = '$mat_id' and workspace_id = '$wid'") === TRUE)
        {
            $res['status'] = 1;
            $res['text'] = "Deleted..";
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Materiality $mat is deleted')");
        }
        else
        {
            $res['text'] = "Failed!!";
        }
        echo json_encode($res);
    }
?>