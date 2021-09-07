<?php
    include 'dbconnection.php';
    include 'customMailer.php';

    session_start();

    $subject = $_SESSION['firm_details']['firm_name']." Firm has requested to upgrade their plan. ";
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $email = "info@auditorsdesk.in";
    
    $firmPlan = $_SESSION['firm_details']['plan'] == 1 ? "Simple Start" : "Go Pro";

    $status = 0;
    $msg = '';

    $msg = "<div>
        <div>Hello Admin,</div>
        <br />
        <div>You have being requested to upgrade the plan for the following firm. The details are as follows:</div>
        <br />
        <div>Firm Name: ".$_SESSION['firm_details']['firm_name']."</div>
        <div>Firm Email: ".$_SESSION['firm_details']['firm_email']."</div>
        <div>Current Plan: ".$firmPlan."</div>
        <br />
        <br />
        <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
        <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
        <div>contact the Help desk or your firm administrator.</div>
        <br />
        <div>Thank you.</div>
        <br />
        <div>Auditor's Desk Team</div>
        </div>";

    if(customMailer($email,$msg,$subject)){
        $status = 1;
    }

    echo json_encode($status);
?>