<?php
include 'scan.php';

function checkFileAllowedExt($filename,$tmp_name){
   $allowed = array(
      'xlsx', 'xlsm', 'xlsb','xltm', 
      'xlt', 'xml', 'xlam', 'xla', 'xlw', 
      'xlr', 'txt', 'pdf', 'doc', 'docm', 
      'docx', 'dot', 'dotm', 'dotx', 'rtf',
      'wps', 'xml', 'xml', 'xps', 'csv');
   $data = $scanFile = 1;
   if(sizeof(explode('.', $filename)) != 2){
      $data = 0;
   }
   else{
      if (!in_array(explode('.', $filename)[1], $allowed)) {
         $data = 0;
      }
   }
   // if($data){
   //    $scanFile = scanFile($tmp_name);
   // }
   // return ($scanFile == 0 && $data == 1) ? 1 : 0;
   return $data;

}

?>