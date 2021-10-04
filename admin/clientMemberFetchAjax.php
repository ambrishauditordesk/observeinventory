<?php
include '../dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }

// if($_SESSION['role'] != 1 && $_SESSION['role'] != -1){
//     $data = $con->query("select client.id id, name FROM client inner join user_client_log on client.id=user_client_log.client_id where user_client_log.user_id = ".$_SESSION['id']);
// }
// else{
//     $data = $con->query("select client.id id, name FROM client");
// }
$data = $con->query("select id, name from user where id = ".$_POST['id']);
$newData = array();
$newData = $data->fetch_assoc();
echo json_encode($newData);
