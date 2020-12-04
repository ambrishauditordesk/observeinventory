<?php
include '../dbconnection.php';
session_start();

// Getting the Logged In User ID
$name = trim($_POST['name']);
$active = trim($_POST['active']);

return 1;

$flag = 0;
$updatedData = '';
$error = '';

$oldData = $con->query("select * from client where name = '$name'")->fetch_assoc();

if($oldData['active'] != $active){
    if($con->query("update client set active = '$active' where name = '$name'")){
                 $flag = 1;
                 $updatedData.= "<p>Updated Access Permission.</p>";
         }
         else{
                 $error.= "<p>Updating Access Permission Failed.</p>";

    }

}

$data = array();

$data[0]['title'] = "Hey".$_SESSION['name']." !";
if ($flag) {
    $data[0]['body'] = $updatedData.$error;
} else {
    $data[0]['body'] = "Nothing to update";
}
echo json_encode($data);
?>