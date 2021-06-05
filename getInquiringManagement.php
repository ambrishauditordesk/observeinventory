<?php
    include 'dbconnection.php';
    session_start();
    $flag = 0;
    if($_POST){
        $id = trim($_POST['id']);
        $result = $con->query("select inquiring_of_management_questions from inquiring_of_management_questions_answer where id = $id");
        if($result->num_rows){
            echo $result->fetch_assoc()['inquiring_of_management_questions'];
            return;
        }
    }
    echo $flag;
?>