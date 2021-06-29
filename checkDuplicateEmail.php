<?php
   function checkDuplicateEmail($email){
      include 'dbconnection.php';
      $totalEmailCount = $con->query("SELECT count(id) total from user where email = '$email'")->fetch_assoc()['total'];
      $data = true;
      if($totalEmailCount > 0)
      {
         $data = false;
      }
      return $data;
   }
?>