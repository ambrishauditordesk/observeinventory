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
    $cHigh = array();
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
        $name = explode(".", $_FILES['file']['name'])[0]."_$submat_id.".explode(".", $_FILES['file']['name'])[1];
        $tmp_name = $_FILES['file']['tmp_name'];
        $path = 'uploads/materiality/';
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
        foreach ($_POST['materialityData']['cHigh'] as $data) {
            $cHigh[] = $data;
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
            if($con->query("update materiality set amount = '$amount[$i]', standard_low='$sLow[$i]', standard_high='$sHigh[$i]', custom_low='$cLow[$i]', custom_high='$cHigh[$i]' where workspace_id='$wid' and id = '$id[$i]'") === TRUE)
            {
                $flag=1;
            }
            else
            {
                $flag=0;
            }
            echo "<br>";
        }

        if ($flag) 
        {
            $con->query("update sub_materiality set comments='$comment',balance_asset='$aScope',balance_liability='$lScope',pl_income='$pliScope',pl_expense='$pleScope' where workspace_id='$wid'");
            if($filePresent)
            {
                $con->query("insert into materiality_files(fname,submat_id,workspace_id) values ('$name','$submat_id','$wid')");
                if(!move_uploaded_file($tmp_name, $path . $name)){
                    // File write permission is not given in the server.
                $error.= "<p>File was not uploaded but record created. Contact Admin ASAP.</p>";
                }
            }
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
        } else 
        {
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
    }
?>
</body>

</html>