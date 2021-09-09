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
<body>
<?php
    include 'dbconnection.php';
    include 'checkFileAllowedExt.php';
    session_start();   
     
    $ser = $_SERVER['HTTP_REFERER'];
    $flag = 0;
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");

    //File Upload
    $filePresent = 0;
    $allowFileUpload = 0;
    if(!empty($_FILES['file']['name'])){
        $filePresent = 1;
        if(!empty($_FILES['file']['name'][0])){
            $allowFileUpload = checkFileAllowedExt($_FILES['file']['name'][0],$_FILES['file']['tmp_name'][0]);
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
                $tmp_name = $_FILES['file']['tmp_name'];
                $path = $_SESSION['upload_file_location'];
                $size = ($_FILES['file']['size']/1000);
            }
            else{
                $errorText = 'Either the file is not allowed or it is malicious.';
            }
        }
    }
    
    if(isset($_POST))
    {
        $answerArray = array();

        $wid = trim($_POST['wid']);
        $textareaResult = trim($_POST['textarea']);
        $answerArray = $_POST['answer'];
        
        for($i = 0; $i < sizeof($answerArray); $i++){
            $id = $answerArray[$i][0];
            $option = $answerArray[$i][1];
            $textarea = $answerArray[$i][2];
            $checkQuestionAnswer = $con->query("select * from inquiring_of_management_questions_answer where workspace_id = '$wid' and answer_option = '$option' and answer_textarea = '$textarea'")->num_rows;
            if($checkQuestionAnswer == 0){
                $con->query("UPDATE inquiring_of_management_questions_answer SET answer_option = '$option', answer_textarea = '$textarea' where id = '$id' and workspace_id = '$wid'");
                $flag = 1;
            }
            else{
                $errorText = 'Nothing to Update';
            }
        }
        $totalCount = $con->query("SELECT id from inquiring_of_management_questions_textarea where workspace_id = $wid");
        if($totalCount->num_rows == 0){
            $con->query("INSERT INTO inquiring_of_management_questions_textarea(workspace_id,textarea) VALUES('$wid','$textareaResult')");
            $flag = 1;
        }
        else{
            $checkTextarea  = $con->query("select * from inquiring_of_management_questions_textarea where workspace_id = '$wid' and textarea = '$textareaResult'")->num_rows;
            if($checkTextarea == 0){
                $con->query("UPDATE inquiring_of_management_questions_textarea set textarea = '$textareaResult' where workspace_id = $wid");
                $flag = 1;
            }
            else{
                $errorText = 'Nothing to Update';
            }
        }        
        $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Inquiring Managements New entery done')");
        // $con->query("update workspace_log set status='1' where program_id='258' and workspace_id='$wid'");
    }
        
    if($filePresent && $allowFileUpload){
        $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
        if($sizeCheck->num_rows > 0){   
            $result = $sizeCheck->fetch_assoc();
            if(($size + $result['storage_used']) < $result['storage']){
                $updatedSize = $result['storage_used'] + $size;
                $con->query("insert into inquiring_of_management_files(wid,files) values('$wid','$name')");
                move_uploaded_file($tmp_name, $path . $name);
                $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                $flag =1;
            } 
            else{
                $flag = 0;
                $errorText = 'Insufficient Storage kindly contact your Firm Admin';
            }
        }
    } 

    if($flag){
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
    }
    else{
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
?>
</body>
</html>