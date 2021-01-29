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
    $description = $client = $request = $date = $id = $fn = array();
    $wid = $_GET['wid'];
    $ser = $_SERVER['HTTP_REFERER'];
    $success = 0;
    $error = '';
    // foreach ($_FILES['file'] as $data) {
    //     $fn[] = $data;
    // }
    // var_dump($fn);
    // return;
    //File Upload
    $filePresent = 0;
    $uploadOk = 1;
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
                    // File size should be less the 2MB
                    if ($_FILES['file']['size'][$id[$i]][$x] > 2000000) {
                        $error .= "<p>File Size is greater than 2MB.</p><br>";
                        $uploadOk = 0;
                    }
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
                    $name = trim($new." ".$date." .".end($str));
                    // $name = explode('.',$_FILES['file']['name'][$id[$i]][$x])[0]." ".date('Y-m-d H:i:s').".".explode('.',$_FILES['file']['name'][$id[$i]][$x])[1];
                    $tmp_name = $_FILES['file']['tmp_name'][$id[$i]][$x];
                    $path = 'uploads/clientrequest/';
                    if($uploadOk){
                        if(move_uploaded_file($tmp_name, $path.$name)){
                            $con->query("INSERT INTO accounts_log_docs(accounts_log_id, documents) VALUES ('$id[$i]','$name')");
                            $success = 1;
                        }
                    }
                }
            }
            if(isset($_SESSION['external']) && $_SESSION['external'] != 1){
                $success = 1;
                $con->query("update accounts_log set description = '$description[$i]', client_contact_id='$client[$i]', request='$request[$i]',date='$date[$i]' where id = '$id[$i]'");
            }
        }
        if($success && $error == ''){
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
                        text: '".$error."',
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