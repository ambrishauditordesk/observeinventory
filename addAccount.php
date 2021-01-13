<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $wid = trim($_POST['wid']);
        $accid = trim($_POST['account_id']);
        
        if($con->query("insert into accounts_log(workspace_id,accounts_id) values ('$wid','$accid')") === TRUE)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
    }
?>