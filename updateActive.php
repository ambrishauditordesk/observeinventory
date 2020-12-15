<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $prog_id = trim($_POST['prog_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['status'] = 0;
        $res['text'] = "Error";
        
        $query = $con->query("select * from workspace_log where program_id = '$prog_id' and workspace_id = '$wid'");
        if ($query->num_rows != 0)
        {
            $queryrow = $query->fetch_assoc();
            if($queryrow['active'] == 1)
            {
                if($con->query("update workspace_log set active = 0, status = 1 where program_id = '$prog_id' and workspace_id = '$wid'") === TRUE)
                {
                    $res['status'] = 1;
                    $res['text'] = "Disabled";
                }
            }
            else
            {
                if($con->query("update workspace_log set active = 1, status = 0 where program_id = '$prog_id' and workspace_id = '$wid'") === TRUE)
                {
                    $res['status'] = 1;
                    $res['text'] = "Enabled";
                }
            }
        }
        echo json_encode($res);
        
    }
?>