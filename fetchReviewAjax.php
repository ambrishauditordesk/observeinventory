<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        if(!isset($_SESSION)){
       session_start();
    }
        $pid = trim($_POST['pid']);
        $wid = trim($_POST['wid']);
        $result = $con->query("select user.signoff_init, review_signoff_date, signoff_review_log.id from signoff_review_log inner join user on signoff_review_log.user_id=user.id where prog_id = '$pid' and workspace_id = '$wid'")->fetch_all();
        echo json_encode($result);
    }
?>