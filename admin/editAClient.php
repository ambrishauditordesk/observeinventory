<?php
    include '../dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }

    // Getting the Logged In User ID
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $active = trim($_POST['active']);

    if($con->query("update client set name = '$name',active = '$active' where id = '$id'") === TRUE)
    {
        echo 1;
    }
    else {
        echo 0;
    }
?>