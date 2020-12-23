<?php
include '../dbconnection.php';
$id = $_POST['id'];

$data = $con->query("select * from user where id =$id");
    $newData = array();
    $newData = $data->fetch_assoc();
    echo json_encode($newData);
