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

ini_set('memory_limit', '8192M');
set_time_limit(3600);
$count = $con->query("select count(id) total from trial_balance where workspace_id = $wid");
if($count->fetch_assoc()['total'] > 0){
    $con->query("delete from trial_balance where workspace_id = $wid");
    $con->query("delete from workspace_log where workspace_id = $wid and program_id = 395");
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

for($i=1;$i<sizeof($rows);$i++){
    $accountNumber = $rows[$i][0];
    $accountName = $rows[$i][1];
    $cyBegBal = $rows[$i][2];
    $cyFinalBal = $rows[$i][3];
    $accountType = $rows[$i][4];
    $accountClass = $rows[$i][5];
    $financialStatement = $rows[$i][6];

    {
        $total = '';
        if(substr($cyBegBal, 0, 1) == '('){
            $value = (float)'-'.(filter_var($cyBegBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
        }
        else{
            $value = (float)filter_var($cyBegBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            if (substr($cyBegBal, 0, 1) == '-' && strlen($cyBegBal) == 1) {
                $value = 0;
            }
        }
        $cyBegBalAmount +=$value;
        $cyBegBal = $value;
    }

    {
        $total = '';
        if(substr($cyFinalBal, 0, 1) == '('){
            $value = (float)'-'.(filter_var($cyFinalBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
        }
        else{
            $value = (float)filter_var($cyFinalBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            if (substr($cyFinalBal, 0, 1) == '-' && strlen($cyFinalBal) == 1) {
                $value = 0;
            }
        }
        $cyFinalBalAmount +=$value;
        $cyFinalBal = $value;
    }

    // Inserting the data into database
    $insertQuery = "INSERT INTO `trial_balance`(`workspace_id`,`account_number`, `account_name`, `cy_beg_bal`, `cy_interim_bal`, `cy_activity`, `cy_end_bal`, `client_adjustment`, `audit_adjustment`, `cy_final_bal`, `account_type`, `account_class`, `financial_statement`) 
    VALUES ('$wid','$accountNumber','$accountName','$cyBegBal','','','','','','$cyFinalBal','$accountType','$accountClass','$financialStatement')";

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
        // $con->query("UPDATE workspace_log SET import = 1 WHERE workspace_id = '".$wid."' and program_id = 395");
        }
        echo "<script>
            $(document).ready(function() {
            $('#successModal').modal();
            });
            </script>";
    }
    else{
        $con->query("delete from trial_balance where workspace_id = $wid");
        $con->query("delete from workspace_log where workspace_id = $wid and program_id = 395");
        
        echo "<script>
        $(document).ready(function() {
        $('#unSuccessModal').modal();
        });
        </script>";
    }
}
else{
    echo "<script>
    $(document).ready(function() {
    $('#unSuccessModal').modal();
    });
    </script>";
}
?>
    <!--Success Modal-->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hey <?php echo $_SESSION['name']; ?> !</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php $totalCount--; ?>
                <!-- <div class="modal-body"><?php // echo "<strong>New Record List:- </strong>".$updatedData."<hr><hr>"; if(!empty($errorList)) echo $errorList; ?><br><?php // echo "Total Count = $totalCount, Entry Count = $successCount"; ?></div> -->
                <div class="modal-body"><?php if(!empty($errorList)) echo $errorList; ?><br><?php echo "Total Row Count = $totalCount, Total Entry Count = $successCount"; ?></div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="subProgram?uid=<?php echo base64_encode(md5($wid));?>&zid=<?php echo base64_encode(md5(time()));?>&aid=<?php echo base64_encode(md5($wid));?>&pid=<?php echo base64_encode($pid); ?>&parent_id=<?php echo base64_encode($parent_id); ?>&wid=<?php echo base64_encode($wid); ?>&uuid=<?php echo base64_encode(md5(date('Y')));?>&zuid=<?php echo base64_encode(md5(date('m-d-Y')));?>">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!--Unsuccess Modal-->
    <div class="modal fade" id="unSuccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hey <?php echo $_SESSION['name']; ?> !</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php 
                        include 'moneyFormatter.php';
                        if($cyBegBalAmount == 0 && $cyFinalBalAmount == 0){
                                if(!empty($errorList)) 
                                    echo $errorList."."; 
                            ?>
                            <br>
                            <?php 
                                echo "Total Row Count = $totalCount, Total Entry Count = $successCount"; 
                        }
                        else{
                            $uploadFailedMsg = '';
                            if($cyBegBalAmount != 0)
                                $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Begining Balance should be ZERO but it is :<b>'.numberToCurrency($cyBegBalAmount).'</b></p>';
                            if($cyFinalBalAmount != 0){
                                if(!empty($uploadFailedMsg)){
                                    $uploadFailedMsg .= '<p>&nbsp;&nbsp;Sum of CY Final Balance should be ZERO but it is :<b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
                                }
                                else{
                                    $uploadFailedMsg = '<p>&nbsp;&nbsp;Sum of CY Final Balance should be ZERO but it is :<b>'.numberToCurrency($cyFinalBalAmount).'</b></p>';
                                }
                            }
                            echo "<p>Trial Balance Upload failed as follows:-</p>$uploadFailedMsg<p>Re upload it once you are done with it.</p>";
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="subProgram?uid=<?php echo base64_encode(md5($wid));?>&zid=<?php echo base64_encode(md5(time()));?>&aid=<?php echo base64_encode(md5($wid));?>&pid=<?php echo base64_encode($pid); ?>&parent_id=<?php echo base64_encode($parent_id); ?>&wid=<?php echo base64_encode($wid); ?>&uuid=<?php echo base64_encode(md5(date('Y')));?>&zuid=<?php echo base64_encode(md5(date('m-d-Y')));?>">OK</a>
                </div>
            </div>
        </div>
    </div>