<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $name = trim($_POST['bspl_name']);
        
        if($con->query("insert into program(parent_id,program_name,hasChild) values ('$prog_id','$name','1')") === TRUE)
        {
            $new_id = $con->insert_id;
            if($con->query("insert into workspace_log(workspace_id,program_id) values ('$wid','$new_id')") === TRUE)
            {
                echo 1;
            }
            else
            {
                $con->query("delete from program where id='$new_id'");
                echo 0;
            }
        }
    }
?>