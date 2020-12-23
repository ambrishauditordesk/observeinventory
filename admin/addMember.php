<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = md5(trim($_POST['password']));
        $role = trim($_POST['role']);
        $signoff = trim($_POST['signoff']);
        $regDate = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");

        $res= $con->query("insert into user(name,email,password,accessLevel,active,reg_date,signoff_init) values('$name', '$email', '$pass', '$role', '1', '$regDate', '$signoff')");
        
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