<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $id = trim($_POST['id']);
        $result = $con->query("select workspace_id, prog_id from signoff_review_log where id = '$id'")->fetch_assoc();
        $response = 0;
        if($con->query("delete from signoff_review_log where id = '$id'") === TRUE)
        {
            $response = 1;
        }
        echo $response;
    }
?>