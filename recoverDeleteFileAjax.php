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
        elseif($type == 0){
            $result = $con->query("select workspace_id, prog_id, file from signoff_files_log where id = '$id'")->fetch_assoc();
            $wid = $result['workspace_id'];
            $prog_id = $result['prog_id'];
            $fileName = $result['file'];
            $response = 0;

            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
            $email = $_SESSION['email'];
            $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];

            $uploadLocation = $con->query("SELECT firm_details.id firm_id, client.id cid, client.name cname, workspace.id wid from workspace inner join client on workspace.client_id = client.id inner join user_client_log on client.id = user_client_log.client_id inner join firm_user_log on user_client_log.user_id = firm_user_log.user_id inner join firm_details on firm_user_log.firm_id = firm_details.id where workspace.id = $wid group by firm_details.id")->fetch_assoc();
            $uploadLocation = trim($uploadLocation['firm_id']).'/'.trim($uploadLocation['cid']).trim($uploadLocation['cname']).'/'.$wid;
            
            if(unlink('uploads/'.$uploadLocation.'/'.$fileName)){
                $con->query("DELETE signoff_files_log where workspace.id = $wid");
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$fileName File has been permanently deleted for program:- $pname ')");
                if(isset($_SESSION['tempFolderName']) && !empty($_SESSION['tempFolderName'])){
                    if(file_exists('view/'.$_SESSION['tempFolderName'].'/'.$fileName)){
                        unlink('view/'.$_SESSION['tempFolderName'].'/'.$fileName);
                    }
                }                
                $response = 1;
            }
            
        }
        echo $response;
    }
?>