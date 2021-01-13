<?php
include '../dbconnection.php';
$id = $_POST['id'];
$cid = $_POST['cid'];

$data = $con->query("select * from user where id =$id and client_id='$cid'");
    $newData = array();
    $newData = $data->fetch_assoc();
    echo json_encode($newData);
