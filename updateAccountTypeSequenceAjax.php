<?php
   include 'dbconnection.php';
   session_start();

   $wid = isset($_SESSION['workspace_id']) ? $_SESSION['workspace_id']: $_SESSION['workspace_id'];
   $data['status'] = 0;
   $data['text'] = 'Nothing to update';

   for($i = 0; $i < sizeof($_POST['accountTypeSeqNumberBS']); $i++){
      if($con->query("SELECT accountTypeSeqNumber from trial_balance where workspace_id = $wid and account_type = '".$_POST['accountTypeSequenceNameBS'][$i]."' limit 1")->fetch_assoc()['accountTypeSeqNumber'] != $_POST['accountTypeSeqNumberBS'][$i]){
         $con->query("UPDATE trial_balance set accountTypeSeqNumber ='".$_POST['accountTypeSeqNumberBS'][$i]."' where workspace_id = $wid and account_type = '".$_POST['accountTypeSequenceNameBS'][$i]."'");
         $data['status'] = 1;
         $data['text'] = 'Updated';
      }
   }

   for($i = 0; $i < sizeof($_POST['accountTypeSeqNumberPL']); $i++){
      if($con->query("SELECT accountTypeSeqNumber from trial_balance where workspace_id = $wid and account_type = '".$_POST['accountTypeSequenceNamePL'][$i]."' limit 1")->fetch_assoc()['accountTypeSeqNumber'] != $_POST['accountTypeSequenceNamePL'][$i]){
         $con->query("UPDATE trial_balance set accountTypeSeqNumber ='".$_POST['accountTypeSeqNumberPL'][$i]."' where workspace_id = $wid and account_type = '".$_POST['accountTypeSequenceNamePL'][$i]."'");
         $data['status'] = 1;
         $data['text'] = 'Updated';
      }
   }

   echo json_encode($data);
?>