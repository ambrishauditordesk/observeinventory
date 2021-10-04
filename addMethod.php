<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        if(!isset($_SESSION)){
       session_start();
    }
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $name = trim($_POST['name']);
        
        if($con->query("insert into materiality(prog_id,workspace_id,name) values ('$prog_id','$wid','$name')") === TRUE)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
    }
?>