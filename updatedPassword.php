<?php

include 'dbconnection.php';
include 'customMailer.php';

$id = trim($_POST['id']);
$pass = md5(trim($_POST['pass']));
$data = 0;
if($con->query("select id from user where id = $id and reset_code != '' ")->num_rows == 1){
   if($con->query("update user set password = '$pass', reset_code = '', first_logged_status = 0 where id = $id")){
      $data = 1;
      $user = $con->query("select email, name from user where id = $id")->fetch_assoc();
      $name = $user['name'];
      $email = $user['email'];
      $loginLink = 'http://yourfirmaudit.com/AuditSoft/login';

      $msg = "<div>
         <div>Hello ".$name.",</div>
         <br />
         <div>Your Password reset was successfull.</div>
         <br />
         <div>Your email id: ".$email."</div>
         <div>Login with the new password.</div>
         <br/>
         <a href='".$loginLink."'><button style=' background-color: #008CBA; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor:pointer;'>Login</button></a>
         <br />
         <br />
         <div>Note:- For security purposes, please do not share this email with anyone as it contains your account</div>
         <div>information. If you have login problems or questions, or you are having problems with this email, please</div>
         <div>contact the Help desk or your firm administrator.</div>
         <br />
         <div>Thank you.</div>
         <br />
         <div>Auditor's Desk Team</div>
         </div>";
      customMailer($email,$msg,'Password Reset was successfull');
   }
}
echo $data;

?>