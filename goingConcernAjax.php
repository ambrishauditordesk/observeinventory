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
    $flag = 0;
    if($_POST['status'] == 0){
        $name = trim($_POST['name']);
        $part_name = trim($_POST['part_name']);
        $wid = trim($_POST['wid']);
        $con->query("INSERT INTO going_concern_procedures(workspace_id, procedure_data, free_text, part) VALUES('$wid','$name','','$part_name')");
        $flag = 1;
        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Going Concen New Entry done.')");
    }
    if($_POST['status'] == 1){

        $uploadOk = 1;

        $prog_id = trim($_POST['pid']);
        $wid = trim($_POST['wid']);

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
        }

        // Inserting into Part B Going Concern
        for($i = 0; $i<sizeof($_POST["going_concern_name_title_date_b"]); $i++){
            $name = trim($_POST["going_concern_name_title_date_b"][$i][0]);
            $title = trim($_POST["going_concern_name_title_date_b"][$i][1]);
            $date = trim($_POST["going_concern_name_title_date_b"][$i][2]);
            $con->query("INSERT INTO going_concern_name_title_date(workspace_id, name, title, date, part) VALUES('$wid','$name','$title','$date','B')");
        }
        
        $conclusionTextResult = $con->query("select conclusion_text from going_concern where id = $id");
        if($conclusionTextResult->num_rows){
            $conclusionText = $conclusionTextResult->fetch_assoc()['conclusion_text'];
        }
        else{
            $conclusionText = "We did not give consideration to modification of our auditor’s report";
        }

        $con->query("DELETE FROM going_concern where workspace_id = $wid");
        $con->query("INSERT INTO going_concern(workspace_id, going_concern_radio, desc_a, desc_b, desc_c, conclusion_text) VALUES('$wid','$conclusion','$descA','$descB','$descC','$conclusionText')");

        for($i = 0; $i<sizeof($_POST['freeTextA']); $i++){
            $id = trim($_POST['freeTextA'][$i][0]);
            $text = trim($_POST['freeTextA'][$i][1]);
            $con->query("UPDATE going_concern_procedures SET free_text = '$text' where id = $id");
        }

        for($i = 0; $i<sizeof($_POST['freeTextB']); $i++){
            $id = trim($_POST['freeTextB'][$i][0]);
            $text = trim($_POST['freeTextB'][$i][1]);
            $con->query("UPDATE going_concern_procedures SET free_text = '$text' where id = $id");
        }
        // $con->query("update workspace_log set status='1' where program_id='$prog_id' and workspace_id='$wid'");
        //File Upload
        $filePresent = 0;
        
        //var_dump($_FILES);
        if(!empty($_FILES['file']['name'])){
            $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
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
                $name = trim($new." ".$date." .".end($str));;
                // $tmp_name = $fileName['file']['tmp_name'];
                // $name = explode(".", $_FILES['file']['name'])[0]."_$submat_id.".explode(".", $_FILES['file']['name'])[1];
                $tmp_name = $_FILES['file']['tmp_name'];
                $path = $_SESSION['upload_file_location'];
                if(move_uploaded_file($tmp_name, $path . $name)){
                    // File write permission is not given in the server.
                    $con->query("insert into going_concern_files(workspace_id,fname) values ('$wid', '$name')");
                }
                else{
                    $uploadOk = 0;
                }
            }
        }
        $flag = $uploadOk == 1 ? 1 : 0;

        $date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
        $email = $_SESSION['email'];
        $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','Going Concen updated.')");
    }
    $ser = $_SERVER['HTTP_REFERER'];
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