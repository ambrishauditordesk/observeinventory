<!DOCTYPE html>
<html lang="en">

<head>
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

<!-- <body style="background-image: url('Icons/bgwall.jpg');"> -->
<body>
    <?php
    include 'dbconnection.php';
    include 'checkFileAllowedExt.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: login");
    }

    $flag = $flagComment = $flagFile = 0;
    $singoffText = $commentText = $fileText = '';
    $wid = $_GET['wid'];
    $prog_id = $_POST['prog_id'];
    $sign = $_SESSION['signoff'];
    $uid = $_SESSION['id'];
    $ser = $_SERVER['HTTP_REFERER'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
    $email = $_SESSION['email'];
    $pname = $con->query("select program_name from program where id = $prog_id")->fetch_assoc()['program_name'];

    if(isset($_POST['reviewSubmit']))
    {
        if($con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
        {
            $flag = 1;
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Review Sign Off done for program:- $pname ')");
        }
        $singoffText = "Review Signoff failed, kindly contact your Firm Admin!";
        if($singoffText){
            $singoffText = "Review Signoff Successfull";
        }
    }
    if(isset($_POST['prepareSubmit']))
    {
        if($con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
        {
            $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
            $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Prepare Sign Off done for program:- $pname ')");
            $flag = 1;
        }
        $singoffText = "Prepare Signoff failed, kindly contact your Firm Admin!";
        if($singoffText){
            $singoffText = "Prepare Signoff Successfull";
        }
    }
    if(isset($_POST['done']))
    {
        $allowFileUpload = 1;
        $allowFileUploadCount = 0;
        //File Upload
        if(!empty($_FILES['file']['name'][0])){
            $fileName = array();
            $totalFileSize = 0;
            for($i = 0; $i < count($_FILES['file']['name']); $i++){
                $allowFileUpload = checkFileAllowedExt($_FILES['file']['name'][$i],$_FILES['file']['tmp_name'][$i]);
                if($allowFileUpload){
                    $allowFileUploadCount++;
                    $str = explode(".", $_FILES['file']['name'][$i]);
                    $new= '';
                    for($j = 0; $j<sizeof($str)-1; $j++){
                        if($new == ''){
                            $new .= $str[$j];
                        }
                        else{
                            $new .= ".".$str[$j];
                        }
                    }
                    $fileName[$i]['name'] = trim($new." ".$date." .".end($str));
                    $fileName[$i]['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $fileName[$i]['size'] = $_FILES['file']['size'][$i];
                    $totalFileSize += ((float)$_FILES['file']['size'][$i])/1000;
                }
            }

            $totalFileCount = $i;

            if($totalFileCount == $allowFileUploadCount){
                $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
                if($sizeCheck->num_rows > 0){   
                    $result = $sizeCheck->fetch_assoc();
                    $fileText = "Insufficient Storage kindly contact your Firm Admin!";
                    if(($totalFileSize + $result['storage_used']) < $result['storage']){
                        $updatedSize = $result['storage_used'] + $totalFileSize;
                        //move
                        $path = $_SESSION['upload_file_location'];
                        for($i = 0; $i < sizeof($fileName); $i++){
                            $name = $fileName[$i]['name'];
                            $tmp_name = $fileName[$i]['tmp_name'];
                            if(move_uploaded_file($tmp_name, $path . $name)){
                                $con->query("insert into signoff_files_log(workspace_id,prog_id,user_id,file,status,deletedDate) values ('$wid','$prog_id','$uid','$name','0','$date')");
                                $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                                $flagFile = 1;
                                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','New file upload:- $name')");   
                            }
                        }
                        $fileText = "File uploading failed kindly, contact your Firm Admin!";
                        if($flagFile){
                            $fileText = "File Uploaded Succesfully";
                        }
                    }
                    else{
                        $fileText = 'Insufficient Storage kindly contact your Firm Admin!';
                    }
                }    
            } 
            else{
                $fileText = 'Either the file is not allowed or it is malicious.';
            }  
        }
        
        if(!empty(trim($_POST['newComment']))){
            $comment = $_SESSION['name'].": ".trim($_POST['newComment']);
            if($con->query("insert into signoff_comments_log(workspace_id,prog_id,user_id,comments,comments_date) values ('$wid','$prog_id','$uid','$comment','$date')") === TRUE){
                $flagComment = 1;
                $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','New Comment:- $comment')");
            }
            $commentText = "Comment addition failed kindly, contact your Firm Admin!";
            if($flagComment){
                $commentText = "Comment Added succesfully";
            }
        }

        if(isset($_POST['assertion']) && !empty($_POST['assertion'])){
            $con->query("delete from assertion where workspace_id = $wid and program_id = $prog_id");
            foreach ($_POST['assertion'] as $key => $value) {
                $con->query("insert into assertion(workspace_id, program_id, assertion_value) values('$wid','$prog_id','$value')");
            }
        }
    }

    $text = 'No Updates!';

    if($singoffText != '')
        $text = $singoffText;
    if($commentText != '')
        $text = $commentText;
    if($fileText != ''){
        if($commentText != '')
            $text .= ', '. $fileText;
        else
            $text = $fileText;
    }

    $icon = ($flag || $flagComment || $flagFile) == 0?'error':'success';
    echo "<script>
        swal({
            icon: '".$icon."',
            text: '".$text."',
            closeOnClickOutside: false,
        }).then(function(isConfirm) {
            if (isConfirm) {
                localStorage.setItem('uploaded','true');
                window.close();
            }
        });
    </script>";
?>