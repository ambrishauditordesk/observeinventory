<!DOCTYPE html>
<html lang="en">

<head>
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
session_start();

$wid = trim($_GET['wid']);
$adjustment_number = trim($_POST['editAdjustment_number']);
$type = trim($_POST['editType']);
$misstatement = trim($_POST['editMisstatement']);
$missstatements_description = trim($_POST['editMissstatements_description']);
$misstatements_account = array();
$misstatements_amount = array();
$ser = trim($_SERVER['HTTP_REFERER']);
$logId = trim($_POST['logId']);

$i =0;
foreach (($_POST['misstatements_account']) as $account) {
    $misstatements_account[$i++] = trim($account);
}

$i =0;
foreach (($_POST['misstatements_amount']) as $amount) {
    $misstatements_amount[$i++] = trim($amount);
}
$count=$i;


$con->query("DELETE FROM summery_of_misstatements_log where summery_of_misstatements_id = $logId");
$con->query("DELETE FROM summery_of_misstatements where id = $logId");

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
                icon: 'success',
                text: 'Updated!',
                closeOnClickOutside: false,
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
                text: 'Error!',
                closeOnClickOutside: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
}

?>