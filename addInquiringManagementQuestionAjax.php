<?php
    $flag = 0;
    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();

        $wid = trim($_POST['wid']);
        $questionName = trim($_POST['name']);

        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];

        $query = "INSERT INTO inquiring_of_management_questions_answer(workspace_id, inquiring_of_management_questions, answer_option, answer_textarea) VALUES('$wid','$questionName','','')";
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','$questionName question has been added.')");

        if($con->query($query) === TRUE){
            $flag = 1;
        }
    }
    echo $flag;
?>