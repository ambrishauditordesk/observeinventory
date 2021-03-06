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
    if(!isset($_SESSION)){
       session_start();
    }
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }

    $flag = 0;
    $responseText = 'Nothing to Updated';
    $responseFile = '';
    if($_POST['status'] == 0){
        $name = trim($_POST['name']);
        $part_name = trim($_POST['part_name']);
        $wid = trim($_POST['wid']);
        $con->query("INSERT INTO going_concern_procedures(workspace_id, procedure_data, free_text, part) VALUES('$wid','$name','','$part_name')");
        $flag = 1;
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        if(isset($_SESSION['email']) && !empty($_SESSION['email']))
            $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Going Concen New Entry done.')");
        $responseText = 'Updated';
    }
    if($_POST['status'] == 1){
        $flag = 1;
        $prog_id = trim($_POST['pid']);
        $wid = trim($_POST['wid']);
        $conclusion = '';

        if(isset($_POST['conclusion']) && !empty($_POST['conclusion']))
            $conclusion = trim($_POST['conclusion']);
        
        $descA = trim($_POST['desc_a']);
        $descB = trim($_POST['desc_b']);
        $descC = trim($_POST['desc_c']);

        $con->query("DELETE FROM going_concern_name_title_date WHERE workspace_id = $wid");

        // Inserting into Part A Going Concern
        for($i = 0; $i<sizeof($_POST["going_concern_name_title_date_a"]); $i++){
            $name = trim($_POST["going_concern_name_title_date_a"][$i][0]);
            $title = trim($_POST["going_concern_name_title_date_a"][$i][1]);
            $date = trim($_POST["going_concern_name_title_date_a"][$i][2]);
            $con->query("INSERT INTO going_concern_name_title_date(workspace_id, name, title, date, part) VALUES('$wid','$name','$title','$date','A')");
            $responseText = 'Updated';
        }

        // Inserting into Part B Going Concern
        for($i = 0; $i<sizeof($_POST["going_concern_name_title_date_b"]); $i++){
            $name = trim($_POST["going_concern_name_title_date_b"][$i][0]);
            $title = trim($_POST["going_concern_name_title_date_b"][$i][1]);
            $date = trim($_POST["going_concern_name_title_date_b"][$i][2]);
            $con->query("INSERT INTO going_concern_name_title_date(workspace_id, name, title, date, part) VALUES('$wid','$name','$title','$date','B')");
            $responseText = 'Updated';
        }
        
        $conclusionTextResult = $con->query("select conclusion_text from going_concern where workspace_id = $wid");
        $conclusionText = "We did not give consideration to modification of our auditor???s report";
        if($conclusionTextResult->num_rows){
            $conclusionText = $conclusionTextResult->fetch_assoc()['conclusion_text'];
        }

        $con->query("DELETE FROM going_concern where workspace_id = $wid");
        if($conclusion != ''){  
            $con->query("INSERT INTO going_concern(workspace_id, going_concern_radio, desc_a, desc_b, desc_c, conclusion_text) VALUES('$wid','$conclusion','$descA','$descB','$descC','$conclusionText')");
        }

        if(isset($_POST['freeTextA']) && !empty($_POST['freeTextA'])){

            for($i = 0; $i<sizeof($_POST['freeTextA']); $i++){
                $id = trim($_POST['freeTextA'][$i][0]);
                $text = trim($_POST['freeTextA'][$i][1]);
                $con->query("UPDATE going_concern_procedures SET free_text = '$text' where id = $id");
            }
        }   

        if(isset($_POST['freeTextB']) && !empty($_POST['freeTextB'])){
            for($i = 0; $i<sizeof($_POST['freeTextB']); $i++){
                $id = trim($_POST['freeTextB'][$i][0]);
                $text = trim($_POST['freeTextB'][$i][1]);
                $con->query("UPDATE going_concern_procedures SET free_text = '$text' where id = $id");
            }
        }
        // $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
        //File Upload
        $filePresent = 0;
        
        //var_dump($_FILES);
        if(!empty($_FILES['file']['name'])){
            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
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

                $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
                if($sizeCheck->num_rows > 0){   
                    $result = $sizeCheck->fetch_assoc();
                    if(($size + $result['storage_used']) < $result['storage']){
                        $updatedSize = $result['storage_used'] + $size;
                        $con->query("insert into going_concern_files(workspace_id,fname) values ('$wid', '$name')");
                        move_uploaded_file($tmp_name, $path . $name);
                        $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                        $flag =1;
                        $responseText = 'Updated';
                    } 
                    else{
                        $flag = 0;
                        $responseFile = 'Insufficient Storage kindly contact your Firm Admin!';
                    }
                }
            }
        }

        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Going Concen updated.')");
    }
    $ser = $_SERVER['HTTP_REFERER'];
    if($flag){
        echo "<script>
            swal({
                icon: 'success',
                text: '".$responseText."',
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
                text: '".$responseText."',
                closeOnClickOutside: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href = '$ser';
                }
            });
        </script>";
    }
?>