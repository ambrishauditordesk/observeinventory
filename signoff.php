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

    $comment='';
    if(!empty($_POST['newComment']))
        $comment = $_SESSION['name'].": ".trim($_POST['newComment']);
    $wid = $_GET['wid'];
    $prog_id = $_POST['prog_id'];
    $sign = $_SESSION['signoff'];
    $uid = $_SESSION['id'];
    $ser = $_SERVER['HTTP_REFERER'];
    $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y");

    if(isset($_POST['reviewSubmit']))
    {
        if($con->query("update signoff_log set Review_SignOff='$sign', review_date='$date' where prog_id='$prog_id' and workspace_id='$wid'") === TRUE)
        {
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
        else
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
    elseif(isset($_POST['prepareSubmit']))
    {
        if($con->query("update signoff_log set Prepare_SignOff='$sign', prepare_date='$date' where prog_id='$prog_id' and workspace_id='$wid'") === TRUE)
        {
            $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
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
        else
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
    elseif(isset($_POST['done']))
    {
        if(empty($_POST['id']))
        {
            $flag = 1;
            //File Upload
            $fileName = array();
            
             if(!empty($_FILES['file']['name'][0])){
                 for($i = 0; $i < count($_FILES['file']['name']); $i++){
                    // File size should be less the 2MB
                    // if ($_FILES["file"]["size"] > 2000000) {
                    //     $error.= "<p>File Size is greater than 2MB.</p><br>";
                    //     $uploadOk = 0;
                    // }
                    $name = explode(".", $_FILES['file']['name'][$i])[0]."_$prog_id.$uid.$wid.".explode(".", $_FILES['file']['name'][$i])[1];
                    // $tmp_name = $_FILES['file']['tmp_name'];
                    $fileName[$i]['name'] = $name;
                    $fileName[$i]['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                 }
                 //move
                 $path = './uploads/program_files/';
             }
             if(!empty($_POST['newComment'])){
                $con->query("insert into signoff_log(workspace_id,prog_id,comment,sign,user_id) values ('$wid','$prog_id','$comment','$sign','$uid')");
             }
             if(empty($_POST['newComment'])){
                $con->query("insert into signoff_log(workspace_id,prog_id,sign,user_id) values ('$wid','$prog_id','$sign','$uid')");
             }
             
            //echo "insert into signoff_log(workspace_id,prog_id,comment,sign,user_id) values ('$wid','$prog_id','$comment','$sign','$uid')";
                $nextid = $con->insert_id;
                for($i = 0; $i < sizeof($fileName); $i++){
                    $name = $fileName[$i]['name'];
                    $tmp_name = $fileName[$i]['tmp_name'];
                    if($con->query("insert into signoff_files(signoff_id,file) values ('$nextid','$name')") === TRUE)
                    {
                        if(!move_uploaded_file($tmp_name, $path . $name)){
                            $flag = 0;
                        }
                    }
                }

            if($flag)
            {
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
            else
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
        else
        {
            $commentOld = $con->query("select comment from signoff_log where prog_id='$prog_id' and workspace_id='$wid'")->fetch_assoc()['comment'];
            if(!empty(trim($commentOld)))
                $newComment = trim($commentOld)."\n".$comment;
            else
                $newComment = $comment;
            // echo $comment;
            $flag = 1;
            $pid = $_POST['id'];

            if(!empty($_POST['newComment']))
            {
                $con->query("update signoff_log set comment='$newComment' where prog_id='$prog_id' and workspace_id='$wid'");
            }
            //File Upload
            $fileName = array();
            if(!empty($_FILES['file']['name'][0]))
            {
                for($i = 0; $i < count($_FILES['file']['name']); $i++){
                    // File size should be less the 2MB
                    // if ($_FILES["file"]["size"] > 2000000) {
                    //     $error.= "<p>File Size is greater than 2MB.</p><br>";
                    //     $uploadOk = 0;
                    // }
                    $name = explode(".", $_FILES['file']['name'][$i])[0]."_$prog_id.$uid.$wid.".explode(".", $_FILES['file']['name'][$i])[1];
                    // $tmp_name = $_FILES['file']['tmp_name'];
                    $fileName[$i]['name'] = $name;
                    $fileName[$i]['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                }
                //move
                $path = './uploads/program_files/';
                for($i = 0; $i < sizeof($fileName); $i++){
                    $name = $fileName[$i]['name'];
                    $tmp_name = $fileName[$i]['tmp_name'];
                    if($con->query("select file from signoff_files where file = '$name'")->num_rows == 0){
                        if($con->query("insert into signoff_files(signoff_id,file) values ('$pid','$name')") === TRUE)
                        {
                            if(!move_uploaded_file($tmp_name, $path . $name)){
                                $flag = 0;
                            }
                        }
                    }
                }
            }
            if($flag)
            {
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
            else
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
    }
?>