<?php
include 'dbconnection.php';

$clientId = base64_decode($_POST['cid']);
$wid = base64_decode($_POST['wid']);
$data = 0;

if($con->query("select * from workspace where id = $wid and client_id = $clientId")->num_rows == 0){
   header('Location: ./');
}
else{
   $fileName = trim($_POST['file']);
   $data = $con->query("select count(id) total from checkBeforeEdit where fileName = '$fileName'")->fetch_assoc()['total'];
   $data = $data == 0 ? 1 : 0;
}
echo $data;
?>