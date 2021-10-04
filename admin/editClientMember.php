<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        if(!isset($_SESSION)){
       session_start();
    }
        
        $data['status'] = true;
        $data['text'] = "Nothing to update!";

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $active= trim($_POST['active']);
        $design = trim($_POST['design']);

        $check = $con->query("select * from user where email = '$email'")->fetch_assoc();

        if($check['name'] != $name){
            $con->query("update user set name='$name' where email = '$email'");
            $data['text'] = "Updated";
        }
        if($check['active'] != $active){
            $con->query("update user set active='$active' where email = '$email'");
            $data['text'] = "Updated";
        }
        if($check['designation'] != $design){
            $con->query("update user set designation='$deign' where email = '$email'");
            $data['text'] = "Updated";
        }

    echo json_encode($data);
    }
?>