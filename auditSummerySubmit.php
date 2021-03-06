<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">

    <title>Auditors Desk</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>

</head>

<body>
<?php
include 'dbconnection.php';
if(!isset($_SESSION)){
       session_start();
    }

$wid = $_GET['wid'];
$adjustment_number = trim($_POST['adjustment_number']);
$type = trim($_POST['type']);
$misstatement = trim($_POST['misstatement']);
$missstatements_description = trim($_POST['missstatements_description']);
$misstatements_account = array();
$misstatements_amount = array();
$ser = $_SERVER['HTTP_REFERER'];

$i =0;
foreach (($_POST['misstatements_account']) as $account) {
    $misstatements_account[$i++] = trim($account);
}

$i =0;
foreach (($_POST['misstatements_amount']) as $amount) {
    $misstatements_amount[$i++] = trim($amount);
}
$count=$i;

$add_misstatement = $con->query("insert into summery_of_misstatements(workspace_id, adjust_number, type, misstatements, description) VALUES ('$wid','$adjustment_number','$type','$misstatement','$missstatements_description')");
    $smid = $con->insert_id;
    for($i=0;$i<$count;$i++){
        // echo "insert into summery_of_misstatements_log(summery_of_misstatements_id, account, amount) VALUES ('$smid','$misstatements_account[$i]','$misstatements_amount[$i])";
        // return;
        $con->query("insert into summery_of_misstatements_log(summery_of_misstatements_id, account, amount) VALUES ('$smid','$misstatements_account[$i]','$misstatements_amount[$i]')");
    }   
    if($add_misstatement == TRUE){
    echo "<script>
            swal({
                closeOnClickOutside: false,
                icon: 'success',
                text: 'Updated!',
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
                closeOnClickOutside: false,
                icon: 'error',
                text: 'Error!',
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
}

?>