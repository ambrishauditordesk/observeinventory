<?php
   include 'dbconnection.php';
   if(!isset($_SESSION)){
       session_start();
    }
   function no_dupes(array $input_array) {
      $oldArr = [];
      $unique = 1;
      for($i = 0; $i < sizeof($input_array); $i++){
         $oldArr[$i] = $input_array[$i][1];
      }
      if(count($oldArr) != count(array_unique($oldArr))){
         $unique = 0;
      }
      return $unique;
   }
   $wid = isset($_SESSION['workspace_id']) ? $_SESSION['workspace_id']: $_SESSION['workspace_id'];
   $data['status'] = 0;
   $data['text'] = 'Nothing to update';
   $flag = 1;
   $checkBS = no_dupes($_POST['accountTypeSeqBS']);
   $checkPL = no_dupes($_POST['accountTypeSeqPL']);
   if(!$checkBS || !$checkPL){
      $flag = 0;
      $data['text'] = 'Duplicate number for ordering is not allowed';
   }
   if($flag){
      for($i = 0; $i < sizeof($_POST['accountTypeSeqBS']); $i++){
         if($con->query("SELECT accountTypeSeqNumber from trial_balance where workspace_id = $wid and account_type = '".$_POST['accountTypeSeqBS'][$i][0]."' limit 1")->fetch_assoc()['accountTypeSeqNumber'] != $_POST['accountTypeSeqBS'][$i][1]){
            $con->query("UPDATE trial_balance set accountTypeSeqNumber ='".$_POST['accountTypeSeqBS'][$i][1]."' where workspace_id = $wid and account_type = '".$_POST['accountTypeSeqBS'][$i][0]."'");
            $con->query("UPDATE tb_performance_map set accountTypeSeqNumber ='".$_POST['accountTypeSeqBS'][$i][1]."' where workspace_id = $wid and accounts_type = '".$_POST['accountTypeSeqBS'][$i][0]."'");
            $data['status'] = 1;
            $data['text'] = 'Updated';
         }
      }
      for($i = 0; $i < sizeof($_POST['accountTypeSeqPL']); $i++){
         if($con->query("SELECT accountTypeSeqNumber from trial_balance where workspace_id = $wid and account_type = '".$_POST['accountTypeSeqPL'][$i][0]."' limit 1")->fetch_assoc()['accountTypeSeqNumber'] != $_POST['accountTypeSeqPL'][$i][1]){
            $con->query("UPDATE trial_balance set accountTypeSeqNumber ='".$_POST['accountTypeSeqPL'][$i][1]."' where workspace_id = $wid and account_type = '".$_POST['accountTypeSeqPL'][$i][0]."'");
            $con->query("UPDATE tb_performance_map set accountTypeSeqNumber ='".$_POST['accountTypeSeqPL'][$i][1]."' where workspace_id = $wid and accounts_type = '".$_POST['accountTypeSeqPL'][$i][0]."'");
            $data['status'] = 1;
            $data['text'] = 'Updated';
         }
      }
   }  
   echo json_encode($data);
?>