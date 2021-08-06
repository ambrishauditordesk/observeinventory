<?php
   function checkDuplicateEmail($email){
      include 'dbconnection.php';
      return $con->query("SELECT count(id) total from user where email = '$email'")->fetch_assoc()['total'] > 0 ? false : true;
   }
?>