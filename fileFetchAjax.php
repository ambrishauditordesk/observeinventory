<?php

include 'dbconnection.php';
session_start();

if (!isset($_SESSION['email']) && empty($_SESSION['email'])){
    header("Location: admin/clientList");    
}

$filename = trim($_POST['file']);

$data['status'] = false;
$data['file_location'] = '';
$folderLocation = 'view/'.$_SESSION['tempFolderName'].'/'.$filename;
$fileFetchFrom = $_SESSION['upload_file_location'].$filename;

if($_SESSION['role'] == -1 || $_SESSION['role'] == 1){
    $data['status'] = true;
    $data['file_location'] = $_SESSION['upload_file_location'].$filename;
}
else{
    if($con->query("select id from user_client_log where user_id = '".$_SESSION['id']."' and client_id = '".$_SESSION['client_id']."'")->num_rows){
        $data['status'] = true;
        $data['file_location'] = $folderLocation;
        
        if(!file_exists($folderLocation)){
            if(!copy($fileFetchFrom,$folderLocation)){
                $data['status'] = false;
            }
        }
    }
}
echo json_encode($data);

?>