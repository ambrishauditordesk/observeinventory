<!DOCTYPE html>
<html lang="en">

<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/pace-theme.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <link href="../css/uiux.css" rel="stylesheet" type="text/css">

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>

</head>

<body>
<?php
include '../dbconnection.php';
session_start();

$ser = $_SERVER['HTTP_REFERER'];

$addedById = trim($_POST['id']);

// Getting the CST Time
$addedByDate = trim($_POST['date']);

$uploadOk = 1;

// Name
$name = trim($_POST['clientname']);

//Nickname
$nickName = trim($_POST['nickname']);

//Date
$date = trim($_POST['dob']);

//Constitution
$const = trim($_POST['constitution']);

//Industry
$industry = trim($_POST['industry']);

//Address
$add = trim($_POST['add']);

//City
$city = trim($_POST['city']);

//State
$state = trim($_POST['state']);

//Pincode
$pin = trim($_POST['pincode']);

//Country
$country = trim($_POST['country']);

//PAN
$pan = trim($_POST['pan']);

//GST
$gst = trim($_POST['gst']);

//TAN
$tan = trim($_POST['tan']);

//CIN
$cin = trim($_POST['cin']);

$clientID = trim($_POST['cid']);

$active = trim($_POST['active']);

if($uploadOk){
    
    $con->query("update client set active='$active',added_by_id='$addedById',added_by_date='$addedByDate',name='$name',nickname='$nickName',incorp_date='$date',const_id='$const',industry_id='$industry',address='$add',city='$city',state='$state',pincode='$pin',country='$country',pan='$pan',gst='$gst',tan='$tan',cin='$cin' where id='$clientID'");
    // var_dump("update client set active='1',added_by_id='$addedById',added_by_date='$addedByDate',name='$name',nickname='$nickName',incorp_date='$date',const_id='$const',industry_id='$industry',address='$add',city='$city',state='$state',pincode='$pin',country='$country',pan='$pan',gst='$gst',tan='$tan',cin='$cin' where id='$clientID'");
    // return;
    echo "<script>
            swal({
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

    <script>
        $(document).ready(function() {
        let darkmode = <?php echo $_SESSION['darkmode']; ?>;
        if(darkmode)
        {
            document.documentElement.classList.toggle('dark-mode');
            // document.querySelectorAll('.dark-invert').forEach((result) => {
            //     result.classList.toggle('invert-dark-mode');
            // });
            $("#settingsModal #dark-active").attr('checked','checked');
        }
        else if(!darkmode){
            document.documentElement.classList.remove('dark-mode');
            $("#settingsModal #dark-inactive").attr('checked','checked');
        }
    });
    </script>

