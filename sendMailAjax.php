<?php
    include 'dbconnection.php';
    session_start();
    $subject = "You have been invited for the documents upload";
    $message = "<p>URL:- http://".$_SERVER['SERVER_NAME']."/AuditSoft</p><p>Email ID:- ";
    $wid = $_POST['wid'];
    $data = [];
    $result = $con->query("select email from user inner join accounts_log on accounts_log.client_contact_id=user.id where mail_send=0 and workspace_id=".$wid)->fetch_all();
    foreach($result as $key => $value)
        foreach($value as $k => $email){
            $data[] = mail($email,$subject,$message.$email." </p><p>Login with you credentials.</p>");
        }
        echo json_encode($data);
?>