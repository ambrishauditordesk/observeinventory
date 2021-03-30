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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

</head>

<body>
<?php
include '../dbconnection.php';
session_start();

$cname = array();
$email = array();
$pass = array();
$desig = array();


$addedById = $_SESSION['id'];

// Getting the CST Time
$addedByDate = date_format(date_create("now", new DateTimeZone('America/Chicago')), "Y-m-d");

$uploadOk = 1;

// Name
$name = '';
if (!empty($_POST['clientname']) && isset($_POST['clientname'])) {
    $name = trim($_POST['clientname']);
    if($con->query("select * from client where name = '$name'")->num_rows != 0){
        $error.= "<p><strong>".$name."</strong> is already present.</p><br>";
        $uploadOk = 0;
    }
}

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

//Person Name
$i =0;

foreach (($_POST['cname']) as $personname) {
    $cname[$i++] = trim($personname);
}

//Email
$i =0;

foreach (($_POST['email']) as $emailID) {
    $email[$i++] = trim($emailID);
}

//Phone
$i =0;

foreach (($_POST['pass']) as $password) {
    $pass[$i++] = md5(trim($password));
}

//Designation
$i =0;

foreach (($_POST['designation']) as $designation) {
    $desig[$i++] = trim($designation);
}
$count = $i;

if($uploadOk) {
    $con->query("insert into client(active,added_by_id,added_by_date,name,nickname,incorp_date,const_id,industry_id,address,city,state,pincode,country,pan,gst,tan,cin)
    values('1','$addedById','$addedByDate','$name','$nickName','$date','$const','$industry','$add','$city','$state','$pin','$country','$pan','$gst','$tan','$cin')");
    $cid = $con->insert_id;
    for($i=0;$i<$count;$i++){
        $con->query("insert into user(client_id,name,email,password,accessLevel,active,designation) values('$cid','$cname[$i]','$email[$i]','$pass[$i]','3','1','$desig[$i]')");
        $uid = $con->insert_id;
        $con->query("insert into user_client_log(user_id,client_id) values('$uid','$cid')");
    }
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
            <div class="modal-body">Successfully added <?php echo $_POST['clientname']; ?>.<a href="clientList">Click Me!</a></div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientList">OK</a>
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
            <div class="modal-body">Client Addition Failed.</div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="clientList">OK</a>
            </div>
        </div>
    </div>
</div> 


