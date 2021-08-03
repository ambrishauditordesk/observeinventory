<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <!-- <meta http-equiv="refresh" content="5;url=login" /> -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <!-- Custom fonts for this template-->
   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="css/sb-admin-2.min.css" rel="stylesheet">
   <link href="css/pace-theme.css" rel="stylesheet">
   <link href="css/custom.css" rel="stylesheet">

   <!-- JQuery CDN -->
   <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

   <!-- Datatable CDN -->
   <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
   <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

</head>
<body oncontextmenu="return false">

<!-- bootstrap js -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>
<script src="js/demo/datatables-demo.js"></script>
<script src="js/pace.min.js"></script>
<script src="js/custom.js"></script>

<!-- sweetalert cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous"></script>
<?php

   include 'dbconnection.php';
   include 'customMailer.php';

   if(isset($_POST['email']) && !empty($_POST['email'])){
      $email = trim($_POST['email']);
      $emailCheck = $con->query("select id, name from user where email = '$email'");
      if($emailCheck->num_rows == 1){
         $emailCheck = $emailCheck->fetch_assoc();
         $id = $emailCheck['id'];
         $name = $emailCheck['name'];

         $resetCode = bin2hex(random_bytes(50));
         $con->query("update user set reset_code = '$resetCode' where id = $id");
         
         if($_SERVER['HTTP_HOST'] == 'http://localhost'){
            $resetLink = $_SERVER['HTTP_HOST'].'/AuditSoft/reset?code='.$resetCode.'&email='.$email;
         }
         elseif($_SERVER['HTTP_HOST'] == 'http://atlats.in'){
            $resetLink = $_SERVER['HTTP_HOST'].'/audit/reset?code='.$resetCode.'&email='.$email;
         }
         elseif($_SERVER['HTTP_HOST'] == 'http://yourfirmaudit.com'){
            $resetLink = $_SERVER['HTTP_HOST'].'/AuditSoft/reset?code='.$resetCode.'&email='.$email;
         }

         $msg = "<div>
         <div>Hello ".$name.",</div>
         <br />
         <div>A request has been received to change the password for your Digital audit software.</div>
         <br />
         <div>Your email id: ".$email."</div>
         <br/>
         <a href='".$resetLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Reset Password</button></a>
         <br />
         <br />
         <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
         <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
         <div>contact the Help desk or your firm administrator.</div>
         <br />
         <div>Thank you.</div>
         <br />
         <div>The Auditedg Team</div>
         </div>";

         $emailStatus = customMailer($email,$msg, 'Reset Password');
         if($emailStatus){
            echo "<script>
                swal({
                    icon: 'success',
                    text: 'Check your email for Reset Password link',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = 'login';
                    }
                });
            </script>";
         }
         else{
            echo "<script>
                swal({
                    icon: 'error',
                    text: 'Please Enter a valid email',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = 'login';
                    }
                });
            </script>";
         }
      }
      else{
         header('Location: login');
      }
   }
   else{
      header('Location: login');
   }

   ?>
</body>
</html>