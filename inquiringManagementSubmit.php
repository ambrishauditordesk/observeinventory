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
    $ser = $_SERVER['HTTP_REFERER'];
    $flag = 0;

    //File Upload
    $filePresent = 0;
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
            $name = trim($new." ".$date." .".end($str));
            $tmp_name = $_FILES['file']['tmp_name'];
            $path = $_SESSION['upload_file_location'];
        }
    }

    if(isset($_POST))
    {
        include 'dbconnection.php';
        session_start();

        $answerArray = array();

        $wid = trim($_POST['wid']);
        $textareaResult = trim($_POST['textarea']);
        $answerArray = $_POST['answer'];
        
        for($i = 0; $i < sizeof($answerArray); $i++){
            $id = $answerArray[$i][0];
            $option = $answerArray[$i][1];
            $textarea = $answerArray[$i][2];
            $con->query("UPDATE inquiring_of_management_questions_answer SET answer_option = '$option', answer_textarea = '$textarea' where id = '$id' and workspace_id = '$wid'");
            $flag = 1;
        }
        $con->query("INSERT INTO inquiring_of_management_questions_textarea(workspace_id,textarea) VALUES('$wid','$textareaResult')");
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Inquiring Managements New entery done')");
        // $con->query("update workspace_log set status='1' where program_id='258' and workspace_id='$wid'");
    }
           
    if($filePresent){
        $con->query("insert into inquiring_of_management_files(wid,files) values('$wid','$name')");
        move_uploaded_file($tmp_name, $path . $name);
        $flag = 1;
    } 

    if($flag){
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