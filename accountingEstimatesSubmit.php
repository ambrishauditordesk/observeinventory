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
    $type = $nameEstimate = $account = $py = $cy = $c = $eo = $mv = $ro = $pd = $risk = array();
    $comments = trim($_POST['comments']);
    $wid = $_GET['wid'];
    $ser = $_SERVER['HTTP_REFERER'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    
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
            $name = trim($new." ".$date." .".end($str));
            // $tmp_name = $fileName['file']['tmp_name'];
            // $name = explode(".", $_FILES['file']['name'])[0]."_$submat_id.".explode(".", $_FILES['file']['name'])[1];
            $tmp_name = $_FILES['file']['tmp_name'];
            $path = $_SESSION['upload_file_location'];
            $size = ($_FILES['file']['size']/1000);
        }
    }

    if(isset($_POST)) {
        $con->query("DELETE from accounting_estimates where workspace_id = $wid");
        $con->query("DELETE from accounting_estimates_comments where workspace_id = $wid");

        if(isset($_POST['submitEstimate']['type']))
            foreach ($_POST['submitEstimate']['type'] as $data) {
                $type[] = $data;
            }
        if(isset($_POST['submitEstimate']['name']))
            foreach ($_POST['submitEstimate']['name'] as $data) {
                $nameEstimate[] = $data;
            }    
        if(isset($_POST['submitEstimate']['account']))
            foreach ($_POST['submitEstimate']['account'] as $data) {
                $account[] = $data;
            }
        if(isset($_POST['submitEstimate']['py']))
            foreach ($_POST['submitEstimate']['py'] as $data) {
                $data = (float) filter_var( $data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                $py[] = $data;
            }  
        if(isset($_POST['submitEstimate']['cy']))
            foreach ($_POST['submitEstimate']['cy'] as $data) {
                $data = (float) filter_var( $data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                $cy[] = $data;
            } 
        if(isset($_POST['submitEstimate']['c']))
            foreach ($_POST['submitEstimate']['c'] as $data) {
                $c[] = $data;
            } 
        if(isset($_POST['submitEstimate']['eo']))
            foreach ($_POST['submitEstimate']['eo'] as $data) {
                $eo[] = $data;
            } 
        if(isset($_POST['submitEstimate']['mv']))
            foreach ($_POST['submitEstimate']['mv'] as $data) {
                $mv[] = $data;
            } 
        if(isset($_POST['submitEstimate']['ro']))
            foreach ($_POST['submitEstimate']['ro'] as $data) {
                $ro[] = $data;
            } 
        if(isset($_POST['submitEstimate']['pd']))
            foreach ($_POST['submitEstimate']['pd'] as $data) {
                $pd[] = $data;
            } 
        if(isset($_POST['submitEstimate']['risk']))
            foreach ($_POST['submitEstimate']['risk'] as $data) {
                $risk[] = $data;
            } 
        $j = sizeof($account);
        $flag = 0;
        
        for ($i = 0; $i < $j; $i++) 
        {
            
            if($con->query("INSERT INTO accounting_estimates(workspace_id,type, nameEstimate, account, py, cy, c, eo, mv, ro, pd, risk) VALUES ('$wid','$type[$i]','$nameEstimate[$i]','$account[$i]','$py[$i]','$cy[$i]','$c[$i]','$eo[$i]','$mv[$i]','$ro[$i]','$pd[$i]','$risk[$i]')") == TRUE)
            {
                $flag = 1;
            }
        } 
        if($filePresent){
            // $con->query("insert into accounting_estimates_files(workspace_id,file_name,status,deletedDate) values('$wid','$name','0','')");
            $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
            if($sizeCheck->num_rows > 0){
                $result = $sizeCheck->fetch_assoc();
                if(($size + $result['storage_used']) < $result['storage']){
                    $updatedSize = $result['storage_used'] + $size;
                    $con->query("insert into accounting_estimates_files(workspace_id,file_name) values('$wid','$name')");
                    move_uploaded_file($tmp_name, $path . $name);
                    $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                    $flag = 1;
                }
                else{
                    $flag = 0;
                }
            }
        } 
        $con->query("insert into accounting_estimates_comments(workspace_id,comments) values('$wid','$comments')");
    }
        if($uploadOk && $flag){
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