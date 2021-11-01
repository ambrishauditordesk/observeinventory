<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">

    <title>Auditors Desk</title>
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
    include 'checkFileAllowedExt.php';
    if(!isset($_SESSION)){
       session_start();
    }
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    if(isset($_POST)){

        $wid = $_POST['wid'];
        $pid = $_POST['pid'];
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $ser = $_SERVER['HTTP_REFERER'];
        $errorText = 'Nothing to update.';

        $_seq = $pid === 239 ? 9 : 15;
        //File Upload
        $filePresent = $flag = 0;
        $uploadOk = 1;
        $allowFileUpload = 1;

        if(!empty($_FILES['file']['name'])){
            $filePresent = 1;
            $allowFileUpload = checkFileAllowedExt($_FILES['file']['name'],$_FILES['file']['tmp_name']);
            if($allowFileUpload){
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
            else{
                $errorText = 'Either the file is not allowed or it is malicious.';
            }
        }
        
        foreach ($_POST['submitData'] as $submitData){
            $id = $submitData[0];
            $amount = $submitData[1];
            $type = $submitData[2];
            $risk = $submitData[3];
            $map = $submitData[4];
            $import = $submitData[5];
            $fetchQuery = $con->query("select * from tb_performance_map where id=$id")->fetch_assoc();
            // echo "<pre>select * from workspace_log where workspace_id=$wid and program_id=$map</pre>";
                
            if($fetchQuery['import'] != $import){
                if($map == 0){
                    $othersImportCheck = $con->query("select * from map_log where workspace_id=$wid and program_name = '".$fetchQuery['accounts_name']."'")->fetch_assoc();
                    $con->query("update workspace_log set import = $import where id = '".$othersImportCheck['workspace_log_id']."'");
                    $con->query("update tb_performance_map set import = $import where id = $id");
                }
                else{
                    $con->query("update workspace_log set import= $import where program_id= $map and workspace_id = $wid");
                    $con->query("update tb_performance_map set import = $import where id = $id");
                }
                $flag=1;
            }
            if($fetchQuery['amount'] != $amount){
                $con->query("update tb_performance_map set amount = '$amount' where id= $id");
                $flag=1;
            }
            if($fetchQuery['type'] != $type){
                $con->query("update tb_performance_map set type = $type where id= $id");
                $flag=1;
            }
            if($fetchQuery['risk'] != $risk){
                $con->query("update tb_performance_map set risk = $risk where id= $id");
                $flag=1;
            }
            if($fetchQuery['mapped_program_id'] != $map){
                if($fetchQuery['mapped_program_id'] == 0 && $map != 0 && $map != -1){
                    $oldMapCheck = $con->query("select * from map_log where workspace_id = $wid and program_name = '".$fetchQuery['accounts_name']."'")->fetch_assoc();
                    $con->query("delete from workspace_log where id = '".$oldMapCheck['workspace_log_id']."'");
                    $con->query("delete from program where id = '".$oldMapCheck['program_id']."'");
                    $con->query("delete from map_log where id = '".$oldMapCheck['id']."'");
                    if($import != $con->query("select import from workspace_log where program_id = $map and workspace_id = $wid")->fetch_assoc()['import']){
                        $con->query("update workspace_log set import = $import where program_id = $map and workspace_id = $wid");
                    }
                }
                if($fetchQuery['mapped_program_id'] != 0 && $map != 0 && $map != -1){
                    if($import != $con->query("select import from workspace_log where program_id = $map and workspace_id = $wid")->fetch_assoc()['import']){
                        $con->query("update workspace_log set import = $import where program_id = $map and workspace_id = $wid");
                    }
                    $con->query("update workspace_log set import = 1 where program_id = '".$fetchQuery['mapped_program_id']."' and workspace_id = $wid");
                }
                $con->query("update tb_performance_map set mapped_program_id = $map where id= $id");
                if($map == 0){
                    if($fetchQuery['mapped_program_id'] != 0){
                        $con->query("update workspace_log set import = 1 where program_id = '".$fetchQuery['mapped_program_id']."' and workspace_id = $wid");
                    }
                    $checkProgram = $con->query("select id from program where parent_id=2 and def_prog=0 and program_name='".$fetchQuery['accounts_name']."'");
                    if($checkProgram->num_rows < 1){
                        $con->query("insert into program (parent_id,program_name,hasChild,def_prog,_seq,firmPlan) values('2','".$fetchQuery['accounts_name']."','1','0','$_seq','0')");
                        $prog_id = $con->insert_id;
                        $con->query("insert into workspace_log(workspace_id,program_id,import) values('$wid','$prog_id','$import')");
                        $wlid = $con->insert_id;
                        $con->query("insert into map_log(workspace_id,workspace_log_id,program_id,program_name) values('$wid','$wlid','$prog_id','".$fetchQuery['accounts_name']."')");
                    }
                }
                $flag=1;
            }
        }

        if($filePresent && $allowFileUpload){
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
                    $errorText .= 'Insufficient Storage kindly contact your Firm Admin!';
                }
            }
        }

        if ($flag) {
            echo "<script>
                    swal({
                        icon: 'success',
                        text: 'Updated!',
                        closeOnClickOutside: false,
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
                        text: '".$errorText."',
                        closeOnClickOutside: false,
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