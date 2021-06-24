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
    $type = array();
    $amount = array();
    $risk = array();
    $import = array();
    $id = array();
    $wid=$_GET['wid'];
    $pid=$_GET['pid'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $ser = $_SERVER['HTTP_REFERER'];

    if(isset($_POST['prepareSubmit']))
    {
        $con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values('$wid','239','$uid','$date')");
        $prepareFlag = 1;
    }
    elseif(isset($_POST['reviewSubmit']))
    {
        $con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values('$wid','239','$uid','$date')");
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
                $path = $_SESSION['upload_file_location'];
                $size = ($_FILES['file']['size']/1000);
            }
        }
        
        if($uploadOk)
        {
            foreach ($_POST['submitData']['type'] as $data) {
                $type[] = $data;
            }
            foreach ($_POST['submitData']['amount'] as $data) {
                $amount[] = $data;
            }
            foreach ($_POST['submitData']['risk'] as $data) {
                $risk[] = $data;
            }
            foreach ($_POST['submitData']['import'] as $data) {
                $import[] = $data;
            }
            foreach ($_POST['submitData']['id'] as $data) {
                $id[] = $data;
            }
            $j = sizeof($type);
            $flag = 0;

            for ($i = 0; $i < $j; $i++) {
                if($con->query("update workspace_log set amount = '$amount[$i]', type='$type[$i]', risk='$risk[$i]', import='$import[$i]' where program_id='$id[$i]' and workspace_id='$wid'") === TRUE)
                {
                    $flag=1;
                }
                else
                {
                    $flag=0;
                }
            }

            if($filePresent){
                // $con->query("insert into insignificant_files(fname,workspace_id,pid,status,deletedDate) values ('$name','$wid','$pid','0','')");
                $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
                if($sizeCheck->num_rows > 0){
                    $result = $sizeCheck->fetch_assoc();
                    if(($size + $result['storage_used']) < $result['storage']){
                        $updatedSize = $result['storage_used'] + $size;
                        $con->query("insert into insignificant_files(fname,workspace_id,pid) values ('$name','$wid','$pid')");
                        move_uploaded_file($tmp_name, $path . $name);
                        $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                        $flag = 1;
                    }
                    else{
                        $flag = 0;
                    }
                }
            }
        }
    }

    if ($flag || $prepareFlag || $reviewFlag) {
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
    } else {
        echo "<script>
                swal({
                    icon: 'error',
                    text: 'Insufficient Storage kindly contact your Firm Admin!',
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