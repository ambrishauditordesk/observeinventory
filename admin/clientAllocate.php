<?php
if(isset($_POST))
    {
        include '../dbconnection.php';
        session_start();
        $name = trim($_POST['name']);
        $client = $_POST['selectedValues'];
        $uid = $_POST['memberId'];
        $con->query("delete from user_client_log where user_id = '$uid'");
        foreach($client as $cid)
        {
            $result = $con->query("insert into user_client_log(client_id,user_id) values('$cid','$uid')");
        }
        $data = array();
            if($result)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
    }
    echo json_encode($data);
?>