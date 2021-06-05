<?php
    include 'dbconnection.php';
    session_start();

    $id = trim($_POST['id']);
    $active = trim($_POST['active']);

    if($con->query("update user set darkmode='$active' where id ='$id'") === TRUE)
    {
        $_SESSION['darkmode'] = $active;
        echo 1;
    }
    else {
        echo 0;
    }
?>