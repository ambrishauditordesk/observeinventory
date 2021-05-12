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

$con->query("insert into chat_history(user_id_from, user_id_to, chat_date, chat_text) values('$user_id_from','$user_id_to','$chat_date','$text')");
echo 1;
?>