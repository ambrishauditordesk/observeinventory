<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}

$user_id_to = trim($_POST['user_id']);
$user_id_from = $_SESSION['id'];
$chat_date = date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "d-m-Y H:m:s");
$text = trim($_POST['text']);

// if(!empty($_FILES['file']['name'][0])){
//     $fileName = array();
//     for($i = 0; $i < count($_FILES['file']['name']); $i++){
//         $str = explode(".", $_FILES['file']['name'][$i]);
//         $new= '';
//         for($j = 0; $j<sizeof($str)-1; $j++){
//             if($new == ''){
//                 $new .= $str[$j];
//             }
//             else{
//                 $new .= ".".$str[$j];
//             }
//         }
//         $fileName[$i]['name'] = trim($new." ".$date." .".end($str));
//         $fileName[$i]['tmp_name'] = $_FILES['file']['tmp_name'][$i];
//     }
//     //move
// //     $path = $_SESSION['upload_file_location'];
// //     for($i = 0; $i < sizeof($fileName); $i++){
// //         $name = $fileName[$i]['name'];
// //         $tmp_name = $fileName[$i]['tmp_name'];
// //         if(move_uploaded_file($tmp_name, $path . $name))
// //             if($con->query("insert into signoff_files_log(workspace_id,prog_id,user_id,file) values ('$wid','$prog_id','$uid','$name')") === TRUE){
// //                 $flagFile = 1;
// //                 $con->query("insert into activity_log(workspace_id, email, activity_date_time, activity_captured) values('$wid', '$email','$date','New file upload:- $name')");
// //             }
// //     }
// // }

$con->query("insert into chat_history(user_id_from, user_id_to, chat_date, chat_text) values('$user_id_from','$user_id_to','$chat_date','$text')");
echo 1;
?>