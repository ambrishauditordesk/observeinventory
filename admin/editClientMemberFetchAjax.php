<?php
include '../dbconnection.php';
$id = $_POST['id'];
$data = $con->query("SELECT user.id id, user.name name, user.email email, user.designation designation, user.active active FROM `user` inner join user_client_log on user.id=user_client_log.user_id where user.id = $id or user_client_log.user_id = $id");
$newData = array();
$newData = $data->fetch_assoc();
echo json_encode($newData);