<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $acc_id = trim($_POST['acc_id']);
        $wid = trim($_POST['wid']);
        $res = array();
        $res['text'] = "Error";
        
        if($con->query("delete from accounts_log where id = '$acc_id' and workspace_id = '$wid'") === TRUE)
        {
            $docFiles = $con->query("select documents from accounts_log_docs where accounts_log_id =".$acc_id);
            while($result = $docFiles->fetch_assoc())
                unlink("uploads/clientrequest/".$result['documents']);
            $con->query("delete from accounts_log_docs where accounts_log_id =".$acc_id);
            $res['text'] = "Deleted..";
        }
        else
        {
            $res['text'] = "Failed!!";
        }
        echo json_encode($res);
    }
?>