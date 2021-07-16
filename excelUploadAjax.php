<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content = "text/html; charset = utf-8">
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
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/pace-theme.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

    <script src="js/pace.min.js"></script>
    <script src="js/custom.js"></script>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';

include 'dbconnection.php';
session_start();

$wid = $_POST['wid'];
$parent_id = $_POST['parent_id'];
$pid = $_POST['pid'];

$uploadFiles = $_FILES['file']['tmp_name'];
$updatedData = $errorList = $errorMessage = '';
$flag = $totalCount = $successCount = $errorCount = $cyBegBalAmount = $cyFinalBalAmount = 0;
$accountTypeSeqNumber = 1;

ini_set('memory_limit', '16384M');
set_time_limit(0);

$count = $con->query("select count(id) total from trial_balance where workspace_id = $wid");
if($count->fetch_assoc()['total'] > 0){
    $con->query("delete from trial_balance where workspace_id = $wid");
    $con->query("UPDATE workspace_log SET import = 0 WHERE workspace_id = $wid and program_id = 395");
}

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($uploadFiles);
$reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($uploadFiles);

$worksheet = $spreadsheet->getActiveSheet();
$rows = [];
foreach ($worksheet->getRowIterator() AS $key) {
    $cellIterator = $key->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
    $cells = [];
    foreach ($cellIterator as $cell) {
        $cells[] = $cell->getValue();
    }
    $rows[] = $cells;
}

$totalCount = sizeof($rows);

for($i = 1; $i < $totalCount; $i++){
    $accountNumber = trim($rows[$i][0]);
    $accountName = trim($rows[$i][1]);
    $cyBegBal = number_format((float)$rows[$i][2], 2, '.', '');
    $cyFinalBal = number_format((float)$rows[$i][3], 2, '.', '');
    $accountType = trim($rows[$i][4]);
    $accountClass = trim($rows[$i][5]);
    $financialStatement = trim($rows[$i][6]);

    $cyBegBalAmount += is_numeric($cyBegBal)? $cyBegBal: 0;
    $cyFinalBalAmount += is_numeric($cyFinalBal)? $cyFinalBal: 0;

    $cyBegBalAmount = number_format((float)$cyBegBalAmount, 2, '.', '');
    $cyFinalBalAmount = number_format((float)$cyFinalBalAmount, 2, '.', '');


    // Inserting the data into database
    $insertQuery = "INSERT INTO `trial_balance`(`workspace_id`,`account_number`, `account_name`, `cy_beg_bal`, `cy_interim_bal`, `cy_activity`, `cy_end_bal`, `client_adjustment`, `audit_adjustment`, `cy_final_bal`, `account_type`, `account_class`, `financial_statement`,`accountTypeSeqNumber`) 
    VALUES ('$wid','$accountNumber','$accountName','$cyBegBal','','','','','','$cyFinalBal','$accountType','$accountClass','$financialStatement','0')";

    if($con->query($insertQuery) === TRUE){
        $updatedData.= '<strong>Record created:- </strong><br>Recorded created <br> Row No:- '.$i."<br>";
        $flag = 1;
        $successCount++;
    }
    else{
        $errorMessage.="<br>Data is invalid.";
    } 
    if(!empty($errorMessage)){
        $errorList.= "<strong>Errors for Record Row number:- </strong>".$i."<br>".$errorMessage."<br><hr>";
    }
}

if($flag){
    if($cyBegBalAmount == 0 && $cyFinalBalAmount == 0){
        $trialBalanceResult = $con->query("select count(id) total from trial_balance where workspace_id = '".$wid."'");
        if($trialBalanceResult->fetch_assoc()['total'] > 0){
            $con->query("UPDATE workspace_log SET import = 1 WHERE workspace_id = $wid and program_id = 395");
        }
        
        $totalCount--;
        if(!empty($errorList))
            $data = $errorList;
        $data = "Total Row Count = $totalCount, Total Entry Count = $successCount"; 
        $resultBalanceSheet = $con->query("SELECT account_type from trial_balance where workspace_id='$wid' and ( account_type not like '%Expense%' and account_type not like '%Revenue%' ) group by account_type");
        if($resultBalanceSheet->num_rows > 0){
            while($row = $resultBalanceSheet->fetch_assoc()){
                $con->query("UPDATE trial_balance set accountTypeSeqNumber = '".$accountTypeSeqNumber++."' where account_type = '".$row['account_type']."' and workspace_id = '$wid'");
            }
        }

        $accountTypeSeqNumber = 1;

        $resultProfitLoss = $con->query("SELECT account_type from trial_balance where workspace_id='$wid' and ( account_type like '%Expense%' or account_type like '%Revenue%' ) group by account_type");
        if($resultProfitLoss->num_rows > 0){
            while($row = $resultProfitLoss->fetch_assoc()){
                $con->query("UPDATE trial_balance set accountTypeSeqNumber = '".$accountTypeSeqNumber++."' where account_type = '".$row['account_type']."' and workspace_id = '$wid'");
            }
        }
    }
    else{
        $con->query("delete from trial_balance where workspace_id = $wid");
        $con->query("UPDATE workspace_log SET import = 0 WHERE workspace_id = $wid and program_id = 395");

        include 'moneyFormatter.php';
        if($cyBegBalAmount == 0 && $cyFinalBalAmount == 0){
                if(!empty($errorList)) 
                    $data = $errorList.".";  
                $data = "Total Row Count = $totalCount, Total Entry Count = $successCount"; 
        }
        else{
            $uploadFailedMsg = '';
            if($cyBegBalAmount != 0)
                $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Begining Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyBegBalAmount).'</b></p>';
            if($cyFinalBalAmount != 0){
                if(!empty($uploadFailedMsg)){
                    $uploadFailedMsg .= '<p>&nbsp;&nbsp;Sum of CY Final Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
                }
                else{
                    $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Final Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
                }
            }
            $data = "<p>Trial Balance Upload failed as follows:-</p>$uploadFailedMsg<p>Re upload it once you are done with it.</p>";
        }
    }
}
else{
    include 'moneyFormatter.php';
    if($cyBegBalAmount == 0 && $cyFinalBalAmount == 0){
            if(!empty($errorList)) 
                $data = $errorList.".";  
            $data = "Total Row Count = $totalCount, Total Entry Count = $successCount"; 
    }
    else{
        $uploadFailedMsg = '';
        if($cyBegBalAmount != 0)
            $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Begining Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyBegBalAmount).'</b></p>';
        if($cyFinalBalAmount != 0){
            if(!empty($uploadFailedMsg)){
                $uploadFailedMsg .= '<p>&nbsp;&nbsp;Sum of CY Final Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
            }
            else{
                $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Final Balance should be <b>'.numberToCurrency(0).'</b> but it is:  <b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
            }
        }
        $data = "<p>Trial Balance Upload failed as follows:-</p>$uploadFailedMsg<p>Re upload it once you are done with it.</p>";
    }
}

echo $data;
?>