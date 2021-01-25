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
        header("Location: login");
    }

    $flag = $flagComment = $flagFile = 0;
    $wid = $_GET['wid'];
    $prog_id = $_POST['prog_id'];
    $sign = $_SESSION['signoff'];
    $uid = $_SESSION['id'];
    $ser = $_SERVER['HTTP_REFERER'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");

    if(isset($_POST['reviewSubmit']))
    {
        if($con->query("insert into signoff_review_log(workspace_id,prog_id,user_id,review_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
        {
            $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
            $flag = 1;
        }
    }
    if(isset($_POST['prepareSubmit']))
    {
        if($con->query("insert into signoff_prepare_log(workspace_id,prog_id,user_id,prepare_signoff_date) values ('$wid','$prog_id','$uid','$date')") === TRUE)
        {
            $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
            $flag = 1;
        }
    }
    if(isset($_POST['done']))
    {
        //File Upload
        if(!empty($_FILES['file']['name'][0])){
            $fileName = array();
            for($i = 0; $i < count($_FILES['file']['name']); $i++){
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
            }
            //move
            $path = './uploads/program_files/';
            for($i = 0; $i < sizeof($fileName); $i++){
                $name = $fileName[$i]['name'];
                $tmp_name = $fileName[$i]['tmp_name'];
                if(move_uploaded_file($tmp_name, $path . $name))
                    if($con->query("insert into signoff_files_log(workspace_id,prog_id,user_id,file) values ('$wid','$prog_id','$uid','$name')") === TRUE)
                        $flagFile = 1;
            }
        }
        if(!empty(trim($_POST['newComment']))){
            $comment = $_SESSION['name'].": ".trim($_POST['newComment']);
            if($con->query("insert into signoff_comments_log(workspace_id,prog_id,user_id,comments,comments_date) values ('$wid','$prog_id','$uid','$comment','$date')") === TRUE)
                $flagComment = 1;
        }
    }
    $icon = ($flag || $flagComment || $flagFile) == 0?'error':'success';
    $text = $icon == 'error'?'Error':'Updated!';
    echo "<script>
        swal({
            icon: '".$icon."',
            text: '".$text."',
        }).then(function(isConfirm) {
            if (isConfirm) {
                window.location.href = '$ser';
            }
        });
    </script>";
?>