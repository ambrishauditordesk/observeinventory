<!DOCTYPE html>
<html lang="en">

<head>
    <title>Audit-EDG</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- sweetalert cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }

    $sLow = array();
    $sHigh = array();
    $cLow = array();
    $amount = array();
    $id = array();
    $comment = trim($_POST['comment']);
    $aScope = trim($_POST['aScope']);
    $lScope = trim($_POST['lScope']);    
    $pliScope = trim($_POST['pliScope']);
    $pleScope = trim($_POST['pleScope']);
    $submat_id = trim($_POST['submat_id']);
    $wid = $_GET['wid'];
    $ser = $_SERVER['HTTP_REFERER'];
    $uid = $_SESSION['id'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $prepareFlag = $flag = 0;

    if(isset($_POST['prepareSubmit']))
    {
        $con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values('$wid','230','$uid','$date')");
        $prepareFlag = 1;
    }
    elseif(isset($_POST['reviewSubmit']))
    {
        $con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values('$wid','230','$uid','$date')");
        $reviewFlag = 1;
    }
    else
    {
        //File Upload
        $filePresent = 0;
        $uploadOk = 1;
        //var_dump($_FILES);
        if(!empty($_FILES['file']['name'])){
            $filePresent = 1;
            // File size should be less the 2MB
            if ($_FILES["file"]["size"] > 2000000) {
                $error.= "<p>File Size is greater than 2MB.</p><br>";
                $uploadOk = 0;
            }
            if(!empty($_FILES['file']['name'][0])){
                $fileName = array();
                $str = explode(".", $_FILES['file']['name']);
                $new= '';
                for($j = 0; $j<sizeof($str)-1; $j++){
                    if($new == ''){
                        $new .= $str[$j];
                    }
                    else{
                        $new .= ".".$str[$j];
                    }
                }
                $name = trim($new." ".$date." .".end($str));;
                // $tmp_name = $fileName['file']['tmp_name'];
                // $name = explode(".", $_FILES['file']['name'])[0]."_$submat_id.".explode(".", $_FILES['file']['name'])[1];
                $tmp_name = $_FILES['file']['tmp_name'];
                $path = 'uploads/materiality/';
            }
        }

        if($uploadOk)
        {
            foreach ($_POST['materialityData']['sLow'] as $data) {
                $sLow[] = $data;
            }
            foreach ($_POST['materialityData']['sHigh'] as $data) {
                $sHigh[] = $data;
            }
            foreach ($_POST['materialityData']['cLow'] as $data) {
                $cLow[] = $data;
            }
            foreach ($_POST['materialityData']['amount'] as $data) {
                $amount[] = $data;
            }
            foreach ($_POST['materialityData']['id'] as $data) {
                $id[] = $data;
            }
            $j = sizeof($sLow);
            $flag = 0;

            for ($i = 0; $i < $j; $i++) 
            {
                if($con->query("update materiality set amount = '$amount[$i]', standard_low='$sLow[$i]', standard_high='$sHigh[$i]', custom='$cLow[$i]' where workspace_id='$wid' and id = '$id[$i]'") === TRUE)
                {
                    $flag=1;
                }
                else
                {
                    $flag=0;
                }
                echo "<br>";
            }

            if($filePresent){
                $con->query("insert into materiality_files(fname,submat_id,workspace_id) values ('$name','$submat_id','$wid')");
                if(!move_uploaded_file($tmp_name, $path . $name)){
                    // File write permission is not given in the server.
                    $error.= "<p>File was not uploaded but record created. Contact Admin ASAP.</p>";
                }
            }
            $con->query("UPDATE sub_materiality SET comments = '$comment',balance_asset='$aScope',balance_liability='$lScope',pl_income='$pliScope',pl_expense= '$pleScope' WHERE workspace_id=$wid");
        }
    }

    if($flag || $prepareFlag || $reviewFlag || $filePresent){
        echo "<script>
            swal({
                icon: 'success',
                text: 'Updated!',
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
    }
    else{
        echo "<script>
            swal({
                icon: 'error',
                text: 'Error!',
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
    }
?>
</body>
</html>