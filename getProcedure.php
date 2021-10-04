<?php
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
    $flag = 0;
    if($_POST){
        $id = trim($_POST['id']);
        $result = $con->query("select procedure_data from going_concern_procedures where id = $id");
        if($result->num_rows){
            echo $result->fetch_assoc()['procedure_data'];
            return;
        }
    }
    echo $flag;
?>