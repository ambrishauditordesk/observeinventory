<?php 
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: ../index");    
}
if(isset($_POST['user_id']) || !empty($_POST['user_id'])){
    $data = [];
    $i = 0;
    $user_id = trim($_POST['user_id']);
        
    if($_SESSION['role'] != 1 && $_SESSION['role'] != -1 ){
        if($con->query("select id from firm_user_log where firm_id = ".$_SESSION['firm_id']." and user_id = ".$_SESSION['id'])->num_rows > 0){
            $result = $con->query("select chat_date, user_id_from, chat_text, status from chat_history where ( user_id_from = $user_id or user_id_from = (select id from user where accessLevel = 1)) and ( user_id_to = $user_id or user_id_to = (select id from user where accessLevel = 1))");
            while($row = $result->fetch_assoc()){
                $data[$i][0] = $row['chat_date'];
                $data[$i][1] = $con->query("select name from user where id = ".$row['user_id_from'])->fetch_assoc()['name'];
                if($row['status'] == 1){
                    $data[$i++][2] = "<img class='chatImage' src='images/".$row['chat_text']."' width='200' height='200'>";
                }
                else{
                    $data[$i++][2] = $row['chat_text'];
                }
            }
        }
    }
    else{
        $result = $con->query("select chat_date, user_id_from, chat_text, status from chat_history where ( user_id_from = $user_id or user_id_from = (select id from user where accessLevel = 1)) and ( user_id_to = $user_id or user_id_to = (select id from user where accessLevel = 1))");
        while($row = $result->fetch_assoc()){
            $data[$i][0] = $row['chat_date'];
            $data[$i][1] = $con->query("select name from user where id = ".$row['user_id_from'])->fetch_assoc()['name'];
            if($row['status'] == 1){
                $data[$i++][2] = "<img class='chatImage' src='images/".$row['chat_text']."' width='200' height='200'>";
            }
            else{
                $data[$i++][2] = $row['chat_text'];
            }
        }
    }
}
else{
    $data = [];
}
echo json_encode($data);
?>
