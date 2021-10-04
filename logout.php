<?php
include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
    if(isset($_SESSION["email"])) {
        $userId = $_SESSION['id'];
                
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        // $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','User Logged Out successfully.')");

        $con->query("update user set logged_status = 0 where id = $userId");
        //Check the login status for the client user and delete temp folder.

        // if(!$con->query("select sum(logged_status) total from user inner join user_client_log on user.id = user_client_log.user_id where user_client_log.user_id = $userId or user.client_id = $userId")->fetch_assoc()['total']){
        //     if($con->query("delete from client_temp_folder where workspace_id = '".$_SESSION['workspace_id']."' and folder_name = '".$_SESSION['tempFolderName']."'")){
        //         shell_exec("rm -rf view/".$_SESSION['tempFolderName']);
        //     }
        // }

        // Get the workspace_id's for the user
        $tempFolderQuery = $con->query("select workspace.id id, sum(user.logged_status) sum from workspace inner join client_temp_folder on workspace.id = client_temp_folder.workspace_id inner join user_client_log on workspace.client_id = user_client_log.client_id inner join user on user_client_log.user_id = user.id group by workspace.id");
        if($tempFolderQuery->num_rows){
            while($tempFolder = $tempFolderQuery->fetch_assoc()){
                if($tempFolder['sum'] == 0){
                    $folderName = $con->query("select folder_name from client_temp_folder where workspace_id = ".$tempFolder['id'])->fetch_assoc()['folder_name'];
                    if($con->query("delete from client_temp_folder where workspace_id = ".$tempFolder['id']." and folder_name = '".$folderName."'")){
                        shell_exec("rm -rf view/".$folderName);
                    }
                }
            }
        }

        session_unset();
        session_destroy();
    }
    header("location:./");