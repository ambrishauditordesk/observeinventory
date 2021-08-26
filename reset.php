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

   <style>
      body {
      background: #f8f8f8;
      font-family: sans-serif;
      font-size: 10px
      }
      form {
      background: #fff;
      padding: 4em 4em 2em;
      max-width: 400px;
      margin: 100px auto 0;
      box-shadow: 0 0 1em #222;
      border-radius: 5px;
      }
      p {
      margin: 0 0 3em 0;
      position: relative;
      }
      label {
      display: block;
      font-size: 1.6em;
      margin: 0 0 .5em;
      color: #333;
      }
      input {
      display: block;
      box-sizing: border-box;
      width: 100%;
      outline: none
      }
      input[type="text"],
      input[type="password"] {
      background: #f5f5f5;
      border: 1px solid #e5e5e5;
      font-size: 1.6em;
      padding: .8em .5em;
      border-radius: 5px;
      }
      input[type="text"]:focus,
      input[type="password"]:focus {
      background: #fff
      }
      .formHint {
      border-radius: 5px;
      display: block;
      font-size: 1.3em;
      text-align: center;
      position: absolute;
      background: #2F558E;
      left: 105%;
      top: 50%;
      width: 100%;
      padding: 7px 10px;
      color: #fff;
      }
      .formHint:after {
      right: 100%;
      top: 50%;
      border: solid transparent;
      content: " ";
      height: 0;
      width: 0;
      position: absolute;
      pointer-events: none;
      border-color: rgba(136, 183, 213, 0);
      border-right-color: #2F558E;
      border-width: 8px;
      margin-top: -8px;
      }
      input[type="submit"] {
      background: #2F558E;
      box-shadow: 0 3px 0 0 #1D3C6A;
      border-radius: 5px;
      border: none;
      color: #fff;
      cursor: pointer;
      display: block;
      font-size: 2em;
      line-height: 1.6em;
      margin: 2em 0 0;
      outline: none;
      padding: .8em 0;
      text-shadow: 0 1px #68B25B;

      }
   </style>

</head>
<body oncontextmenu="return false">
<?php

   include 'dbconnection.php';

   $resetCode = trim($_GET['code']);
   $email = trim($_GET['email']);

   $result = $con->query("select id from user where reset_code = '$resetCode' and email = '$email'");
   if($result->num_rows == 1){
      $id = $result->fetch_assoc()['id'];
      ?>
      <form id="resetForm" action="#" method="post" autocomplete="off">
         <p>
            <label for="password">Password</label>
            <input id="password" name="password" type="password">
            <label class="formHint" style="top: 0;">Must be longer than 8 characters.<br>Must contain 1 lower case character.<br>Must contain 1 upper case character.<br>Must contain 1 number.<br>Must contain 1 special character</label>
            <a href="" class="text-decoration-none position-absolute" id="showPasswordButton" style="top: 50%; right: 0;"><i class="fas fa-eye fa-3x"></i></a>
            <a href="" class="text-decoration-none d-none position-absolute" id="hidePasswordButton" style="top: 50%; right: 0;"><i class="fas fa-eye-slash fa-3x"></i></a>
         </p>
         <p class="d-none" id="showPasswordTextDiv">
            <label id="showPasswordText"></label>
         </p>
         <p>
            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" name="confirm_password" type="password">
            <label class="formHint">Please confirm your password</label>
         </p>
         <p>
            <input id="submit" type="submit" value="SUBMIT" >
         </p>
      </form>
      <?php
   }
   else{
      header('Location: login');
   }

?>

<!-- bootstrap js -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom scripts for all pages-->
<script src="js/custom.js"></script>

<!-- sweetalert cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous"></script>
<script>
   $(document).ready(function(){
         var $password = $("#password");
         var $confirmPass = $("#confirm_password");

         //Check the length of the Password
         function checkLength(){
            return $password.val().length > 8;
         }

         function checkLengthConfirm(){
            return $confirmPass.val().length > 8;
         }

         //Check to see if the value for pass and confirmPass are the same
         function samePass(){
            if($password.val()===$confirmPass.val() && checkLengthConfirm() && checkLength()){
               $("#confirm_password").css('border','2px solid green');
               return $password.val()===$confirmPass.val();
            }
            else{
               $("#confirm_password").css('border','1px solid #e5e5e5');
               return $password.val()===$confirmPass.val();
            }
         }

         //If checkLength() is > 8 then we'll hide the hint
         function PassValidate(){
            if(checkLength() && PassValidateRegex()){
               $("#password").css('border','2px solid green');
               $password.next().hide();
            }else{
               $("#password").css('border','1px solid #e5e5e5');
               $password.next().show();
            }
         }

         function PassValidateRegex(){
            var pattern = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$");
            console.log(pattern.test($password.val()))
            return pattern.test($password.val());
         }

         //If samePass returns true, we'll hide the hint
         function PassMatch(){
            if(samePass()){
               $confirmPass.next().hide();
            }else{
               $confirmPass.next().show();
            }
         }
         function canSubmit(){
            return samePass() && checkLength();
         }
         function enableSubmitButton(){
            $("#submit").prop("disabled",!canSubmit());
         }
         function showPassword(){
            $("#showPasswordText").html($password.val())
         }
         //Calls the enableSubmitButton() function to disable the button
         enableSubmitButton();

         $password.keyup(PassValidate).keyup(PassMatch).keyup(enableSubmitButton).keyup(showPassword);
         $confirmPass.focus(PassMatch).focus(checkLengthConfirm).keyup(PassMatch).keyup(enableSubmitButton);
      });

      $("#resetForm").submit(function(e){
         e.preventDefault();
         $.ajax({
            url: 'updatedPassword.php',
            type: 'POST',
            data: {
               id: <?php echo $id; ?>,
               pass: $("#password").val()
            },
            success: function(data){
               swal({
                    icon: data == 1? 'success':'error',
                    text: data == 1? 'Password reset is successful': 'Error happened contact admin',
                    closeOnClickOutside: false,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = 'login';
                    }
                });
            }
         })
      });

      $("#showPasswordButton").click(function(e){
         e.preventDefault();
         $("#hidePasswordButton").removeClass("d-none");
         $("#showPasswordButton").addClass("d-none");
         $("#showPasswordTextDiv").removeClass("d-none")
      });

      $("#hidePasswordButton").click(function(e){
         e.preventDefault();
         $("#showPasswordButton").removeClass("d-none");
         $("#hidePasswordButton").addClass("d-none");
         $("#showPasswordTextDiv").addClass("d-none")
      })

</script>