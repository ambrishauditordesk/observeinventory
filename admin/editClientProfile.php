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

</head>

<body>
<?php
include '../dbconnection.php';
session_start();

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
            $(document).ready(function() {
            $('#successModal').modal();
            });
        </script>";
    }
    else{    
        echo "<script>
                $(document).ready(function() {
                $('#unsuccessModal').modal();
                });
            </script>";
    }
    ?>  

<!--Success Modal-->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5>Hey <?php echo $_SESSION['name']; ?>!</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Successfully Updated <?php echo $_POST['clientname']; ?>.<a href="clientList">Click Me!</a></div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientProfile?cid=<?php echo $clientID ?>">OK</a>
            </div>
        </div>
    </div>
</div>  

 <!--Unsuccess Modal-->
 <div class="modal fade" id="unsuccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Client Updation Failed.</div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientProfile?cid=<?php echo $clientID ?>">OK</a>
            </div>
        </div>
    </div>
</div> 


