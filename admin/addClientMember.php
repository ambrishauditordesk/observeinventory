<?php
    if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pass = md5(trim($_POST['password']));
        $design = trim($_POST['design']);
        $cid= trim($_POST['cid']);
        $ser = $_SERVER['HTTP_REFERER'];

        $duplicate = $con->query("select * from user where email = '$email'");
        if ($duplicate->num_rows > 0)
        {
            echo 0;
        }
        else
        {
            $res= $con->query("insert into user(client_id,name,email,password,accessLevel,active,designation) values('$cid','$name', '$email', '$pass', '5', '1','$design')");
            $uid= $con->insert_id;
            $con->query("insert into user_client_log(user_id,client_id) values('$uid','$cid')");
            if($res)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
    }
?>

