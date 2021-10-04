<?php
    include 'dbconnection.php';
    if(!isset($_SESSION)){
       session_start();
    }
    $flag = 0;
    if($_POST){
        $id = trim($_POST['id']);
        $result = $con->query("select conclusion_text from going_concern where id = $id");
        if($result->num_rows){
            echo $result->fetch_assoc()['conclusion_text'];
            return;
        }
        else{
            echo "We did not give consideration to modification of our auditor’s report";
            return;
        }
    }
    echo $flag;
?>