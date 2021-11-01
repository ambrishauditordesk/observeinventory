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
    $description = $client = $request = $date = $id = $fn = array();
    $fileDate = date("now");
    $wid = $_GET['wid'];
    $email = $_SESSION['email'];
    $ser = $_SERVER['HTTP_REFERER'];
    $success = 0;
    // foreach ($_FILES['file'] as $data) {
    //     $fn[] = $data;
    // }
    // var_dump($fn);
    // return;
    //File Upload
    $filePresent = 0;
    $uploadOk = 1;
    $allowFileUpload = 1;

    if(isset($_POST)) {
        if(isset($_POST['account']['des']))
            foreach ($_POST['account']['des'] as $data) {
                $description[] = $data;
            }
        if(isset($_POST['account']['client']))
            foreach ($_POST['account']['client'] as $data) {
                $client[] = $data;
            }    
        if(isset($_POST['account']['request']))
            foreach ($_POST['account']['request'] as $data) {
                $request[] = $data;
            }
        if(isset($_POST['account']['date']))
            foreach ($_POST['account']['date'] as $data) {
                $date[] = $data;
            }  
        foreach ($_POST['account']['id'] as $data) {
            $id[] = $data;
        }
        $accounts_log_id_list = [];
        if(!empty($_FILES['file']['name'])){
            foreach ($_FILES['file']['name'] as $key => $value) {
                // var_dump($value);
                foreach($value as $accounts_log_id => $document_name){
                    $accounts_log_id_list[] = $accounts_log_id;
                }
            }
        }

        for ($i = 0; $i < sizeof($id); $i++) 
        {
            if(!empty($_FILES['file']['name'][$id[$i]])){
                for($x = 0; $x < sizeof($_FILES['file']['name'][$id[$i]]); $x++){
                    $allowFileUpload = checkFileAllowedExt($_FILES['file']['name'][$id[$i]][$x],$_FILES['file']['tmp_name'][$id[$i]][$x]);
                    if($allowFileUpload){
                        $str = explode('.',$_FILES['file']['name'][$id[$i]][$x]);
                        $new= '';
                        for($j = 0; $j<sizeof($str)-1; $j++){
                            if($new == ''){
                                $new .= $str[$j];
                            }
                            else{
                                $new .= ".".$str[$j];
                            }
                        }
                        $name = trim($new." ".$fileDate." .".end($str));
                        // $name = explode('.',$_FILES['file']['name'][$id[$i]][$x])[0]." ".date('Y-m-d H:i:s').".".explode('.',$_FILES['file']['name'][$id[$i]][$x])[1];
                        $tmp_name = $_FILES['file']['tmp_name'][$id[$i]][$x];
                        $uploadLocation = $con->query("SELECT CONCAT(firm_details.id, '/', client.id, client.name, '/',workspace.id) uploadLocation from workspace inner join client on workspace.client_id = client.id inner join user_client_log on client.id = user_client_log.client_id inner join firm_user_log on user_client_log.user_id = firm_user_log.user_id inner join firm_details on firm_user_log.firm_id = firm_details.id where workspace.id = $wid group by firm_details.id")->fetch_assoc()['uploadLocation'];
                        $uploadLocation = explode(' ',$uploadLocation);
                        $path = 'uploads/';
                        for($s=0;$s<sizeof($uploadLocation);$s++){
                            $path .= $uploadLocation[$s];
                        }
                        $path .= '/';
                        $size = (float)($_FILES['file']['size'][$id[$i]][$x])/1000;
                        
                        if($uploadOk){
                            $sizeCheck = $con->query("select storage,storage_used from firm_details where id=".$_SESSION['firm_id']);
                            if($sizeCheck->num_rows > 0){   
                                $result = $sizeCheck->fetch_assoc();
                                if(($size + $result['storage_used']) < $result['storage']){
                                    $updatedSize = $result['storage_used'] + $size;
                                    $con->query("INSERT INTO accounts_log_docs(accounts_log_id, documents) VALUES ('$id[$i]','$name')");
                                    move_uploaded_file($tmp_name, $path.$name);
                                    $con->query("update firm_details set storage_used = $updatedSize where id = ".$_SESSION['firm_id']);
                                    $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$fileDate','File uploaded $name')");
                                    $success = 1;
                                } 
                                else{
                                    $errorText = 'Insufficient Storage kindly contact your Firm Admin!';
                                }
                            }
                        }
                    }
                    else{
                        $errorText = 'Either the file is not allowed or it is malicious.';
                    }
                }
            }
            if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                $check = $con->query("select * from accounts_log where description = '$description[$i]' and client_contact_id='$client[$i]' and request='$request[$i]' and date='$date[$i]' and id = '$id[$i]'");
                if($check->num_rows == 0){
                    $con->query("update accounts_log set description = '$description[$i]', client_contact_id='$client[$i]', request='$request[$i]',date='$date[$i]' where id = '$id[$i]'");
                    $success = 1;
                }
                else{
                    $errorText = 'Nothing to Update!';
                }
            }
        }
        if($success){
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
    }
    
?>
</body>
</html>