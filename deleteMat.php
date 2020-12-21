<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $mat_id = trim($_POST['mat_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['status'] = 0;
        $res['text'] = "Error";
        
        if($con->query("delete from materiality where id = '$mat_id' and workspace_id = '$wid'") === TRUE)
        {
            $res['status'] = 1;
            $res['text'] = "Deleted..";
        }
        else
        {
            $res['text'] = "Failed!!";
        }
        echo json_encode($res);
    }
?>