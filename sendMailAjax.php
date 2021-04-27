<?php
    include 'dbconnection.php';
    session_start();
    $subject = "You have been invited for the documents upload";
    $message = "<p>URL:- http://".$_SERVER['SERVER_NAME']."/AuditSoft</p><p>Email ID:- ";
    $wid = $_POST['wid'];
    $data = [];
    $result = $con->query("select email from user inner join accounts_log on accounts_log.client_contact_id=user.id where mail_send=0 and workspace_id=".$wid)->fetch_all();
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $userEmail = $_SESSION['email'];
    foreach($result as $key => $value)
        foreach($value as $k => $email){
            $data[] = mail($email,$subject,$message.$email." </p><p>Login with you credentials.</p>");
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid','$userEmail','$date','Email send successfully to $email')");
        }
        echo json_encode($data);
?>