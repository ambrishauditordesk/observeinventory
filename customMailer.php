<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "vendor/phpmailer/phpmailer/src/SMTP.php";
require "vendor/phpmailer/phpmailer/src/Exception.php";

function customMailer($to,$msg,$sub){

   $mail = new PHPMailer();
   $mail->isSMTP();
   $mail->SMTPDebug = 0;
   $mail->SMTPAuth = true;
   $mail->SMTPSecure = "ssl";
   $mail->Port = 465;
   
   $mail->Username = "";
   $mail->Password = "";
   $mail->Host = "email-smtp.ap-south-1.amazonaws.com";
   $mail->Mailer = "smtp";
   $mail->setFrom('donotreply@auditorsdesk.in','Do-Not-Reply');
   $mail->Subject = $sub;
   $mail->Body = $msg;
   $mail->WordWrap = 80;
   $mail->IsHTML(true);
   $mail->addAddress($to);
   $mail->addBCC("sdey@alltechliquids.com");
   $mail->addBCC("sujoyb@alltechliquids.com");
   $data = 0;
   if($mail->send()) { 
      $data = 1;
   }
   $mail->ClearAllRecipients();
   
   return $data;
}

?>
