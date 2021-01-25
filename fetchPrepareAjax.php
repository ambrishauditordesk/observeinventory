<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $pid = trim($_POST['pid']);
        $wid = trim($_POST['wid']);
        $result = $con->query("select user.signoff_init, prepare_signoff_date, signoff_prepare_log.id from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where prog_id = '$pid' and workspace_id = '$wid'")->fetch_all();
        echo json_encode($result);
    }
?>