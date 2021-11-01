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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>
<body>
<?php
include 'dbconnection.php';


function checkFileAllowedExt($filename){
    $allowed = array('jpeg', 'jpg', 'png', 'pdf');
    $data = 1;
    if(sizeof(explode('.', $filename)) != 2){
        $data = 0;
    }
    else{
        if (!in_array(explode('.', $filename)[1], $allowed)) {
            $data = 0;
        }
    }
    return $data;
}



$uid = $_POST['uid'];
$date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$flag = 0;
$ser = $_SERVER['HTTP_REFERER'];
if(!empty($_FILES['image']['name'])){
    $filePresent = 1;
    if(!empty($_FILES['image']['name'][0])){
        $allowFileUpload = checkFileAllowedExt($_FILES['image']['name'],$_FILES['image']['tmp_name']);
        if($allowFileUpload){
            $fileName = array();
            $str = explode(".", $_FILES['image']['name']);
            $new= '';
            for($j = 0; $j<sizeof($str)-1; $j++){
                if($new == ''){
                    $new .= $str[$j];
                }
                else{
                    $new .= ".".$str[$j];
                }
            }
            $name = trim($new." ".$date.".".end($str));;
            // $tmp_name = $fileName['file']['tmp_name'];
            // $name = explode(".", $_FILES['file']['name'])[0]."_$submat_id.".explode(".", $_FILES['file']['name'])[1];
            $tmp_name = $_FILES['image']['tmp_name'];
            $path = 'images/';
            if(move_uploaded_file($tmp_name, $path . $name) == true){
                $con->query("update user set img='$name' where id=$uid");
                $flag = 1;
            }
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
            text: 'File format not allowed.',
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