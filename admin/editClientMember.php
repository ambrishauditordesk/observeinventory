<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $active= trim($_POST['active']);
        $design = trim($_POST['design']);

        $res= $con->query("update user set name='$name', designation='$design', active='$active' where email='$email'");
        if($res)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
?>