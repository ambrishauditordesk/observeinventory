<?php
    $flag = 0;
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();

        $wid = trim($_POST['wid']);
        $questionName = trim($_POST['name']);

        $query = "INSERT INTO inquiring_of_management_questions_answer(workspace_id, inquiring_of_management_questions, answer_option, answer_textarea) VALUES('$wid','$questionName','','')";

        if($con->query($query) === TRUE){
            $flag = 1;
        }
    }
    echo $flag;
?>