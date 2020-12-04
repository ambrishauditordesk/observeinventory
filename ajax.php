<?php
    require 'dbconnection.php';

    $folderData = mysqli_query($con,"SELECT program.id as id, program.parent_id as parent_id, program.program_name as program_name,status FROM `workspace_log` inner join program on workspace_log.program_id=program.id where workspace_id='".$_GET['id']."'");
    $folders_arr = array();
    while($row = mysqli_fetch_assoc($folderData)){
        $parentid = $row['parent_id'];
        if($parentid == '0') $parentid = "#";
        $selected = false;$opened = false;
        $folders_arr[] = array(
            "id" => $row['id'],
            "parent" => $parentid,
            "text" => $row['program_name']
        );
   }
    echo json_encode($folders_arr);
    
?>