<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();

        $email = trim($_POST['email']);
        $active= trim($_POST['active']);
        $role = trim($_POST['role']);
        $signoff = trim($_POST['signoff']);

        $res= $con->query("update user set accessLevel='$role', active='$active', signoff_init='$signoff' where email='$email'");
        var_dump("update user set accessLevel='$role', active='$active', signoff_init='$signoff' where email='$email'");
        // if($res)
        // {
        //     echo 1;
        // }
        // else
        // {
        //     echo 0;
        // }
    }
?>