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
    $text = 'Nothing to update.';
    if($_POST){
        $id = trim($_POST['id']);
        $name = trim($_POST['name']);
        $ser = $_SERVER['HTTP_REFERER'];
        $going_concern_conclusion = $con->query("select going_concern_conclusion_data from going_concern_conclusion where id  = $id")->fetch_assoc()['going_concern_conclusion_data'];
        if(trim($going_concern_conclusion) != $name){
            $con->query("update going_concern_conclusion set going_concern_conclusion_data = '$name' where id = $id");
            $text = 'Successfully updated.';
        }
        echo "<script>
                swal({
                    icon: 'success',
                    text: '".$text."',
                    closeOnClickOutside: false,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = '$ser';
                    }
                });
            </script>";
    }

?>