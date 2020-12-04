<?php
include '../dbconnection.php';
$id = $_POST['id'];

$clientEdit = $con->query("select a.id,a.name,b.const,a.added_by_date,a.active from client a inner join constitution b on a.const_id=b.id where a.id =$id");
if($clientEdit->num_rows != 0){
    $data = array();
    $data = $clientEdit->fetch_assoc();
    echo json_encode($data);
}