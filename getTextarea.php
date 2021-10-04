<?php
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
    $flag = 0;
    if($_POST){
        $id = trim($_POST['id']);
        $result = $con->query("select going_concern_conclusion_data from going_concern_conclusion where id = $id");
        if($result->num_rows){
            echo $result->fetch_assoc()['going_concern_conclusion_data'];
            return;
        }
    }
    echo $flag;
?>