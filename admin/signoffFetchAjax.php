<?php
include '../dbconnection.php';
$id = $_POST['id'];
$wid = $_POST['wid'];
$newData = array();

$data = $con->query("select program_name from program inner join workspace_log on program.id = workspace_log.program_id where program_id='$id' and workspace_id='$wid'");
$newData['pname'] = $data->fetch_assoc();

$data = $con->query("select id,file from signoff_files_log where workspace_id='$wid' and prog_id='$id' and status = 0");
$newData['file'] = $data->fetch_all();

$data = $con->query("select id,comments,comments_date from signoff_comments_log where workspace_id='$wid' and prog_id='$id'");
$newData['comment'] = $data->fetch_all();

$data = $con->query("select user.signoff_init,prepare_signoff_date from signoff_prepare_log inner join user on signoff_prepare_log.user_id=user.id where workspace_id='$wid' and prog_id='$id'");
$newData['prepareSignOff'] = $data->fetch_all();

$data = $con->query("SELECT * FROM assertion WHERE workspace_id = $wid and program_id = $id");
if($data->num_rows > 0){
    $i = 0;
    while($row = $data->fetch_assoc()['assertion_value']){
        $newData['assertion_value'][$i++] = $row;   
    }
}
else{
    $newData['assertion_value'] = 0;
}

echo json_encode($newData);
