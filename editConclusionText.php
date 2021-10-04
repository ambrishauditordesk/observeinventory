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
    $text = 'Nothing to update.';
    if($_POST){
        $id = trim($_POST['id']);
        $wid = trim($_POST['wid']);
        $name = trim($_POST['name']);
        $ser = $_SERVER['HTTP_REFERER'];
        $conclusionQuery = $con->query("select conclusion_text from going_concern where id = $id");
        if($conclusionQuery->num_rows > 0){
            if($conclusionQuery->fetch_assoc()['conclusion_text'] != $name){
                if($con->query("update going_concern set conclusion_text = '$name' where id = $id") === TRUE){
                    $flag = 1;
                    $text = 'Successfully Updated.';
                }
            }
        }
        else{
            if($con->query("INSERT INTO going_concern(workspace_id, going_concern_radio, desc_a, desc_b, desc_c, conclusion_text) VALUES ('$wid','','','','','$name')") === TRUE){
                $flag = 1;
                $text = 'Successfully added.';
            }
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