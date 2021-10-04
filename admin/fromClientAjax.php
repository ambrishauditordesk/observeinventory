<?php
include "../dbconnection.php";
if(!isset($_SESSION)){
       session_start();
    }

    $i = 0;
    $clientData = array();

    if($_SESSION['role'] == 1 || $_SESSION['role'] == -1){
        // $clientResult = $con->query("select id, name FROM client where id not in ( select client_id from user_client_log where user_client_log.user_id = ".$_POST['id'].") and id not in ( select user_client_log.client_id from user_client_log inner join user on user_client_log.user_id = user.id where user.client_id is null)");    
        $clientResult = $con->query("select id, name FROM client where id not in ( select client_id from user_client_log where user_client_log.user_id = ".$_POST['id']." )");
    }
    else{
        // $clientResult = $con->query("select client.id, name FROM client inner join user_client_log on client.id = user_client_log.client_id where user_client_log.user_id = ".$_SESSION['id']." and client.id not in( select client_id from user_client_log where user_client_log.user_id = ".$_POST['id'].") and client.id not in ( select user_client_log.client_id from user_client_log inner join user on user_client_log.user_id = user.id where user.client_id is null)");
        $clientResult = $con->query("select client.id, client.name FROM client inner join user_client_log on client.id=user_client_log.client_id where user_client_log.user_id= ".$_SESSION['id']." and client.id not in ( select client_id from user_client_log where user_client_log.user_id = ".$_POST['id']." ) ");
    }
    while ($data = $clientResult->fetch_assoc()) {
        $clientData[$i]['id'] = $data['id'];
        $clientData[$i++]['name'] = $data['name'];
    }
    echo json_encode($clientData);

