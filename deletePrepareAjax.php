<?php
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();
        $id = trim($_POST['id']);
        $result = $con->query("select workspace_id, prog_id from signoff_prepare_log where id = '$id'")->fetch_assoc();
        $wid = $result['workspace_id'];
        $prog_id = $result['prog_id'];
        $response = 0;
        if($con->query("delete from signoff_prepare_log where id = '$id'") === TRUE)
        {
            $response = 1;
            $prepareResult = $con->query("select count(id) total from signoff_prepare_log where workspace_id = '$wid' and prog_id = '$prog_id'")->fetch_assoc()['total'];
            if($prepareResult == 0){
                $con->query("delete from signoff_review_log where workspace_id = $wid and prog_id = $prog_id");
                $con->query("update workspace_log set status = 0 where workspace_id = $wid and program_id = $prog_id");
            }
        }
        echo $response;
    }
?>