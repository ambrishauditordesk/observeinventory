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

    <script src="js/pace.min.js"></script>
    <script src="js/custom.js"></script>


    <?php
include 'dbconnection.php';
session_start();
$wid = $_POST['wid'];
$parent_id = $_POST['parent_id'];
$pid = $_POST['pid'];

$uploadFiles = $_FILES['file']['tmp_name'];

require 'vendor/PHPExcel/Classes/PHPExcel.php';
require_once 'vendor/PHPExcel/Classes/PHPExcel/IOFactory.php';

$updatedData = $errorList = '';
$totalCount = 0;
$flag = 0;

ini_set('memory_limit', '8192M');
set_time_limit(3600);
$count = $con->query("select count(id) total from trial_balance where workspace_id = $wid");
if($count->fetch_assoc()['total'] > 0){
    $con->query("delete from trial_balance where workspace_id = $wid");
    $con->query("delete from workspace_log where workspace_id = $wid and program_id = 395");
}

$objExcel = PHPExcel_IOFactory::load($uploadFiles);
$totalCount = $successCount = $errorCount = 0;
foreach($objExcel->getWorksheetIterator() as $worksheet){
    $highestRow = $worksheet->getHighestRow();
    for($row = 2; $row<=$highestRow; $row++){
        $totalCount = $highestRow;
        $errorMessage = '';
        $accountNumber = trim($worksheet->getCellByColumnAndRow(0,$row)->getValue());
        $accountName = trim($worksheet->getCellByColumnAndRow(1,$row)->getValue());
        $cyBegBal = trim($worksheet->getCellByColumnAndRow(2,$row)->getValue());
        $cyInterimBal = trim($worksheet->getCellByColumnAndRow(3,$row)->getValue());
        $cyActivity = trim($worksheet->getCellByColumnAndRow(4,$row)->getValue());
        $cyEndBal = trim($worksheet->getCellByColumnAndRow(5,$row)->getValue());
        $cyFinalBal = trim($worksheet->getCellByColumnAndRow(8,$row)->getFormattedValue());
        $accountType = trim($worksheet->getCellByColumnAndRow(9,$row)->getValue());
        $accountClass = trim($worksheet->getCellByColumnAndRow(10,$row)->getValue());
        $financialStatement = trim($worksheet->getCellByColumnAndRow(11,$row)->getValue());
        
        $cyBegBalAmount = $cyInterimBalAmount = $cyActivityAmount = $cyEndBalAmount = $cyFinalBalAmount = 0;

        // For CY_Beg_Bal
        // $total = '';
        // $cyBegBalStr = preg_split('/,|(|)/', $cyBegBal,-1, PREG_SPLIT_NO_EMPTY);
        // if($cyBegBalStr[0] != '-'){
        //     if($cyBegBalStr[0] == '(' || end($cyBegBalStr) == ')')
        //     foreach($cyBegBalStr as $key => $value){
        //     if(is_numeric($value))
        //         $total .=$value;
        //     }
        //     $cyBegBalAmount +=$total;
        // }
        {
            $total = '';
            if($cyBegBal[0] == '('){
                $value = '-'.(filter_var($cyBegBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            }
            else{
                $value = filter_var($cyBegBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                if ($cyBegBal[0] == '-' && strlen($cyBegBal) == 1) {
                    $value = 0;
                }
            }
            $cyBegBalAmount +=$value;
            $cyBegBal = $value;
        }

        // For CY_Interim_Bal
        // $total = '';
        // $cyInterimBalStr = preg_split('/,|(|)/', $cyInterimBal,-1, PREG_SPLIT_NO_EMPTY);
        // if($cyInterimBalStr[0] != '-'){
        //     if($cyInterimBalStr[0] == '(' || end($cyInterimBalStr) == ')')
        //         $total = '-';
        //     foreach($cyInterimBalStr as $key => $value){
        //     if(is_numeric($value))
        //         $total .=$value;
        //     }
        //     $cyInterimBalAmount +=$total;
        // }
        {
            $total = '';
            if($cyInterimBal[0] == '('){
                $value = '-'.(filter_var($cyInterimBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            }
            else{
                $value = filter_var($cyInterimBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                if ($cyInterimBal[0] == '-' && strlen($cyInterimBal) == 1) {
                    $value = 0;
                }
            }
            $cyInterimBalAmount +=$value;
            $cyInterimBal = $value;
        }

        // For CY_Activity
        // $total = '';
        // $cyActivityStr = preg_split('/,|(|)/', $cyActivity,-1, PREG_SPLIT_NO_EMPTY);
        // if($cyActivityStr[0] != '-'){
        //     if($cyActivityStr[0] == '(' || end($cyActivityStr) == ')')
        //         $total = '-';
        //     foreach($cyActivityStr as $key => $value){
        //     if(is_numeric($value))
        //         $total .=$value;
        //     }
        //     $cyActivityAmount +=$total;
        // }
        {
            $total = '';
            if($cyActivity[0] == '('){
                $value = '-'.(filter_var($cyActivity, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            }
            else{
                $value = filter_var($cyActivity,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                if ($cyActivity[0] == '-' && strlen($cyActivity) == 1) {
                    $value = 0;
                }
            }
            $cyActivityAmount +=$value;
            $cyActivity = $value;
        }

        // For CY_End_Bal
        // $total = '';
        // $cyEndBalStr = preg_split('/,|(|)/', $cyEndBal,-1, PREG_SPLIT_NO_EMPTY);
        // if($cyEndBalStr[0] != '-'){
        //     if($cyEndBalStr[0] == '(' || end($cyEndBalStr) == ')')
        //         $total = '-';
        //     foreach($cyEndBalStr as $key => $value){
        //     if(is_numeric($value))
        //         $total .=$value;
        //     }
        //     $cyEndBalAmount +=$total;
        // }
        {
            $total = '';
            if($cyEndBal[0] == '('){
                $value = '-'.(filter_var($cyEndBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            }
            else{
                $value = filter_var($cyEndBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                if ($cyEndBal[0] == '-' && strlen($cyEndBal) == 1) {
                    $value = 0;
                }
            }
            $cyEndBalAmount +=$value;
            $cyEndBal = $value;
        }

        // For CY_End_Bal
        // $total = '';
        // $cyFinalBalStr = preg_split('/,|(|)/', $cyFinalBal,-1, PREG_SPLIT_NO_EMPTY);
        // if($cyFinalBalStr[0] != '-'){
        //     if($cyFinalBalStr[0] == '(' || end($cyFinalBalStr) == ')')
        //         $total = '-';
        //     foreach($cyFinalBalStr as $key => $value){
        //     if(is_numeric($value))
        //         $total .=$value;
        //     }
        //     $cyFinalBalAmount +=$total;
        //     $cyFinalBal = $total;
        // }
        {
            $total = '';
            if($cyFinalBal[0] == '('){
                $value = '-'.(filter_var($cyFinalBal, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            }
            else{
                $value = filter_var($cyFinalBal,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                if ($cyFinalBal[0] == '-' && strlen($cyFinalBal) == 1) {
                    $value = 0;
                }
            }
            $cyFinalBalAmount +=$value;
            $cyFinalBal = $value;
        }

        // Inserting the data into database
        $insertQuery = "INSERT INTO `trial_balance`(`workspace_id`,`account_number`, `account_name`, `cy_beg_bal`, `cy_interim_bal`, `cy_activity`, `cy_end_bal`, `client_adjustment`, `audit_adjustment`, `cy_final_bal`, `account_type`, `account_class`, `financial_statement`) 
        VALUES ('$wid','$accountNumber','$accountName','$cyBegBal','$cyInterimBal','$cyActivity','$cyEndBal','','','$cyFinalBal','$accountType','$accountClass','$financialStatement')";

        if($con->query($insertQuery) === TRUE){
            $updatedData.= '<strong>Record created:- </strong><br>Recorded created <br> Row No:- '.($row-1)."<br>";
            $flag = 1;
            $successCount++;
        }
        else{
            $errorMessage.="<br>Data is invalid.";
        } 
        if(!empty($errorMessage))
            $errorList.= "<strong>Errors for Record Row number:- </strong>".($row-1)."<br>".$errorMessage."<br><hr>";
    }
}
if($flag){
    echo "<script>
    $(document).ready(function() {
        $('#successModal').modal();
    });
    </script>";
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
                    <a class="btn btn-primary" href="subProgram.php?pid=<?php echo $pid; ?>&parent_id=<?php echo $parent_id; ?>&wid=<?php echo $wid; ?>">OK</a>
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
                <div class="modal-body"><?php echo $errorList; ?>.</div>
                <div class="modal-footer">
                <a class="btn btn-primary" href="subProgram.php?pid=<?php echo $pid; ?>&parent_id=<?php echo $parent_id; ?>&wid=<?php echo $wid; ?>">OK</a>
                </div>
            </div>
        </div>
    </div>