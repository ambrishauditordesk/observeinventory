<?php
    include '../dbconnection.php';
    $cid=$_POST['id'];
    // echo $cid;
    $query = "select * from client where id = '$cid'";
    $result = array();
    $result = $con->query($query)->fetch_assoc();

    echo json_encode($result);

?>