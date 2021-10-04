<!DOCTYPE html>
<html lang="en">

<head>
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
    $flag = 0;
    if($_POST){
        $type = trim($_POST['type']);
        $id = trim($_POST['prodecureId']);
        $name = trim($_POST['name']);
        $ser = $_SERVER['HTTP_REFERER'];
        if($con->query("update going_concern_procedures set procedure_data = '$name' where id = $id") === TRUE){
            $flag = 1;
        }
        if($flag){
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
    }

?>