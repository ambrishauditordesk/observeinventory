<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $prog_id = trim($_POST['prog_id']);

        $wid = trim($_POST['wid']);
        $name = trim($_POST['bspl_name']);
        $bspl_header_type = trim($_POST['bspl_header_type']);
        $newSeq = $con->query("select (_seq+1) newSeq from program inner join workspace_log on program.id=workspace_log.program_id inner join assets_liabilities_check on assets_liabilities_check.id =program.id where header_type = $bspl_header_type and workspace_log.workspace_id=$wid order by _seq DESC LIMIT 1")->fetch_assoc()['newSeq'];
        if($con->query("insert into program(parent_id,program_name,hasChild,def_prog,_seq,firmPlan) values ('$prog_id','$name','1','0','$newSeq','0')") === TRUE)    
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