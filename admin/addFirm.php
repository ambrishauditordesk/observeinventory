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
$email = trim($_POST['firmEmail']);
$ser = $_SERVER['HTTP_REFERER'];

if ($con->query("INSERT INTO `firm_details`(`firm_name`, `firm_address`, `firm_email`,`storage`) VALUES ('$name','$add','$email','10000')")== TRUE){
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
?>
</body>
</html>
