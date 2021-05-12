<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $acc_id = trim($_POST['acc_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['text'] = "Error";
        $acc = $con->query("select account from accounts inner join accounts_log on accounts.id = accounts_log.accounts_id where accounts_log.id = '$acc_id' and workspace_id = '$wid'")->fetch_assoc()['account'];
        
        if($con->query("delete from accounts_log where id = '$acc_id' and workspace_id = '$wid'") === TRUE)
        {
            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
            $email = $_SESSION['email'];
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$acc is deleted from Request Client assistance Schedule')");
            $docFiles = $con->query("select documents from accounts_log_docs where accounts_log_id =".$acc_id);
            while($result = $docFiles->fetch_assoc()){
                $file = $result['documents'];
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$file is deleted')");
                unlink($_SESSION['upload_file_location'].$result['documents']);
            }
            $con->query("delete from accounts_log_docs where accounts_log_id =".$acc_id);
            $res['text'] = "Deleted..";
        }
        else
        {
            $res['text'] = "Failed!!";
        }
        echo json_encode($res);
    }
?>