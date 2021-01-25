<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $name = trim($_POST['bspl_name']);
        $bspl_header_type = trim($_POST['bspl_header_type']);
        if($con->query("insert into program(parent_id,program_name,hasChild,def_prog) values ('$prog_id','$name','1','0')") === TRUE)    
        {
            $new_id = $con->insert_id;
            if($con->query("insert into workspace_log(workspace_id,program_id) values ('$wid','$new_id')") === TRUE)
            {                
                $con->query("insert into assets_liabilities_check(id,parent_id,program_name,header_type) values('$new_id','$prog_id','$name','$bspl_header_type')");
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