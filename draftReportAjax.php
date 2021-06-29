<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
$wid = $_POST['wid'];
$status = $_POST['status'];
$reponse = 0;
$uid = $_SESSION['id'];
$date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$email = $_SESSION['email'];
$pname = $con->query("select program_name from program where id = 496")->fetch_assoc()['program_name'];

if($status == 0){
    $audit_report_data = trim($_POST['audit_report_data']);
    $type_report_audit_report = trim($_POST['type_report_audit_report']);
    $emphasis_of_matters = trim($_POST['emphasis_of_matters']);
    $other_matter = trim($_POST['other_matter']);
    if($con->query("INSERT INTO draft_report(workspace_id, type_of_audit_report, audit_report, emphasis_of_matter, other_matter) VALUES ('$wid','$type_report_audit_report','$audit_report_data','$emphasis_of_matters','$other_matter')") === TRUE){
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Draft Report Created.')");
        $reponse = 1;
    }
}
elseif($status == 1){
    $audit_report_data = trim($_POST['audit_report_data']);
    if($con->query("UPDATE draft_report set audit_report = '$audit_report_data' where workspace_id = $wid") === TRUE){
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Draft Report Updated.')");
        $reponse = 1;
    }
}
elseif($status == 2){
    // Prepare Submit
    if($con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values ('$wid','496','$uid','$date')") === TRUE)
    {
        $con->query("update workspace_log set status='1' where program_id='496' and workspace_id='$wid'");
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Prepare Sign Off done for program:- $pname ')");
        $reponse = 1;
    }
}
else{
    // Review Submit
    if($con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values ('$wid','496','$uid','$date')") === TRUE)
    {
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Review Sign Off done for program:- $pname ')");
        $reponse = 1;
    }
}
echo $reponse;