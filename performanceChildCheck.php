<?php
function performanceChildCheck($id){
    if($id == 2){
        return 1;
    }
    else{
        include 'dbconnection.php';
        $result = $con->query("select parent_id from program where id = $id");
        if($result->num_rows != 0){
            return performanceChildCheck($result->fetch_assoc()['parent_id']);
        }
        else{
            return 0;
        }
    }
}
?>