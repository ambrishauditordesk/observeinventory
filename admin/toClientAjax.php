<?php
include "../dbconnection.php";
$id = $_POST['id'];

    $i = 0;
    $clientData = array();
    
    $clientResult = $con->query("SELECT id,name FROM client where id in (select client_id from user_client_log where user_id='".$_POST['id']."')");
    while ($data = $clientResult->fetch_assoc()) {
        $clientData[$i]['id'] = $data['id'];
        $clientData[$i++]['name'] = $data['name'];
    }
    echo json_encode($clientData);
