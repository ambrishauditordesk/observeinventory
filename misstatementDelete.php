<?php
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: ../login");
}
$flag = 0;
if($_POST){
    
    $id = trim($_POST['id']);
    $wid = trim($_POST['wid']);

    if($con->query("DELETE FROM summery_of_misstatements_log WHERE summery_of_misstatements_id = $id") == TRUE)
    {
        if($con->query("DELETE FROM summery_of_misstatements WHERE id = $id and workspace_id=$wid") == TRUE)
        {
            $flag = 1;
        }
    }
}
echo $flag;
?>