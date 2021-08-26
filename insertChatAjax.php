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



session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}

$user_id_to = trim($_POST['user_id']);
$user_id_from = $_SESSION['id'];
$chat_date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$text = trim($_POST['chatText']);

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
            $name = trim($new." ".$chat_date.".".end($str));
            $tmp_name = $_FILES['file']['tmp_name'];
            $path = 'images/';
            if(move_uploaded_file($tmp_name, $path . $name) == true){
                $con->query("insert into chat_history(user_id_from, user_id_to,chat_date,chat_text,status) values('$user_id_from','$user_id_to','$chat_date','$name','1')");
                echo 1;
            }
        }
        else{
            echo 0;
        }
    }
}
else{
    if($text != ''){
        $textQuery = "insert into chat_history(user_id_from, user_id_to,chat_date,chat_text,status) values('$user_id_from','$user_id_to','$chat_date','$text','0')";
        $con->query($textQuery);
    }
    echo 1; 
}
?>