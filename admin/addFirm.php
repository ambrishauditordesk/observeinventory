<!DOCTYPE html>
<html lang="en">
<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>
<body>
<?php

include '../dbconnection.php';

$name = trim($_POST['firmName']);
$add = trim($_POST['firmAdd']);
$username = trim($_POST['name']);
$email = trim($_POST['firmEmail']);
$ser = $_SERVER['HTTP_REFERER'];
$date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");;
$pass = md5($name.$date);
$flag = 0;

$checkFirm = $con->query("select * from firm_details where firm_name like '%$name%'");
if($checkFirm->num_rows > 0){
    echo "<script>
        swal({
            icon: 'error',
            text: 'Additon Failed! Firm Already Exists.',
        }).then(function(isConfirm) {
            if (isConfirm) {
                window.location.href = '$ser';
            }
        });
    </script>";
}
else{
    $firmDetails = $con->query("INSERT INTO `firm_details`(`firm_name`, `firm_address`, `firm_email`,`storage`) VALUES ('$name','$add','$email','10000')");
    $firm_id = $con->insert_id;
    $userDetails = $con->query("insert into user(name,email,password,accessLevel,active,reset_code,img) values('$username', '$email', '$pass', '4', '1', '', '')");
    $user_id = $con->insert_id;
    $con->query("insert into firm_user_log(firm_id,user_id) values('$firm_id','$user_id')");
    $flag = 1;

    $sub = "You have been registered as a Firm Admin ";
    $loginLink = 'http://yourfirmaudit.com/AuditSoft/login';
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");;
    $tempPass = $name.$date;

    $msg = "<div>
        <div>Hello ".$username.",</div>
        <br />
        <div>You have been registered as a Firm Admin to join Digital audit workspace. Use your user
        id and temporary password to login to the workspace and reset the password.</div>
        <br />
        <div>Your email id: ".$email."</div>
        <div>Your temporary password: ".$tempPass."</div>
        <br/>
        <a href='".$loginLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Login</button></a>
        <br />
        <br />
        <div><b>This is a system generated email, please do not reply to this email.</b></div>
        <br />
        <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
        <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
        <div>contact the Help desk or your firm administrator.</div>
        <br />
        <div>Thank you.</div>
        <br />
        <div>Auditor's Desk Team</div>
        </div>";

    customMailer($email,$msg,$sub);

    if($flag){
    echo "<script>
            swal({
                icon: 'success',
                text: '$name - Firm Added',
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
    }
    else{
        echo "<script>
            swal({
                icon: 'error',
                text: 'Failed Addition',
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
    }
}
?>
</body>
</html>
